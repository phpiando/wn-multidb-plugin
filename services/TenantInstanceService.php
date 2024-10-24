<?php
namespace Sommer\Multidb\Services;

use Throwable;
use Sommer\MultiDB\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Sommer\Multidb\Models\Setting;
use Illuminate\Database\Migrations\Migrator;
use Sommer\MultiDB\Classes\TenantManager;
use Sommer\Multidb\Queues\TenantDatabaseUpQueue;
use Sommer\MultiDB\Services\UpdateManagerService;

/**
 * @class TenantServices
 * @package Sommer\MultiDB\Services
 * @since 1.0.0
 * @author Roni Sommerfeld<roni@4tech.mobi>
 */
class TenantInstanceService
{
    /**
     * Settings instance
     * @since 1.0.0
     * @var Setting
     */
    protected Setting $settings;

    /**
     * Tenant Model
     * @since 1.0.0
     * @var Tenant
     */
    protected ?Tenant $tenant;

    /**
     * Migrator instance
     * @since 1.0.0
     * @var Migrator
     */
    protected ?Migrator $migrator;

    /**
     * @var \Illuminate\Console\OutputStyle
     */
    protected ?\Illuminate\Console\OutputStyle $notesOutput = null;

    /**
     * TenantService constructor
     * @since 1.0.0
     * @param Tenant $tenant
     * @return void
     */
    public function __construct(?Tenant $tenant = null)
    {
        $this->setTenant($tenant);
        $this->settings = Setting::instance();
        $this->migrator = app('migrator');
    }

    /**
     * Start the creation of a new database
     * @since 1.0.0
     * @throws Throwable if an error occurs
     * @return void
     */
    public function startCreateDatabase() : void
    {
        try {
            $this->createDatabaseIfNotExists();
            $this->validateHasTenant();

            if ($this->settings->get('database_queue_on_create')) {
                $this->addQueueToUpdate($this->tenant->id);
                return;
            }

            $this->migrationUp();
            $this->closeConnection();

            $this->tenant->has_database_created = true;
            $this->tenant->save();
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * Add the tenant to the queue to update
     * @since 1.2.0
     * @param string $tenantId
     * @return void
     */
    public function addQueueToUpdate(string $tenantId): void
    {
        (new TenantDatabaseUpQueue)->registerQueue(['tenant_id' => $tenantId]);
    }

    /**
     * Close the connection
     * @since 1.3.0
     * @return void
     */
    public function closeConnection(): void
    {
        // Fecha a conexão do tenant atual
        DB::disconnect(name: TenantManager::getTenantConnectionName());
    }

    /**
     * Validate if has tenant
     * @since 1.0.0
     * @throws Throwable if an error occurs
     * @return void
     */
    public function validateHasTenant(): void
    {
        if (!$this->tenant) {
            throw new Throwable(trans('sommer.multidb::lang.messages.errors.tenant_not_found'));
        }
    }

    /**
     * Create the database if not exists
     * @since 1.0.0
     * @return void
     */
    public function createDatabaseIfNotExists(): void
    {
        $stringConfig = "database.connections." . config('sommer.multidb::database.multidb_connection');
        $charset      = config("{$stringConfig}.charset", 'utf8mb4');
        $collation    = config("{$stringConfig}.collation", 'utf8mb4_unicode_ci');

        $query = "CREATE DATABASE IF NOT EXISTS {$this->tenant->database_name} CHARACTER SET {$charset} COLLATE {$collation};";

        DB::statement($query);
    }

    /**
     * Force update the tenant
     * @since 1.0.0
     * @throws Throwable if an error occurs
     * @return void
     */
    public function forceUpdate(): void
    {
        try {
            $this->createDatabaseIfNotExists();
            $this->migrationUp();
            $this->closeConnection();
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * Run the migrations
     * @since 1.0.0
     * @return void
     */
    public function migrationUp(): void
    {
        UpdateManagerService::instance()
            ->setTenant($this->tenant)
            ->setNotesOutput($this->notesOutput)
            ->update();
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
     * Start the connection to the tenant database
     * @since 1.0.0
     * @return self
     */
    public function setTenant(?Tenant $tenant = null): self
    {
        $this->tenant = $tenant;

        return $this;
    }
}
