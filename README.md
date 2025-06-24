# WinterCMS Multi Databases

[WinterCMS](http://wintercms.com/) Plugin to create SaaS applications in different databases, making it easier to manage multiple clients.
To develop this plugin I reused several classes that WinterCMS itself has, trying to create the simplest code possible.

**Important**
This plugin is still in the testing and development stages, use at your own risk.

## Usage
### Installation
You can install this plugin either via composer.

#### With Composer
Execute below at the root of your project.
```
composer require sommer/wn-multidb-plugin
```

### Settings MultiDB - Backend
To use MultiDB you will need to follow the steps below.

**Important**
It is necessary to have a user in the database who can Read, Edit and Update data, as well as create new databases.

### Configuration .env
This is optional, but if you want to configure data from another connection in the tenants, you can put Multidb in the .env. If you don't put anything, it will automatically assume the access data from your main database server that is used in DB_*.

```
MULTIDB_CONNECTION=multidb
MULTIDB_HOST=127.0.0.1
MULTIDB_PORT=3306
MULTIDB_DATABASE=my_base
MULTIDB_USERNAME=root
MULTIDB_PASSWORD=
```

#### MultiDB Plugin
First Navigate to `Settings -> MultiDB -> Settings Plugin`, in this place you can configure the prefixes that the databases may have, configure what will be the names of each databases, and also, you can select which plugins will be replicated for the new created instances.

**Save changes to update data**

### Using in PHP Code
After making the configurations mentioned above, it is necessary to edit the models of the plugins.

#### Trait UsesTenantConnection
This trait is responsible for managing the data that will be saved in the databases. It will be necessary to add the following trait to all models that have the replicated database, example below:

```php
class Product extends Model
{
	use \Sommer\Multidb\Traits\UsesTenantConnection;

	...
}
```
#### Trait UsesMainConnection
This Trait allows you to relate a table from the main database to the child database, for example, you have a Customers plugin, this plugin is using the UsesTenantConnection trait, however ou have a Core plugin that has a Country Model, this plugin you are not replicating, but you need to relate the `country_id` attribute of the customers table to the country table, in this situation there would be an error because MultiDB change connections momentarily, so as not to have a problem, you need to add in your model Country the trait below.

```php
class Product extends Model
{
    use \Sommer\Multidb\Traits\UsesMainConnection;

    ...
}
```

### Behaviors
#### TenantSelectionBehavior
The TenantSelectionBehavior class added to the controller will allow the user to choose a Tenant to view once they try to access this controller (if they haven't selected one previously). At this point, this behavior adds a button next to the user's image to the navigation menu, which allows them to change the selected Tenant.

```php
class Summaries extends Controller
{
    /**
     * @var array
     */
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Sommer\MultiDB\Behaviors\TenantSelectionBehavior', // <-- add this line
    ];
}
```


### Models Helpers
#### Files
If in case you want to save the files in the new database, you will need to use the model `Sommer\Multidb\Models\File`, example use:

```php
class Product extends Model
{
	use \Sommer\Multidb\Traits\UsesTenantConnection;

	...

	public $attachMany = [
        'images' => [
            'Sommer\Multidb\Models\File',
            'softDelete' => true,
        ],
    ];

    ...
}
```

## License
This plugin is released under the [MIT License](https://opensource.org/license/mit/). You can freely use, modify, and distribute it in your projects, but please keep the original license intact.

## Sponsors
If you find this plugin useful and would like to support its development, consider sponsoring the project. Your contributions help keep the project alive and improve its features.
You can sponsor the project on [GitHub Sponsors](https://github.com/sponsors/phpiando).

## Contributing
Contributions are welcome! If you find any issues or have suggestions for improvements, feel free to open an issue or submit a pull request on the [GitHub repository](https://github.com/phpiando/wn-multidb-plugin).