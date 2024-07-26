<?php namespace Sommer\MultiDB\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateSommerMultidbMigrationErrors extends Migration
{
    public function up()
{
    Schema::create('sommer_multidb_migration_errors', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id')->unsigned();
        $table->string('database_name')->nullable();
        $table->string('type')->nullable();
        $table->string('version')->nullable();
        $table->text('detail')->nullable();
        $table->text('error')->nullable();
        $table->timestamp('created_at')->nullable();
        $table->timestamp('updated_at')->nullable();
    });
}

public function down()
{
    Schema::dropIfExists('sommer_multidb_migration_errors');
}
}