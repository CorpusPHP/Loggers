<?php

use Corpus\Loggers\LoggerWithContext;
use Corpus\Loggers\LogLevelFilter;
use Corpus\Loggers\MemoryLogger;
use Corpus\Loggers\MultiLogger;
use Corpus\Loggers\StreamResourceLogger;
use Psr\Log\LogLevel;

require __DIR__ . '/../vendor/autoload.php';

$memoryLogger = new MemoryLogger;
$cliLogger    = new StreamResourceLogger(
	fopen('php://output', 'w')
);

$logger = new MultiLogger(
	new LogLevelFilter(
		(new LoggerWithContext($memoryLogger))->withContext([ 'Logger' => 'Number 1' ]),
		[ Psr\Log\LogLevel::INFO ]
	),
	new LogLevelFilter(
		(new LoggerWithContext($cliLogger))->withContext([ 'Logger' => 'Number 2' ]),
		[ Psr\Log\LogLevel::INFO, LogLevel::DEBUG ]
	),
	new LogLevelFilter(
		(new LoggerWithContext($cliLogger))->withContext([ 'Logger' => 'Number 3' ]),
		[ Psr\Log\LogLevel::INFO, LogLevel::DEBUG ],
		true // reverse filter - only log levels NOT in the array
	)
);

$logger->info('Hello World', [ 'hello' => 'world' ]);
$logger->debug('How are you?', [ 'foo' => 'bar' ]);
$logger->error('I am fine', [ 'bar' => 'baz', 'baz' => 'qux' ]);

echo "\n--- dumping memory logger ---\n\n";

var_export($memoryLogger->getLogs());
