<?php namespace Sommer\MultiDB\Traits;

use Illuminate\Support\Facades\Config;
use Sommer\MultiDB\Classes\TenantManager;
use Sommer\MultiDB\Models\Tenant;

/**
 * @trait UsesTenantConnection
 * @package Sommer\MultiDB\Services
 * @since 1.0.0
 * @author Roni Sommerfeld<roni@4tech.mobi>
 */
trait UsesTenantConnection
{
    /**
     * Boot the UsesTenantConnection trait for a model.
     * @since 1.0.0
     * @return void
     */
    protected static function bootUsesTenantConnection(): void
    {
        static::extend(function ($model){
            $model->setConnectionTenant($model);
            $model->setTenantSchemaName($model);
        });
    }

    /**
     * Set the tenant connection
     * @since 1.0.0
     * @param Tenant $tenant
     * @return void
     */
    protected function setConnectionTenant($model){
        $tenant = $this->getTenant();

        if (!$tenant) {
            return;
        }

        $model->setConnection(TenantManager::getTenantConnectionName());
    }

    /**
     * Set the tenant schema name
     * @since 1.0.0
     * @param Tenant $tenant
     * @return void
     */
    protected function setTenantSchemaName($model){
        $tenant = $this->getTenant();

        if (!$tenant) {
            return;
        }

        $model->setTable($tenant->database_name . '.' . $model->getTable());
    }

    /**
     * Set the tenant connection
     * @since 1.0.0
     * @param string $connectionName
     * @param Tenant $tenant
     * @return self
     */
    public function startTenantConnection(string $connectionName, Tenant $tenant): self
    {
        $tenantManager = new TenantManager();
        $tenantManager->setConfigConnection($connectionName, $tenant);
        $tenantManager->reconnectTenantConnection($connectionName);
        $this->setConnection($connectionName);

        return $this;
    }

    /**
     * Get the tenant connection
     * @since 1.0.0
     * @return Tenant|null
     */
    public function getTenant(): ?Tenant
    {
        return TenantManager::getTenant();
    }

    /**
     * Get the tenant id connection
     * @since 1.0.0
     * @return Tenant|null
     */
    public function getTenantId(): ?int
    {
        return TenantManager::getTenant()->id ?? null;
    }

    /**
     * Get the tenant database name
     * @since 1.0.0
     * @return string
     */
    public function getDatabaseName(): string
    {
        return TenantManager::getTenantDatabaseName();
    }
}
