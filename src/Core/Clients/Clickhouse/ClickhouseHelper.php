<?php
namespace Source\Core\Clients\Clickhouse;

use ClickHouseDB\Client;

class ClickhouseHelper
{
    private static string $dbName;

    private static string $tblName;

    public static function createDatabase(Client $clickhouse, $name): void
    {
        self::$dbName = $name;
        $clickhouse->write("CREATE DATABASE IF NOT EXISTS {$name}");
    }

    public static function createTable(Client $clickhouse, string $dbName, string $tblName, array $fields): void
    {
        if (empty($fields['execute_time'])) {
            throw new \Exception("Field execute_time in Clickhouse table must be set");
        }

        self::$tblName = $tblName;

        $tblBody = "";
        foreach ($fields as $fieldName => $fieldType) {
            $tblBody .= "{$fieldName} {$fieldType},";
        }

        $clickhouse->database($dbName)->write(
            "CREATE TABLE IF NOT EXISTS {$tblName} ({$tblBody})
                ENGINE MergeTree() PARTITION BY toYYYYMM(execute_time) ORDER BY (execute_time) SETTINGS index_granularity=8192
        ");
    }

    public static function save(Client $clickhouse, array $fieldList, array $data, $dbName = null, $tblName = null): void
    {
        if (empty($dbName) && empty(self::$dbName)) {
            throw new \Exception("Set Clickhouse`s database name");
        }

        if (empty($tblName) && empty(self::$tblName)) {
            throw new \Exception("Set Clickhouse`s table name");
        }

        if (empty($data)) {
            echo "Nothing to save. Clickhouse.";
            exit;
        }

        $clickhouse->database($dbName ?? self::$dbName)->insert($tblName ?? self::$tblName, $data, $fieldList);
    }
}