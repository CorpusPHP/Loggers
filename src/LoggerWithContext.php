<?php

namespace Corpus\Loggers;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

/**
 * LoggerWithContext is a logger that adds a given context to all log messages
 * before delegating to another logger.
 *
 * This is useful for adding context to all log messages, such as the current
 * request ID, IP address or the current user ID.
 */
class LoggerWithContext implements LoggerInterface {

	use LoggerTrait;

	private array $context;
	private LoggerInterface $logger;

	/**
	 * Create a new LoggerWithContext instance with the given logger and context.
	 *
	 * The given context will be added to all log messages.
	 *
	 * @param LoggerInterface $logger  The logger to delegate to.
	 * @param array           $context The context to add to all log messages.
	 */
	public function __construct( LoggerInterface $logger, array $context = [] ) {
		$this->logger  = $logger;
		$this->context = $context;
	}

	/**
	 * @inheritDoc See LoggerInterface::log()
	 * @mddoc-ignore
	 */
	public function log( $level, $message, array $context = [] ) : void {
		$this->logger->log($level, $message, array_merge($this->context, $context));
	}

	/**
	 * Returns a new instance with the given context
	 * replacing the existing context.
	 */
	public function withContext( array $context ) : self {
		$clone          = clone $this;
		$clone->context = $context;

		return $clone;
	}

	/**
	 * Returns a new instance with the given context
	 * added to the existing context.
	 */
	public function withAddedContext( array $context ) : self {
		$clone          = clone $this;
		$clone->context = array_merge($clone->context, $context);

		return $clone;
	}

}
