<?php

/*
 * This file is part of the ekino/tiny-png-sonata-media-bundle project.
 *
 * (c) Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$header = <<<EOF
This file is part of the ekino/tiny-png-sonata-media-bundle project.

(c) Ekino

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF;

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->in(['src','tests'])
;

$config = new PhpCsFixer\Config();

$config->setUsingCache(true)
    ->setRiskyAllowed(true)
    ->setFinder($finder);

return $config->setRules([
    'array_indentation' => true,
    'array_syntax' => ['syntax' => 'short'],
    'binary_operator_spaces' => ['default' => 'align', 'operators' => ['=>' => 'align', '=' => 'align_single_space']],
    'blank_line_after_namespace' => true,
    'blank_line_after_opening_tag' => true,
    'declare_strict_types' => true,
    'header_comment' => ['header' => $header],
    'indentation_type' => true,
    'linebreak_after_opening_tag' => true,
    'constant_case' => true,
    'lowercase_keywords' => true,
    'native_function_invocation' => ['include' => ['@compiler_optimized']],
    'no_alias_functions' => true,
    'no_closing_tag' => true,
    'no_extra_blank_lines' => [
        'tokens' => [
            'break',
            'continue',
            'curly_brace_block',
            'extra',
            'parenthesis_brace_block',
            'return',
            'square_brace_block',
            'throw',
            'use',
        ],
    ],
    'echo_tag_syntax' => ['format' => 'long'],
    'no_useless_else' => true,
    'no_unused_imports' => true,
    'no_useless_return' => true,
    'ordered_imports' => false,
    'phpdoc_add_missing_param_annotation' => true,
    'phpdoc_order' => true,
    'semicolon_after_instruction' => true,
    'visibility_required' => [
        'elements' => ['method', 'property', 'const']
    ],
])
;
