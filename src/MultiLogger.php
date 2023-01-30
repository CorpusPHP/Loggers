<?php

namespace Corpus\Loggers;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

/**
 * MultiLogger is a logger that delegates to multiple other loggers.
 */
class MultiLogger implements LoggerInterface {

	use LoggerTrait;

	/** @param LoggerInterface[] $loggers */
	private array $loggers;

	public function __construct( LoggerInterface ...$loggers ) {
		$this->loggers = $loggers;
	}

	/**
	 * withAdditionalLoggers returns a new instance with the given loggers
	 * added to the list of loggers to delegate to.
	 */
	public function withAdditionalLoggers( LoggerInterface ...$loggers ) : self {
		$clone = clone $this;
		$clone->loggers = array_merge($clone->loggers, $loggers);

		return $clone;
	}

	public function log( $level, $message, array $context = [] ) : void {
		foreach( $this->loggers as $logger ) {
			$logger->log($level, $message, $context);
		}
	}

}
