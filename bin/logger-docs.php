#!/usr/bin/env php
<?php

use Corpus\RecursiveRequire\Loader;

require __DIR__ . '/../vendor/autoload.php';

(new Loader(__DIR__ . '/../src', true))();

$loggers = array_filter(
	get_declared_classes(),
	fn ( string $class ) : bool => strpos($class, 'Corpus\\Loggers\\') === 0,
);

sort($loggers);

foreach( $loggers as $logger ) {
	if( is_subclass_of($logger, \Exception::class, true) ) {
		continue;
	}

	$ref = new ReflectionClass($logger);
	$doc = (string)$ref->getDocComment();

	echo "- **{$ref->getShortName()}**";

	$result = preg_replace('/^\/?\h*\*+\/?\h*/m', '', $doc);
	$result = trim($result);

	$lines = preg_split('/\v{2,}/', $result);

	foreach( $lines as $line ) {
		$line = preg_replace('/\v+/m', ' ', $line);
		echo " - {$line}";
		break;
	}

	echo "\n";
}
