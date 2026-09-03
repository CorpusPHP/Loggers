<?php

namespace Corpus\Loggers;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

/**
 * MemoryLogger is a PSR Logger that stores all logs in local memory.
 *
 * This is primarily useful for testing purposes.
 */
class MemoryLogger implements LoggerInterface {

	use LoggerTrait;

	public const KEY_LEVEL   = 'level';
	public const KEY_MESSAGE = 'message';
	public const KEY_CONTEXT = 'context';

	/** @var list<array{level:mixed,message:mixed,context:array<string,mixed>}> */
	private array $logs = [];

	/**
	 * @inheritDoc See LoggerInterface::log()
	 *
	 * @param array<string,mixed> $context
	 * @mddoc-ignore
	 */
	public function log( $level, $message, array $context = [] ) : void {
		$this->logs[] = self::makeLogRecord($level, $message, $context);
	}

	/**
	 * getLogs returns all logs that have been logged to this logger.
	 *
	 * The returned array is a list of log records, each of which is an array keyed by:
	 *
	 * - MemoryLogger::KEY_LEVEL : The log level
	 * - MemoryLogger::KEY_MESSAGE : The log message
	 * - MemoryLogger::KEY_CONTEXT : The log context
	 *
	 * @return array[]
	 * @phpstan-return list<array{level:mixed,message:mixed,context:array<string,mixed>}>
	 */
	public function getLogs() : array {
		return $this->logs;
	}

	/**
	 * clearLogs clears all logs that have been logged to this logger.
	 */
	public function clearLogs() : void {
		$this->logs = [];
	}

	/**
	 * makeLogRecord is a helper function to create a log record.
	 *
	 * It is exposed publicly so that it may be used in tests.
	 *
	 * @param mixed               $level   The log level
	 * @param mixed               $message The log message
	 * @param array<string,mixed> $context The log context
	 * @return array{level:mixed,message:mixed,context:array<string,mixed>}
	 * @mddoc-ignore
	 */
	public static function makeLogRecord( $level, $message, array $context = [] ) : array {
		return [
			self::KEY_LEVEL   => $level,
			self::KEY_MESSAGE => $message,
			self::KEY_CONTEXT => $context,
		];
	}

}
