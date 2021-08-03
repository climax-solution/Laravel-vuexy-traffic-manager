<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferrersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referrers', function (Blueprint $table) {
            $table->id();
            $table->boolean('action')->nullable();
            $table->boolean('domain_type')->nullable();
            $table->enum('domain_reg',[0,1])->default(0);
            $table->string('domain_name')->nullable();
            $table->string('table_name')->nullable();
            $table->string('item_id')->nullable();
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
        Schema::dropIfExists('referrers');
    }
}
