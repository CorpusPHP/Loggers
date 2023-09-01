<?php

namespace Corpus\Loggers;

use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use TRegx\PhpUnit\DataProviders\DataProvider;

class LoggerVerbosityFilterTest extends TestCase {

	/**
	 * @dataProvider standardVerbosityLevel_logLevelSetMode_provider
	 */
	public function test_LoggerVerbosityFilter( int $logLevel, array $loggedLevels, bool $levelMode ) : void {
		$memoryLogger = new MemoryLogger;
		if( $levelMode ) {
			$logger = new LoggerVerbosityFilter($memoryLogger, $logLevel);
		} else {
			$logger = (new LoggerVerbosityFilter($memoryLogger))->withVerbosity($logLevel);
		}

		$i   = 0;
		$log = [];
		foreach( LogLevels::ALL_LEVELS as $level ) {
			$logger->log($level, 'test ' . $i, [ 'level' => $level ]);
			if( in_array($level, $loggedLevels, true) ) {
				$log[] = MemoryLogger::makeLogRecord($level, 'test ' . $i, [ 'level' => $level ]);
			}
		}

		$this->assertSame($log, $memoryLogger->getLogs());
	}

	public function standardVerbosityLevel_logLevelSetMode_provider() : iterable {
		return DataProvider::cross(
			$this->standardVerbosityLevelProvider(),
			$this->binaryChoiceProvider()
		);
	}

	/**
	 * @dataProvider customCallbackProvider
	 */
	public function test_LoggerVerbosityFilter_customLevelCallback(
		callable $callback,
		array $matchedCallbackLevelMap
	) : void {
		foreach( $matchedCallbackLevelMap as $logLevel => $matchedCallbackLevels ) {
			$memoryLogger = new MemoryLogger;
			$logger       = new LoggerVerbosityFilter($memoryLogger, $logLevel, $callback);

			$i   = 0;
			$log = [];
			foreach( LogLevels::ALL_LEVELS as $level ) {
				$i++;
				$logger->log($level, 'test ' . $i, [ 'level' => $level ]);
				if( in_array($level, $matchedCallbackLevels, true) ) {
					$log[] = MemoryLogger::makeLogRecord($level, 'test ' . $i, [ 'level' => $level ]);
				}
			}

			$this->assertSame($log, $memoryLogger->getLogs());
		}
	}

	public function customCallbackProvider() : array {
		return [
			[
				function ( string $level ) : int { return 0; },
				[
					0 => [ LogLevel::EMERGENCY, LogLevel::ALERT, LogLevel::CRITICAL, LogLevel::ERROR, LogLevel::WARNING, LogLevel::NOTICE, LogLevel::INFO, LogLevel::DEBUG ],
					5 => [ LogLevel::EMERGENCY, LogLevel::ALERT, LogLevel::CRITICAL, LogLevel::ERROR, LogLevel::WARNING, LogLevel::NOTICE, LogLevel::INFO, LogLevel::DEBUG ],
				],
			],
			[
				function ( string $level ) : int {
					switch( $level ) {
						case LogLevel::EMERGENCY:
							return 1;
						case LogLevel::ALERT:
							return 2;
						case LogLevel::CRITICAL:
							return 3;
						case LogLevel::ERROR:
							return 4;
						case LogLevel::WARNING:
							return 5;
						case LogLevel::NOTICE:
							return 6;
						case LogLevel::INFO:
							return 7;
						case LogLevel::DEBUG:
							return 8;
					}

					return 0;
				},
				[
					0 => [],
					1 => [ LogLevel::EMERGENCY ],
					2 => [ LogLevel::EMERGENCY, LogLevel::ALERT ],
					3 => [ LogLevel::EMERGENCY, LogLevel::ALERT, LogLevel::CRITICAL ],
					4 => [ LogLevel::EMERGENCY, LogLevel::ALERT, LogLevel::CRITICAL, LogLevel::ERROR ],
					5 => [ LogLevel::EMERGENCY, LogLevel::ALERT, LogLevel::CRITICAL, LogLevel::ERROR, LogLevel::WARNING ],
					6 => [ LogLevel::EMERGENCY, LogLevel::ALERT, LogLevel::CRITICAL, LogLevel::ERROR, LogLevel::WARNING, LogLevel::NOTICE ],
					7 => [ LogLevel::EMERGENCY, LogLevel::ALERT, LogLevel::CRITICAL, LogLevel::ERROR, LogLevel::WARNING, LogLevel::NOTICE, LogLevel::INFO ],
					8 => [ LogLevel::EMERGENCY, LogLevel::ALERT, LogLevel::CRITICAL, LogLevel::ERROR, LogLevel::WARNING, LogLevel::NOTICE, LogLevel::INFO, LogLevel::DEBUG ],
				],
			],
		];
	}

	public function standardVerbosityLevelProvider() : array {
		return [
			[ 0, [] ],
			[ 1, [ LogLevel::EMERGENCY, LogLevel::ALERT, LogLevel::CRITICAL, LogLevel::ERROR ] ],
			[ 2, [ LogLevel::EMERGENCY, LogLevel::ALERT, LogLevel::CRITICAL, LogLevel::ERROR, LogLevel::WARNING, LogLevel::NOTICE ] ],
			[ 3, [ LogLevel::EMERGENCY, LogLevel::ALERT, LogLevel::CRITICAL, LogLevel::ERROR, LogLevel::WARNING, LogLevel::NOTICE, LogLevel::INFO ] ],
			[ 4, [ LogLevel::EMERGENCY, LogLevel::ALERT, LogLevel::CRITICAL, LogLevel::ERROR, LogLevel::WARNING, LogLevel::NOTICE, LogLevel::INFO, LogLevel::DEBUG ] ],
		];
	}

	public function binaryChoiceProvider() : array {
		return [ [ true ], [ false ] ];
	}

}
