<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeywordRotatorListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keyword_rotator_lists', function (Blueprint $table) {
            $table->id();
            $table->string('parent_id')->nullable();
            $table->integer('uuid')->default(0);
            $table->string('keyword')->nullable();
            $table->string('dest_url')->nullable();
            $table->integer('weight')->default(0);
            $table->integer('max_hit_day')->default(0);
            $table->integer('take_count')->default(0);
            $table->string('request_id')->nullable();
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
        Schema::dropIfExists('keyword_rotator_lists');
    }
}
