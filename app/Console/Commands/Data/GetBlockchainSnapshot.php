<?php

namespace App\Console\Commands\Data;

use App\BlockchainSnapshot;
use Illuminate\Console\Command;

class GetBlockchainSnapshot extends Command
{
	const SIZE_ENDPOINT = "https://blockchain.info/charts/blocks-size?timespan=30days&format=json";
	const HASHRATE_ENDPOINT = "https://blockchain.info/charts/hash-rate?timespan=30days&format=json";

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'data:blockchain-snapshot';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Gets the blockchain snapshot from the external APIs.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$sizeRaw = json_decode(file_get_contents(self::SIZE_ENDPOINT), true);
		$hashrateRaw = json_decode(file_get_contents(self::HASHRATE_ENDPOINT), true);

		foreach($sizeRaw['values'] as $sD) {
			// Don't save the last one, incorrect data
			if($sD === end($sizeRaw['values']))
				break;
			$exists = BlockchainSnapshot::where('time', date('Y-m-d H:i:s', $sD['x']))->count();
			if(!$exists) {
				$snapshot = new BlockchainSnapshot();
				$snapshot->size = $sD['y'];
				$snapshot->hashrate = 0;
				foreach($hashrateRaw['values'] as $hrD) {
					if(date('Y-m-d', $sD['x']) == date('Y-m-d', $hrD['x'])) {
						$snapshot->hashrate = $hrD['y'];
					}
				}
				$snapshot->time = date('Y-m-d H:i:s', $sD['x']);
				$snapshot->save();
				$this->line("Added a new blockchain snapshot from " . date('Y-m-d H:i:s', $sD['x']) . ".");
			}
		}
	}

}
