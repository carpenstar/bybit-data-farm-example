<?php
namespace Source\Core\Clients\MongoDB;

use MongoDB\Client;
use MongoDB\Driver\ServerApi;
use Source\Core\Settings;

class MongoDB
{
    private static Client $instance;

    private function __construct() {}

    public static function getInstance(): Client
    {
        if (empty(self::$instance)) {
            $mongoHost = Settings::getSetting("MONGODB_HOST");
            $mongoUser = Settings::getSetting("MONGODB_ROOT_USERNAME");
            $mongoPassword = Settings::getSetting("MONGODB_ROOT_PASSWORD");
            $mongoPort = Settings::getSetting("MONGODB_PORT");

            self::$instance = new Client("mongodb://{$mongoUser}:{$mongoPassword}@{$mongoHost}:{$mongoPort}", [], [
                'serverApi' => new ServerApi(ServerApi::V1)
            ]);
        }

        return self::$instance;
    }

}