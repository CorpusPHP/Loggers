<?php

namespace Corpus\Loggers;

use PHPUnit\Framework\TestCase;

class LoggerUnwrapTest extends TestCase {

	public function test_unwrapLogger() : void {
		$loggerMemory          = new MemoryLogger;
		$loggerVerbosityFilter = new LoggerVerbosityFilter($loggerMemory, 1);
		$loggerWithContext     = new LoggerWithContext($loggerVerbosityFilter, [ 'request_id' => 123 ]);

		$this->assertSame($loggerVerbosityFilter, $loggerWithContext->unwrapLogger(false));
		$this->assertSame($loggerMemory, $loggerWithContext->unwrapLogger(true));
	}

}
