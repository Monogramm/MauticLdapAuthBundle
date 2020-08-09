<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/Config')
    ->in(__DIR__.'/EventListener')
    ->in(__DIR__.'/Form')
    ->in(__DIR__.'/Integration')
    ->in(__DIR__.'/Views');

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony'               => true,
        'binary_operator_spaces' => [
            'align_double_arrow' => true,
            'align_equals'       => true,
        ],
        'phpdoc_to_comment' => false,
        'ordered_imports'   => true,
        'array_syntax'      => [
            'syntax' => 'short',
        ],
        'no_unused_imports' => false,
    ])
    ->setFinder($finder);
