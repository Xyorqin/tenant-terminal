# Packagist.org ga Yuklash - To'liq Qo'llanma

## Packagist nima?

Packagist - bu PHP paketlari uchun asosiy repository. Bu yerda paketlaringizni yuklasangiz, hamma `composer require` orqali o'rnatishi mumkin.

---

## Qadam 1: Packagist Account Yaratish

1. https://packagist.org ga kiring
2. "Sign up" ni bosing
3. GitHub/GitLab/Bitbucket account orqali ro'yxatdan o'ting

---

## Qadam 2: Repository ni Packagist ga Qo'shish

### 2.1. Packagist dashboard ga kiring

https://packagist.org/profile/ ga kiring

### 2.2. "Submit" yoki "Submit Package" ni bosing

### 2.3. Git Repository URL ni kiriting

Masalan:
```
https://github.com/your-username/tenant-terminal.git
```

Yoki:
```
git@github.com:your-username/tenant-terminal.git
```

### 2.4. "Check" yoki "Submit" ni bosing

Packagist avtomatik ravishda:
- `composer.json` ni tekshiradi
- Paket nomini (`xyorqin/tenant-terminal`) ko'rsatadi
- Paketni yuklaydi

---

## Qadam 3: Webhook Sozlash (Ixtiyoriy, lekin tavsiya etiladi)

Har safar Git ga push qilganingizda, Packagist avtomatik yangilanishi uchun:

### GitHub uchun:

1. Packagist dashboard da paketingizni oching
2. "Settings" yoki "Webhook" bo'limiga kiring
3. GitHub repository ga kiring
4. Settings > Webhooks > Add webhook
5. Payload URL: `https://packagist.org/api/webhooks/github?packages=YOUR_USERNAME/tenant-terminal`
6. Content type: `application/json`
7. "Just the push event" ni tanlang
8. "Add webhook" ni bosing

### GitLab uchun:

1. Repository > Settings > Webhooks
2. URL: `https://packagist.org/api/webhooks/gitlab?packages=YOUR_USERNAME/tenant-terminal`
3. Trigger: Push events
4. "Add webhook"

---

## Qadam 4: Tekshirish

Paket yuklangandan keyin:

```bash
composer search xyorqin/tenant-terminal
```

Yoki to'g'ridan-to'g'ri:

```bash
composer require xyorqin/tenant-terminal
```

**Eslatma:** Avval `composer.json` da `"minimum-stability": "dev"` bo'lishi kerak yoki versiya tag yaratish kerak.

---

## Qadam 5: Versiya Yaratish (Tavsiya etiladi)

Packagist da versiyalar tag orqali ishlaydi:

### 5.1. Tag yaratish:

```bash
git tag -a v1.0.0 -m "Version 1.0.0"
git push origin v1.0.0
```

### 5.2. Composer.json da versiya tekshirish:

Packagist avtomatik tag larni aniqlaydi. Lekin `composer.json` da versiya ko'rsatish yaxshi:

```json
{
    "version": "1.0.0"
}
```

**Eslatma:** `composer.json` da `version` field i tavsiya etilmaydi, lekin ba'zi hollarda foydali.

---

## Qadam 6: Foydalanish

Endi hamma paketingizni o'rnatishi mumkin:

```bash
composer require xyorqin/tenant-terminal
```

Yoki specific versiya:

```bash
composer require xyorqin/tenant-terminal:^1.0
```

---

## Muammolar va Yechimlar

### 1. Paket topilmayapti

- Packagist da paket yuklanganligini tekshiring
- `composer clear-cache` qiling
- Paket nomi to'g'ri ekanligini tekshiring

### 2. Versiya topilmayapti

- Git tag yaratilganligini tekshiring
- Tag nomi `v1.0.0` formatida bo'lishi kerak
- Packagist da "Update" ni bosing

### 3. Webhook ishlamayapti

- GitHub/GitLab webhook sozlamalarini tekshiring
- Packagist da "Update" ni manual bosing
- Webhook URL to'g'ri ekanligini tekshiring

---

## Best Practices

1. **Semantic Versioning:** `v1.0.0`, `v1.1.0`, `v2.0.0` formatida versiya yarating
2. **CHANGELOG.md:** O'zgarishlarni yozing
3. **README.md:** To'liq dokumentatsiya
4. **LICENSE:** Litsenziya fayli qo'shing
5. **Tags:** Har bir release uchun tag yarating

---

## Misol: To'liq Jarayon

```bash
# 1. Git repository ga push qilish
git add .
git commit -m "Release v1.0.0"
git push

# 2. Tag yaratish
git tag -a v1.0.0 -m "Version 1.0.0"
git push origin v1.0.0

# 3. Packagist ga yuklash (yoki webhook avtomatik qiladi)

# 4. Tekshirish
composer require xyorqin/tenant-terminal
```

---

## Foydali Linklar

- Packagist: https://packagist.org
- Packagist Submit: https://packagist.org/packages/submit
- Semantic Versioning: https://semver.org
