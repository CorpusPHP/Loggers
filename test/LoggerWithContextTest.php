<?php

namespace Corpus\Loggers;

use PHPUnit\Framework\TestCase;

class LoggerWithContextTest extends TestCase {

	public function test_LoggerWithContext() {
		$memoryLogger = new MemoryLogger;
		$logger       = new LoggerWithContext($memoryLogger, [ 'foo' => 'bar' ]);

		$logger->log('info', 'test', [ 'level' => 'info' ]);

		$this->assertSame([
			[
				'level'   => 'info',
				'message' => 'test',
				'context' => [
					'foo'   => 'bar',
					'level' => 'info',
				],
			],
		], $memoryLogger->getLogs());
	}

	public function test_LoggerWithContext_overwrite() {
		$memoryLogger = new MemoryLogger;
		$logger       = new LoggerWithContext($memoryLogger, [ 'foo' => 'bar', 'bar' => 'baz' ]);

		$logger->log('info', 'test', [ 'level' => 'info', 'foo' => 'qux' ]);

		$this->assertSame([
			[
				'level'   => 'info',
				'message' => 'test',
				'context' => [
					'foo'   => 'qux',
					'bar'   => 'baz',
					'level' => 'info',
				],
			],
		], $memoryLogger->getLogs());
	}

	public function test_withContext() {
		$memoryLogger = new MemoryLogger;
		$logger       = new LoggerWithContext($memoryLogger, [ 'foo' => 'bar' ]);

		$logger = $logger->withContext([ 'baz' => 'qux' ]);

		$logger->log('info', 'test', [ 'level' => 'info' ]);

		$this->assertSame([
			[
				'level'   => 'info',
				'message' => 'test',
				'context' => [
					'baz'   => 'qux',
					'level' => 'info',
				],
			],
		], $memoryLogger->getLogs());
	}

	public function test_withAddedContext() {
		$memoryLogger = new MemoryLogger;
		$logger       = new LoggerWithContext($memoryLogger, [ 'foo' => 'bar' ]);

		$logger = $logger->withAddedContext([ 'baz' => 'qux' ]);

		$logger->log('info', 'test', [ 'level' => 'info' ]);

		$this->assertSame([
			[
				'level'   => 'info',
				'message' => 'test',
				'context' => [
					'foo'   => 'bar',
					'baz'   => 'qux',
					'level' => 'info',
				],
			],
		], $memoryLogger->getLogs());
	}

}
