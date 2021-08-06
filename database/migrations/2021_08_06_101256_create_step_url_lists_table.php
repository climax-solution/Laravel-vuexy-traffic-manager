<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStepUrlListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('step_url_lists', function (Blueprint $table) {
            $table->id();
            $table->string('parent_id')->nullable();
            $table->integer('uuid')->default(0);
            $table->string('dest_url')->nullable();
            $table->integer('weight')->default(0);
            $table->integer('max_hit_day')->default(0);
            $table->integer('take_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('step_url_lists');
    }
}
