<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
	protected $table = 'transactions';

	public $timestamps = false;

	protected $fillable = ['id', 'hash', 'size', 'amount', 'fee', 'ip', 'time'];

	public function fillFromBlockchainJson($json) {
		$rawTransaction = json_decode($json, true);

		$this->hash = $rawTransaction['x']['hash'];
		$this->size = $rawTransaction['x']['size'];
		$this->index = $rawTransaction['x']['tx_index'];

		$inputSize = 0;
		foreach($rawTransaction['x']['inputs'] as $input) {
			$inputSize += (int) $input['prev_out']['value'];
		}
		$outputSize = 0;
		foreach($rawTransaction['x']['out'] as $output) {
			$outputSize += (int) $output['value'];
		}

		// Inputs not in the output = miner fee
		$this->fee = $inputSize - $outputSize;
		// Outputs = size transferred
		$this->amount = $outputSize;
		$this->ip = $rawTransaction['x']['relayed_by'];
		$this->time = date('Y-m-d H:i:s', $rawTransaction['x']['time']);
		$this->confirmed = false;
	}

	public static function setConfirmedByTransactionIndex($indexes) {
		Transaction::whereIn('index', $indexes)->update(['confirmed' => 1]);
	}

	public static function setConfirmedByTransactionHash($hashes) {
		Transaction::whereIn('hash', $hashes)->update(['confirmed' => 1]);
	}

}
