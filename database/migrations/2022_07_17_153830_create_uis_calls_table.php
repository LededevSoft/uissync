<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUisCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uis_calls_reports', function (Blueprint $table) {
            $table->string("id")->primary();
            $table->string("source")->nullable();
            $table->boolean("is_lost")->nullable();
			$table->string("direction")->nullable();			
            $table->timestamp("start_time")->nullable();
            $table->timestamp("finish_time")->nullable();          
            $table->string("call_records")->nullable();            						
			$table->integer("cpn_region_id");           
			$table->integer("talk_duration");
			$table->integer("wait_duration");
			$table->integer("total_duration");
			$table->mediumText("cpn_region_name")->nullable();
            $table->string("communication_id")->nullable();			
			$table->string("communication_type")->nullable();
            $table->string("contact_phone_number")->nullable();
            $table->string("virtual_phone_number")->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uis_calls_reports');
    }
}
