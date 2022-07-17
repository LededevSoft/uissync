<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUisTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uis_tags', function (Blueprint $table) {
            $table->integer("id")->primary();
            $table->mediumText("name");
			$table->integer("rating")->nullable();
			$table->boolean("is_system")->nullable();			
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uis_tags');
    }
}
