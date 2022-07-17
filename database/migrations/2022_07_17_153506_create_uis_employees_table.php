<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUisEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uis_employees', function (Blueprint $table) {
            $table->string("id")->primary();
            $table->mediumText("full_name")->nullable();
            $table->mediumText("email")->nullable();
            $table->string("group_id")->nullable();
            $table->mediumText("group_name")->nullable();
			$table->string("status_id")->nullable();
			$table->mediumText("extension_phone_number")->nullable();
			$table->mediumText("phone_number")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uis_employees');
    }
}
