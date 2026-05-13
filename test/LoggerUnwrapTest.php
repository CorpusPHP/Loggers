<?php

namespace Corpus\Loggers;

use PHPUnit\Framework\TestCase;

class LoggerUnwrapTest extends TestCase {

	public function test_unwrapLogger() : void {
		$loggerMemory      = new MemoryLogger;
		$loggerWithContext = new LoggerWithContext(
			new LoggerWithContext(
			new LoggerVerbosityFilter(
				new LogLevelFilter($loggerMemory, ['foo' => 'bar']),
				1), [
					'request_id' => 123,
				]), []);

		$this->assertSame($loggerMemory, $loggerWithContext->unwrapLogger());
	}

}
