<?php
namespace App\Http\Controllers;

class TransactionCheckController extends Controller {
    const RAW_TRANSACTION_API = "https://blockchain.info/rawtx/";

    public function isTransactionConfirmed($txHash) {
        $raw = json_decode(file_get_contents(self::RAW_TRANSACTION_API . $txHash), true);
        if($raw != null) {
            if(isset($raw['block_height'])) {
                return "true";
            }
        }
        return "false";
    }

}