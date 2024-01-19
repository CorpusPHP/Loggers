# (PSR-3) Loggers and Logging Utilities

[![Latest Stable Version](https://poser.pugx.org/corpus/loggers/version)](https://packagist.org/packages/corpus/loggers)
[![License](https://poser.pugx.org/corpus/loggers/license)](https://packagist.org/packages/corpus/loggers)
[![ci.yml](https://github.com/CorpusPHP/Loggers/actions/workflows/ci.yml/badge.svg)](https://github.com/CorpusPHP/Loggers/actions/workflows/ci.yml)


Utilities for and Loggers for [PSR-3: Logger Interface](https://www.php-fig.org/psr/psr-3/).

- **LogLevelFilter** - LogLevelFilter is a PSR Logger that filters logs based on the log level. It can be used to filter out log messages that are not needed for a specific use case.  
- **LogLevelLoggerMux** - LogLevelLoggerMux is a PSR Logger that multiplexes logs to different loggers based on the log level.  
- **LoggerVerbosityFilter** - LoggerVerbosityFilter mutes log messages based on a given integer verbosity level.  
- **LoggerWithContext** - LoggerWithContext is a logger that adds a given context to all log messages before delegating to another logger.  
- **MemoryLogger** - MemoryLogger is a PSR Logger that stores all logs in local memory.  
- **MultiLogger** - MultiLogger is a PSR Logger that delegates logs to multiple other loggers.  
- **StreamResourceLogger** - StreamResourceLogger is a PSR Logger that writes to a stream resource.

## Requirements

- **psr/log**: ^1 || ^2 || ^3
- **php**: ^7.4 || ^8.0

## Installing

Install the latest version with:

```bash
composer require 'corpus/loggers'
```

## Example

This example demonstrates how the loggers may be chained together to create complex interactions

```php
<?php

use Corpus\Loggers\LoggerWithContext;
use Corpus\Loggers\LogLevelFilter;
use Corpus\Loggers\MemoryLogger;
use Corpus\Loggers\MultiLogger;
use Corpus\Loggers\StreamResourceLogger;
use Psr\Log\LogLevel;

require __DIR__ . '/../vendor/autoload.php';

$memoryLogger = new MemoryLogger;
$cliLogger    = new StreamResourceLogger(
	fopen('php://output', 'w')
);

$logger = new MultiLogger(
	new LogLevelFilter(
		(new LoggerWithContext($memoryLogger))->withContext([ 'Logger' => 'Number 1' ]),
		[ Psr\Log\LogLevel::INFO ]
	),
	new LogLevelFilter(
		(new LoggerWithContext($cliLogger))->withContext([ 'Logger' => 'Number 2' ]),
		[ Psr\Log\LogLevel::INFO, LogLevel::DEBUG ]
	),
	new LogLevelFilter(
		(new LoggerWithContext($cliLogger))->withContext([ 'Logger' => 'Number 3' ]),
		[ Psr\Log\LogLevel::INFO, LogLevel::DEBUG ],
		true // reverse filter - only log levels NOT in the array
	)
);

$logger->info('Hello World', [ 'hello' => 'world' ]);
$logger->debug('How are you?', [ 'foo' => 'bar' ]);
$logger->error('I am fine', [ 'bar' => 'baz', 'baz' => 'qux' ]);

echo "\n--- dumping memory logger ---\n\n";

var_export($memoryLogger->getLogs());

```

```
2023-11-30T05:34:35+00:00      info: Hello World
    Logger: 'Number 2'
     hello: 'world'
2023-11-30T05:34:35+00:00     debug: How are you?
    Logger: 'Number 2'
       foo: 'bar'
2023-11-30T05:34:35+00:00     error: I am fine
    Logger: 'Number 3'
       bar: 'baz'
       baz: 'qux'

--- dumping memory logger ---

array (
  0 =>
  array (
    'level' => 'info',
    'message' => 'Hello World',
    'context' =>
    array (
      'Logger' => 'Number 1',
      'hello' => 'world',
    ),
  ),
)
```

## Documentation

### Class: \Corpus\Loggers\Interfaces\LoggerWithContextInterface

#### Method: LoggerWithContextInterface->withContext

```php
function withContext(array $context) : self
```

Returns a new instance with the given context  
replacing the existing context.

---

#### Method: LoggerWithContextInterface->withAddedContext

```php
function withAddedContext(array $context) : self
```

Returns a new instance with the given context  
added to the existing context.

### Class: \Corpus\Loggers\Interfaces\MultiLoggerInterface

#### Method: MultiLoggerInterface->withAdditionalLoggers

```php
function withAdditionalLoggers(\Psr\Log\LoggerInterface ...$loggers) : self
```

withAdditionalLoggers returns a new instance with the given loggers  
added to the list of loggers to delegate to.

### Class: \Corpus\Loggers\Interfaces\WithAdditionalLoggersInterface

#### Method: WithAdditionalLoggersInterface->withAdditionalLoggers

```php
function withAdditionalLoggers(\Psr\Log\LoggerInterface ...$loggers) : self
```

withAdditionalLoggers returns a new instance with the given loggers  
added to the list of loggers to delegate to.

### Class: \Corpus\Loggers\Interfaces\WithContextInterface

#### Method: WithContextInterface->withContext

```php
function withContext(array $context) : self
```

Returns a new instance with the given context  
replacing the existing context.

---

#### Method: WithContextInterface->withAddedContext

```php
function withAddedContext(array $context) : self
```

Returns a new instance with the given context  
added to the existing context.

### Class: \Corpus\Loggers\LoggerVerbosityFilter

LoggerVerbosityFilter mutes log messages based on a given integer verbosity level.

By default,

- level 0 logs no messages.
- level 1 logs emergency, alert, critical, and error messages.
- level 2 logs emergency, alert, critical, error, warning, and notice messages.
- level 3 logs emergency, alert, critical, error, warning, notice, and info messages.
- level 4 or greater logs emergency, alert, critical, error, warning, notice, info, and debug messages.

The levels can be changed by passing a callback redefine the verbosity level for each log level.

The verbosity level can be changed by calling withVerbosity()

#### Method: LoggerVerbosityFilter->__construct

```php
function __construct(\Psr\Log\LoggerInterface $logger [, int $verbosity = 0 [, ?callable $verbosityFromLevelCallback = null]])
```

##### Parameters:

- ***callable*** | ***null*** `$verbosityFromLevelCallback` - A callback that takes a Psr\Log\LogLevel log level string and
returns an integer verbosity level. If null, the default callback will be used.

---

#### Method: LoggerVerbosityFilter->withVerbosity

```php
function withVerbosity(int $verbosity) : self
```

Returns a new instance with the specified verbosity level.

---

#### Method: LoggerVerbosityFilter->withVerbosityFromLevelCallback

```php
function withVerbosityFromLevelCallback(callable $verbosityFromLevelCallback) : self
```

Returns a new instance with the specified verbosity level callback.

### Class: \Corpus\Loggers\LoggerWithContext

LoggerWithContext is a logger that adds a given context to all log messages
before delegating to another logger.

This is useful for adding context to all log messages, such as the current
request ID, IP address or the current user ID.

#### Method: LoggerWithContext->__construct

```php
function __construct(\Psr\Log\LoggerInterface $logger [, array $context = []])
```

Create a new LoggerWithContext instance with the given logger and context.  
  
The given context will be added to all log messages.

##### Parameters:

- ***\Psr\Log\LoggerInterface*** `$logger` - The logger to delegate to.
- ***array*** `$context` - The context to add to all log messages.

---

#### Method: LoggerWithContext->withContext

```php
function withContext(array $context) : self
```

Returns a new instance with the given context  
replacing the existing context.

---

#### Method: LoggerWithContext->withAddedContext

```php
function withAddedContext(array $context) : self
```

Returns a new instance with the given context  
added to the existing context.

### Class: \Corpus\Loggers\LogLevelFilter

LogLevelFilter is a PSR Logger that filters logs based on the log level.

It can be used to filter out log messages that are not needed for a specific
use case.

For example, you may want to log all messages at the DEBUG level to a file,
but only log messages at the ERROR level or higher to the console.

This logger can be used to filter out the DEBUG messages from the console
logger.

This logger accepts a list of log levels to filter, and a boolean indicating
whether to exclude or include the given log levels.

#### Method: LogLevelFilter->__construct

```php
function __construct(\Psr\Log\LoggerInterface $logger, array $levels [, bool $exclude = false])
```

##### Parameters:

- ***string[]*** `$levels` - The log levels to filter.
- ***bool*** `$exclude` - Whether to exclude the given levels, or include them.

### Class: \Corpus\Loggers\LogLevelLoggerMux

LogLevelLoggerMux is a PSR Logger that multiplexes logs to different loggers
based on the log level.

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

### Class: \Corpus\Loggers\MemoryLogger

MemoryLogger is a PSR Logger that stores all logs in local memory.

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

### Class: \Corpus\Loggers\MultiLogger

MultiLogger is a PSR Logger that delegates logs to multiple other loggers.

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

### Class: \Corpus\Loggers\StreamResourceLogger

StreamResourceLogger is a PSR Logger that writes to a stream resource.

This is particularly useful for writing to STDERR or STDOUT, or to a file.

#### Method: StreamResourceLogger->__construct

```php
function __construct($resource)
```

##### Parameters:

- ***resource*** `$resource` - Writable stream resource

**Throws**: `\Corpus\Loggers\Exceptions\LoggerArgumentException` - If the given resource is not a stream

**Throws**: `\Corpus\Loggers\Exceptions\LoggerInitException` - If the given resource is not writable