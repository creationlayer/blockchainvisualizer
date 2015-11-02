<?php
/**
 * Created by PhpStorm.
 * User: igorrinkovec
 * Date: 24/07/15
 * Time: 19:06
 */

namespace App\Statistics;

use App\Services\BitcoinHelperService;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;


class TransactionStatisticsService {

	/*
	 * Current unconfirmed transaction amount
	 */
	public static function getPendingTransactionsAmount() {
		return (int) Transaction::where('confirmed', 0)->count();
	}

	/*
	 * Current unconfirmed transaction amount
	 */
	public static function getPendingTransactionStatus() {
		$transactions = Transaction::where('confirmed', 0)->get();
		$result = [
			'count' => 0,
			'size' => 0,
			'fees' => 0,
			'transferred' => 0,
		];
		foreach($transactions as $t) {
			$result['count']++;
			// Kilobytes
			$result['size'] += $t->size / 1024;
			$result['fees'] += BitcoinHelperService::satoshiToBitcoin($t->fee);
			$result['transferred'] += BitcoinHelperService::satoshiToBitcoin($t->amount);
		}
		// Megabytes - 2 decimal points
		$result['size'] = round($result['size'] / 1024, 2);

		return $result;
	}

	public static function getTPSForTimeInterval($dateIntervalString, $endTime = null) {
		$dateInterval = new \DateInterval($dateIntervalString);
		$now = Carbon::now()->getTimestamp();
		$after = Carbon::now()->add($dateInterval)->getTimestamp();
		$secondsPassed = $after - $now;
		return Transaction::where('time', '>=', Carbon::now()->sub($dateInterval))->count() / $secondsPassed;
	}

	public static function getTPSForDate($year, $month, $day) {
        if(!Cache::has($year.$month.$day.'_TPS')){
            $start = Carbon::createFromDate($year, $month, $day)->startOfDay()->getTimestamp();
            $end = Carbon::createFromDate($year, $month, $day)->endOfDay()->getTimestamp();
            if(Carbon::createFromDate($year, $month, $day)->isToday()) {
                $end = Carbon::now()->getTimestamp();
            }
            $txNum = Transaction::where('time', '>=', date('Y-m-d H:i:s', $start))->where('time', '<=', date('Y-m-d H:i:s', $end))->count();
            $result = $txNum / ($end - $start);
            Cache::put($year.$month.$day.'_TPS', $result, 60 * 24);
        }
		return Cache::get($year.$month.$day.'_TPS');
	}

	public static function getDailyTransactionAmountBreakdown() {
        if(!Cache::has('dailyTxBreakdown')) {
            $txs = Transaction::where('time', '>=', Carbon::now()->subDay())->lists('amount')->toArray();
            $result = [
                ">25 BTC" => 0,
                ">5 BTC" => 0,
                ">1 BTC" => 0,
                "<1 BTC" => 0,
            ];

            foreach($txs as $a) {
                $a = BitcoinHelperService::satoshiToBitcoin($a);
                if($a > 25) $result[">25 BTC"]++;
                else if($a > 5) $result[">5 BTC"]++;
                else if($a > 1) $result[">1 BTC"]++;
                else $result["<1 BTC"]++;
            }
            Cache::put('dailyTxBreakdown', $result, 60);
        }
		return Cache::get('dailyTxBreakdown');
	}

}