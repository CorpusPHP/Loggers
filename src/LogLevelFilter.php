<?php

namespace Corpus\Loggers;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

/**
 * LogLevelFilter is a logger that filters log messages based on the log level.
 * It can be used to filter out log messages that are not needed for a specific
 * use case.
 *
 * For example, you may want to log all messages at the DEBUG level to a file,
 * but only log messages at the ERROR level or higher to the console.
 *
 * This logger can be used to filter out the DEBUG messages from the console
 * logger.
 *
 * This logger accepts a list of log levels to filter, and a boolean indicating
 * whether to exclude or include the given log levels.
 */
class LogLevelFilter implements LoggerInterface {

	use LoggerTrait;

	private LoggerInterface $logger;
	/** @var string[] */
	private array $levels;
	private bool $exclude;

	/**
	 * @param string[] $levels  The log levels to filter.
	 * @param bool     $exclude Whether to exclude the given levels, or include them.
	 */
	public function __construct( LoggerInterface $logger, array $levels, bool $exclude = false ) {
		$this->logger  = $logger;
		$this->levels  = $levels;
		$this->exclude = $exclude;
	}

	/**
	 * @inheritDoc See LoggerInterface::log()
	 * @mddoc-ignore
	 */
	public function log( $level, $message, array $context = [] ) : void {
		if( $this->exclude ) {
			if( !in_array($level, $this->levels, true) ) {
				$this->logger->log($level, $message, $context);
			}
		} else {
			if( in_array($level, $this->levels, true) ) {
				$this->logger->log($level, $message, $context);
			}
		}
	}

}
