# Database Setup Guide

## 🗄️ إعداد قاعدة البيانات بأمان

هذا المشروع يحتوي على عدة طرق لإعداد قاعدة البيانات بأمان، حتى لو كانت الجداول موجودة بالفعل.

## 🛠️ الطرق المتاحة:

### 1️⃣ الطريقة التلقائية (Recommended)

```bash
# للـ Windows
./setup-database.bat

# للـ Linux/Mac
chmod +x setup-database.sh
./setup-database.sh
```

### 2️⃣ استخدام Laravel Commands

```bash
# Safe Migration - يتجاهل الجداول الموجودة
php artisan migrate:safe

# Check and Migrate - يفحص كل جدول قبل الإنشاء
php artisan db:check-and-migrate

# Migration عادي (قد يفشل إذا كانت الجداول موجودة)
php artisan migrate
```

### 3️⃣ فحص حالة الـ Migrations

```bash
# عرض حالة جميع migrations
php artisan migrate:status

# تشغيل migrations بالقوة
php artisan migrate --force
```

## 📋 الجداول المطلوبة:

-   ✅ `users` - بيانات المستخدمين
-   ✅ `posts` - الإعلانات والفرص
-   ✅ `categories` - الفئات
-   ✅ `areas` - المناطق الجغرافية
-   ✅ `payments` - المدفوعات
-   ✅ `commission_payments` - مدفوعات العمولة
-   ✅ `contacts` - رسائل التواصل
-   ✅ `settings` - إعدادات الموقع
-   ✅ `pages` - الصفحات الثابتة
-   ✅ `admins` - المديرين

## 🔧 المشاكل الشائعة وحلولها:

### خطأ "Table already exists"

```bash
# استخدم Safe Migration
php artisan migrate:safe
```

### خطأ "Connection refused"

```bash
# تأكد من إعدادات قاعدة البيانات في .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### مشكلة في Migration معين

```bash
# تشغيل migration محدد
php artisan migrate --path=database/migrations/specific_migration.php
```

## 📝 ملاحظات:

1. **البيئة Production**: استخدم دائماً `--force` flag
2. **النسخ الاحتياطي**: اعمل backup قبل تشغيل أي migrations
3. **الأذونات**: تأكد من صلاحيات قاعدة البيانات
4. **الـ Cron Jobs**: بعد إعداد قاعدة البيانات، فعّل الـ cron jobs

## 🚀 After Setup:

بعد إعداد قاعدة البيانات بنجاح:

```bash
# تشغيل الـ seeders (إختياري)
php artisan db:seed

# فحص الإعلانات المميزة
php artisan posts:check-featured

# إعداد الـ cron job للهوستنجر
# أضف في cPanel:
# * * * * * cd /path/to/your/project && php artisan schedule:run
```
