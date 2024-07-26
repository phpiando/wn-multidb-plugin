<?php namespace Sommer\MultiDB\Traits;

use Sommer\MultiDB\Classes\TenantManager;

/**
 * @trait UsesMainConnection
 * @package Sommer\MultiDB\Services
 * @since 1.0.0
 * @author Roni Sommerfeld<roni@4tech.mobi>
 */
trait UsesMainConnection
{
    /**
     * Boot the UsesMainConnection trait for a model.
     * @since 1.0.0
     * @return void
     */
    protected static function bootUsesMainConnection(): void
    {
        static::extend(function ($model){
            $model->setMainConnection($model);
        });
    }

    /**
     * Set the main connection
     * @since 1.0.0
     * @return void
     */
    protected function setMainConnection($model){
        $tenant = TenantManager::getTenant();

        if (!$tenant) {
            return;
        }

        $model->setConnection(env('DB_CONNECTION'));
        $table = $model->getTable();
        $model->setTable(env('DB_DATABASE').'.'.$table);
    }
}
