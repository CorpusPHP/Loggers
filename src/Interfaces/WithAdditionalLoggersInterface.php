<?php

namespace Corpus\Loggers\Interfaces;

use Psr\Log\LoggerInterface;

interface WithAdditionalLoggersInterface {

	/**
	 * withAdditionalLoggers returns a new instance with the given loggers
	 * added to the list of loggers to delegate to.
	 */
	public function withAdditionalLoggers( LoggerInterface ...$loggers ) : self;

}
