<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterKeywordRotatorListsToTableName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('keyword_rotator_lists', function (Blueprint $table) {
            $table->boolean('spoof_confirm')->after('request_id')->default(0);
            $table->boolean('spoof_referrer')->after('request_id')->default(0);
            $table->string('spoof_service')->after('request_id')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('keyword_rotator_lists', function (Blueprint $table) {
            $table->dropColumn('spoof_confirm');
            $table->dropColumn('spoof_referrer');
            $table->dropColumn('spoof_service');
        });
    }
}
