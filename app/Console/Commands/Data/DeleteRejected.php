<?php

namespace App\Console\Commands\Data;

use App\Block;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteRejected extends Command
{
	const TX_ENDPOINT = "https://blockchain.info/q/txtotalbtcoutput/";

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'data:delete-rejected';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Deletes all rejected transactions from the database.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$unconfirmedTxs = Transaction::where('confirmed', 0)->orderBy('time', 'asc')->get();
		
		foreach($unconfirmedTxs as $tx) {
			$data = @file_get_contents(self::TX_ENDPOINT . $tx->hash);
			while($data === false) {
				$this->line("Limit reached, waiting 8 minutes and retrying.");
				sleep(8 * 60);
				$data = @file_get_contents(self::TX_ENDPOINT . $tx->hash);
			}
			if(trim($data) == "Transaction Not Found") {
				$this->line("Deleted transaction. Hash: " . $tx->hash);
				$tx->delete();
			}
		}
	}

}
