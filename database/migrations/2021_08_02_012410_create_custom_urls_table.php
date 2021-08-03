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
            $table->string('uuid')->unique();
            $table->string('dest_url')->nullable();
            $table->json('advance_options')->nullable();
            $table->string('tracking_url')->nullable();
            $table->boolean('spoof_service')->nullable();
            $table->string('pixel')->nullable();
            $table->string('campaign')->nullable();
            $table->integer('max_hit_day')->default(0);
            $table->integer('take_count')->default(0);
            $table->string('fallback_url')->nullable();
            $table->string('active_rule')->nullable();
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
