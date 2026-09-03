<?php

namespace Corpus\Loggers\Interfaces;

interface WithContextInterface {

	/**
	 * Returns a new instance with the given context
	 * replacing the existing context.
	 *
	 * @param mixed[] $context The context to add to all log messages.
	 */
	public function withContext( array $context ) : self;

	/**
	 * Returns a new instance with the given context
	 * added to the existing context.
	 *
	 * @param mixed[] $context The context to add to all log messages.
	 */
	public function withAddedContext( array $context ) : self;

}
