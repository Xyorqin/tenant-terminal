# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2024-01-XX

### Added
- Interactive tenant terminal command (`php artisan tenant`)
- Automatic tenant selection from list
- Support for stancl/tenancy package
- Support for spatie/laravel-multitenancy package
- Support for custom tenant models
- Terminal session with tenant context
- Command execution within tenant context
- Exit with `exit` command or `Ctrl+X` / `Cmd+X`
- Command history support (with readline)
- Improved command parsing with quoted strings support

### Features
- No need to specify `--tenant` flag for each command
- Easy tenant switching
- All artisan commands work within tenant context
