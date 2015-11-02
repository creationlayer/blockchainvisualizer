<?php
/**
 * Created by PhpStorm.
 * User: igorrinkovec
 * Date: 01/08/15
 * Time: 15:05
 */

namespace App\Services;

class BitcoinHelperService {

	public static function satoshiToBitcoin($amount) {
		return $amount / 100000000;
	}

	public static function satoshiToMicrobitcoin($amount) {
		return $amount / 1000000;
	}

	public static function satoshiToMilibitcoin($amount) {
		return $amount / 1000;
	}

} 