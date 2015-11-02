<?php
/**
 * Created by PhpStorm.
 * User: igorrinkovec
 * Date: 01/08/15
 * Time: 13:56
 */

namespace App\Http\Controllers;


use App\Block;
use App\Statistics\BlockchainStatisticsService as BCSS;
use App\Statistics\BlockStatisticsService as BSS;
use App\Statistics\TransactionStatisticsService as TSS;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class InitialisationController extends Controller {

	public function show() {
		$data = [
			'pending' => TSS::getPendingTransactionStatus(),
			'volume' => BSS::getVolumeStatus(),
			'hashrate' => BCSS::getHashrateDiff(),
			'avg_block_size' => round(BSS::getLatestXBlocksAverageSize(25) / 1024, 2),
			'blocks' => Block::orderBy('height', 'desc')->take(15)->get(),
			'dailyTPS' => [],
			'dailyAvgBlockSize' => [],
			'dailyAmountBreakdown' => TSS::getDailyTransactionAmountBreakdown(),
			'poolDistribution' => Storage::get('pool-distribution.json'),
		];
		for($i = 0; $i < 12; $i++) {
			$day = Carbon::today()->subDays($i);
			$data['dailyTPS'][] = [$day->getTimestamp()*1000, TSS::getTPSForDate($day->year, $day->month, $day->day)];
			$data['dailyAvgBlockSize'][] = [$day->getTimestamp()*1000, BSS::getAverageBlockSizeForDate($day->year, $day->month, $day->day)];
		}

		return view('main', $data);
	}

} 