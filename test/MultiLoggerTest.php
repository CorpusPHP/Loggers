<?php

namespace Corpus\Loggers;

use PHPUnit\Framework\TestCase;

class MultiLoggerTest extends TestCase {

	public function test_MultiLogger_single() : void {
		$memoryLogger = new MemoryLogger;
		$logger       = new MultiLogger($memoryLogger);

		$i   = 0;
		$log = [];
		foreach( LogLevels::ALL_LEVELS as $level ) {
			$i++;
			$logger->log($level, 'test ' . $i, [ 'level' => $level ]);
			$log[] = MemoryLogger::makeLogRecord($level, 'test ' . $i, [ 'level' => $level ]);
		}

		$this->assertSame($log, $memoryLogger->getLogs());
	}

	public function test_MultiLogger_multiple() : void {
		$memoryLogger1 = new MemoryLogger;
		$memoryLogger2 = new MemoryLogger;
		$logger        = new MultiLogger($memoryLogger1, $memoryLogger2);

		$i   = 0;
		$log = [];
		foreach( LogLevels::ALL_LEVELS as $level ) {
			$i++;
			$logger->log($level, 'test ' . $i, [ 'level' => $level ]);
			$log[] = MemoryLogger::makeLogRecord($level, 'test ' . $i, [ 'level' => $level ]);
		}

		$this->assertSame($log, $memoryLogger1->getLogs());
		$this->assertSame($log, $memoryLogger2->getLogs());
	}

	public function test_MultiLogger_withAdditionalLoggers() : void {
		$memoryLogger1 = new MemoryLogger;
		$memoryLogger2 = new MemoryLogger;
		$logger        = new MultiLogger($memoryLogger1, $memoryLogger2);

		$i   = 0;
		$log = [];
		foreach( LogLevels::ALL_LEVELS as $level ) {
			$i++;
			$logger->log($level, 'test ' . $i, [ 'level' => $level ]);
			$log[] = MemoryLogger::makeLogRecord($level, 'test ' . $i, [ 'level' => $level ]);
		}

		$this->assertSame($log, $memoryLogger1->getLogs());
		$this->assertSame($log, $memoryLogger2->getLogs());

		$memoryLogger3 = new MemoryLogger;
		$memoryLogger4 = new MemoryLogger;
		$logger        = $logger->withAdditionalLoggers($memoryLogger3, $memoryLogger4);

		$log2 = [];
		foreach( LogLevels::ALL_LEVELS as $level ) {
			$i++;
			$logger->log($level, 'test ' . $i, [ 'level' => $level ]);
			$log2[] = MemoryLogger::makeLogRecord($level, 'test ' . $i, [ 'level' => $level ]);
		}

		$mergedLogs = array_merge($log, $log2);
		$this->assertSame($mergedLogs, $memoryLogger1->getLogs());
		$this->assertSame($mergedLogs, $memoryLogger2->getLogs());

		$this->assertSame($log2, $memoryLogger3->getLogs());
		$this->assertSame($log2, $memoryLogger4->getLogs());
	}

}
