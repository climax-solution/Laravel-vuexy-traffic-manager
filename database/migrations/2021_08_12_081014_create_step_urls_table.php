<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStepUrlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('step_urls', function (Blueprint $table) {
          $table->id();
          $table->json('advance_options')->nullable();
          $table->string('spoof_service')->nullable();
          $table->string('pixel')->nullable();
          $table->string('amazon_aff_id')->nullable();
          $table->string('active_rule')->nullable();
          $table->integer('active_position')->default(0);
          $table->enum('rotation_option',[0,1,2,3])->default(0);
          $table->enum('link_type',[0,1,2,3,4])->default(0);
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
        Schema::dropIfExists('step_urls');
    }
}
