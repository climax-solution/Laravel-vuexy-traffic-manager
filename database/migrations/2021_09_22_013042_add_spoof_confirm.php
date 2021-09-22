<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpoofConfirm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('url_rotator_lists', function (Blueprint $table) {
          $table->boolean('spoof_confirm')->after('request_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('url_rotator_lists', function (Blueprint $table) {
            $table->dropColumn('spoof_confirm');
        });
    }
}
