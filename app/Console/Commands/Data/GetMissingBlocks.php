<?php

namespace App\Console\Commands\Data;

use App\Block;
use App\Transaction;
use Illuminate\Console\Command;

class GetMissingBlocks extends Command
{
	const BLOCK_ENDPOINT = "https://bitcoin.toshi.io/api/v0/blocks/";

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'data:missing-blocks';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Gets blocks that are missing between first and the last in the db.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$downloaded = Block::orderBy('height', 'desc')->select('height')->get()->lists('height')->toArray();

		$first = Block::orderBy('height', 'desc')->first();
		$first = $first->height;
		$last = Block::orderBy('height', 'asc')->first();
		$last = $last->height;

		for($i = $last; $i < $first; $i++) {
			if(!in_array($i, $downloaded)) {
				$data = json_decode(file_get_contents(self::BLOCK_ENDPOINT.$i), true);
				$block = new Block();
				$block->hash = $data['hash'];
				$block->tx_amount = $data['transactions_count'];
				$block->total_sent = $data['total_out'];
				$block->estimated_sent = $data['total_out'];
				$block->size = $data['size'];
				$block->height = $data['height'];
				$block->time = date('Y-m-d H:i:s',strtotime($data['time']));
                $block->created = date('Y-m-d H:i:s', strtotime($data['time']));
				$block->save();
				Transaction::setConfirmedByTransactionHash($data['transaction_hashes']);
				$this->line("Saved block height " . $block->height);
			}
		}
	}

}
