# HTTP Message Utils

[![Latest Stable Version](https://poser.pugx.org/corpus/loggers/version)](https://packagist.org/packages/corpus/loggers)
[![License](https://poser.pugx.org/corpus/loggers/license)](https://packagist.org/packages/corpus/loggers)
[![ci.yml](https://github.com/CorpusPHP/Loggers/actions/workflows/ci.yml/badge.svg?)](https://github.com/CorpusPHP/Loggers/actions/workflows/ci.yml)


Utilities for and Loggers for [PSR-3: Logger Interface](https://www.php-fig.org/psr/psr-3/).

## Requirements

- **psr/log**: ^1
- **php**: ^7.4 || ^8.0

## Installing

Install the latest version with:

```bash
composer require 'corpus/loggers'
```

## Documentation

### Class: \Corpus\Loggers\LogLevelLoggerMux

LogLevelLoggerMux multiplexes logs to different loggers based on the log level.

#### Method: LogLevelLoggerMux->__construct

```php
function __construct([ ?\Psr\Log\LoggerInterface $defaultLogger = null [, ?\Psr\Log\LoggerInterface $emergencyLogger = null [, ?\Psr\Log\LoggerInterface $alertLogger = null [, ?\Psr\Log\LoggerInterface $criticalLogger = null [, ?\Psr\Log\LoggerInterface $errorLogger = null [, ?\Psr\Log\LoggerInterface $warningLogger = null [, ?\Psr\Log\LoggerInterface $noticeLogger = null [, ?\Psr\Log\LoggerInterface $infoLogger = null [, ?\Psr\Log\LoggerInterface $debugLogger = null]]]]]]]]])
```

##### Parameters:

- ***\Psr\Log\LoggerInterface*** | ***null*** `$defaultLogger` - The default logger to use for levels where no other logger
is specified. If null, a Psr\Log\NullLogger will be used.

---

#### Method: LogLevelLoggerMux->withEmergencyLogger

```php
function withEmergencyLogger(\Psr\Log\LoggerInterface $logger) : self
```

Returns a new instance with the specified logger handling the Emergency log level.

---

#### Method: LogLevelLoggerMux->withAlertLogger

```php
function withAlertLogger(\Psr\Log\LoggerInterface $logger) : self
```

Returns a new instance with the specified logger handling the Alert log level.

---

#### Method: LogLevelLoggerMux->withCriticalLogger

```php
function withCriticalLogger(\Psr\Log\LoggerInterface $logger) : self
```

Returns a new instance with the specified logger handling the Critical log level.

---

#### Method: LogLevelLoggerMux->withErrorLogger

```php
function withErrorLogger(\Psr\Log\LoggerInterface $logger) : self
```

Returns a new instance with the specified logger handling the Error log level.

---

#### Method: LogLevelLoggerMux->withWarningLogger

```php
function withWarningLogger(\Psr\Log\LoggerInterface $logger) : self
```

Returns a new instance with the specified logger handling the Warning log level.

---

#### Method: LogLevelLoggerMux->withNoticeLogger

```php
function withNoticeLogger(\Psr\Log\LoggerInterface $logger) : self
```

Returns a new instance with the specified logger handling the Notice log level.

---

#### Method: LogLevelLoggerMux->withInfoLogger

```php
function withInfoLogger(\Psr\Log\LoggerInterface $logger) : self
```

Returns a new instance with the specified logger handling the Info log level.

---

#### Method: LogLevelLoggerMux->withDebugLogger

```php
function withDebugLogger(\Psr\Log\LoggerInterface $logger) : self
```

Returns a new instance with the specified logger handling the Debug log level.

---

#### Method: LogLevelLoggerMux->log

```php
function log($level, $message [, array $context = []])
```

### Class: \Corpus\Loggers\MemoryLogger

MemoryLogger is a logger that stores all logs in local memory.

This is primarily useful for testing purposes.

```php
<?php
namespace Corpus\Loggers;

class MemoryLogger {
	public const KEY_LEVEL = 'level';
	public const KEY_MESSAGE = 'message';
	public const KEY_CONTEXT = 'context';
}
```

#### Method: MemoryLogger->log

```php
function log($level, $message [, array $context = []]) : void
```

---

#### Method: MemoryLogger->getLogs

```php
function getLogs() : array
```

getLogs returns all logs that have been logged to this logger.  

##### The returned array is a list of log records, each of which is an array keyed by

- MemoryLogger::KEY_LEVEL : The log level  
- MemoryLogger::KEY_MESSAGE : The log message  
- MemoryLogger::KEY_CONTEXT : The log context

##### Returns:

- ***array[]***

---

#### Method: MemoryLogger->clearLogs

```php
function clearLogs() : void
```

clearLogs clears all logs that have been logged to this logger.

---

#### Method: MemoryLogger::makeLogRecord

```php
function makeLogRecord($level, $message [, array $context = []]) : array
```

makeLogRecord is a helper function to create a log record.  
  
It is exposed publicly so that it may be used in tests.

### Class: \Corpus\Loggers\MultiLogger

MultiLogger is a logger that delegates to multiple other loggers.

#### Method: MultiLogger->__construct

```php
function __construct(\Psr\Log\LoggerInterface ...$loggers)
```

Create a new MultiLogger instance with the given loggers to delegate to.

---

#### Method: MultiLogger->withAdditionalLoggers

```php
function withAdditionalLoggers(\Psr\Log\LoggerInterface ...$loggers) : self
```

withAdditionalLoggers returns a new instance with the given loggers  
added to the list of loggers to delegate to.

---

#### Method: MultiLogger->log

```php
function log($level, $message [, array $context = []]) : void
```