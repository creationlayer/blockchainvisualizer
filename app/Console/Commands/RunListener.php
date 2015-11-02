<?php

namespace App\Console\Commands;

use App\Block;
use App\Statistics\BlockStatisticsService;
use App\Statistics\TransactionStatisticsService as TSS;
use App\Transaction;
use Devristo\Phpws\Server\WebSocketServer;
use Illuminate\Console\Command;
use React\EventLoop\Factory;
use Devristo\Phpws\Client\WebSocket;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

class RunListener extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:bc-listener';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Opens a socket connection and starts gathering data.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		// Amount of ticks no new data came in (disconnected?)
		$ticksIdle = 0;

		ignore_user_abort(1);

		$loop = Factory::create();

		$logger = new Logger();
		$writer = new Stream("php://output");
		$logger->addWriter($writer);

		/*
		 * SERVER LOGIC
		 */
		$server = new WebSocketServer("tcp://0.0.0.0:4024", $loop, $logger);

		$loop->addPeriodicTimer(1, function() use ($server, $logger){
			$string = json_encode(['pending' => TSS::getPendingTransactionStatus()]);
			foreach($server->getConnections() as $client)
				$client->sendString($string);
		});

		$server->bind();

		/*
		 * CLIENT LOGIC
		 */
		$client = new WebSocket("wss://ws.blockchain.info/inv", $loop, $logger);

		$client->on("request", function() use ($logger){
			$logger->notice("Request object created!");
		});

		$client->on("handshake", function() use ($logger) {
			$logger->notice("Handshake received!");
		});

		$client->on("connect", function() use ($logger, $client){
			$logger->notice("Connected!");
			$client->send('{"op":"blocks_sub"}');
			$client->send('{"op":"unconfirmed_sub"}');
		});

		$client->on("message", function($message) use ($client, $logger, $server, &$ticksIdle){
			$ticksIdle = 0;
			$payload = json_decode($message->getData(), true);
			switch($payload['op']) {
				case 'block':
					$block = new Block();
					$block->fillFromBlockchainJson($message->getData());
					$block->save();
					$latestBlock = Block::getLastBlockData();
					Transaction::setConfirmedByTransactionIndex($latestBlock['txIndexes']);

					// Broadcast new block
					$broadcastData = BlockStatisticsService::getWebsocketBlocksSummary();
					$string = json_encode($broadcastData);
					foreach($server->getConnections() as $c)
						$c->sendString($string);

					$logger->notice("Block saved.");
					break;
				case 'utx':
					$transaction = new Transaction();
					$transaction->fillFromBlockchainJson($message->getData());
					$transaction->save();
					break;
			}
		});

		$client->open();

		$loop->addPeriodicTimer(1, function() use ($logger, &$ticksIdle){
			if($ticksIdle >= 15) {
				$logger->notice("Timeout!");
				die();
			}
			$ticksIdle++;
		});


		// RUN LOOP
		$loop->run();

    }
}
