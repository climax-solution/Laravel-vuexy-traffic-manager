<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeoIpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geo_ips', function (Blueprint $table) {
            $table->id();
            $table->string('table_name')->nullable();
            $table->string('item_id')->nullable();
            $table->text('country_list')->nullable();
            $table->text('country_group')->nullable();
            $table->enum('action',['0','1'])->default('0');
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
        Schema::dropIfExists('geo_ips');
    }
}
