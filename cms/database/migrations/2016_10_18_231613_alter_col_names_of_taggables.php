<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColNamesOfTaggables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('taggables', function (Blueprint $table) {
            //
            $table->renameColumn('taggable_id','tag_object_id');
            $table->renameColumn('taggable_type','tag_object_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taggables', function (Blueprint $table) {
            //
            $table->renameColumn('tag_object_id','taggable_id');
            $table->renameColumn('tag_object_type','taggable_type');
        });
    }
}
