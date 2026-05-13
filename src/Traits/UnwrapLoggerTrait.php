<?php

namespace Corpus\Loggers\Traits;

use Corpus\Loggers\Interfaces\WrappedLoggerInterface;
use Psr\Log\LoggerInterface;

/**
 * UnwrapLoggerTrait is a trait that provides an implementation of the
 * unwrapLogger() method for loggers that implement the WrappedLoggerInterface.
 *
 * @mddoc-ignore
 */
trait UnwrapLoggerTrait {

	abstract public function getWrappedLogger() : LoggerInterface;

	public function unwrapLogger() : LoggerInterface {
		$logger = $this->logger;
		for(;;) {
			if( $logger instanceof WrappedLoggerInterface ) {
				$logger = $logger->unwrapLogger(false);

				continue;
			}

			return $logger;
		}
	}

}
