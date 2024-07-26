<?php namespace Sommer\MultiDB\Classes;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Sommer\MultiDB\Models\Tenant;
use Winter\Storm\Exception\ApplicationException;

/**
 * @class TenantServices
 * @package Sommer\MultiDB\Services
 * @since 1.0.0
 * @author Roni Sommerfeld <roni@4tech.mobi>
 */
class TenantManager
{
    /**
     * Tenant connection name
     * @since 1.0.0
     * @var ?string
     */
    protected static ?string $tenantConnection = null;

    /**
     * Tenant
     * @since 1.0.0
     * @var ?Tenant
     */
    protected static ?Tenant $tenant = null;

    /**
     * Tenant database name
     * @since 1.0.0
     * @var ?string
     */
    protected static ?string $tenantDatabaseName = null;

    /**
     * Constructor
     * @since 1.0.0
     */
    public function __construct(){
        self::$tenantConnection = config('sommer.multidb::database.multidb_connection');
    }

    /**
     * Configure the tenant connection
     * @since 1.0.0
     * @param ?Tenant $tenant If null will use the tenant setted
     * @param ?string $connectionName If null will use the tenant connection setted
     * @throws ApplicationException if tenant not found
     * @return void
     */
    public function startTenantConnection(?Tenant $tenant = null, ?string $connectionName = null): void
    {
        $connectionName = $connectionName ?? self::$tenantConnection;
        $tenant = $tenant ?? self::$tenant;

        if (!$tenant) {
            throw new ApplicationException(trans('sommer.multidb::lang.messages.errors.tenant_not_found'));
        }

        $this->setTenant($tenant);
        $this->setTenantConnectionName($connectionName);
        $this->setTenantDatabaseName($tenant->database_name);
        $this->setConfigConnection($connectionName, $tenant);
        $this->reconnectTenantConnection($connectionName);
    }

    /**
     * Reconnect the tenant connection
     * @since 1.0.0
     * @param string $connectionName
     * @return void
     */
    public function reconnectTenantConnection(string $connectionName): void
    {
        DB::purge($connectionName);
        DB::reconnect($connectionName);
    }

    /**
     * Set the tenant connection
     * @since 1.0.0
     * @param string $connectionName
     * @param Tenant $tenant
     * @return void
     */
    public function setConfigConnection(string $connectionName, Tenant $tenant): void
    {
        $configDatabase = config('sommer.multidb::database.multidb_configuration');

        $tenant->database_host ? $configDatabase['host'] = $tenant->database_host : null;
        $tenant->database_port ? $configDatabase['port'] = $tenant->database_port : null;
        $tenant->database_user ? $configDatabase['username'] = $tenant->database_user : null;
        $tenant->database_password ? $configDatabase['password'] = $tenant->database_password : null;

        $configDatabase['database'] = $tenant->database_name;

        Config::set("database.connections.{$connectionName}", $configDatabase);
    }

    /**
     * Set the tenant
     * @since 1.0.0
     * @param Tenant $tenant
     * @return void
     */
    public function setTenant(Tenant $tenant) : void
    {
        self::$tenant = $tenant;
    }

    /**
     * Set the tenant database name
     * @since 1.0.0
     * @param string $databaseName
     * @return void
     */
    public function setTenantDatabaseName(string $databaseName): void
    {
        self::$tenantDatabaseName = $databaseName;
    }

    /**
     * Get the tenant
     * @since 1.0.0
     * @return ?Tenant
     */
    public static function getTenant() : ?Tenant
    {
        return self::$tenant;
    }

    /**
     * Get the tenant database name
     * @since 1.0.0
     * @return ?string
     */
    public static function getTenantDatabaseName() : ?string
    {
        return self::$tenantDatabaseName;
    }

    /**
     * Set the tenant connection name
     * @since 1.0.0
     * @param string $connectionName
     * @return void
     */
    public function setTenantConnectionName(string $connectionName): void
    {
        self::$tenantConnection = $connectionName;
    }

    /**
     * Get the tenant connection name
     * @since 1.0.0
     * @return string
     */
    public static function getTenantConnectionName(): ?string
    {
        return self::$tenantConnection;
    }
}
