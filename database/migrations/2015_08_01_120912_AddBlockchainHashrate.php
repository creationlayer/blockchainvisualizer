<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBlockchainHashrate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blockchain_snapshots', function (Blueprint $table) {
			$table->bigInteger('hashrate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blockchain_snapshots', function (Blueprint $table) {
            $table->dropColumn('hashrate');
        });
    }
}
