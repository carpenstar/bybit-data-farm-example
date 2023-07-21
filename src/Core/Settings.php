<?php

namespace Source\Core;

class Settings
{
    private static array $settings = [];

    private function __construct() {}

    public static function setSetting(string $name, $value, $defaultValue = null): string
    {
        static::$settings[$name] = $value ?? $defaultValue;
        return static::class;
    }

    public static function getSetting(string $name)
    {
        return static::$settings[$name] ?? false;
    }
}