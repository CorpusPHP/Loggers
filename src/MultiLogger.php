<?php

namespace Corpus\Loggers;

use Corpus\Loggers\Interfaces\MultiLoggerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

/**
 * MultiLogger is a PSR Logger that delegates logs to multiple other loggers.
 */
class MultiLogger implements MultiLoggerInterface {

	use LoggerTrait;

	/** @var LoggerInterface[] $loggers */
	private array $loggers;

	/**
	 * Create a new MultiLogger instance with the given loggers to delegate to.
	 */
	public function __construct( LoggerInterface ...$loggers ) {
		$this->loggers = $loggers;
	}

	public function withAdditionalLoggers( LoggerInterface ...$loggers ) : self {
		$clone = clone $this;
		$clone->loggers = array_merge($clone->loggers, $loggers);

		return $clone;
	}

	/**
	 * @inheritDoc See LoggerInterface::log()
	 * @mddoc-ignore
	 */
	public function log( $level, $message, array $context = [] ) : void {
		foreach( $this->loggers as $logger ) {
			$logger->log($level, $message, $context);
		}
	}

}
