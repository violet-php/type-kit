<?php

$finder = \PhpCsFixer\Finder::create()
    ->in(__DIR__);

return (new PhpCsFixer\Config())
    ->setCacheFile(__DIR__ . '/build/.php-cs-fixer.cache')
    ->setRules([
        '@PSR12' => true,
        'no_unused_imports' => true,
    ])
    ->setFinder($finder);
