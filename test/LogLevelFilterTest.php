<?php

namespace Corpus\Loggers;

use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

class LogLevelFilterTest extends TestCase {

	public function testExclude() : void {
		$memoryLogger = new MemoryLogger;
		$logger       = new LogLevelFilter(
			$memoryLogger,
			[ LogLevel::INFO ],
			true
		);

		$logger->info('Hello World', [ 'foo' => 'bar' ]);
		$logger->debug('How are you?', [ 'bar' => 'baz' ]);
		$logger->error('I am fine', [ 'baz' => 'qux' ]);

		$this->assertSame(
			[
				[ 'level' => 'debug', 'message' => 'How are you?', 'context' => [ 'bar' => 'baz' ] ],
				[ 'level' => 'error', 'message' => 'I am fine', 'context' => [ 'baz' => 'qux' ] ],
			],
			$memoryLogger->getLogs()
		);
	}

	public function testInclude() : void {
		$memoryLogger = new MemoryLogger;
		$logger       = new LogLevelFilter(
			$memoryLogger,
			[ LogLevel::INFO ],
			false
		);

		$logger->info('Hello World', [ 'foo' => 'bar' ]);
		$logger->debug('How are you?', [ 'bar' => 'baz' ]);
		$logger->error('I am fine', [ 'baz' => 'qux' ]);

		$this->assertSame(
			[
				[ 'level' => 'info', 'message' => 'Hello World', 'context' => [ 'foo' => 'bar' ] ],
			],
			$memoryLogger->getLogs()
		);
	}

}
