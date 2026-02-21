# Xyorqin Tenant Terminal

Interactive terminal for running Laravel commands within tenant context. Instead of typing `php artisan tenants:run command --tenant=id`, simply run `php artisan tenant`, select a tenant, and all subsequent commands will run in that tenant's context.

## Installation

### Via Composer (Packagist)

Eng oson usul - Packagist orqali:

```bash
composer require xyorqin/tenant-terminal
```

### Git Repository orqali

Agar paket hali Packagist da bo'lmasa:

1. Git repository URL ni oling (GitHub, GitLab, Bitbucket, va h.k.)

2. Loyihangizning `composer.json` fayliga qo'shing:

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

3. Composer orqali o'rnating:

```bash
composer require xyorqin/tenant-terminal:dev-main
```

### Local Path orqali (Development)

1. Paket local papkada bo'lsa:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "./path/to/tenant-terminal"
        }
    ],
    "require": {
        "xyorqin/tenant-terminal": "*"
    }
}
```

2. Run `composer update`

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=tenant-terminal-config
```

This will create a `config/tenant-terminal.php` file where you can customize the package settings.

### Supported Tenant Packages

The package automatically detects and works with:
- **stancl/tenancy** - Most popular Laravel tenancy package
- **spatie/laravel-multitenancy** - Spatie's multitenancy package
- **Custom tenant models** - Configure via `tenant_model` in config

## Usage

### Basic Usage

1. Run the tenant command:
   ```bash
   php artisan tenant
   ```

2. You'll see a list of available tenants:
   ```
   Available Tenants:

     [1] Tenant Name (ID: 1)
     [2] Another Tenant (ID: 2)
     [3] Third Tenant (ID: 3)

   Select tenant number (or press Ctrl+X to exit):
   ```

3. Enter the tenant number to select it

4. You're now in tenant context! Any command you run will execute within that tenant:
   ```
   tenant:Tenant Name> migrate
   tenant:Tenant Name> db:seed
   tenant:Tenant Name> tinker
   ```

5. To exit, type `exit` or press `Ctrl+X` (or `Cmd+X` on Mac)

### Example Session

```bash
$ php artisan tenant

Available Tenants:

  [1] Acme Corp (ID: 1)
  [2] Example Inc (ID: 2)

Select tenant number: 1

Entered tenant context: Acme Corp
Type 'exit' or press Ctrl+X to leave tenant context

tenant:Acme Corp> migrate
Running migrations...
tenant:Acme Corp> db:seed --class=UserSeeder
Seeding database...
tenant:Acme Corp> exit
Exited tenant context.
```

## Features

- ✅ Interactive tenant selection
- ✅ Automatic tenant context initialization
- ✅ All artisan commands work within tenant context
- ✅ No need to specify `--tenant` flag for each command
- ✅ Easy exit with `exit` command or `Ctrl+X`
- ✅ Supports multiple tenant packages
- ✅ Command history support (if readline is available)

## Requirements

- PHP >= 8.1
- Laravel >= 10.0
- A tenant package (stancl/tenancy, spatie/laravel-multitenancy, or custom)

## License

MIT

## Author

Xyorqin
