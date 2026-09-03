<?php

namespace Corpus\Loggers;

use PHPUnit\Framework\TestCase;

class LoggerWithContextTest extends TestCase {

	public function test_LoggerWithContext() : void {
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

	public function test_LoggerWithContext_overwrite() : void {
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

	public function test_withContext() : void {
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

	public function test_withAddedContext() : void {
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

	public function test_numericContextKeys_arePreserved() : void {
		$memoryLogger = new MemoryLogger;
		$logger       = new LoggerWithContext($memoryLogger, [ 2 => 'initial' ]);

		$logger = $logger->withAddedContext([ 4 => 'additional' ]);
		$logger->log('info', 'test', [ 6 => 'per-message' ]);

		$this->assertSame([
			[
				'level'   => 'info',
				'message' => 'test',
				'context' => [
					2 => 'initial',
					4 => 'additional',
					6 => 'per-message',
				],
			],
		], $memoryLogger->getLogs());
	}

}
