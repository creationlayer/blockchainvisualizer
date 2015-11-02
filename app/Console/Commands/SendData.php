<?php

namespace App\Console\Commands;

use App\Block;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class SendData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:blockchain-data {json}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send blockchain json for importing.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		$json = $this->argument('json');
		$payload = json_decode($json, true);
		switch($payload['op']) {
			case 'block':
				Redis::set('block.unconfirmed', 0);
				$block = new Block();
				$block->fillFromBlockchainJson($json);
				$block->save();
				$this->line("New block saved.");
				break;
			case 'utx':
				Redis::incr('block.unconfirmed');
				$this->line("New unconfirmed tx recorded.");
				break;
		}
    }

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	public function getArguments()
	{
		return array(
			array(
				'json',
				InputArgument::REQUIRED,
				'Json payload recieved from blockchain.'
			),
		);
	}
}
