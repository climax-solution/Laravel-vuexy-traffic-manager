<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUrlRotatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('url_rotators', function (Blueprint $table) {
            $table->id();
            $table->json('advance_options')->nullable();
            $table->string('pixel')->nullable();
            $table->string('active_rule')->nullable();
            $table->integer('active_position')->default(0);
            $table->enum('rotation_option',[0,1,2,3])->default(0);
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
        Schema::dropIfExists('url_rotators');
    }
}
