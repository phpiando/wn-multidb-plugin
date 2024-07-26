<?php namespace Sommer\MultiDB\Tests\Migrations;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnitsTable extends Migration
{
    public function up()
    {
        Schema::connection('multidb_testing')->create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('company_id')->constrained('mysql_testing.companies');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('multidb_testing')->dropIfExists('units');
    }
}
