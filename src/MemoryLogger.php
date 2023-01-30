<?php

namespace Corpus\Loggers;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

/**
 * MemoryLogger is a logger that stores all logs in local memory.
 *
 * This is useful for testing purposes.
 */
class MemoryLogger implements LoggerInterface {

	use LoggerTrait;

	public const KEY_LEVEL   = 'level';
	public const KEY_MESSAGE = 'message';
	public const KEY_CONTEXT = 'context';

	private array $logs = [];

	public function log( $level, $message, array $context = [] ) : void {
		$this->logs[] = self::makeLogRecord($level, $message, $context);
	}

	public function getLogs() : array {
		return $this->logs;
	}

	public function clearLogs() : void {
		$this->logs = [];
	}

	/**
	 * makeLogRecord is a helper function to create a log record.
	 *
	 * It is exposed publicly so that it may be used in tests.
	 */
	public static function makeLogRecord($level, $message, array $context = []) : array {
		return [
			self::KEY_LEVEL   => $level,
			self::KEY_MESSAGE => $message,
			self::KEY_CONTEXT => $context,
		];
	}

}
