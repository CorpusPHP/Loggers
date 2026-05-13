<?php

namespace Corpus\Loggers;

use Corpus\Loggers\Exceptions\LoggerArgumentException;
use Corpus\Loggers\Exceptions\LoggerInitException;
use PHPUnit\Framework\TestCase;

class StreamResourceLoggerTest extends TestCase {

	public function test_log_writesToStream() : void {
		$stream = fopen('php://memory', 'r+');
		$logger = new StreamResourceLogger($stream);

		$logger->log('info', 'hello world');

		rewind($stream);
		$output = stream_get_contents($stream);
		fclose($stream);

		$this->assertStringContainsString('info', $output);
		$this->assertStringContainsString('hello world', $output);
	}

	public function test_log_withContext() : void {
		$stream = fopen('php://memory', 'r+');
		$logger = new StreamResourceLogger($stream);

		$logger->log('debug', 'msg with context', [ 'key' => 'value' ]);

		rewind($stream);
		$output = stream_get_contents($stream);
		fclose($stream);

		$this->assertStringContainsString('debug', $output);
		$this->assertStringContainsString('msg with context', $output);
		$this->assertStringContainsString('key', $output);
		$this->assertStringContainsString('value', $output);
	}

	public function test_log_withNonStringMessage() : void {
		$stream = fopen('php://memory', 'r+');
		$logger = new StreamResourceLogger($stream);

		$logger->log('warning', 42);

		rewind($stream);
		$output = stream_get_contents($stream);
		fclose($stream);

		$this->assertStringContainsString('warning', $output);
		$this->assertStringContainsString('42', $output);
	}

	public function test_constructor_throwsOnNonResource() : void {
		$this->expectException(LoggerArgumentException::class);
		new StreamResourceLogger('not a resource');
	}

	public function test_constructor_throwsOnClosedResource() : void {
		$tmp = tmpfile();
		fclose($tmp);

		$this->expectException(LoggerArgumentException::class);
		/** @phpstan-ignore-next-line */
		new StreamResourceLogger($tmp);
	}

	public function test_constructor_throwsOnReadOnlyStream() : void {
		$stream = fopen('php://memory', 'r');

		try {
			$this->expectException(LoggerInitException::class);
			new StreamResourceLogger($stream);
		} finally {
			fclose($stream);
		}
	}

}
