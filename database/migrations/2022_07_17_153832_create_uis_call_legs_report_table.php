<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUisCall_legs_reportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uis_call_legs_report', function (Blueprint $table) {
            $table->string("id")->primary();
            $table->integer("duration")->nullable();
            $table->string("group_id")->nullable();
            $table->boolean("is_coach")->nullable();
			$table->string("action_id")->nullable();
			$table->mediumText("direction")->nullable();
			$table->boolean("is_failed")->nullable();
			$table->mediumText("group_name")->nullable();
            $table->timestamp("start_time")->nullable();
            $table->mediumText("action_name")->nullable();
            $table->string("employee_id")->nullable();
			$table->string("call_session_id")->nullable();
			$table->integer("total_duration")->nullable();
			$table->mediumText("finish_reason")->nullable();
            $table->mediumText("calling_phone_number")->nullable();
			$table->mediumText("virtual_phone_number")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uis_call_legs_report');
    }
}
