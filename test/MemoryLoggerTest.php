<?php

namespace Corpus\Loggers;

use PHPUnit\Framework\TestCase;

class MemoryLoggerTest extends TestCase {

	public function test_MemoryLogger() : void {
		$logger = new MemoryLogger;

		$i   = 0;
		$log = [];
		foreach( LogLevels::ALL_LEVELS as $level ) {
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
