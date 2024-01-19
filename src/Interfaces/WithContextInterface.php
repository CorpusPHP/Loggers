<?php

namespace Corpus\Loggers\Interfaces;

interface WithContextInterface {

	/**
	 * Returns a new instance with the given context
	 * replacing the existing context.
	 */
	public function withContext( array $context ) : self;

	/**
	 * Returns a new instance with the given context
	 * added to the existing context.
	 */
	public function withAddedContext( array $context ) : self;

}
