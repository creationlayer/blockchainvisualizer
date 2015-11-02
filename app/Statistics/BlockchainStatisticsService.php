<?php
/**
 * Created by PhpStorm.
 * User: igorrinkovec
 * Date: 01/08/15
 * Time: 13:40
 */

namespace App\Statistics;


use App\BlockchainSnapshot;

class BlockchainStatisticsService {

	/*
	 * Returns the info of the latest snapshot, with changes from the day before
	 */
	public static function getSizeDelta() {
		$latest = BlockchainSnapshot::getLatestBlockchainSnapshot();
		$before = BlockchainSnapshot::getLatestBlockchainSnapshot(1);

		$result = [
			'size' => $latest->size,
			'daySizeDiff' => $latest->size - $before->size,
		];


		if($before->size == 0) {
			$result['daySizeDiffPercent'] = '+ 100%';
		}
		else {
			$result['daySizeDiffPercent'] = (($latest->size / $before->size) - 1) * 100;
			$result['daySizeDiffPercent'] = $result['daySizeDiffPercent'] >= 0 ? '+ ' . $result['daySizeDiffPercent'] : (string) $result['daySizeDiffPercent'];
		}

		return $result;
	}

	/*
 * Returns the info of the latest snapshot, with changes from the day before
 */
	public static function getHashrateDiff() {
		$latest = BlockchainSnapshot::getLatestBlockchainSnapshot();
		$before = BlockchainSnapshot::getLatestBlockchainSnapshot(1);

		$result = [
			'hashrate' => round($latest->hashrate / 1024 / 1024, 0),
			'dayHashrateDiff' => round(($latest->hashrate - $before->hashrate) / 1024 / 1024, 0),
		];

		if($before->hashrate == 0) {
			$result['dayHashrateDiffPercent'] = '+ 100%';
		}
		else {
			$result['dayHashrateDiffPercent'] = round((($latest->hashrate / $before->hashrate) - 1) * 100, 0);
			$result['dayHashrateDiffPercent'] = $result['dayHashrateDiffPercent'] >= 0 ? '+ ' . $result['dayHashrateDiffPercent'] : '- ' . -$result['dayHashrateDiffPercent'];
		}

		return $result;
	}

}