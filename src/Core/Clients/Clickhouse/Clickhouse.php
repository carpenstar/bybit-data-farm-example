<?php
namespace Source\Core\Clients\Clickhouse;

use ClickHouseDB\Client;
use Source\Core\Settings;

class Clickhouse
{
    private static Client $instance;

    private function __construct() {}

    public static function getInstance(): Client
    {
        if (empty(self::$instance)) {
            self::$instance = new Client([
                'username' => Settings::getSetting("CLICKHOUSE_USER"),
                'password' => Settings::getSetting("CLICKHOUSE_PASSWORD"),
                'port' => Settings::getSetting("CLICKHOUSE_PORT"),
                'host' => Settings::getSetting("CLICKHOUSE_HOST")
            ]);
        }

        return self::$instance;
    }
}