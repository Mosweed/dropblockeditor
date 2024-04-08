<p><img src="./logos/logo1.svg" alt="Logo DropBlockEditor"></p>


## Introduction

The DropBlockEditor package provides you with a cool drag and drop editor. It allows you to easily create your own custom blocks and make them editable through Livewire components.

- > Note: This package is in the pre-release stage. Code is subject to change and should not considered stable at this time.
- > To use with Livewire v3.
- > PHP 8.1 or higher

## Installation

 Begin by installing this package through Composer. Just run following command to terminal-

```shell script
composer require shazzoo/dropblockeditor
```

 Once this operation completes,  Run  following command to terminal to publish all files

 ```shell script
php artisan vendor:publish --provider="Shazzoo\DropBlockEditor\DropBlockEditorServiceProvider" --force
```

 Once this operation completes, Open `routes/web.php`, and add this code on the eind.

```php
require __DIR__.'/dropblockeditor.php';
```

# Routes dropblockeditor

Route::get('update_page/{page:slug}', PageEiditor::class)->name('pages.edit');
Route::get('create_page', PageEiditor::class)->name('pages.create');

# Create blacks

 ```shell script
php artisan dropblockeditor:make  blackname
```

## Changelog

php artisan vendor:publish --provider="Shazzoo\DropBlockEditor\DropBlockEditorServiceProvider" --force

## Security Vulnerabilities

If you discover any security-related issues, please [send an email](https://vanrossum.dev/en/contact) instead of using the issue tracker.

## Credits

- [Mohmad Yazan Sweed](https://github.com/ps200735)
- Hamzah Sari

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
