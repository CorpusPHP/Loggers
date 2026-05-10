<?php

namespace Corpus\Loggers\Traits;

use Corpus\Loggers\Interfaces\UnwrappableLoggerInterface;
use Psr\Log\LoggerInterface;

/**
 * UnwrapLoggerTrait is a trait that provides an implementation of the
 * unwrapLogger() method for loggers that implement the UnwrappableLoggerInterface.
 */
trait UnwrapLoggerTrait {

	private LoggerInterface $logger;

	public function unwrapLogger( bool $recursive = true ) : LoggerInterface {
		if( !$recursive ) {
			return $this->logger;
		}

		$logger = $this->logger;
		for(;;) {
			if( $logger instanceof UnwrappableLoggerInterface ) {
				$logger = $logger->unwrapLogger(false);

				continue;
			}

			return $logger;
		}
	}

}
