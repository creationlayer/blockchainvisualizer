<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Block extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('blocks', function (Blueprint $table) {
			$table->increments('id');
			$table->string('hash', 64);
			$table->integer('tx_amount');
			$table->bigInteger('total_sent');
			$table->bigInteger('estimated_sent');
			$table->integer('size');
			$table->integer('height');
			$table->timestamp('time');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::drop('blocks');
    }
}
