<?php

namespace Corpus\Loggers\Traits;

use Corpus\Loggers\Interfaces\WrappedLoggerInterface;
use Psr\Log\LoggerInterface;

/**
 * UnwrapTrait is a trait that provides an implementation of the
 * unwrapAll() method for loggers that implement the WrappedLoggerInterface.
 *
 * @mddoc-ignore
 */
trait UnwrapTrait {

	abstract public function unwrap() : LoggerInterface;

	public function unwrapAll() : LoggerInterface {
		$logger = $this->unwrap();
		for(;;) {
			if( $logger instanceof WrappedLoggerInterface ) {
				$logger = $logger->unwrap();

				continue;
			}

			return $logger;
		}
	}

}
