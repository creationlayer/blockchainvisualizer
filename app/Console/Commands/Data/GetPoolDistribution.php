<?php

namespace App\Console\Commands\Data;

use App\BlockchainSnapshot;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GetPoolDistribution extends Command
{
	const POOL_DISTRIBUTION_URL = "https://blockchain.info/pools";

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'data:pool-distribution';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Gets the blockchain pool distribution.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$page = file_get_contents(self::POOL_DISTRIBUTION_URL);
		$json = substr($page, strpos($page, 'data-json="') + 11);
		$json = substr($json, 0, strpos($json, '">'));
		Storage::put('pool-distribution.json', html_entity_decode($json, ENT_QUOTES));
	}

}
