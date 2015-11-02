<?php

namespace App\Console\Commands\Data;

use App\Block;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ReconfirmTransactions extends Command
{
	const BLOCK_ENDPOINT = "https://bitcoin.toshi.io/api/v0/blocks/";

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'data:reconfirm-txs';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Gets block in the last day and confirms the transactions.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$first = Block::where('time', '>=', Carbon::now()->subHours(2))->orderBy('height', 'desc')->first();
		$first = $first->height;
		$last = Block::where('time', '>=', Carbon::now()->subHours(2))->orderBy('height', 'asc')->first();
		$last = $last->height;

		for($i = $last; $i < $first; $i++) {
			$data = json_decode(file_get_contents(self::BLOCK_ENDPOINT.$i), true);
			Transaction::setConfirmedByTransactionHash($data['transaction_hashes']);
			$this->line("Saved block height " . $i);
		}
	}

}
