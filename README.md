# Jetstream Powered Admin CRUD Generator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/savannabits/jetstream-inertia-generator.svg?style=flat-square)](https://packagist.org/packages/savannabits/jetstream-inertia-generator)
[![Build Status](https://img.shields.io/travis/coolsam726/jetstream-inertia-generator/master.svg?style=flat-square)](https://travis-ci.com/coolsam726/jetstream-inertia-generator)
[![Quality Score](https://img.shields.io/scrutinizer/g/coolsam726/jetstream-inertia-generator.svg?style=flat-square)](https://scrutinizer-ci.com/g/coolsam726/jetstream-inertia-generator)
[![Total Downloads](https://img.shields.io/packagist/dt/savannabits/jetstream-inertia-generator.svg?style=flat-square)](https://packagist.org/packages/savannabits/jetstream-inertia-generator)

**Jetstream Inertia Generator** a.k.a **jig** allows you to generate code for simple Admin CRUDs (Create, Read, UPdate, Delete) which are fully compatible with a Laravel Project powered by the [Jetstream - Inertia - Vue.js](https://jetstream.laravel.com/2.x/stacks/inertia.html) Stack. 
## Scenario
You are developing a NextGen project. The data model is complex. It requires **Many CRUDS** managed by the admin in order to power the main end-user functionality. You don't want to spend **Days or even Months** writing boilerplate code for all the CRUDs.
If that is you, this package comes to your rescue. Just follow these simple steps:

* Generate a Migration for your CRUD table, e.g articles, and run `php artisan migrate` (About **2 minutes**)
* With this package, just run `php artisan jig:generate articles` (About **3 seconds!!!**)
* Build your css and javascript (About **27 seconds**)

DONE! In about **2 and a half minutes**, you get a fully working module consisting of -:
- Model
- Admin Controller - Index, Create, Show, Edit, Store, Update, Delete
- API Controller - Index, Store, Show, Update, Delete
- An Authorization Policy - viewAny, view, create, update, delete, restore, forceDelete
- Generated Permissions for [spatie/laravel-permissions](https://spatie.be/docs/laravel-permission/v4/introduction) - articles, articles.index, articles.create, articles.show, articles.edit, articles.delete
- Frontend Menu entry
- Frontend Datatable with Actions thanks to [savannabits/pagetables](https://github.com/savannabits/pagetables)
- Tailwindcss-powered CREATE and EDIT forms,
- Tailwindcss - powered SHOW view.
- web and API routes
- Validation and Authorization Request Classes

What more could you ask for? Cut a day's work down to less than 3 minutes.

## Dependencies
If you have followed the [Jetstream - Inertia - Vue.js Installation](https://jetstream.laravel.com/2.x/stacks/inertia.html) instructions, then the project will work with minimal configuration.
### Composer dependencies:
```json
{
        "php": "^7.4",
        "doctrine/dbal": "^3.0",
        "illuminate/support": "^8.0",
        "inertiajs/inertia-laravel": "^v0.3.6",
        "laravel/helpers": "^1.4",
        "laravel/jetstream": "^2.1",
        "savannabits/laravel-pagetables": "^1.0.0",
        "spatie/laravel-permission": "^3.18.0"
}
```
These will be installed automatically when installing the package, but if you want additional configuration steps, be sure to check out their installation and configuration instructions
### NPM Dependencies
You need to install the following if you haven't using either `yarn` or `npm` in order for the generated code to compile without hickups.
__NB__ Again, if you followed Jetstream's installation instructions, most of these dependencies are already installed. Install only the missing ones.
```json
{
        "@babel/plugin-syntax-dynamic-import": "^7.8.3",
        "@inertiajs/inertia": "^0.8.2",
        "@inertiajs/inertia-vue": "^0.5.4",
        "@tailwindcss/forms": "^0.2.1",
        "@tailwindcss/typography": "^0.3.0",
        "autoprefixer": "^10.0.2",
        "axios": "^0.21",
        "laravel-mix": "^6.0.6",
        "lodash": "^4.17.19",
        "pagetables": "^0.0.3",
        "popper.js": "^1.16.1",
        "portal-vue": "^2.1.7",
        "postcss": "^8.1.14",
        "postcss-import": "^12.0.1",
        "tailwindcss": "^2.0.1",
        "vue": "^2.5.17",
        "vue-loader": "^15.9.6",
        "vue-select": "^3.11.2",
        "vue-template-compiler": "^2.6.10"
    }
```
## Installation

1. You can install the package via composer:
```bash
composer require savannabits/jetstream-inertia-generator
```
2. Install the yarn dependencies listed above by adding them as `devDependencies` in `packages.json` and running `yarn install` or `npm install`,
3. Ensure your webpack mix is properly configured to support [code splitting](https://inertiajs.com/client-side-setup).
4.Ensure tailwind.config.js is present and has the correct configuration, including the @tailwindcss/forms plugin, and all the necessary paths for purge. Here is my recommendation:
   
```js
const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    purge: [
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {...defaultTheme.colors.indigo,default: defaultTheme.colors.indigo["500"]}
            }
        },
    },

    variants: {
        extend: {
            opacity: ['disabled'],
        },
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};

```
## Usage
The hard part is over. This is the easy part.
### General Steps:
1. Publish the Package's assets and views. This is necessary for you to get the admin layout and all the vue components used in the generated code:
```shell
php artisan vendor:publish --tag=jig-views
```
2. Generate and write a migration for your table with `php artisan make:migration` command.
3. Run the migration to the database with `php artisan migrate` command
4. Generate the Whole Admin Scaffold for the module with `php artisan jig:generate` command
### Example
Assuming you want to generate a `books` table:
```shell
php artisan make:migration create_books_table
```
* Open your migration and modify it as necessary, adding your fields. After that, run the migration.
```shell
php artisan migrate
```
* __The Fun Part:__ Scaffold a whole admin module for books with jig using the following command:
```shell
php artisan jig:generate books #Format: php artisan generate [table_name] [-f]
```
__NB:__ To get a full list of `jig` commands called under the hood and the full description of the `jig:generate` command, you can run the following: 
```shell
php artisan jig --help
php artisan jig:generate --help
```
The command above will generate a number of files and add routes to both your `api.php` and `web.php` route files. It will also append menu entries to the published `Menus.json` file.
The generated vue files are placed under the Pages/Books folder in the js folder.

* Finally, run `yarn watch, yarn dev or yarn prod` to compile the assets. There you have your CRUD.

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email maosa.sam@gmail.com instead of using the issue tracker.

## Credits

- [Sam Maosa](https://github.com/savannabits)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
