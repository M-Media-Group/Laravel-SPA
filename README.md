# Add required configs for Fortify and Sanctum based SPA auth

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mmedia/laravel-spa.svg?style=flat-square)](https://packagist.org/packages/mmedia/laravel-spa)
[![Total Downloads](https://img.shields.io/packagist/dt/mmedia/laravel-spa.svg?style=flat-square)](https://packagist.org/packages/mmedia/laravel-spa)
![GitHub Actions](https://github.com/mmedia/laravel-spa/actions/workflows/main.yml/badge.svg)

Because Fortify is designed to be frontend agnostic, it requires some configuration if you'd like to use it with an SPA on a subdomain and Sanctum. This package provides the required configurations for that.

### What this package does
- Adds CORS paths for Sanctum cookie and Fortify routes, including login, logout, and more
- Forces CORS with_credentials to true
- Adds API route to get currently authenticated user (both for cookie based auth and API token based auth), /api/user
- Adds optional route to check if email exists (like Google login where it asks you for your email, and depending on if it exists or not, it will either ask you for password or create a new account)
- Forces Fortify config option "views" to true, where each view redirects to the SPA as defined in the config (see below)
- Adds loginView, registerView, twoFactorChallengeView, requestPasswordResetLinkView, resetPasswordView, verifyEmailView, and confirmPasswordView definitions for Fortify that redirect to your SPA app, with fully configurable paths (useful for links sent in emails, for example)

## Installation

You can install the package via composer:

```bash
composer require mmedia/laravel-spa
```

## Usage

The service provider will automatically add the functionality as described above. You can publish the config file with:

```bash
php artisan vendor:publish --provider="Mmedia\LaravelSpa\LaravelSpaServiceProvider" --tag="config"
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
