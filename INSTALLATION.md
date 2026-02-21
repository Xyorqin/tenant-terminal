# Paketni O'rnatish

## Usul 1: Local Path Repository (Rekomendatsiya - Development uchun)

Agar paket sizning kompyuteringizda boshqa joyda bo'lsa:

### 1-qadam: Boshqa loyihangizning `composer.json` fayliga qo'shing:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "../tenant-terminal"
        }
    ],
    "require": {
        "xyorqin/tenant-terminal": "*"
    }
}
```

**Eslatma:** `../tenant-terminal` o'rniga paketning to'liq pathini yozing. Masalan:
- Windows: `"D:/OSPanel/domains/tenant-terminal"`
- Linux/Mac: `"/home/user/packages/tenant-terminal"`

### 2-qadam: Composer update qiling:

```bash
composer update xyorqin/tenant-terminal
```

### 3-qadam: Paket o'rnatiladi va symlink yaratiladi (development uchun qulay)

---

## Usul 2: Git Repository orqali

Agar paketni Git repositoryga yuklagan bo'lsangiz:

### 1-qadam: `composer.json` ga repository qo'shing:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/your-username/tenant-terminal.git"
        }
    ],
    "require": {
        "xyorqin/tenant-terminal": "dev-main"
    }
}
```

### 2-qadam: Composer install/update:

```bash
composer require xyorqin/tenant-terminal:dev-main
```

---

## Usul 3: Packagist orqali (Production uchun)

Agar paketni Packagist.org ga yuklagan bo'lsangiz:

```bash
composer require xyorqin/tenant-terminal
```

---

## O'rnatilgandan keyin

Paket o'rnatilgandan keyin, Laravel avtomatik ravishda service provider ni yuklaydi.

### Tekshirish:

```bash
php artisan list | grep tenant
```

Siz `tenant` komandasini ko'rishingiz kerak.

### Ishlatish:

```bash
php artisan tenant
```

---

## Muammo bo'lsa

Agar paket topilmasa:

1. `composer.json` da `repositories` to'g'ri ekanligini tekshiring
2. Path to'g'ri ekanligini tekshiring
3. `composer clear-cache` qiling
4. `composer dump-autoload` qiling
