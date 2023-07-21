<?php
namespace Source\Dispatchers;

use MongoDB\Client as MongoDB;
use Source\Core\AbstractDispatcher;
use Source\Core\Helpers\MongoDataExtractor;
use Source\Core\Interfaces\IClickhouseDatabasesEnum;
use Source\Core\Interfaces\IClickhouseTablesEnum;
use Source\Core\Interfaces\IClickhouseTypesEnum;
use Source\Dispatchers\DataExtractor\OrderBookDataExtractor;

class OrderBookDispatcher extends AbstractDispatcher
{
    const CLICKHOUSE_DATABASE_NAME = IClickhouseDatabasesEnum::CLICKHOUSE_DATABASE_SPOT;
    const CLICKHOUSE_TABLE_NAME = IClickhouseTablesEnum::CLICKHOUSE_TABLE_ORDERBOOK;
    const CLICKHOUSE_TABLE_BODY = [
        "execute_time" => IClickhouseTypesEnum::DATETIME64,
        "execute_time_unix" => IClickhouseTypesEnum::INT32,
        "order_type" => IClickhouseTypesEnum::BOOL,
        "symbol" => IClickhouseTypesEnum::STRING,
        "price" => IClickhouseTypesEnum::FLOAT32,
        "volume" => IClickhouseTypesEnum::FLOAT32
    ];

    protected function setDataExtractor(MongoDB $mongoDB): MongoDataExtractor
    {
        return new OrderBookDataExtractor($mongoDB);
    }

    protected function collect(MongoDB $mongoDB): array
    {
        $data = [];
        foreach ($this->dataExtractor->get() as $item) {

            $bidList = json_decode($item['bid'], true);
            foreach ($bidList as $bidItem) {
                $unix = $item['exec_time'] / 1000;
                $data[] = [
                    $item['exec_time'],
                    (int)$unix,
                    1, // bid
                    $item['symbol'],
                    $bidItem[0],
                    $bidItem[1]
                ];
            }

            $askList = json_decode($item['ask'], true);
            foreach ($askList as $askItem) {
                $unix = $item['exec_time'] / 1000;
                $data[] = [
                    $item['exec_time'],
                    (int)$unix,
                    0, // ask
                    $item['symbol'],
                    (float)$askItem[0],
                    (float)$askItem[1]
                ];
            }
        }

        return $data;
    }
}