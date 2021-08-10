<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRedirectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redirects', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('link_name')->nullable();
            $table->string('dest_url')->nullable();
            $table->string('tracking_url')->nullable();
            $table->string('fallback_url')->nullable();
            $table->integer('take_count')->default(0);
            $table->integer('max_hit_day')->default(0);
            $table->string('campaign')->nullable();
            $table->string('pixel')->nullable();
            $table->string('item_id')->nullable();
            $table->string('table_name')->nullable();
            $table->boolean('active')->default(0);
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
        Schema::dropIfExists('redirects');
    }
}
