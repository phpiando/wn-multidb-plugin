<?php namespace Sommer\MultiDB\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateSommerMultidbTenants extends Migration
{
    public function up()
{
    Schema::create('sommer_multidb_tenants', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id')->unsigned();
        $table->string('name')->nullable()->comment('name of the tenancy');
        $table->string('description')->nullable()->comment('description of the tenancy');
        $table->boolean('is_active')->nullable()->default(1)->index();
        $table->boolean('has_updates')->nullable()->default(1)->index();
        $table->boolean('has_waiting_sync')->nullable()->default(1)->comment('If the tenancy has a waiting sync');
        $table->boolean('has_database_created')->nullable()->default(0)->comment('If the tenancy has a database created');
        $table->boolean('has_custom_auth_database')->nullable()->default(0)->comment('If the tenancy has a custom auth database');
        $table->string('database_name', 63)->index()->comment('Name of the database, max 63 characters');
        $table->string('database_host')->nullable()->comment('Host of the database');
        $table->integer('database_port')->nullable()->comment('Port of the database');
        $table->string('database_user')->nullable()->comment('User of the database');
        $table->string('database_pass')->nullable()->comment('Password of the database');
        $table->timestamp('created_at')->nullable();
        $table->timestamp('updated_at')->nullable();
        $table->timestamp('deleted_at')->nullable();
    });
}

public function down()
{
    Schema::dropIfExists('sommer_multidb_tenants');
}
}