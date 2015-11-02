<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
		\App\Console\Commands\RunListener::class,
		\App\Console\Commands\SendData::class,
		\App\Console\Commands\SendUnconfirmed::class,
		\App\Console\Commands\Data\GetBlockchainSnapshot::class,
		\App\Console\Commands\Data\GetMissingBlocks::class,
        \App\Console\Commands\Data\ReconfirmTransactions::class,
        \App\Console\Commands\Data\GetPoolDistribution::class,
        \App\Console\Commands\Data\DeleteRejected::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
		$schedule->command('data:blockchain-snapshot')->daily();
		$schedule->command('data:missing-blocks')->everyFiveMinutes();
        $schedule->command('data:reconfirm-txs')->hourly();
        $schedule->command('data:pool-distribution')->hourly();
        $schedule->command('data:delete-rejected')->twiceDaily(0, 12);
    }
}
