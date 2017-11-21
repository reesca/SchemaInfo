<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllTables extends Migration
{
     /**
      * The name of the database table
      *
      * @var string
      */
    protected $tableName;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $tables = \App\SchemaInfo::getTableNames();


        foreach($tables as $tableName) {
        
            $this->tableName = $tableName;

            //  print($tableName ."\n");

            Schema::create($tableName, function (Blueprint $table) {

                new \App\SchemaInfo($this->tableName, $table);

            });

        }

        // print('asdfgh');exit;

        // exit;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tables = array_reverse(\App\SchemaInfo::getTableNames());

        foreach ($tables as $tableName) {

            $this->tableName = $tableName;

            Schema::dropIfExists($this->tableName);
        }
    }
}
