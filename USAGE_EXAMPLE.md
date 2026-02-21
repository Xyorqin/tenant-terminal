# Foydalanish Misollari

## Asosiy Foydalanish

### 1. Paketni o'rnatish

Loyihangizning `composer.json` fayliga qo'shing:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "D:/OSPanel/domains/tenant-terminal"
        }
    ],
    "require": {
        "blaze/tenant-terminal": "*"
    }
}
```

Keyin:
```bash
composer update blaze/tenant-terminal
```

### 2. Terminalni ishga tushirish

```bash
php artisan tenant
```

### 3. Tenant tanlash

```
Available Tenants:

  [1] Acme Corporation (ID: 1)
  [2] Example Inc (ID: 2)
  [3] Test Company (ID: 3)

Select tenant number (or press Ctrl+X/Ctrl+C to exit): 1
```

### 4. Tenant kontekstida buyruqlar ishlatish

Tanlaganingizdan keyin, siz tenant kontekstidasiz:

```
Entered tenant context: Acme Corporation
Type 'exit' or press Ctrl+X to leave tenant context

tenant:Acme Corporation> migrate
tenant:Acme Corporation> db:seed
tenant:Acme Corporation> tinker
tenant:Acme Corporation> cache:clear
tenant:Acme Corporation> queue:work
```

### 5. Chiqish

```
tenant:Acme Corporation> exit
Exited tenant context.
```

Yoki `Ctrl+X` (Windows) yoki `Cmd+X` (Mac) bosing.

---

## Misol: Migration ishga tushirish

**Eski usul:**
```bash
php artisan tenants:run migrate --tenant=1
php artisan tenants:run migrate --tenant=2
php artisan tenants:run migrate --tenant=3
```

**Yangi usul:**
```bash
php artisan tenant
# Tenant 1 ni tanlang
tenant:Tenant1> migrate
# Chiqib, yana kirish
php artisan tenant
# Tenant 2 ni tanlang
tenant:Tenant2> migrate
```

---

## Misol: Database seeding

```
tenant:Acme Corporation> db:seed --class=UserSeeder
tenant:Acme Corporation> db:seed --class=ProductSeeder
```

---

## Misol: Tinker bilan ishlash

```
tenant:Acme Corporation> tinker
# Tinker ochiladi va tenant kontekstida ishlaydi
>>> User::count()
=> 150
>>> exit
tenant:Acme Corporation> exit
```

---

## Qo'shimcha buyruqlar

Har qanday artisan buyruq ishlaydi:

```
tenant:Acme Corporation> route:list
tenant:Acme Corporation> config:cache
tenant:Acme Corporation> queue:restart
tenant:Acme Corporation> schedule:run
```

---

## Eslatmalar

1. Har bir buyruq tenant kontekstida ishlaydi
2. `--tenant` flag kerak emas - siz allaqachon tenant ichidasiz
3. `exit` yozib yoki `Ctrl+X` bosib chiqishingiz mumkin
4. Chiqib ketganingizdan keyin, yana `php artisan tenant` qilib boshqa tenantga kirishingiz mumkin
