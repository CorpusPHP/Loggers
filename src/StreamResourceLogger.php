<?php

namespace Corpus\Loggers;

use Corpus\Loggers\Exceptions\LoggerArgumentException;
use Corpus\Loggers\Exceptions\LoggerInitException;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

/**
 * StreamResourceLogger is a logger that writes to a stream resource.
 *
 * This is particularly useful for writing to STDERR or STDOUT, or to a file.
 */
class StreamResourceLogger implements LoggerInterface {

	use LoggerTrait;

	/** @var resource */
	private $stream;

	/**
	 * @param resource $resource Writable stream resource
	 * @throws LoggerArgumentException If the given resource is not a stream
	 * @throws LoggerInitException If the given resource is not writable
	 */
	public function __construct( $resource ) {
		if( !is_resource($resource)
			|| !(get_resource_type($resource) === 'stream')
		) {
			throw new LoggerArgumentException('Logger argument is not a valid stream.');
		}

		$meta = stream_get_meta_data($resource);

		if( !in_array(rtrim($meta['mode'], 'b'), [ 'r+', 'w', 'w+', 'a', 'a+', 'x', 'x+', 'c', 'c+' ]) ) {
			throw new LoggerInitException('Resource is not writable');
		}

		$this->stream = $resource;
	}

	/**
	 * @inheritDoc See LoggerInterface::log()
	 */
	public function log( $level, $message, array $context = [] ) {
		fprintf(
			$this->stream,
			"%s %9s: %s\n",
			date('c'),
			$level,
			is_string($message) ? $message : var_export($message, true)
		);

		$max = ($context ? max(array_map('strlen', array_keys($context))) : 0) + 4;
		foreach( $context as $key => $val ) {
			$content = var_export($val, true);
			$lines   = explode("\n", $content);
			$out     = '';
			foreach( $lines as $i => $line ) {
				if( $i > 0 ) {
					$out .= str_repeat(' ', $max) . '  ';
				}

				$out .= $line . "\n";
			}

			fprintf($this->stream, "%{$max}s: %s\n", $key, $out);
		}
	}

}
