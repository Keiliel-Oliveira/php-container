<?php

use PhpCsFixer\Finder;
use PhpCsFixer\Config;

$finder = new Finder()->in(__DIR__);

$customRules = [
    'braces_position' => [
        'classes_opening_brace' => 'same_line',
        'functions_opening_brace' => 'same_line'
    ],
    'class_attributes_separation' => true,
    'ordered_class_elements' => true,
    'ordered_interfaces' => true,
    'empty_loop_body' => [
        'style' => 'braces'
    ],
    'declare_equal_normalize' => [
        'space' => 'single'
    ],
    'concat_space' => [
        'spacing' => 'one'
    ],
    'spaces_inside_parentheses' => [
        'space' => 'single'
    ]
];

return new Config()
->setRules([
    "@Symfony" => true,
    "@PSR12" => true,
    "@PhpCsFixer" => true,
    ...$customRules
])
->setFinder($finder);