<?php
namespace Source\Dispatchers;

use MongoDB\Client as MongoDB;
use Source\Core\AbstractDispatcher;
use Source\Core\Helpers\MongoDataExtractor;
use Source\Core\Interfaces\IClickhouseDatabasesEnum;
use Source\Core\Interfaces\IClickhouseTablesEnum;
use Source\Core\Interfaces\IClickhouseTypesEnum;
use Source\Dispatchers\DataExtractor\TickersDataExtractor;

class TickersDispatcher extends AbstractDispatcher
{
    const CLICKHOUSE_DATABASE_NAME = IClickhouseDatabasesEnum::CLICKHOUSE_DATABASE_SPOT;
    const CLICKHOUSE_TABLE_NAME = IClickhouseTablesEnum::CLICKHOUSE_TABLE_TICKERS;
    const CLICKHOUSE_TABLE_BODY = [
        "execute_time" => IClickhouseTypesEnum::DATETIME64,
        "query_time" => IClickhouseTypesEnum::DATETIME64,
        "symbol" => IClickhouseTypesEnum::STRING,
        "open_price" => IClickhouseTypesEnum::FLOAT32,
        "high_price" => IClickhouseTypesEnum::FLOAT32,
        "low_price" => IClickhouseTypesEnum::FLOAT32,
        "close_price" => IClickhouseTypesEnum::FLOAT32,
        "volume" => IClickhouseTypesEnum::FLOAT32,
        "trading_volume_quote" => IClickhouseTypesEnum::FLOAT32,
        "change" => IClickhouseTypesEnum::FLOAT32,
        "index_price" => IClickhouseTypesEnum::FLOAT32
    ];

    protected function setDataExtractor(MongoDB $mongoDB): MongoDataExtractor
    {
        return new TickersDataExtractor($mongoDB);
    }

    protected function collect(MongoDB $mongoDB): array
    {
        $data = [];
        foreach ($this->dataExtractor->get() as $item) {

            $data[] = [
                $item['exec_time'],
                $item['timestamp'],
                $item['symbol'],
                (float)$item['open'],
                (float)$item['high'],
                (float)$item['low'],
                (float)$item['close'],
                (float)$item['volume'],
                (float)$item['trading_volume_quote'],
                (float)$item['change'],
                (float)$item['index_price']
            ];

        }

        return $data;
    }


}