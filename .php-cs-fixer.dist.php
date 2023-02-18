<?php

// Run with: ./vendor/bin/php-cs-fixer fix --allow-risky=yes

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'declare_strict_types' => true,
        'header_comment' => [
            'header' => 'Made for YouTube channel https://www.youtube.com/@eazy-dev'
        ]
    ])
    ->setFinder($finder)
;
