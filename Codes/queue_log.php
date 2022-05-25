<?php

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;


function AddLog($response){
	// create a log channel
	$logger = new Logger('channel-name');
	$logger->pushHandler(new StreamHandler(__DIR__ . '/logs/queue_.log', Logger::DEBUG));
	$logger->info('This is a log! ^_^ ');
	$logger->info($response );
//	$logger->error('This is a log error! ^_^ ');
}



