<?php

namespace Corpus\Loggers\Interfaces;

use Psr\Log\LoggerInterface;

interface MultiLoggerInterface extends LoggerInterface, WithAdditionalLoggersInterface {

}
