<?php
/**
 * ماشین‌حساب ارسال رایگان
 * - مبلغ خرید: ریال (کاربر وارد می‌کند)
 * - کرایه دیتابیس: تومان (nissan_price)
 * - نرخ سهم حمل: 0.012272727 (~1.227%) روی مبلغ تومانی
 * - ملاک ارسال رایگان: فقط کرایه نیسان
 */

require_once( dirname(__FILE__) . '/wp-load.php' );

global $wpdb;

define( 'SHIPPING_RATE', 0.012272727 );
define( 'RIAL_PER_TOMAN', 10 );
define( 'MIN_PURCHASE_RIAL', 1100000000 ); // 110 میلیون تومان

// دریافت لیست شهرها
$cities = $wpdb->get_results(
    "SELECT DISTINCT destination_city, peykan_price, mazda_price, nissan_price 
     FROM mm_woo_excel_shipping_routes 
     WHERE destination_city IS NOT NULL AND destination_city != ''
     ORDER BY destination_city ASC"
);

// محاسبه AJAX
if ( isset($_POST['action']) && $_POST['action'] === 'calculate' ) {
    header('Content-Type: application/json; charset=utf-8');
    
    $amount = floatval($_POST['amount']);
    $city   = sanitize_text_field($_POST['city']);
    
    if ( $amount <= 0 || empty($city) ) {
        echo json_encode(['error' => 'اطلاعات نادرست است']);
        exit;
    }
    
    $row = $wpdb->get_row( $wpdb->prepare(
        "SELECT nissan_price 
         FROM mm_woo_excel_shipping_routes 
         WHERE destination_city = %s 
         LIMIT 1",
        $city
    ));
    
    if ( ! $row ) {
        echo json_encode(['error' => 'شهر یافت نشد']);
        exit;
    }
    
    $fare_toman = floatval($row->nissan_price);
    $amount_toman = $amount / RIAL_PER_TOMAN;
    $shipping_share_toman = $amount_toman * SHIPPING_RATE;
    
    if ($amount < MIN_PURCHASE_RIAL) {
        $is_free = false;
        $needed_purchase_toman = 0;
        $diff_toman = 0;
    } else {
        $is_free = $shipping_share_toman >= $fare_toman;
        $needed_purchase_toman = $is_free ? 0 : ceil($fare_toman / SHIPPING_RATE);
        $diff_toman = $is_free ? 0 : ($needed_purchase_toman - $amount_toman);
    }
    
    $result = [
        'nissan' => [
            'label'              => 'نیسان',
            'fare'               => $fare_toman * RIAL_PER_TOMAN,
            'shipping_share'     => $shipping_share_toman * RIAL_PER_TOMAN,
            'is_free'            => $is_free,
            'needed_purchase'    => $needed_purchase_toman * RIAL_PER_TOMAN,
            'diff'               => $diff_toman * RIAL_PER_TOMAN,
            'amount'             => $amount,
            'is_below_threshold' => $amount < MIN_PURCHASE_RIAL
        ]
    ];
    
    echo json_encode(['success' => true, 'data' => $result, 'city' => $city]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
<title>محاسبه ارسال رایگان</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  :root {
    --bg:      #f4f7fb;
    --surface: #ffffff;
    --card:    #ffffff;
    --border:  #dde3ed;
    --accent:  #2563eb;
    --accent2: #06b6d4;
    --green:   #10b981;
    --orange:  #f97316;
    --text:    #1e293b;
    --text-muted: #64748b;
  }

  body {
    font-family: 'Vazirmatn', sans-serif;
    background: var(--bg);
    color: var(--text);
    min-height: 100vh;
  }

  .desktop-layout {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 20px;
  }

  .desktop-wrapper {
    width: 100%;
    max-width: 1200px;
    background: transparent;
  }

  .mobile-layout {
    display: block;
    min-height: 100vh;
    padding: 16px;
  }

  .header {
    text-align: center;
    margin-bottom: 20px;
  }
  
  .badge {
    display: inline-block;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    padding: 4px 16px;
    border-radius: 40px;
    margin-bottom: 8px;
  }
  
  .header h1 {
    font-size: 24px;
    font-weight: 900;
    background: linear-gradient(135deg, #0f172a, var(--accent));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 2px;
  }
  
  .header p { 
    color: var(--text-muted); 
    font-size: 12px; 
  }

  .form-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 24px;
    padding: 20px 24px;
    margin-bottom: 20px;
    box-shadow: 0 8px 20px -8px rgba(0,0,0,0.05);
  }

  .form-grid {
    display: flex;
    align-items: anchor-center;
    gap: 16px;
    flex-wrap: wrap;
  }
  
  .field {
    flex: 1 1 250px;
    display: flex;
    flex-direction: column;
    gap: 4px;
  }
  
  .field label { 
    font-size: 12px; 
    font-weight: 600; 
    color: var(--text-muted); 
  }

  .field input {
    background: #f9fafc;
    border: 1.5px solid var(--border);
    border-radius: 14px;
    padding: 12px 16px;
    font-family: inherit;
    font-size: 14px;
    color: var(--text);
    outline: none;
    transition: all .2s;
    width: 100%;
    height: 48px;
  }
  
  .field input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
  }
  
  .field .hint { 
    font-size: 10px; 
    color: var(--text-muted); 
  }

  .city-selector {
    position: relative;
    width: 100%;
  }
  
  .selected-item {
    background: #f9fafc;
    border: 1.5px solid var(--border);
    border-radius: 14px;
    padding: 0 16px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 48px;
    transition: all .2s;
    width: 100%;
  }
  
  .selected-item:hover {
    border-color: var(--accent);
  }
  
  .selected-item i {
    color: var(--text-muted);
    transition: transform .3s;
    font-size: 14px;
  }
  
  .selected-item.active i {
    transform: rotate(180deg);
  }
  
  .dropdown-content {
    position: absolute;
    top: calc(100% + 5px);
    left: 0;
    right: 0;
    background: white;
    border: 1.5px solid var(--border);
    border-radius: 14px;
    max-height: 300px;
    overflow-y: auto;
    z-index: 1000;
    display: none;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
  }
  
  .dropdown-content.show {
    display: block;
  }
  
  .search-box {
    padding: 12px;
    border-bottom: 1px solid var(--border);
    position: sticky;
    top: 0;
    background: white;
    border-radius: 14px 14px 0 0;
  }
  
  .search-box input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid var(--border);
    border-radius: 10px;
    font-family: inherit;
    font-size: 13px;
    outline: none;
    background: #f9fafc;
  }
  
  .options-list {
    max-height: 200px;
    overflow-y: auto;
  }
  
  .city-option {
    padding: 10px 16px;
    cursor: pointer;
    transition: background .2s;
    font-size: 13px;
    border-bottom: 1px solid #f0f3f8;
  }
  
  .city-option:hover {
    background: #f0f7ff;
  }
  
  .city-option.selected {
    background: #e6f0ff;
    color: var(--accent);
    font-weight: 500;
  }

  .calc-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: linear-gradient(135deg, var(--accent), #1d4ed8);
    color: #fff;
    font-family: inherit;
    font-size: 15px;
    font-weight: 700;
    border: none;
    border-radius: 14px;
    padding: 0 28px;
    cursor: pointer;
    transition: all .2s;
    box-shadow: 0 6px 14px -4px rgba(37,99,235,0.3);
    white-space: nowrap;
    height: 48px;
    min-width: 120px;
  }
  
  .calc-btn:hover { 
    opacity: .92; 
    transform: translateY(-1px); 
  }

  .results-section {
    margin-top: 20px;
  }

  .section-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-muted);
    margin-bottom: 12px;
  }

  .vehicles-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 14px;
    max-width: 420px;
    margin: 0 auto;
  }

  .vehicle-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 16px;
    display: flex;
    flex-direction: column;
  }

  .v-icon {
    font-size: 42px;
    margin-bottom: 8px;
    text-align: center;
    color: var(--accent);
  }
  
  .v-name  { 
    font-size: 18px; 
    font-weight: 800; 
    margin-bottom: 12px; 
    border-bottom: 2px solid #eef2f6; 
    padding-bottom: 6px;
    color: #0f172a;
    text-align: center;
  }

  .stat {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 0;
    border-bottom: 1px dashed #e9eef3;
    font-size: 12px;
  }
  
  .stat .val { 
    font-weight: 700; 
    direction: ltr; 
    background: #f1f4f9; 
    padding: 3px 8px; 
    border-radius: 30px;
    font-size: 11px;
  }

  .status-pill {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    width: 100%;
    padding: 6px 0;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 700;
    margin: 10px 0 6px;
    border: 1px solid transparent;
  }
  
  .status-pill.free { 
    background: #e6f7f0; 
    color: #0d6b4b; 
    border-color: #b2e6d4; 
  }
  
  .status-pill.paid { 
    background: #ffefe2; 
    color: #b45309; 
    border-color: #ffd6b3; 
  }
  
  .status-pill.info { 
    background: #e6f0ff; 
    color: #1e4b8c; 
    border-color: #b8d1ff; 
  }

  .diff-box {
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-radius: 16px;
    padding: 10px 6px;
    margin-top: 6px;
    font-size: 11px;
    color: #92400e;
    text-align: center;
  }
  
  .diff-box strong { 
    display: block; 
    font-size: 16px; 
    font-weight: 900; 
    color: #b45309;
  }

  .error-box {
    background: #fee2e2;
    border: 1px solid #fecaca;
    border-radius: 12px;
    padding: 10px 14px;
    color: #b91c1c;
    font-size: 12px;
    margin-top: 16px;
    display: none;
  }

  .spinner {
    width: 16px; 
    height: 16px;
    border: 2px solid rgba(255,255,255,.3);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin .6s linear infinite;
    display: none;
  }

  @keyframes spin { 
    to { transform: rotate(360deg); } 
  }

  @media (max-width: 768px) {
    body {
      overflow-y: auto;
      height: auto;
    }

    .desktop-layout {
      display: none;
    }

    .mobile-layout {
      display: block;
    }

    .vehicles-grid {
      grid-template-columns: 1fr;
      gap: 12px;
    }

    .form-card {
      padding: 16px;
    }

    .form-grid {
      flex-direction: column;
      gap: 12px;
    }

    .field {
      width: 100%;
    }

    .calc-btn {
      width: 100%;
      margin-top: 8px;
    }

    .vehicle-card {
      padding: 14px;
    }
  }

  @media (min-width: 769px) {
    .desktop-layout {
      display: flex;
    }

    .mobile-layout {
      display: none;
    }
  }
</style>
</head>
<body>

<div class="desktop-layout">
  <div class="desktop-wrapper">
    <div class="header">
      <span class="badge">🚚 ماشین‌حساب حمل‌ونقل</span>
      <h1>محاسبه ارسال رایگان</h1>
      <p>مبلغ خرید (ریال) و شهر را وارد کنید — ملاک ارسال رایگان: کرایه نیسان</p>
    </div>

    <div class="form-card">
      <div class="form-grid">
        <div class="field">
          <label>💰 مبلغ خرید (ریال)</label>
          <input type="text" id="amount" placeholder="مثال: ۹۴,۵۰۰,۰۰۰" inputmode="numeric">
          <span class="hint">فرمت خودکار با جداکننده</span>
        </div>

        <div class="field">
          <label>📍 شهر مقصد (قابل جستجو)</label>
          <div class="city-selector" id="citySelect">
            <div class="selected-item" onclick="toggleDropdown()">
              <span id="selectedCity">— انتخاب کنید —</span>
              <i class="fas fa-chevron-down"></i>
            </div>
            <div class="dropdown-content" id="dropdown">
              <div class="search-box">
                <input type="text" id="searchInput" placeholder="جستجوی شهر..." onkeyup="filterCities('desktop')">
              </div>
              <div class="options-list" id="optionsList">
                <?php foreach ($cities as $c): ?>
                <div class="city-option" onclick="selectCity('<?php echo esc_js($c->destination_city); ?>')">
                  <?php echo esc_html($c->destination_city); ?>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
          <input type="hidden" id="city" value="">
          <span class="hint">تایپ کن تا سریع پیدا کنی</span>
        </div>

        <button class="calc-btn" onclick="calculate()">
          <span id="btn-text">محاسبه کن</span>
          <div class="spinner" id="spinner"></div>
        </button>
      </div>
      <div class="error-box" id="error-box"></div>
    </div>

    <div id="results" style="display: none;">
      <div class="section-label">نتایج برای «<span id="result-city"></span>»</div>
      <div class="vehicles-grid" id="vehicles-grid"></div>
    </div>
  </div>
</div>

<div class="mobile-layout">
  <div class="header">
    <span class="badge">🚚 ماشین‌حساب</span>
    <h1>محاسبه ارسال رایگان</h1>
    <p>مبلغ (ریال) و شهر — ملاک: کرایه نیسان</p>
  </div>

  <div class="form-card">
    <div class="field" style="margin-bottom: 16px;">
      <label>💰 مبلغ خرید (ریال)</label>
      <input type="text" id="amount-mobile" placeholder="مثال: ۹۴,۵۰۰,۰۰۰" inputmode="numeric">
    </div>

    <div class="field" style="margin-bottom: 16px;">
      <label>📍 شهر مقصد</label>
      <div class="city-selector" id="citySelect-mobile">
        <div class="selected-item" onclick="toggleDropdownMobile()">
          <span id="selectedCity-mobile">انتخاب شهر...</span>
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="dropdown-content" id="dropdown-mobile">
          <div class="search-box">
            <input type="text" id="searchInput-mobile" placeholder="جستجوی شهر..." onkeyup="filterCities('mobile')">
          </div>
          <div class="options-list" id="optionsList-mobile">
            <?php foreach ($cities as $c): ?>
            <div class="city-option" onclick="selectCityMobile('<?php echo esc_js($c->destination_city); ?>')">
              <?php echo esc_html($c->destination_city); ?>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <input type="hidden" id="city-mobile" value="">
    </div>

    <button class="calc-btn" onclick="calculateMobile()" style="width: 100%;">
      <span id="btn-text-mobile">محاسبه</span>
      <div class="spinner" id="spinner-mobile"></div>
    </button>

    <div class="error-box" id="error-box-mobile"></div>
  </div>

  <div id="results-mobile" style="display: none;">
    <div class="section-label">نتایج برای «<span id="result-city-mobile"></span>»</div>
    <div class="vehicles-grid" id="vehicles-grid-mobile"></div>
  </div>
</div>

<script>
function formatNumber(num) {
  if (!num && num !== 0) return '';
  let number = num.toString().replace(/[^\d]/g, '');
  if (number === '') return '';
  let parts = parseInt(number).toString().split('.');
  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '٬');
  return parts[0];
}

function parseNumber(str) {
  if (!str) return 0;
  return parseInt(str.toString().replace(/[^\d]/g, '')) || 0;
}

const fmt = n => {
  if (n === undefined || n === null) return '۰';
  const parts = Math.round(n).toString().split('.');
  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '٬');
  return parts[0] + ' ریال';
};

const amountInput = document.getElementById('amount');
if (amountInput) {
  amountInput.addEventListener('input', function() {
    let numeric = parseNumber(this.value);
    this.value = formatNumber(numeric);
  });
}

function toggleDropdown() {
  document.getElementById('dropdown').classList.toggle('show');
  document.querySelector('#citySelect .selected-item').classList.toggle('active');
  if (document.getElementById('dropdown').classList.contains('show')) {
    document.getElementById('searchInput').focus();
  }
}

function filterCities(mode) {
  const listId = mode === 'mobile' ? 'optionsList-mobile' : 'optionsList';
  const inputId = mode === 'mobile' ? 'searchInput-mobile' : 'searchInput';
  const filter = document.getElementById(inputId).value.toLowerCase();
  const options = document.querySelectorAll('#' + listId + ' .city-option');

  options.forEach(option => {
    const txtValue = option.textContent || option.innerText;
    option.style.display = txtValue.toLowerCase().indexOf(filter) > -1 ? '' : 'none';
  });
}

function selectCity(city) {
  document.getElementById('selectedCity').textContent = city;
  document.getElementById('city').value = city;
  document.getElementById('dropdown').classList.remove('show');
  document.querySelector('#citySelect .selected-item').classList.remove('active');
}

document.addEventListener('click', function(event) {
  if (!event.target.closest('#citySelect')) {
    document.getElementById('dropdown')?.classList.remove('show');
    document.querySelector('#citySelect .selected-item')?.classList.remove('active');
  }
});

async function calculate() {
  const amount = parseNumber(document.getElementById('amount').value);
  const city = document.getElementById('city').value;
  const errBox = document.getElementById('error-box');

  if (!amount || amount <= 0) { showErr(errBox, 'لطفاً مبلغ خرید را وارد کنید'); return; }
  if (!city) { showErr(errBox, 'لطفاً شهر مقصد را انتخاب کنید'); return; }

  setLoading(true, 'btn-text', 'spinner', 'محاسبه کن');
  document.getElementById('results').style.display = 'none';
  errBox.style.display = 'none';

  try {
    const fd = new FormData();
    fd.append('action', 'calculate');
    fd.append('amount', amount);
    fd.append('city', city);

    const res = await fetch(location.href, { method: 'POST', body: fd });
    const json = await res.json();

    if (json.error) { showErr(errBox, json.error); return; }
    renderResults(json.data, json.city, 'result-city', 'vehicles-grid');
    document.getElementById('results').style.display = 'block';
  } catch(e) {
    showErr(errBox, 'خطا در ارتباط با سرور');
  } finally {
    setLoading(false, 'btn-text', 'spinner', 'محاسبه کن');
  }
}

const amountMobile = document.getElementById('amount-mobile');
if (amountMobile) {
  amountMobile.addEventListener('input', function() {
    let numeric = parseNumber(this.value);
    this.value = formatNumber(numeric);
  });
}

function toggleDropdownMobile() {
  document.getElementById('dropdown-mobile').classList.toggle('show');
  document.querySelector('#citySelect-mobile .selected-item').classList.toggle('active');
  if (document.getElementById('dropdown-mobile').classList.contains('show')) {
    document.getElementById('searchInput-mobile').focus();
  }
}

function selectCityMobile(city) {
  document.getElementById('selectedCity-mobile').textContent = city;
  document.getElementById('city-mobile').value = city;
  document.getElementById('dropdown-mobile').classList.remove('show');
  document.querySelector('#citySelect-mobile .selected-item').classList.remove('active');
}

document.addEventListener('click', function(event) {
  if (!event.target.closest('#citySelect-mobile')) {
    document.getElementById('dropdown-mobile')?.classList.remove('show');
    document.querySelector('#citySelect-mobile .selected-item')?.classList.remove('active');
  }
});

async function calculateMobile() {
  const amount = parseNumber(document.getElementById('amount-mobile').value);
  const city = document.getElementById('city-mobile').value;
  const errBox = document.getElementById('error-box-mobile');

  if (!amount || amount <= 0) { showErr(errBox, 'لطفاً مبلغ خرید را وارد کنید'); return; }
  if (!city) { showErr(errBox, 'لطفاً شهر مقصد را انتخاب کنید'); return; }

  setLoading(true, 'btn-text-mobile', 'spinner-mobile', 'محاسبه');
  document.getElementById('results-mobile').style.display = 'none';
  errBox.style.display = 'none';

  try {
    const fd = new FormData();
    fd.append('action', 'calculate');
    fd.append('amount', amount);
    fd.append('city', city);

    const res = await fetch(location.href, { method: 'POST', body: fd });
    const json = await res.json();

    if (json.error) { showErr(errBox, json.error); return; }
    renderResults(json.data, json.city, 'result-city-mobile', 'vehicles-grid-mobile');
    document.getElementById('results-mobile').style.display = 'block';
  } catch(e) {
    showErr(errBox, 'خطا در ارتباط با سرور');
  } finally {
    setLoading(false, 'btn-text-mobile', 'spinner-mobile', 'محاسبه');
  }
}

function showErr(element, msg) {
  element.textContent = '⚠️ ' + msg;
  element.style.display = 'block';
}

function setLoading(on, btnId, spinnerId, defaultText) {
  document.getElementById(btnId).textContent = on ? 'در حال محاسبه...' : defaultText;
  document.getElementById(spinnerId).style.display = on ? 'block' : 'none';
}

function renderResults(data, city, cityElementId, gridElementId) {
  document.getElementById(cityElementId).textContent = city;
  const grid = document.getElementById(gridElementId);
  grid.innerHTML = '';

  const icons = { 
    nissan: '<i class="fas fa-truck-moving"></i>'
  };

  for (const [key, v] of Object.entries(data)) {
    const card = document.createElement('div');
    card.className = 'vehicle-card';

    let statusClass = 'paid';
    let statusText = '⏳ نیاز به خرید بیشتر';
    let additionalContent = '';
    
    if (v.is_below_threshold) {
      statusClass = 'info';
      statusText = 'ℹ️ زیر ۱,۱۰۰,۰۰۰,۰۰۰ ریال (۱۱۰ میلیون تومان)';
    } else if (v.is_free) {
      statusClass = 'free';
      statusText = '✅ ارسال رایگان!';
    } else {
      additionalContent = `
        <div class="diff-box">
          <strong>${fmt(v.diff)}</strong>
          <span>بیشتر خرید کن</span><br>
          خرید نهایی <b>${fmt(v.needed_purchase)}</b>
        </div>`;
    }

    card.innerHTML = `
      <div class="v-icon">${icons[key]}</div>
      <div class="v-name">${v.label}</div>
      <div class="stat"><span class="lbl">کرایه نیسان</span><span class="val">${fmt(v.fare)}</span></div>
      <span class="status-pill ${statusClass}">${statusText}</span>
      ${additionalContent}
    `;
    grid.appendChild(card);
  }
}
</script>
</body>
</html>
