<?php

namespace Sommer\MultiDB\Console;

use Sommer\MultiDB\Models\Tenant;
use Winter\Storm\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Sommer\Multidb\Services\TenantInstanceService;

/**
 * @class Multidb Update Command
 * @package Sommer\MultiDB\Console
 * @since 1.0.0
 * @author Roni Sommerfeld<roni@4tech.mobi>
 */
class TenantMultiDBUpdate extends Command
{
    /**
     * @var string The console command name.
     */
    protected static $defaultName = 'multidb:up';

    /**
     * @var string The console command description.
     */
    protected $description = 'Update the tenant database.';

    /**
     * @var string The console command signature.
     */
    protected $signature = 'multidb:up {--database_name=} {--tenant_id=} {--all} {--limit=}';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $databaseName = $this->option('database_name');
        $tenantId     = $this->option('tenant_id');
        $all          = $this->option('all');
        $limit        = $this->option('limit') ?: 0;

        if (!$databaseName && !$all && !$tenantId) {
            if ($this->output->confirm('You want to update all tenants?')) {
                $this->updateAllTenants($limit);
            }

            return;
        }

        if ($all) {
            $this->updateAllTenants($limit);
            return;
        }

        if($tenantId) {
            $tenant = Tenant::find($tenantId);

            if(!$tenant) {
                $this->output->error('Tenant not found.');
                return;
            }

            (new TenantInstanceService($tenant))->setNotesOutput($this->output)->forceUpdate();
            return;
        }

        if (!$databaseName) {
            $this->output->error('You must provide a database name or use the --all option.');
            return;
        }

        $tenant                = new Tenant();
        $tenant->database_name = $databaseName;

        (new TenantInstanceService($tenant))->setNotesOutput($this->output)->forceUpdate();
    }

    /**
     * Update all tenants.
     * @since 1.0.0
     * @return void
     */
    public function updateAllTenants(int $limit = 0): void
    {
        $tenants = Tenant::isActive()->hasUpdates()->orderBy('updated_at', 'asc');

        if($limit) {
            $tenants = $tenants->take($limit);
        }

        $tenants = $tenants->get();

        $service = (new TenantInstanceService)->setNotesOutput($this->output);

        foreach ($tenants as $tenant) {
            $this->output->success('Updating tenant: ' . $tenant->database_name);
            $service->setTenant($tenant)
                ->forceUpdate();

            $tenant->updated_at = now();
            $tenant->save();
        }
    }

    /**
     * Get the console command options.
     * @since 1.0.0
     * @return array
     */
    public function getOptions(): array
    {
        return [
            ['--database_name', null, InputOption::VALUE_NONE, 'The tenant name to update the database.', null],
            ['--all', null, InputOption::VALUE_NONE, 'Force the update.', null],
            ['--tenant_id', null, InputOption::VALUE_NONE, 'The tenant id to update the database.', null],
            ['--limit', null, InputOption::VALUE_NONE, 'The limit of tenants to update.', null],
        ];
    }
}
