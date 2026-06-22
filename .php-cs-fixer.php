<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude(
        [
            'vendor',
        ]
    );

return (new PhpCsFixer\Config())
    ->setFinder($finder)
    ->registerCustomFixers([])
    ->setRules(
        [
            '@Symfony' => true,
            'strict_param' => false,
            'array_syntax' => ['syntax' => 'short'],
            'concat_space' => ['spacing' => 'one'],
            'phpdoc_align' => ['align' => 'left'],
            'phpdoc_summary' => false,
            'void_return' => false,
            'phpdoc_var_without_name' => false,
            'phpdoc_to_comment' => false,
            'single_line_throw' => false,
            'modernize_types_casting' => true,
            'function_declaration' => false,
            'ordered_imports' => [
                'imports_order' => [
                    'class',
                    'function',
                    'const',
                ],
                'sort_algorithm' => 'alpha',
            ],
            'phpdoc_separation' => ['skip_unlisted_annotations' => true],
            'nullable_type_declaration' => true,
            'nullable_type_declaration_for_default_null_value' => true,
            'trailing_comma_in_multiline' => ['elements' => ['arrays', 'parameters']],
        ]
    )
    ->setRiskyAllowed(true);
