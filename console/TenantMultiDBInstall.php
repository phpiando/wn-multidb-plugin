<?php

namespace Sommer\MultiDB\Console;

use Sommer\MultiDB\Models\Tenant;
use Winter\Storm\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Sommer\Multidb\Services\TenantInstanceService;

/**
 * @class Multidb Install Command
 * @package Sommer\MultiDB\Console
 * @since 1.0.0
 * @author Roni Sommerfeld<roni@4tech.mobi>
 */
class TenantMultiDBInstall extends Command
{
    /**
     * @var string The console command name.
     */
    protected static $defaultName = 'multidb:install';

    /**
     * @var string The console command description.
     */
    protected $description = 'Create the new database and run the migrations.';

    /**
     * @var string The console command signature.
     */
    protected $signature = 'multidb:install {--database_name=}';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $databaseName          = $this->option('database_name');
        $tenant                = new Tenant;
        $tenant->database_name = $databaseName ?: uniqid('tenant_');

        (new TenantInstanceService($tenant))->startCreateDatabase();
    }

    /**
     * Get the console command options.
     * @since 1.0.0
     * @return array
     */
    public function getOptions(): array
    {
        return [
            ['--database_name', null, InputOption::VALUE_REQUIRED, 'The tenant id to install the database.', null],
        ];
    }
}
