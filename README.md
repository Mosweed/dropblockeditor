<p><img src="./logos/logo1.svg" alt="Logo DropBlockEditor"></p>


# Introduction

The DropBlockEditor package provides you with a cool drag and drop editor. It allows you to easily create your own custom blocks and make them editable through Livewire components.

- > Note: This package is in the pre-release stage. Code is subject to change and should not considered stable at this time.

# Requirements

To use this package, you'll need:

- > To use with Livewire v3.
- > PHP 8.1 or higher

# Installation

You can install the package via composer:

```shell script
composer require mo_sweed/dropblockeditor
```

You can publish all files with:

```shell script
php artisan vendor:publish --provider="Mo_sweed\DropBlockEditor\DropBlockEditorServiceProvider" –-force
```

Once all files are published, open `routes/web.php`, and add this code at the end of the page:

```php
require __DIR__.'/dropblockeditor.php';
```

After that, run this command:

```shell script
php artisan migrate
```

## Run the following artisan command to create a new Block with an edit component

```shell script
php artisan dropblockeditor:make Text
```

# Migrations

- > **Name:** pages

# Model

- > **Name:** pages
- > **Data:** [title, slug, status, content]
- > The Route key is a slug

# Route

- > `update_page/{page:slug}`: if you have a page
- > `create_page`: to make a page
- > Both routes using the same page: PageEiditor

# PageEiditor

- > **Location:** App\Livewire\DropBlockEditor
- > **Function:** Check if the URL has `(page:slug)`. If a page exists with `(page:slug)`, then send all info page to DropBlockEditor. Else return 404. If the URL doesn't have `(page:slug)`, then call DropBlockEditor.

# DropBlockEditor

- > **Location:** App\Livewire\DropBlockEditor
- > **Function:** Get all existing blocks, page settings, and change Blocks location.

# Blocks

## A block has a visual side which is the `Block` class and an optional modified Livewire component (`BlockEditComponent`) to make it editable.

- > **Block location:** App\Classes\Blocks
- > **BlockEditComponent:** App\Livewire\DropBlockEditor\Components

## Credits

- [Mohmad Yazan Sweed](https://github.com/Mosweed)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
