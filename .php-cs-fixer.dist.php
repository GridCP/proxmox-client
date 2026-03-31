<?php

$finder = new PhpCsFixer\Finder()
    ->in(__DIR__ . '/src')
    ->exclude('var')
    ->exclude('vendor');

return new PhpCsFixer\Config()
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        'concat_space' => [
            'spacing' => 'one'
        ],
        'no_unused_imports' => true,
        'declare_strict_types' => true,
        'method_argument_space' => [
            'on_multiline' => 'ensure_fully_multiline',
            'attribute_placement' => 'ignore',
        ],
        'single_line_throw' => false,
        'multiline_comment_opening_closing' => true,
        'phpdoc_align' => [
            'align' => 'left',
        ],
        'yoda_style' => true,
        'type_declaration_spaces' => [
            'elements' => ['function', 'property', 'constant'],
        ],
    ])
    ->setFinder($finder);
