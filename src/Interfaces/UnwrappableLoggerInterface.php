<?php

namespace Corpus\Loggers\Interfaces;

use Psr\Log\LoggerInterface;

/**
 * UnwrappableLoggerInterface is an interface for loggers that can be unwrapped
 * to access the underlying logger.
 */
interface UnwrappableLoggerInterface {

	/**
	 * Returns the underlying logger that this logger wraps.
	 *
	 * If $recursive is true, this method will unwrap all nested loggers and
	 * return the innermost logger.
	 *
	 * If $recursive is false, this method will return the immediate underlying
	 * logger without unwrapping further.
	 */
	public function unwrapLogger( bool $recursive = true ) : LoggerInterface;

}
