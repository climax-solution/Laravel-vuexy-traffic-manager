<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpoofReferrer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('step_url_lists', function (Blueprint $table) {
            $table->boolean('spoof_referrer')->default(0);
            $table->boolean('spoof_confirm')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('step_url_lists', function (Blueprint $table) {
            //
        });
    }
}
