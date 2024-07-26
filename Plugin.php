<?php namespace Sommer\MultiDB;

use Backend\Facades\Backend;
use Sommer\MultiDB\Classes\TenantCacheManager;
use Sommer\MultiDB\Classes\TenantManager;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    /**
     * Register the plugin
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TenantManager::class, function ($app) {
            return new TenantManager;
        });

        $this->app->singleton('cache.tenant', function ($app) {
            return new TenantCacheManager($app);
        });

        $this->registerConsoleCommand('multidb:install', 'Sommer\Multidb\Console\TenantMultiDBInstall');
        $this->registerConsoleCommand('multidb:up', 'Sommer\Multidb\Console\TenantMultiDBUpdate');
    }

    public function registerComponents()
    {
    }

    public function registerSettings()
    {
        return [
            'sommer_multidb_settings' => [
                'label' => 'sommer.multidb::lang.menus.settings.label',
                'description' => 'sommer.multidb::lang.menus.settings.description',
                'category' => 'sommer.multidb::lang.plugin.name',
                'icon' => 'icon-cubes',
                'class' => 'Sommer\Multidb\Models\Setting',
                'permissions' => ['sommer.multidb.settings'],
                'order' => 400,
                'keywords' => 'multidb sommer settings',
            ],
            'sommer_multidb_tenants' => [
                'label' => 'sommer.multidb::lang.menus.tenants.label',
                'description' => 'sommer.multidb::lang.menus.tenants.description',
                'category' => 'sommer.multidb::lang.plugin.name',
                'icon' => 'icon-globe',
                'url' => Backend::url('sommer/multidb/tenants'),
                'permissions' => ['sommer.multidb.tenants'],
                'order' => 500,
                'keywords' => 'multidb domains themes',
            ],
        ];
    }
}
