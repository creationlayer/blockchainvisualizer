<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
	protected $table = 'blocks';

	public $timestamps = false;

    protected $fillable = ['id', 'hash', 'tx_amount', 'total_sent', 'estimated_sent', 'size', 'height', 'time'];

	/*
	 * Fill the model from the Blockchain.info JSON format
	 */
	public function fillFromBlockchainJson($json) {
		$rawBlock = json_decode($json, true);

		$this->hash = $rawBlock['x']['hash'];
		$this->tx_amount = $rawBlock['x']['nTx'];
		$this->total_sent = $rawBlock['x']['totalBTCSent'];
		$this->estimated_sent = $rawBlock['x']['estimatedBTCSent'];
		$this->size = $rawBlock['x']['size'];
		$this->height = $rawBlock['x']['height'];
		$this->time = date('Y-m-d H:i:s', $rawBlock['x']['time']);
        $this->created = date('Y-m-d H:i:s');
	}

	/*
	 * Get the info of the last block from Blockchain.info
	 */
	public static function getLastBlockData() {
		$latestBlock = file_get_contents("https://blockchain.info/latestblock");
		$latestBlock = json_decode($latestBlock, true);
		return $latestBlock;
	}

}
