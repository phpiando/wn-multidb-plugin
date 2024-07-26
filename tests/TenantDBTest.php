<?php namespace Sommer\MultiDB\Tests;


use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Sommer\MultiDB\Classes\TenantManager;
use Sommer\MultiDB\Models\Tenant;
use Sommer\Multidb\Services\TenantInstanceService;
use Sommer\MultiDB\Tests\Migrations\CreateCompaniesTable;
use Sommer\MultiDB\Tests\Migrations\CreateUnitsTable;
use System\Tests\Bootstrap\PluginTestCase;

class TenantDBTest extends PluginTestCase
{
    protected $tenantManager;
    protected $tenant;

    public function setUp(): void
    {
        parent::setUp();

        $this->tenantManager = app(TenantManager::class);

        // Configurar conexões de banco de dados dinamicamente
        $this->configureDatabaseConnections();

        // // Criar um tenant de teste
        // $this->tenant = Tenant::create([
        //     'database_host' => '127.0.0.1',
        //     'database_name' => 'test_multidb',
        //     'database_user' => 'root',
        //     'database_password' => '',
        // ]);

        // // Configurar a conexão para o tenant
        // $this->tenantManager->setTenant($this->tenant);

        // Criar as tabelas necessárias no banco principal e no banco tenant
        $this->runMigrations();
    }

    protected function configureDatabaseConnections()
    {
        Config::set('database.connections.mysql_testing', [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'database' => 'test_winter',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);

        Config::set('database.connections.multidb_testing', [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'database' => 'test_multidb',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
    }

    protected function runMigrations()
    {
        // Rodar migrações para o banco principal
        // $tenant = new Tenant;
        // $tenant->database_name = 'test_winter';
        // Config::set('database.default', 'mysql_testing');
        // $query = "CREATE DATABASE IF NOT EXISTS test_winter CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
        // DB::statement($query);
        // (new CreateCompaniesTable)->up();

        // // Rodar migrações para o banco tenant
        // Config::set('database.default', 'multidb_testing');
        // Config::set('database.connections.multidb_testing.database', 'test_multidb');
        // $query = "CREATE DATABASE IF NOT EXISTS test_multidb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
        // DB::statement($query);
        // (new CreateUnitsTable)->up();
        // Rodar migrações para o banco principal
        $this->artisan('migrate', ['--database' => 'mysql_testing', '--path' => 'plugins/sommer/multidb/database/migrations']);

        // Rodar migrações para o banco tenant
        Config::set('database.connections.multidb_testing.database', $this->tenant->database_name);
        $this->artisan('migrate', ['--database' => 'test_multidb', '--path' => 'plugins/sommer/multidb/database/migrations']);
    }

    public function testTenantConnection()
    {
        // Verificar se a conexão do tenant está configurada corretamente
        // $this->assertEquals('127.0.0.1', Config::get('database.connections.multidb_testing.host'));
        // $this->assertEquals('test_multidb', Config::get('database.connections.multidb_testing.database'));
    }

    public function testRelations()
    {
        // Criar um registro no banco principal
        // $company = Company::create(['name' => 'Test Company']);

        // // Criar um registro no banco tenant
        // $unit = Unit::create(['name' => 'Test Unit', 'company_id' => $company->id]);

        // // Verificar se o relacionamento funciona
        // $this->assertEquals('Test Company', $unit->company->name);
    }

    public function tearDown(): void
    {
        // // Limpar as tabelas e dados de teste
        DB::connection('mysql_testing')->table('companies')->truncate();
        DB::connection('multidb_testing')->table('units')->truncate();

        parent::tearDown();
    }
}