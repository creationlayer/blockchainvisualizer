<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TransactionIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('transactions', function ($table) {
			$table->integer('index');
			$table->index('index');
			$table->boolean('confirmed');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('transactions', function ($table) {
			$table->dropColumn('index', 'confirmed');
		});
    }
}
