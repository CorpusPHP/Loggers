<?php

namespace Corpus\Loggers;

use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;

class LogLevelLoggerMuxTest extends TestCase {

	/**
	 * @dataProvider levelTestProvider
	 */
	public function test_LogLevelLoggerMux_catchNone( array $levels ) : void {
		$memoryLogger = new MemoryLogger;
		$logger       = new LogLevelLoggerMux;
		foreach( $levels as $level ) {
			$logger->log($level, 'test', [ 'level' => $level ]);
		}

		$this->assertSame([], $memoryLogger->getLogs());
	}

	public function test_LogLevelLoggerMux_catchAll() : void {
		$memoryLogger = new MemoryLogger;
		$logger       = new LogLevelLoggerMux($memoryLogger);

		$i   = 0;
		$log = [];
		foreach( LogLevels::ALL_LEVELS as $level ) {
			$i++;
			$logger->log($level, 'test ' . $i, [ 'level' => $level ]);
			$log[] = MemoryLogger::makeLogRecord($level, 'test ' . $i, [ 'level' => $level ]);
		}

		$this->assertSame($log, $memoryLogger->getLogs());
	}

	/**
	 * @dataProvider levelTestProvider
	 */
	public function test_LogLevelLoggerMux_catchSome( array $levels ) : void {
		$memoryLogger = new MemoryLogger;
		$logger       = new LogLevelLoggerMux($memoryLogger);

		$i   = 0;
		$log = [];
		foreach( $levels as $level ) {
			$i++;
			$logger->log($level, 'test ' . $i, [ 'level' => $level ]);
			$log[] = MemoryLogger::makeLogRecord($level, 'test ' . $i, [ 'level' => $level ]);
		}

		$this->assertSame($log, $memoryLogger->getLogs());
	}

	private const METHOD_MAP = [
		LogLevel::EMERGENCY => 'withEmergencyLogger',
		LogLevel::ALERT     => 'withAlertLogger',
		LogLevel::CRITICAL  => 'withCriticalLogger',
		LogLevel::ERROR     => 'withErrorLogger',
		LogLevel::WARNING   => 'withWarningLogger',
		LogLevel::NOTICE    => 'withNoticeLogger',
		LogLevel::INFO      => 'withInfoLogger',
		LogLevel::DEBUG     => 'withDebugLogger',
	];

	/**
	 * @dataProvider levelTestProvider
	 */
	public function test_LogLevelLoggerMux_catchWith( array $levels ) : void {
		$memoryLogger = new MemoryLogger;
		$logger       = new LogLevelLoggerMux;

		foreach( $levels as $level ) {
			$logger = $logger->{self::METHOD_MAP[$level]}($memoryLogger);
		}

		$i   = 0;
		$log = [];
		foreach( LogLevels::ALL_LEVELS as $level ) {
			$i++;
			$logger->log($level, 'test ' . $i, [ 'level' => $level ]);
			if( in_array($level, $levels, true) ) {
				$log[] = MemoryLogger::makeLogRecord($level, 'test ' . $i, [ 'level' => $level ]);
			}
		}

		$this->assertSame($log, $memoryLogger->getLogs());
	}

	/**
	 * @dataProvider levelTestProvider
	 */
	public function test_LogLevelLoggerMux_catchWith_includingDefault( array $levels ) : void {
		$memoryLoggerHit  = new MemoryLogger;
		$memoryLoggerMiss = new MemoryLogger;
		$logger           = new LogLevelLoggerMux($memoryLoggerMiss);

		foreach( $levels as $level ) {
			$logger = $logger->{self::METHOD_MAP[$level]}($memoryLoggerHit);
		}

		$i = 0;

		$hitLog  = [];
		$missLog = [];
		foreach( LogLevels::ALL_LEVELS as $level ) {
			$i++;
			$logger->log($level, 'test ' . $i, [ 'level' => $level ]);
			if( in_array($level, $levels, true) ) {
				$hitLog[] = MemoryLogger::makeLogRecord($level, 'test ' . $i, [ 'level' => $level ]);
			} else {
				$missLog[] = MemoryLogger::makeLogRecord($level, 'test ' . $i, [ 'level' => $level ]);
			}
		}

		$this->assertSame($hitLog, $memoryLoggerHit->getLogs());
		$this->assertSame($missLog, $memoryLoggerMiss->getLogs());
	}

	public function test_LogLevelLoggerMux_invalidLogLevel() : void {
		$catchAll   = new MemoryLogger;
		$nullLogger = new NullLogger;
		$logger     = new LogLevelLoggerMux(
			$catchAll, $nullLogger, $nullLogger, $nullLogger, $nullLogger, $nullLogger, $nullLogger, $nullLogger
		);

		$logger->log('invalid', 'test');
		$this->assertSame([ MemoryLogger::makeLogRecord('invalid', 'test') ], $catchAll->getLogs());
	}

	public function levelTestProvider() : \Generator {
		foreach( self::arrayPowerSet(LogLevels::ALL_LEVELS) as $combination ) {
			yield [ $combination ];
		}
	}

	private static function arrayPowerSet( array $array ) : array {
		// initialize by adding the empty set
		$results = [ [] ];

		foreach( $array as $element ) {
			foreach( $results as $combination ) {
				$results[] = array_merge([ $element ], $combination);
			}
		}

		return $results;
	}

}
