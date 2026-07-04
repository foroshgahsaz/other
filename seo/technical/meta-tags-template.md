# Meta Tags Template — همه صفحات

## قوانین کلی

- **Title:** ۵۰–۶۰ کاراکتر، کلمه کلیدی در ابتدا، برند در انتها
- **Description:** ۱۵۰–۱۶۰ کاراکتر، CTA یا عدد
- **Canonical:** URL کامل با https
- **OG:** برای اشتراک شبکه‌های اجتماعی

## Template HTML

```html
<title>{TITLE}</title>
<meta name="description" content="{DESCRIPTION}">
<meta name="keywords" content="{KEYWORDS}">
<link rel="canonical" href="{CANONICAL}">

<!-- Open Graph -->
<meta property="og:title" content="{TITLE}">
<meta property="og:description" content="{DESCRIPTION}">
<meta property="og:url" content="{CANONICAL}">
<meta property="og:type" content="website">
<meta property="og:locale" content="fa_IR">
<meta property="og:site_name" content="فروشگاه‌ساز">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{TITLE}">
<meta name="twitter:description" content="{DESCRIPTION}">
```

## لیست Title/Description همه صفحات

| صفحه | Title |
|------|-------|
| / | فروشگاه ساز \| طراحی و ساخت فروشگاه اینترنتی حرفه‌ای - فروشگاه‌ساز |
| /store-builder | فروشگاه ساز آنلاین \| ساخت فروشگاه اینترنتی - فروشگاه‌ساز |
| /design-online-store | طراحی فروشگاه اینترنتی \| ساخت سایت فروشگاهی - فروشگاه‌ساز |
| /pricing | قیمت فروشگاه ساز \| هزینه طراحی فروشگاه ۱۴۰۴ - فروشگاه‌ساز |
| /contact | تماس \| درخواست ساخت فروشگاه - فروشگاه‌ساز |

(بقیه در frontmatter هر فایل pages/ و blog/)

## جایگزینی متغیرها

| متغیر | مقدار |
|-------|-------|
| {PHONE} | 09XX XXX XXXX |
| {WHATSAPP} | 98XXXXXXXXXX |
| {BRAND} | فروشگاه‌ساز |
