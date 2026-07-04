# محتوای سئو — foroshgahsaz.ir

این فولدر شامل تمام محتوای سئو برای سایت **foroshgahsaz.ir** است.

## ساختار فولدر

```
seo/
├── README.md                 ← همین فایل
├── strategy/                 ← استراتژی و نقشه کلمات
├── technical/                ← robots.txt، Schema، چک‌لیست
├── pages/                    ← محتوای صفحات لندینگ
│   └── solutions/            ← صفحات صنف‌محور
├── blog/                     ← ۱۲ مقاله کامل سئو شده (با Meta Tags)
│   ├── publish-ready/        ← ⭐ فقط متن مقاله — کپی مستقیم در سایت
│   └── PUBLISH-GUIDE.md      ← راهنمای انتشار
├── components/               ← CTA، فرم، المان‌های مشترک
└── portfolio/                ← نمونه‌کارها
```

## نحوه استفاده

### مقالات (کپی در سایت)
1. برو به **`blog/publish-ready/`** — متن کامل هر مقاله بدون frontmatter
2. Meta Tags (title, description) را از فایل اصلی `blog/*.md` بردار
3. `{PHONE}` و `{WHATSAPP}` را با شماره واقعی جایگزین کن
4. راهنمای کامل: `blog/PUBLISH-GUIDE.md`

### صفحات لندینگ
1. هر فایل `pages/*.md` شامل frontmatter + محتوا
2. در Blade یا CMS کپی کنید

## اولویت انتشار

### هفته ۱
- `pages/home.md`
- `pages/store-builder.md`
- `pages/design-online-store.md`
- `pages/contact.md`
- `technical/robots.txt`

### هفته ۲
- بقیه صفحات `pages/`
- `blog/01` تا `blog/03`

### هفته ۳–۴
- `blog/04` تا `blog/12`

## دامنه
https://foroshgahsaz.ir
