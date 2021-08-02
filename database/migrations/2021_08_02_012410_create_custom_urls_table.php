<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomUrlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_urls', function (Blueprint $table) {
            $table->id();
            $table->string('link_name')->nullable();
            $table->string('dest_url')->nullable();
            $table->string('tracking_url')->nullable();
            $table->json('advance_options')->nullable();
            $table->string('pixel')->nullable();
            $table->string('campaign')->nullable();
            $table->string('max_hit_day')->nullable();
            $table->string('fallback_url')->nullable();
            $table->enum('active_rule',[0,1,2,3,4])->nullable();
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
        Schema::dropIfExists('custom_urls');
    }
}
