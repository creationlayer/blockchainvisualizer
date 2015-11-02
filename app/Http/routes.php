<?php

Route::get('socket_test', function() {
	return view('socket_test');
});

Route::get('test', function() {
    return \App\Statistics\TransactionStatisticsService::getTPSForDate(2015, 8, 15);
});

Route::get('/', 'InitialisationController@show');
Route::get('/transaction-confirmed/{txHash}', 'TransactionCheckController@isTransactionConfirmed');
