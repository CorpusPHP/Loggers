<?php

namespace Corpus\Loggers;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Psr\Log\LogLevel;

/**
 * LoggerVerbosityFilter mutes log messages based on a given integer verbosity level.
 *
 * By default,
 *
 * - level 0 logs no messages.
 * - level 1 logs emergency, alert, critical, and error messages.
 * - level 2 logs emergency, alert, critical, error, warning, and notice messages.
 * - level 3 logs emergency, alert, critical, error, warning, notice, and info messages.
 * - level 4 or greater logs emergency, alert, critical, error, warning, notice, info, and debug messages.
 *
 * The levels can be changed by passing a callback redefine the verbosity level for each log level.
 *
 * The verbosity level can be changed by calling withVerbosity()
 */
class LoggerVerbosityFilter implements LoggerInterface {

	use LoggerTrait;

	private LoggerInterface $logger;
	private int $verbosity;
	/** @var callable|null */
	private $verbosityFromLevelCallback;

	/**
	 * @param callable|null $verbosityFromLevelCallback A callback that takes a Psr\Log\LogLevel log level string and
	 *                                                  returns an integer verbosity level. If null, the default callback will be used.
	 */
	public function __construct(
		LoggerInterface $logger,
		int $verbosity = 0,
		?callable $verbosityFromLevelCallback = null
	) {
		$this->logger                     = $logger;
		$this->verbosity                  = $verbosity;
		$this->verbosityFromLevelCallback = $verbosityFromLevelCallback
			?? fn ( string $level ) : int => $this->getVerbosityFromLevel($level);
	}

	/**
	 * Returns a new instance with the specified verbosity level.
	 */
	public function withVerbosity( int $verbosity ) : self {
		$clone            = clone $this;
		$clone->verbosity = $verbosity;

		return $clone;
	}

	/**
	 * Returns a new instance with the specified verbosity level callback.
	 */
	public function withVerbosityFromLevelCallback( callable $verbosityFromLevelCallback ) : self {
		$clone                             = clone $this;
		$clone->verbosityFromLevelCallback = $verbosityFromLevelCallback;

		return $clone;
	}

	/**
	 * @inheritDoc See LoggerInterface::log()
	 */
	public function log( $level, $message, array $context = [] ) {
		if( $this->verbosity >= ($this->verbosityFromLevelCallback)($level) ) {

			$this->logger->log($level, $message, $context);
		}
	}

	private function getVerbosityFromLevel( string $level ) : int {
		switch( $level ) {
			case LogLevel::EMERGENCY:
			case LogLevel::ALERT:
			case LogLevel::CRITICAL:
			case LogLevel::ERROR:
				return 1;
			case LogLevel::WARNING:
			case LogLevel::NOTICE:
				return 2;
			case LogLevel::INFO:
				return 3;
			case LogLevel::DEBUG:
				return 4;
		}

		return 0;
	}

}
