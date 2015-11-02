<?php

namespace App\Console\Commands;

use App\Block;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class SendUnconfirmed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:unconfirmed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Logs a single unconfirmed transaction.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		Redis::incr('block.unconfirmed');
    }
}
