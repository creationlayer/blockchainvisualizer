<?php
/**
 * Created by PhpStorm.
 * User: igorrinkovec
 * Date: 24/07/15
 * Time: 19:06
 */

namespace App\Statistics;

use App\Block;
use App\Services\BitcoinHelperService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;


class BlockStatisticsService {

	/*
	 * Size of the latest block in bytes
	 */
	public static function getLatestBlock() {
		$block = Block::orderBy('height', 'desc')->first();
		return $block;
	}

	/*
	 * Average size of the latest 5 blocks in bytes
	 */
	public static function getLatestXBlocksAverageSize($amount) {
		if($amount < 1) {
			return null;
		}

		$blocks = Block::orderBy('time', 'desc')->take( (int) $amount )->get();
		// Calculate average
		$sum = 0;
		foreach($blocks as $block) {
			$sum += $block->size / 1024;
		}
		return $sum / $blocks->count();
	}

	/*
	 * % of blocks bigger than set size in the last 24 hours
	 */
	public static function blocksAboveSizeInThePastDay($size) {
		$blocksAbove = Block::where('time', '>=', Carbon::now()->subDay())->where('size', '>=', $size)->count();
		$blocksTotal = Block::where('time', '>=', Carbon::now()->subDay())->count();

		return [
			'total' => $blocksTotal,
			'above' => $blocksAbove,
			'percentage' => ($blocksAbove / $blocksTotal) * 100,
		];
	}

	public static function getWebsocketBlocksSummary() {
		$block = self::getLatestBlock();
		$result = [
			'block' => $block,
			'volume' => self::getVolumeStatus(),
			'avg_block_size' => round(self::getLatestXBlocksAverageSize(25) / 1024, 2),
		];
		return $result;
	}

	/*
	 * Bitcoin volume status
	 */
	public static function getVolumeStatus() {
		$blocksToday = Block::where('time', '>=', Carbon::now()->subDay())->get()->lists('total_sent')->toArray();
		$blocksDayBefore = Block::where('time', '>=', Carbon::now()->subDays(2))->where('time', '<=', Carbon::now()->subDay())->get()->lists('total_sent')->toArray();

		$satoshiToBitcoin = function(&$item, $key) {
			$item = round(BitcoinHelperService::satoshiToBitcoin($item), 0);
		};
		array_walk($blocksToday, $satoshiToBitcoin);
		array_walk($blocksDayBefore, $satoshiToBitcoin);
		$blocksToday = array_sum($blocksToday);
		$blocksDayBefore = array_sum($blocksDayBefore);

		if($blocksDayBefore == 0) {
			$change = '+ 100%';
		}
		else {
			$change = (($blocksToday / $blocksDayBefore) - 1) * 100;
			$change = round($change, 0);
			$change = $change >= 0 ? '+ ' . $change : '- ' . -$change;
		}

		return [
			'today' => $blocksToday,
			'diffPercentage' => $change
		];
	}

	public static function getAverageBlockSizeForDate($year, $month, $day) {
        if(!Cache::has($year.$month.$day.'_AvgBlockSize')) {
            $start = Carbon::createFromDate($year, $month, $day)->startOfDay()->getTimestamp();
            $end = Carbon::createFromDate($year, $month, $day)->endOfDay()->getTimestamp();
            $sizes = Block::where('time', '>=', date('Y-m-d H:i:s', $start))->where('time', '<=', date('Y-m-d H:i:s', $end))->lists('size')->toArray();

            if(count($sizes) == 0) {
                return 0;
            }
            $result = round(array_sum($sizes) / 1024 / count($sizes), 0);
            Cache::put($year.$month.$day.'_AvgBlockSize', $result, 60 * 24);
        }
        return Cache::get($year.$month.$day.'_AvgBlockSize');
	}

} 