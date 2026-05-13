<?php

namespace Corpus\Loggers;

use PHPUnit\Framework\TestCase;

class LoggerUnwrapTest extends TestCase {

	public function test_unwrap() : void {
		$loggerMemory = new MemoryLogger;

		$loggerWithContextInner = new LoggerWithContext(
			new LoggerVerbosityFilter(
				new LogLevelFilter($loggerMemory, [ \Psr\Log\LogLevel::INFO ]),
				1), [
					'request_id' => 123,
				]);

		$loggerWithContext = new LoggerWithContext($loggerWithContextInner, []);

		$this->assertSame($loggerMemory, $loggerWithContext->unwrapAll());
		$this->assertSame($loggerWithContextInner, $loggerWithContext->unwrap());
	}

}
