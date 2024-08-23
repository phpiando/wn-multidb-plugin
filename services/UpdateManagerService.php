<?php
namespace Sommer\MultiDB\Services;

use Exception;
use Sommer\MultiDB\Models\Tenant;
use System\Classes\UpdateManager;
use Sommer\Multidb\Models\Setting;
use System\Classes\VersionManager;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Sommer\MultiDB\Classes\TenantManager;

/**
 * @class UpdateManagerService
 * @package Sommer\MultiDB\Services
 * @since 1.0.0
 * @author Roni Sommerfeld<roni@4tech.mobi>
 */
class UpdateManagerService extends UpdateManager
{
    /**
     * @var VersionManagerService
     */
    protected $versionManager;

    /**
     * Tenant Model
     * @since 1.0.0
     * @var Tenant|null
     */
    protected ?Tenant $tenant;

    protected function init()
    {
        parent::init();
        $this->versionManager = VersionManagerService::instance();
    }

    /**
     * Creates the migration table and updates
     * @override UpdateManager::update
     * @since 1.0.0
     * @return self
     */
    public function update(): void
    {
        $this->startConnectionTenant();
        $this->createMigrationTable();
        $this->createRepository();
        $this->versionManager->resetCaches();

        /*
         * Update plugins
         */
        $plugins = $this->getTenantPlugins();

        foreach ($plugins as $code => $plugin) {
            $this->updatePlugin($plugin);
        }

        $this->startConnectionMain();
    }

    /**
     * Create repository for the tenant
     * with migrations bases necessary
     * @since 1.0.0
     * @return void
     */
    public function createRepository(): void
    {
        $migrationPath = config('sommer.multidb::database_migration_path');

        if (!is_dir($migrationPath)) {
            throw new Exception("Migration path not found: {$migrationPath}");
        }

        $this->migrator->run([$migrationPath]);
    }

    /**
     * Create table migrations if not exists
     * @since 1.0.0
     * @return void
     */
    public function createMigrationTable(): void
    {
        $firstUp = !Schema::hasTable($this->getMigrationTableName());

        if (!$firstUp) {
            return;
        }

        $this->migrator->getRepository()->createRepository();
    }

    /**
     * Get plugins associated with the tenant
     * in Sommer\Multidb\Models\Setting
     *
     * @since 1.0.0
     * @return array
     */
    private function getTenantPlugins(): array
    {
        $pluginsId = Setting::get('plugins', []);

        return $pluginsId;
    }

    /**
     * Get the migration table name
     * @since 1.0.0
     * @return string
     */
    public function getMigrationTableName(): string
    {
        return Config::get('database.migrations', 'migrations');
    }

    /**
     * Set the connection to the multidb
     * @since 1.0.0
     * @return void
     */
    public function startConnectionTenant(): void
    {
        app(TenantManager::class)->startTenantConnection($this->tenant, $this->tenant->database_name);
        Config::set('database.default', $this->tenant->database_name);
    }

    /**
     * Set the connection to the main database
     * @since 1.0.0
     * @return void
     */
    public function startConnectionMain(): void
    {
        Config::set('database.default', config('database.default'));
    }

    /**
     * Sets an output stream for writing notes.
     * @param Illuminate\Console\Command $output
     * @return self
     */
    public function setNotesOutput($output)
    {
        $this->notesOutput = $output;

        return $this;
    }

    /**
     * Set the tenant model
     * @since 1.0.0
     * @param Tenant $tenant
     * @return self
     */
    public function setTenant(Tenant $tenant): self
    {
        $this->tenant = $tenant;

        return $this;
    }
}
