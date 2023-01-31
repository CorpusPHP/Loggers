<?php

namespace Corpus\Loggers;

use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

class MemoryLoggerTest extends TestCase {

	public const ALL_LEVELS = [
		LogLevel::EMERGENCY,
		LogLevel::ALERT,
		LogLevel::CRITICAL,
		LogLevel::ERROR,
		LogLevel::WARNING,
		LogLevel::NOTICE,
		LogLevel::INFO,
		LogLevel::DEBUG,
	];

	public function test_MemoryLogger() : void {
		$logger = new MemoryLogger;

		$i   = 0;
		$log = [];
		foreach( self::ALL_LEVELS as $level ) {
			$i++;
			$logger->log($level, 'test ' . $i, [ 'level' => $level ]);
			$log[] = [
				'level'   => $level,
				'message' => 'test ' . $i,
				'context' => [ 'level' => $level ],
			];
		}

		$this->assertSame($log, $logger->getLogs());

		$logger->clearLogs();

		$this->assertSame([], $logger->getLogs());
	}

}
