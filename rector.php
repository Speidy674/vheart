<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use RectorLaravel\Set\LaravelSetProvider;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/app',
        __DIR__.'/bootstrap/app.php',
        __DIR__.'/database',
        __DIR__.'/public',
    ])
    ->withSkip([
        Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector::class,
        Rector\Php85\Rector\Property\AddOverrideAttributeToOverriddenPropertiesRector::class,
    ])
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        typeDeclarations: true,
        privatization: true,
        earlyReturn: true,
    )
    /** @see https://github.com/driftingly/rector-laravel */
    ->withSetProviders(LaravelSetProvider::class)
    ->withComposerBased(laravel: true)
    ->withPhpSets();
