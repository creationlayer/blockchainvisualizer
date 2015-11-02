<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBlockRecievedTime extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('blocks', function (Blueprint $table) {
            $table->timestamp('created');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('blocks', function (Blueprint $table) {
            $table->dropColumn('created');
        });
    }
}
