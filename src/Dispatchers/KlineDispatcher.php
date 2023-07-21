<?php
namespace Source\Dispatchers;

use MongoDB\Client as MongoDB;
use Source\Core\AbstractDispatcher;
use Source\Core\Helpers\MongoDataExtractor;
use Source\Core\Interfaces\IClickhouseDatabasesEnum;
use Source\Core\Interfaces\IClickhouseTablesEnum;
use Source\Core\Interfaces\IClickhouseTypesEnum;
use Source\Dispatchers\DataExtractor\KlineDataExtractor;

class KlineDispatcher extends AbstractDispatcher
{
    const CLICKHOUSE_DATABASE_NAME = IClickhouseDatabasesEnum::CLICKHOUSE_DATABASE_SPOT;
    const CLICKHOUSE_TABLE_NAME = IClickhouseTablesEnum::CLICKHOUSE_TABLE_KLINE;
    const CLICKHOUSE_TABLE_BODY = [
        "execute_time" => IClickhouseTypesEnum::DATETIME64,
        "query_time" => IClickhouseTypesEnum::DATETIME64,
        "symbol" => IClickhouseTypesEnum::STRING,
        "open_value" => IClickhouseTypesEnum::FLOAT32,
        "close_value" => IClickhouseTypesEnum::FLOAT32,
        "high_value" => IClickhouseTypesEnum::FLOAT32,
        "low_value" => IClickhouseTypesEnum::FLOAT32,
        "volume" => IClickhouseTypesEnum::FLOAT32
    ];

    protected function setDataExtractor(MongoDB $mongoDB): MongoDataExtractor
    {
        return new KlineDataExtractor($mongoDB);
    }

    protected function collect(MongoDB $mongoDB): array
    {
        $data = [];
        foreach ($this->dataExtractor->get() as $item) {
            $data[] = [
                $item['execute_time'],
                $item['timestamp'],
                $item['symbol'],
                $item['open'],
                $item['close'],
                $item['high'],
                $item['low'],
                $item['volume']
            ];
        }

        return $data;
    }
}