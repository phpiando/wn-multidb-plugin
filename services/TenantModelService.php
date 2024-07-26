<?php
namespace Sommer\MultiDB\Services;

use Winter\Storm\Support\Str;
use Sommer\MultiDB\Models\Tenant;
use Sommer\Multidb\Models\Setting;
use Sommer\MultiDB\Constants\TenantConstants;
use Sommer\Multidb\Queues\TenantCreateDatabaseQueue;

/**
 * @class TenantServices
 * @package Sommer\MultiDB\Services
 * @since 1.0.0
 * @author Roni Sommerfeld<roni@4tech.mobi>
 */
class TenantModelService
{
    /**
     * Settings instance
     * @since 1.0.0
     * @var Setting
     */
    protected Setting $settings;

    /**
     * TenantService constructor
     * @since 1.0.0
     * @param Setting $settings
     * @return void
     */
    public function __construct()
    {
        $this->settings = Setting::instance();
    }

    /**
     * Before create tenant
     * @since 1.0.0
     * @param Tenant $tenant
     * @return void
     */
    public function beforeCreate(Tenant &$tenant): void
    {
        $this->generateDatabaseName($tenant);
    }

    /**
     * After create tenant
     * @since 1.0.0
     * @param Tenant $tenant
     * @return void
     */
    public function afterCreate(Tenant $tenant): void
    {
        if ($tenant->has_database_created) {
            return;
        }

        if ($this->settings->get('database_queue_on_create')) {
            (new TenantCreateDatabaseQueue)->registerQueue(['tenant_id' => $tenant->id]);
            return;
        }

        // Create the database
        (new TenantInstanceService($tenant))->startCreateDatabase();
    }

    /**
     * Generate the database name
     * @since 1.0.0
     * @param Tenant $tenant
     * @return void
     */
    private function generateDatabaseName(Tenant &$tenant): void
    {
        if ($tenant->database_name) {
            return;
        }

        $tenant->database_name = $this->getDatabasePrefix();
        $tenant->database_name .= $this->createTenantName($tenant->name);
        $tenant->database_name = $this->databaseLimitName($tenant->database_name);
    }

    /**
     * Create the tenant name
     *
     * @since 1.0.0
     * @param string $name
     * @return string
     */
    private function createTenantName(string $name): string
    {
        if ($this->settings->get('database_rule_to_name', config('sommer.multidb::database_rule_to_name')) === TenantConstants::DATABASE_RULE_NAME_DB_CODE) {
            return $this->getUniqueHash();
        }

        return Str::slug($name);
    }

    /**
     * Limit the database name because
     * the database name is limited to 64/65 characters
     *
     * @since 1.0.0
     * @param string $name
     * @return string
     */
    private function databaseLimitName(string $name): string
    {
        return substr($name, 0, TenantConstants::DATABASE_NAME_LIMIT);
    }

    /**
     * Get a unique hash
     *
     * @since 1.0.0
     * @return string
     */
    private function getUniqueHash(): string
    {
        return Str::random(TenantConstants::DATABASE_CODE_LENGTH);
    }

    /**
     * Get the database prefix
     *
     * @since 1.0.0
     * @return string
     */
    private function getDatabasePrefix(): string
    {
        return $this->settings->get('database_prefix', config('sommer.multidb::database_prefix'));
    }
}
