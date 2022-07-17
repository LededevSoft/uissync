<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUisStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uis_statuses', function (Blueprint $table) {
            $table->string("id")->primary();
            $table->mediumText("name");
			$table->mediumText("color")->nullable();;
            $table->integer("priority")->nullable();;
			$table->boolean("is_deleted")->nullable();;
			$table->mediumText("description")->nullable();;
			$table->boolean("is_worktime")->nullable();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uis_statuses');
    }
}
