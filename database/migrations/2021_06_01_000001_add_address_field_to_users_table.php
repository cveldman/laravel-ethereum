<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToSensorGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sensor_groups', function (Blueprint $table) {
            $table->integer('adults')->default(0);
            $table->boolean('adults_hidden')->default(false);
            $table->integer('children')->default(0);
            $table->boolean('children_hidden')->default(false);
            $table->integer('period')->default(1);
            $table->boolean('period_hidden')->default(false);
            $table->integer('group')->default(1);
            $table->boolean('group_hidden')->default(false);
            $table->text('periods');
            $table->text('doors');
            $table->text('windows');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // TODO: delete columns
        Schema::table('sensor_groups', function (Blueprint $table) {
            //
        });
    }
}
