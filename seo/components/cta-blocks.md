# بلوک‌های CTA — کپی در همه صفحات

## CTA اصلی (بالای صفحه)

```html
<div class="cta-primary">
  <h2>فروشگاه اینترنتی خود را بسازید</h2>
  <p>مشاوره رایگان — بدون تعهد</p>
  <a href="/contact" class="btn">درخواست مشاوره رایگان</a>
  <a href="https://wa.me/98XXXXXXXXXX" class="btn-whatsapp">📱 واتساپ</a>
</div>
```

## CTA وسط مقاله

```html
<div class="cta-inline">
  <strong>می‌خواهید فروشگاه اینترنتی داشته باشید؟</strong>
  <p>تیم فروشگاه‌ساز فروشگاه کامل را برایتان راه‌اندازی می‌کند.</p>
  <a href="/contact">همین الان تماس بگیرید ←</a>
</div>
```

## CTA پایین صفحه

```html
<div class="cta-footer">
  <h2>آماده شروع هستید؟</h2>
  <p>☎️ {PHONE} | 📱 {WHATSAPP} | ✉️ info@foroshgahsaz.ir</p>
  <a href="/contact" class="btn-lg">درخواست ساخت فروشگاه</a>
</div>
```

## دکمه واتساپ شناور

```html
<a href="https://wa.me/98XXXXXXXXXX?text=سلام، برای ساخت فروشگاه اینترنتی تماس گرفتم" 
   class="whatsapp-float" 
   aria-label="واتساپ">
  📱
</a>
```

<style>
.whatsapp-float {
  position: fixed;
  bottom: 20px;
  left: 20px;
  z-index: 999;
  /* استایل دکمه سبز واتساپ */
}
</style>
```
