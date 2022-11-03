# Add required configs for Fortify and Sanctum based SPA auth

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mmedia/laravel-spa.svg?style=flat-square)](https://packagist.org/packages/mmedia/laravel-spa)
[![Total Downloads](https://img.shields.io/packagist/dt/mmedia/laravel-spa.svg?style=flat-square)](https://packagist.org/packages/mmedia/laravel-spa)
![GitHub Actions](https://github.com/mmedia/laravel-spa/actions/workflows/main.yml/badge.svg)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

### What this package does
- Adds CORS paths for Sanctum cookie and Fortify routes
- Forces CORS with_credentials to true
- Adds API route to get currently authenticated user (both for cookie based auth and API token based auth)
- Adds optional route to check if email exists (like Google login where it asks you for your email, and depending on if it exists or not, it will either ask you for password or create a new account)
- Forces Fortify config option "views" to false

## Installation

You can install the package via composer:

```bash
composer require mmedia/laravel-spa
```

## Usage

```php
// Usage description here
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email contact@mmediagroup.fr instead of using the issue tracker.

## Credits

-   [M Media](https://github.com/mmedia)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
