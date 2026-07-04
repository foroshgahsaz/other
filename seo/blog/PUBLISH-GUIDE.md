# راهنمای انتشار مقالات — کپی مستقیم در سایت

## کدام فولدر را استفاده کنم؟

| فولدر | کاربرد |
|-------|--------|
| `blog/publish-ready/` | **فقط متن مقاله** — بدون frontmatter، آماده کپی در ویرایشگر سایت |
| `blog/*.md` | متن + Meta Tags (title, description, slug) در frontmatter |

## قبل از انتشار

1. `{PHONE}` را با شماره تماس جایگزین کنید
2. `{WHATSAPP}` را با شماره واتساپ (بدون +) جایگزین کنید — مثال: `989121234567`
3. لینک‌های `/contact` و `/store-builder` را با URL واقعی سایت یکسان کنید

## Meta Tags هر مقاله

از frontmatter فایل اصلی در `blog/` کپی کنید:

```html
<title>عنوان از frontmatter</title>
<meta name="description" content="توضیح از frontmatter">
<link rel="canonical" href="https://foroshgahsaz.ir/blog/...">
```

## لیست ۱۲ مقاله

| # | فایل | کلمه کلیدی | کلمات |
|---|------|------------|-------|
| 1 | 01-guide-building-online-store-iran | ساخت فروشگاه اینترنتی | ~2700 |
| 2 | 02-what-is-store-builder | فروشگاه ساز | ~2300 |
| 3 | 03-cost-building-online-store | هزینه ساخت فروشگاه | ~2300 |
| 4 | 04-best-iranian-store-builders | بهترین فروشگاه ساز ایرانی | ~2700 |
| 5 | 05-instagram-to-online-store | فروشگاه اینترنتی اینستاگرام | ~2700 |
| 6 | 06-store-builder-vs-custom-design | فروشگاه ساز یا طراحی اختصاصی | ~2400 |
| 7 | 07-payment-gateway-setup | درگاه پرداخت فروشگاه | ~3000 |
| 8 | 08-common-mistakes-launching-store | اشتباهات راه‌اندازی | ~2500 |
| 9 | 09-clothing-store-design-guide | طراحی فروشگاه پوشاک | ~2700 |
| 10 | 10-free-store-builder-truth | فروشگاه ساز رایگان | ~2100 |
| 11 | 11-ecommerce-seo-tips | سئو فروشگاه اینترنتی | ~2700 |
| 12 | 12-store-builder-vs-woocommerce | فروشگاه ساز یا ووکامرس | ~2400 |

**جمع:** بیش از ۳۰٬۰۰۰ کلمه محتوای آماده انتشار

## ترتیب پیشنهادی انتشار

**هفته ۱:** 01، 02، 03  
**هفته ۲:** 04، 05، 06  
**هفته ۳:** 07، 08، 09  
**هفته ۴:** 10، 11، 12  

## تبدیل Markdown به HTML

اگر ویرایشگر شما HTML می‌خواهد:
- از پلاگین Markdown در وردپرس/لاراول استفاده کنید
- یا آنلاین: markdown به HTML converter
- `#` → `<h1>`، `##` → `<h2>`، جدول‌ها را دستی یا با converter

## Schema FAQ

در انتهای هر مقاله بخش «سوالات متداول» دارید — برای Rich Results در گوگل Schema FAQ اضافه کنید (نمونه در `technical/schema-organization.json`).
