<?php

namespace Corpus\Loggers;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;

/**
 * LogLevelLoggerMux multiplexes logs to different loggers based on the log level.
 */
class LogLevelLoggerMux implements LoggerInterface {

	use LoggerTrait;

	private LoggerInterface $defaultLogger;
	private LoggerInterface $emergencyLogger;
	private LoggerInterface $alertLogger;
	private LoggerInterface $criticalLogger;
	private LoggerInterface $errorLogger;
	private LoggerInterface $warningLogger;
	private LoggerInterface $noticeLogger;
	private LoggerInterface $infoLogger;
	private LoggerInterface $debugLogger;

	/**
	 * @param \Psr\Log\LoggerInterface|null $defaultLogger The default logger to use for levels where no other logger
	 *     is specified. If null, a Psr\Log\NullLogger will be used.
	 */
	public function __construct(
		LoggerInterface $defaultLogger = null,

		?LoggerInterface $emergencyLogger = null,
		?LoggerInterface $alertLogger = null,
		?LoggerInterface $criticalLogger = null,
		?LoggerInterface $errorLogger = null,
		?LoggerInterface $warningLogger = null,
		?LoggerInterface $noticeLogger = null,
		?LoggerInterface $infoLogger = null,
		?LoggerInterface $debugLogger = null
	) {
		if( !$defaultLogger ) {
			$defaultLogger = new NullLogger();
		}

		$this->defaultLogger = $defaultLogger;

		$this->emergencyLogger = $emergencyLogger ?? $defaultLogger;
		$this->alertLogger     = $alertLogger ?? $defaultLogger;
		$this->criticalLogger  = $criticalLogger ?? $defaultLogger;
		$this->errorLogger     = $errorLogger ?? $defaultLogger;
		$this->warningLogger   = $warningLogger ?? $defaultLogger;
		$this->noticeLogger    = $noticeLogger ?? $defaultLogger;
		$this->infoLogger      = $infoLogger ?? $defaultLogger;
		$this->debugLogger     = $debugLogger ?? $defaultLogger;
	}

	public function withEmergencyLogger( LoggerInterface $logger ) : self {
		$clone                  = clone $this;
		$clone->emergencyLogger = $logger;

		return $clone;
	}

	public function withAlertLogger( LoggerInterface $logger ) : self {
		$clone              = clone $this;
		$clone->alertLogger = $logger;

		return $clone;
	}

	public function withCriticalLogger( LoggerInterface $logger ) : self {
		$clone                 = clone $this;
		$clone->criticalLogger = $logger;

		return $clone;
	}

	public function withErrorLogger( LoggerInterface $logger ) : self {
		$clone              = clone $this;
		$clone->errorLogger = $logger;

		return $clone;
	}

	public function withWarningLogger( LoggerInterface $logger ) : self {
		$clone                = clone $this;
		$clone->warningLogger = $logger;

		return $clone;
	}

	public function withNoticeLogger( LoggerInterface $logger ) : self {
		$clone               = clone $this;
		$clone->noticeLogger = $logger;

		return $clone;
	}

	public function withInfoLogger( LoggerInterface $logger ) : self {
		$clone             = clone $this;
		$clone->infoLogger = $logger;

		return $clone;
	}

	public function withDebugLogger( LoggerInterface $logger ) : self {
		$clone              = clone $this;
		$clone->debugLogger = $logger;

		return $clone;
	}

	public function log( $level, $message, array $context = [] ) {
		switch( true ) {
			case $level === LogLevel::EMERGENCY:
				$this->emergencyLogger->log($level, $message, $context);
				break;
			case $level === LogLevel::ALERT:
				$this->alertLogger->log($level, $message, $context);
				break;
			case $level === LogLevel::CRITICAL:
				$this->criticalLogger->log($level, $message, $context);
				break;
			case $level === LogLevel::ERROR:
				$this->errorLogger->log($level, $message, $context);
				break;
			case $level === LogLevel::WARNING:
				$this->warningLogger->log($level, $message, $context);
				break;
			case $level === LogLevel::NOTICE:
				$this->noticeLogger->log($level, $message, $context);
				break;
			case $level === LogLevel::INFO:
				$this->infoLogger->log($level, $message, $context);
				break;
			case $level === LogLevel::DEBUG:
				$this->debugLogger->log($level, $message, $context);
				break;
		}

		$this->defaultLogger->log($level, $message, $context);
	}
}
