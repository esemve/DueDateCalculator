<?php

declare(strict_types=1);


$finder = PhpCsFixer\Finder::create()
    ->ignoreVCSIgnored(true)
    ->exclude('vendor/')
    ->in(__DIR__)
;

$config = new PhpCsFixer\Config();
$config
    ->setRiskyAllowed(false)
    ->setRules([
        '@PhpCsFixer' => true,
    ])
    ->setFinder($finder)
;

return $config;