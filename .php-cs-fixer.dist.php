<?php

$finder = PhpCsFixer\Finder::create()
	->files()
	->in(__DIR__ . '/src')
	->in(__DIR__ . '/test')
	->in(__DIR__ . '/bin')
	->in(__DIR__ . '/example')
	->name('*.php');

$config = Corpus\PhpCsFixer\Config::php7x4();

return $config
	->setRules(
		array_merge(
			$config->getRules(),
			[ '@PHPUnit9x1Migration:risky' => true ],
		)
	)
	->setFinder($finder)
	->setUnsupportedPhpVersionAllowed(true);
