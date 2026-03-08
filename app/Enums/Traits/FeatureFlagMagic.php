<?php

declare(strict_types=1);

namespace App\Enums\Traits;

use App\Support\FeatureFlag\Attributes\DefaultFeatureFlagState;
use App\Support\FeatureFlag\Attributes\Description;
use App\Support\FeatureFlag\Attributes\Environment;
use App\Support\FeatureFlag\Attributes\Issue;
use ReflectionClassConstant;

trait FeatureFlagMagic
{
    public static function makeConfigIdentifier(string $name): string
    {
        $lowercaseName = mb_strtolower($name);

        return "features.{$lowercaseName}";
    }

    public static function makeCacheIdentifier(string $name): string
    {
        $lowercaseName = mb_strtolower($name);

        return "feature_flag_{$lowercaseName}";
    }

    public function cacheIdentifier(): string
    {
        return self::makeCacheIdentifier($this->name);
    }

    public function configIdentifier(): string
    {
        return self::makeConfigIdentifier($this->name);
    }

    /**
     * Get the disabled state of this flag (based on environment)
     */
    public function getDisabledOnEnvironment(): bool
    {
        static $cache = [];

        if (array_key_exists($this->name, $cache)) {
            return $cache[$this->name];
        }

        $reflection = new ReflectionClassConstant(self::class, $this->name);
        $attributes = $reflection->getAttributes(Environment::class);

        if ($attributes === []) {
            return $cache[$this->name] = false;
        }

        return $cache[$this->name] = ! app()->environment($attributes[0]->newInstance()->state);
    }

    /**
     * Get the default state of this flag
     */
    public function getDefaultState(): bool
    {
        static $cache = [];

        if (array_key_exists($this->name, $cache)) {
            return $cache[$this->name];
        }

        $reflection = new ReflectionClassConstant(self::class, $this->name);
        $attributes = $reflection->getAttributes(DefaultFeatureFlagState::class);

        if ($attributes === []) {
            return $cache[$this->name] = false;
        }

        return $cache[$this->name] = $attributes[0]->newInstance()->state;
    }

    /**
     * Get the flag description
     * use the Description attribute to set it
     */
    public function getDescription(): ?string
    {
        static $cache = [];

        if (array_key_exists($this->name, $cache)) {
            return $cache[$this->name];
        }

        $reflection = new ReflectionClassConstant(self::class, $this->name);
        $attributes = $reflection->getAttributes(Description::class);

        if ($attributes === []) {
            return $cache[$this->name] = null;
        }

        return $cache[$this->name] = $attributes[0]->newInstance()->description;
    }

    /**
     * Get the Issue url for this flag
     * use the Issue attribute to set it
     */
    public function getIssue(): ?string
    {
        static $cache = [];

        if (array_key_exists($this->name, $cache)) {
            return $cache[$this->name];
        }

        $reflection = new ReflectionClassConstant(self::class, $this->name);
        $attributes = $reflection->getAttributes(Issue::class);

        if ($attributes === []) {
            return $cache[$this->name] = null;
        }

        return $cache[$this->name] = 'https://github.com/VHeart-Clips/website/issues/'.$attributes[0]->newInstance()->issue;
    }
}
