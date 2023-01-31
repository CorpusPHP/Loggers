<?php

namespace Corpus\Loggers;

use Psr\Log\LogLevel;

interface LogLevels {

	public const ALL_LEVELS = [
		LogLevel::EMERGENCY,
		LogLevel::ALERT,
		LogLevel::CRITICAL,
		LogLevel::ERROR,
		LogLevel::WARNING,
		LogLevel::NOTICE,
		LogLevel::INFO,
		LogLevel::DEBUG,
	];

}
