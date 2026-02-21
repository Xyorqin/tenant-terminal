# Git Repository orqali O'rnatish

## Qadam 1: Git Repositoryga Push Qilish

### 1.1. Git repository yaratish (agar yo'q bo'lsa)

```bash
cd D:\OSPanel\domains\tenant-terminal
git init
git add .
git commit -m "Initial commit: Blaze Tenant Terminal package"
```

### 1.2. Remote repository qo'shing

**GitHub, GitLab yoki Bitbucket da repository yarating, keyin:**

```bash
git remote add origin https://github.com/your-username/tenant-terminal.git
# yoki
git remote add origin git@github.com:your-username/tenant-terminal.git
```

### 1.3. Push qiling

```bash
git branch -M main
git push -u origin main
```

---

## Qadam 2: Boshqa Loyihada O'rnatish

### 2.1. `composer.json` ga repository qo'shing

Boshqa loyihangizning `composer.json` fayliga:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/your-username/tenant-terminal.git"
        }
    ],
    "require": {
        "blaze/tenant-terminal": "dev-main"
    }
}
```

**Yoki SSH orqali:**

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:your-username/tenant-terminal.git"
        }
    ],
    "require": {
        "blaze/tenant-terminal": "dev-main"
    }
}
```

### 2.2. Composer orqali o'rnatish

```bash
composer require blaze/tenant-terminal:dev-main
```

Yoki:

```bash
composer update blaze/tenant-terminal
```

---

## Branch va Tag ishlatish

### Specific branch o'rnatish:

```json
{
    "require": {
        "blaze/tenant-terminal": "dev-feature-branch"
    }
}
```

### Tag yaratish va ishlatish:

```bash
# Paket papkasida
git tag -a v1.0.0 -m "Version 1.0.0"
git push origin v1.0.0
```

Keyin loyihada:

```json
{
    "require": {
        "blaze/tenant-terminal": "v1.0.0"
    }
}
```

---

## Misol: To'liq composer.json

```json
{
    "name": "your-project/app",
    "type": "project",
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/your-username/tenant-terminal.git"
        }
    ],
    "require": {
        "php": "^8.1",
        "blaze/tenant-terminal": "dev-main",
        "laravel/framework": "^10.0"
    }
}
```

---

## Eslatmalar

1. **Private Repository:** Agar private repository bo'lsa, GitHub token yoki SSH key kerak bo'ladi
2. **Branch nomi:** `dev-main` o'rniga `dev-master` yoki boshqa branch nomini ishlatishingiz mumkin
3. **HTTPS vs SSH:** 
   - HTTPS: Oddiy, lekin har safar credential so'raydi
   - SSH: Bir marta setup qilsangiz, keyin credential so'ramaydi

---

## Private Repository uchun

Agar repository private bo'lsa:

### GitHub Personal Access Token:

1. GitHub Settings > Developer settings > Personal access tokens
2. Token yarating
3. Composer config:

```bash
composer config --global github-oauth.github.com YOUR_TOKEN
```

### SSH Key:

```bash
ssh-keygen -t ed25519 -C "your_email@example.com"
# Key ni GitHub account ga qo'shing
```

---

## Tekshirish

Paket o'rnatilgandan keyin:

```bash
php artisan list | grep tenant
php artisan tenant
```
