<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlockchainSnapshot extends Model
{
	public $timestamps = false;

    protected $table = 'blockchain_snapshots';

	protected $fillable = ['size', 'hashrate'];

	/*
	 * Get the latest blockchain snapshot from the database
	 */
	public static function getLatestBlockchainSnapshot($offset = 0) {
		$snapshot = BlockchainSnapshot::orderBy('time', 'DESC')->take($offset+1)->get();
		return $snapshot->pop();
	}
}
