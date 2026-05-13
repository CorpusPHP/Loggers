<?php

namespace Corpus\Loggers\Interfaces;

use Psr\Log\LoggerInterface;

/**
 * WrappedLoggerInterface is an interface for loggers that can be unwrapped
 * to access the underlying logger.
 */
interface WrappedLoggerInterface {

	/**
	 * Returns the logger directly wrapped by the current logger, without
	 * unwrapping any nested loggers.
	 *
	 * This method allows access to the immediate underlying logger, which may
	 * itself be a wrapper around another logger. If you want to access the
	 * innermost logger, you can use the unwrapLogger() method
	 */
	public function unwrap() : LoggerInterface;

	/**
	 * Returns the underlying logger that this logger wraps, unwrapping any
	 * nested wrapping loggers recursively.
	 */
	public function unwrapAll() : LoggerInterface;

}
