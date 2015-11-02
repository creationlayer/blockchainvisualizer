<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BlockchainSnapshot extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('blockchain_snapshots', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('size');
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
		Schema::drop('blockchain_snapshots');
	}
}
