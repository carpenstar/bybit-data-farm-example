<?php
require_once "./vendor/autoload.php";

use Source\Process;
use Source\Core\Settings;

Settings
    ::setSetting("BYBIT_HOST", getenv("BYBIT_HOST"))
    ::setSetting("BYBIT_APIKEY", getenv("BYBIT_APIKEY"))
    ::setSetting("BYBIT_SECRET", getenv("BYBIT_SECRET"))
    ::setSetting("CLICKHOUSE_HOST", getenv("CLICKHOUSE_HOST"))
    ::setSetting("CLICKHOUSE_USER", getenv("CLICKHOUSE_USER"))
    ::setSetting("CLICKHOUSE_PASSWORD", getenv("CLICKHOUSE_PASSWORD"))
    ::setSetting("CLICKHOUSE_PORT", getenv("CLICKHOUSE_PORT"))
    ::setSetting("MONGODB_HOST", getenv("MONGODB_HOST"))
    ::setSetting("MONGODB_PORT", getenv("MONGODB_PORT"))
    ::setSetting("MONGODB_ROOT_USERNAME", getenv("MONGODB_ROOT_USERNAME"))
    ::setSetting("MONGODB_ROOT_PASSWORD", getenv("MONGODB_ROOT_PASSWORD"))
    ::setSetting("SYMBOL", getenv("SYMBOL"))
    ::setSetting("INTERVAL", getenv("INTERVAL"))
    ::setSetting("ORDERBOOK_DEPTH", getenv("ORDERBOOK_DEPTH"));

switch ($argv[1]) {
    case "collector":
        Process::startCollector($argv[2]);
        break;
    case "dispatcher":
        Process::startDispatcher($argv[2]);
        break;
    default:
        echo "Set process that will be execute";
}