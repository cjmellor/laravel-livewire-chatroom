<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use RectorLaravel\Set\LaravelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__.'/app',
//        __DIR__ . '/bootstrap',
        __DIR__.'/config',
        __DIR__.'/database',
        __DIR__.'/lang',
//        __DIR__ . '/public',
        __DIR__.'/resources',
        __DIR__.'/routes',
//        __DIR__ . '/storage',
        __DIR__.'/tests',
    ]);

    // register a single rule
    $rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);
    $rectorConfig->importNames();

    // define sets of rules
    $rectorConfig->sets([
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
        SetList::EARLY_RETURN,
        SetList::PRIVATIZATION,
        SetList::TYPE_DECLARATION,
        LevelSetList::UP_TO_PHP_82,
        LaravelSetList::LARAVEL_90,
        LaravelSetList::LARAVEL_CODE_QUALITY,
    ]);
};
