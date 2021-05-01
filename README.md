# Jetstream Powered Admin CRUD Generator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/savannabits/jetstream-inertia-generator.svg?style=flat-square)](https://packagist.org/packages/savannabits/jetstream-inertia-generator)
![GitHub code size in bytes](https://img.shields.io/github/languages/code-size/coolsam726/jetstream-inertia-generator)
[![Travis (.com) Build](https://img.shields.io/travis/com/coolsam726/jetstream-inertia-generator/master?label=travis-ci)](https://travis-ci.com/github/coolsam726/jetstream-inertia-generator)
[![Scrutinizer build ](https://img.shields.io/scrutinizer/build/g/coolsam726/jetstream-inertia-generator/master?label=scrutnizer-build)](https://scrutinizer-ci.com/g/coolsam726/jetstream-inertia-generator)
[![Total Downloads](https://img.shields.io/packagist/dt/savannabits/jetstream-inertia-generator.svg?style=flat-square)](https://packagist.org/packages/savannabits/jetstream-inertia-generator)

**Jetstream Inertia Generator** a.k.a **jig** allows you to generate code for simple Admin CRUDs (Create, Read, UPdate, Delete) which are fully compatible with a Laravel Project powered by the [Jetstream - Inertia - Vue.js](https://jetstream.laravel.com/2.x/stacks/inertia.html) Stack.
![](.README_images/jig-preview.gif)
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
- Frontend Datatable with Actions thanks to Using Yajra Datatables and datatables.net
- Tailwindcss-powered CREATE and EDIT forms,
- Tailwindcss - powered SHOW view.
- web and API routes
- Validation and Authorization Request Classes

What more could you ask for? Cut a day's work down to less than 3 minutes.

## Dependencies
If you have followed the [Jetstream - Inertia - Vue.js Installation](https://jetstream.laravel.com/2.x/stacks/inertia.html) instructions, then the project will work with minimal configuration.
Other Important dependencies that you MUST configure include:
1. [Spatie Laravel Permissions](https://spatie.be/docs/laravel-permission/v4/introduction) - This is used to manage roles and permissions. Its migrations will be published during asset publishing, after which you can go ahead and configure the user trait.
2. [Laravel Sanctum](https://laravel.com/docs/8.x/sanctum) - Used to manage both API and stateful authentication. Since the whole app will be a Single Page Application, make sure you configure the middleware sanctum middleware in `app/Http/Kernel.php` as follows:
```php
'api' => [
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    ...
],
```

## Installation
1. You can install the package via composer:
```bash
composer require savannabits/jetstream-inertia-generator
```
2. Install the necessary `npm` dev dependencies by running the following command:
If you are using npm:
```shell
npm run --dev pagetables popper.js @babel/plugin-syntax-dynamic-import dayjs dotenv numeral portal-vue postcss postcss-import pusher-js laravel-echo sass sass-loader vue3-vt-notifications vue-flatpickr-component  vue-numerals vue-pdf mitt "https://github.com/sagalbot/vue-select/tarball/feat/vue-3-compat"
```
Or if you are using yarn:
```shell
yarn add -D pagetables popper.js @babel/plugin-syntax-dynamic-import dayjs dotenv numeral portal-vue postcss postcss-import pusher-js laravel-echo sass sass-loader vue3-vt-notifications vue-flatpickr-component  vue-numerals vue-pdf mitt "https://github.com/sagalbot/vue-select/tarball/feat/vue-3-compat"
```
Feel free to configure the color palette to your own preference, but for uniformity be sure to include `primary`,`secondary`, `success` and `danger` variants since they are used in the jig template.
3.  Publish the Package's assets, configs, templates, components and layouts.
   This is necessary for you to get the admin layout and all the vue components used in the generated code:

__Option 1__ (Suitable for fresh installations)
```shell
php artisan vendor:publish --force --provider="Savannabits\JetstreamInertiaGenerator\JetstreamInertiaGeneratorServiceProvider"
```

__Option 2__ (Useful if you are upgrading the package or already have local changes you don't want to override.)
NB: If you only want to update some published files, delete only the published files that you want to update, then run the appropriate command below:
```shell
php artisan vendor:publish --tag=jig-blade-templates #Publishes resources/views/app.blade.php. If it already exists, use --force to replace it
php artisan vendor:publish --tag=jig-config #Publishes the config file. If it exists use --force to replace it.
php artisan vendor:publish --tag=jig-routes #Publishes routes/jig.php to hold routes for generated modules.If you have already generated some routes, be sure to back them up as this file will be reset if you --force it.
php artisan vendor:publish --tag=jig-views #publishes Vue Components, app.js, bootstrap.js and Layout files. Use --force to force replace
php artisan vendor:publish --tag=jig-assets #publishes logos and other assets
php artisan vendor:publish --tag=jig-compiler-configs #publishes webpack.config.js and tailwind.config.js
php artisan vendor:publish --tag=jig-migrations #Publish database migrations
```
4. Then finish installation steps for spatie/laravel-permission by publishing its migrations.
```shell
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```
NB: The `title` field will be automatically added to the `roles` and `permissions` tables when the first CRUD is generated.

5. Add the `JigMiddleware` to the Global middleware and the `web` middleware group in `app/Http/Kernel.php`:
```php
protected $middleware = [
    ...,
    \Savannabits\JetstreamInertiaGenerator\Middleware\JigMiddleware::class,
];

protected $middlewareGroups = [
    'web' => [
        ...,
        \Savannabits\JetstreamInertiaGenerator\Middleware\JigMiddleware::class,
    ],
];
```
6. Allow First-Party access to the Sanctum API by adding the following to the `api` middleware group in `app/Http/Kernel.php`
```php
protected $middlewareGroups = [
    'api' => [
        \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ...
    ],
];
```
7. Modify the .env to have the following keys:
```env
APP_BASE_DOMAIN=mydomain.test
APP_SCHEME=http #or https
#optional mix_app_uri (The path under which the app will be served. It is recommended to run the app from the root of the domain.
MIX_APP_URI= 
APP_URL=${APP_SCHEME}://${APP_BASE_DOMAIN} #If MIX_APP_URI is empty.
#APP_URL=${APP_SCHEME}://${APP_BASE_DOMAIN}/${MIX_APP_URI} #If MIX_APP_URI is not empty.

# Append the following key to your .env to allow 1st party consumption of your api:
SANCTUM_STATEFUL_DOMAINS="${APP_BASE_DOMAIN}" #You can add other comma separated domains
```
8. create the storage:link (See laravel documentation) to allow access to the public disk assets (e.g logos) via web:
```shell
php artisan storage:link
```

## Usage
### The initial seeded admin user and role
When you run `php artisan vendor:publish --tag=jig-migrations`, a migration is published that creates an initial default user called `Administrator` and a role with the name `administrator` to enable you gain access to the system with admin privileges. The credentials for the user account are:
* Email: **admin@savannabits.com**
* Password: **password**
Use this to login and explore all parts of the application

#### Create the Permissions, Roles and Users Modules first, in that order:
Run the following commands to generate the User Access Control Module before proceeding to generate your admin:
```shell
php artisan jig:generate:permission -f
php artisan jig:generate:role -f
php artisan jig:generate:user -f
```
You can now proceed to generate any other CRUD you want using the steps in the following section.
### General Steps to generate a CRUD:
![](.README_images/jig-generate.gif)
1. Generate and write a migration for your table with `php artisan make:migration` command.
2. Run the migration to the database with `php artisan migrate` command
3. Generate the Whole Admin Scaffold for the module with `php artisan jig:generate` command
4. Modify and customize the generated code to suit your specific requirements if necessary.
__NB: If the crud already exists, and you would like to generate, you can use the `-f` or `--force` option to force replacement of files.
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
## Roles, permissions and Sidebar Menu:
* By Default, generation of a module generates the following permissions:
    - index
    - create
    - show
    - edit
    - delete
    
* The naming convention for permissions is ${module-name}.${perm} e.g payments.index, users.create etc.
* This package manages access control using policies. Each generated module generates a policy with the default laravel actions:
    - viewAny, view, store, update, delete, restore, forceDelete
  The permissions generated above are checked in these policies. If you need to modify any of the access permissions, policies is where to look.
      
* Special permissions MUST also be generated to control access to the sidebar menus. These permissions SHOULD NOT contain two parts separated with a dot, but only one part.
* Menus are configured in a json file published at `./resources/js/Layouts/Jig/Menu.json`. 
    - For all menu items, the json key MUST match the permision that controls that menu. A permission without any verb is generated when generating each module for this very purpose. For example, generating a `payments` module will generate a `payments` permission.
      Then the menu for payments must have `payments` as the json key.
    - For parent menus and any other menus which may not match any module, you have to create a permission with the key name to control its access. For example, if you have a parent menu called `master-data` you have to generate a permission with the same name.

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
