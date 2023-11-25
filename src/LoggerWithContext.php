<?php

namespace Corpus\Loggers;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

/**
 * LoggerWithContext is a logger that adds a given context to all log messages.
 *
 * This is useful for adding context to all log messages, such as the current
 * request ID, IP address or the current user ID.
 */
class LoggerWithContext implements LoggerInterface {

	use LoggerTrait;

	private array $context;
	private LoggerInterface $logger;

	public function __construct( LoggerInterface $logger, array $context = [] ) {
		$this->logger  = $logger;
		$this->context = $context;
	}

	/**
	 * @inheritDoc See LoggerInterface::log()
	 */
	public function log( $level, $message, array $context = [] ) : void {
		$this->logger->log($level, $message, array_merge($this->context, $context));
	}

	/**
	 * withContext returns a new instance with the given context
	 * replacing the existing context.
	 */
	public function withContext( array $context ) : self {
		$clone          = clone $this;
		$clone->context = $context;

		return $clone;
	}

	/**
	 * withAddedContext returns a new instance with the given context
	 * added to the existing context.
	 */
	public function withAddedContext( array $context ) : self {
		$clone          = clone $this;
		$clone->context = array_merge($clone->context, $context);

		return $clone;
	}

}
