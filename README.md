

# Social Media Task Project - API Documentation

## مقدمة
مشروع "Social Media Task" هو منصة تواصل اجتماعي تقدم واجهات برمجة تطبيقات (APIs) لإدارة المستخدمين، المنشورات، والتعليقات.  
**الفئات الرئيسية**:
1. **المصادقة (Auth)** - تسجيل الدخول، الخروج، ومعلومات المستخدم.
2. **المشرفين (Admin)** - إدارة المستخدمين (عرض، إضافة، تحديث، وحذف).
3. **المستخدمين (User)** - إدارة المنشورات، التعليقات، والملف الشخصي.

## المتغيرات العامة (Global Variables)
- `base_url`: عنوان الخادم الأساسي (`http://127.0.0.1:8000/api/`).

---

## 1. قسم المصادقة (Auth)

### 1.1 تسجيل الدخول (Login)
```http
POST {{base_url}}auth/login
Headers:
  Accept: application/json
  Content-Type: application/json

Body (JSON):
{
  "email": "arely02@example.com",
  "password": "12345678"
}
```

### 1.2 تسجيل الخروج (Logout)
```http
POST {{base_url}}auth/logout
Headers:
  Authorization: Bearer {token}
```

### 1.3 معلومات المستخدم (User Info)
```http
GET {{base_url}}auth/user-info
Headers:
  Authorization: Bearer {token}
```

---

## 2. قسم المشرفين (Admin)

### 2.1 عرض جميع المستخدمين
```http
GET {{base_url}}admin/users-management/all
Headers:
  Authorization: Bearer {token}
```

### 2.2 عرض المستخدمين العاديين
```http
GET {{base_url}}admin/users-management/all-regular-users
Headers:
  Authorization: Bearer {token}
```

### 2.3 عرض جميع المشرفين
```http
GET {{base_url}}admin/users-management/all-admins
Headers:
  Authorization: Bearer {token}
```

### 2.4 إضافة مستخدم
```http
POST {{base_url}}admin/users-management/add
Headers:
  Authorization: Bearer {token}
  Content-Type: multipart/form-data

Body (Form-Data):
  name: "wasem"
  email: "wasem.saleh@gmail.com"
  image: (File)
  role_title: "admin" أو "user"
```

### 2.5 حذف مستخدم
```http
DELETE {{base_url}}admin/users-management/delete/6
Headers:
  Authorization: Bearer {token}
```

### 2.6 تحديث تفعيل المستخدم
```http
PATCH {{base_url}}admin/users-management/update-activation/2
Headers:
  Authorization: Bearer {token}
```

---

## 3. قسم المستخدمين (User)

### 3.1 المنشورات (Posts)
#### 3.1.1 عرض جميع المنشورات
```http
GET {{base_url}}user/posts/all
Headers:
  Authorization: Bearer {token}
```

#### 3.1.2 إضافة تعليق
```http
POST {{base_url}}user/posts/add-comment
Headers:
  Authorization: Bearer {token}

Body (JSON):
{
  "post_id": 1,
  "comment": "nice"
}
```

### 3.2 منشوراتي (My Posts)
#### 3.2.1 عرض جميع المنشورات
```http
GET {{base_url}}user/my-posts/all
Headers:
  Authorization: Bearer {token}
```

#### 3.2.2 إضافة منشور
```http
POST {{base_url}}user/my-posts/add
Headers:
  Authorization: Bearer {token}
  Content-Type: multipart/form-data

Body (Form-Data):
  title: "Post Without Images"
  content: "محتوى المنشور"
  images[]: (File1, File2, ...)
```

#### 3.2.3 حذف منشور
```http
DELETE {{base_url}}user/my-posts/delete/50
Headers:
  Authorization: Bearer {token}
```

#### 3.2.4 تحديث منشور
```http
PATCH {{base_url}}user/my-posts/update/3
Headers:
  Authorization: Bearer {token}

Body (JSON):
{
  "title": "wasem",
  "content": "testing"
}
```

#### 3.2.5 إضافة صور إلى منشور
```http
POST {{base_url}}user/my-posts/add-post-images
Headers:
  Authorization: Bearer {token}
  Content-Type: multipart/form-data

Body (Form-Data):
  post_id: 2
  images[]: (File1, File2, ...)
```

#### 3.2.6 حذف صورة منشور
```http
DELETE {{base_url}}user/my-posts/delete-post-image/3
Headers:
  Authorization: Bearer {token}

Body (JSON):
{
  "post_id": 1
}
```

### 3.3 الملف الشخصي (User Profile)
#### 3.3.1 الحصول على الملف الشخصي
```http
GET {{base_url}}user/user-profile/2
Headers:
  Authorization: Bearer {token}
```

---

## ملاحظات عامة
- **المصادقة**: معظم الطلبات تتطلب `Bearer Token` (يُحصل عليه بعد تسجيل الدخول).
- **رفع الملفات**: استخدم `form-data` مع تحديد نوع الحقل كـ `File`.
- **البيئة**: تأكد من تعيين `base_url` ليتوافق مع عنوان الخادم المستخدم.
- **الاختبار**: يُنصح باستخدام [Postman](https://www.postman.com/) لاختبار الـ APIs.

---


يمكنك استيراد [Postman Collection](رابط_مجموعة_Postman_هنا) مباشرةً إلى برنامج Postman.

---

مرحبًا! إليك تنسيق واضح لتعليمات التنصيب والتشغيل لمشروعك على GitHub، يمكنك إضافتها إلى ملف `README.md`:

---

# تعليمات التنصيب والتشغيل

اتبع هذه الخطوات لتشغيل المشروع محليًا:

## المتطلبات المسبقة
- [PHP](https://www.php.net/downloads) (الإصدار المطلوب حسب المشروع)
- [Composer](https://getcomposer.org/download/)
- [Git](https://git-scm.com/downloads)

## خطوات التنصيب

1. **استنساخ المشروع**
   ```bash
   git clone https://github.com/wasem328saleh/posts-management
   cd your-repo
   ```

2. **تثبيت الـ Dependencies**
   ```bash
   composer install
   ```

3. **تشغيل المشروع**
   ```bash
   php artisan project:run
   ```

---

## ماذا يفعل الأمر `php artisan project:run`؟

هذا الأمر يُنفذ سلسلة من المهام التلقائية لإعداد المشروع وتشغيله، ويتضمن:

### 1. **تحديث dependencies (اختياري)**
- يطلب منك التأكيد إذا كنت تريد تحديث حزم Composer.
- إذا وافقت، ينفذ الأمر:
  ```bash
  composer update --ignore-platform-req=ext-sodium
  ```

### 2. **إنشاء ملف `.env`**
- إذا لم يوجد ملف `.env`، ينشئ نسخة من `.env.example` تلقائيًا.
- يُعين القيم التالية في ملف `.env` (يمكنك تعديلها لاحقًا):
  ```env
  APP_NAME=Social-Media
  DB_CONNECTION=mysql
  DB_DATABASE=social_media_project_db
  MAIL_MAILER=smtp
  MAIL_HOST=smtp.gmail.com
  MAIL_PORT=587
  MAIL_USERNAME=levanttask@gmail.com
  MAIL_PASSWORD=your-email-password
  MAIL_FROM_ADDRESS=levanttask@gmail.com
  GEMINI_AI_KEY=your-ai-key
  ```

### 3. **تهيئة قاعدة البيانات**
- ينفذ الأمرين التاليين تلقائيًا:
  ```bash
  php artisan migrate:fresh    # يحذف جميع الجداول ويعيد إنشائها
  php artisan db:seed         # يعبئ الجداول ببيانات تجريبية
  ```

### 4. **تثبيت Laravel Passport**
- يُنشئ مفاتيح OAuth2 لتأمين الـ APIs عبر الأمر:
  ```bash
  php artisan passport:install
  ```

### 5. **توليد مفتاح التطبيق**
- يُنشئ مفتاحًا فريدًا للتطبيق عبر الأمر:
  ```bash
  php artisan key:generate
  ```

### 6. **تشغيل السيرفر**
- يبدأ تشغيل الخادم المحلي تلقائيًا عبر الأمر:
  ```bash
  php artisan serve
  ```

---

## ملاحظات مهمة:
- **بيئة الإنتاج**: لا تستخدم `migrate:fresh` في الإنتاج لأنه سيحذف جميع البيانات!
- **إعدادات البريد**: القيم المُعَدة مسبقًا في `.env` (مثل `MAIL_USERNAME` و`MAIL_PASSWORD`) للإرشاد فقط. غيّرها إلى بيانات حسابك الفعلي.
- **الأمان**: احذر من حفظ بيانات حساسة (ككلمات المرور) في الكود مباشرةً.

---

---

4. **تشغيل المهام المجدولة (في نافذة تيرمينال جديدة)**  
   افتح نافذة تيرمينال جديدة في نفس المسار ثم نفذ:
   ```bash
   php artisan schedule:run
   ```

## ملاحظات مهمة
- احتفظ بنافذة `php artisan schedule:run` مفتوحة طوال فترة استخدام المهام المجدولة.
- في البيئة الإنتاجية، يُنصح باستخدام [Supervisor](https://laravel.com/docs/scheduling#running-the-scheduler) لإدارة المهام المجدولة تلقائيًا.
- قد تحتاج إلى تكوين ملف `.env` وإعداد قاعدة البيانات قبل التشغيل.

## استكشاف الأخطاء
- إذا واجهت مشاكل في الـ dependencies، حاول حذف مجلد `vendor` وتشغيل `composer install` مجددًا.
- تأكد من منح الصلاحيات اللازمة للملفات (مثل `storage/` و `bootstrap/cache`).

---

---

## 🔐 بيانات المصادقة (للاختبار)

### حساب المشرف (Admin)
- **البريد الإلكتروني**: `levanttask@gmail.com`
- **كلمة المرور**: `12345678`

### حساب المستخدم العادي (User)
- **البريد الإلكتروني**: `user.demo@task.com`
- **كلمة المرور**: `12345678`

---

### ملاحظات هامة:
1. هذه الحسابات مُعدة مسبقًا في قاعدة البيانات بعد تنفيذ الأمر `php artisan project:run`.
2. يُنصح **بتغيير كلمات المرور** في بيئة الإنتاج لأسباب أمنية.
3. يمكنك إنشاء حسابات جديدة عبر واجهة المشرف (Admin -> إضافة مستخدم).

---
