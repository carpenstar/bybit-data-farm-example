<?php
namespace Source\Core;

use ClickHouseDB\Client as ClickhouseDB;
use MongoDB\Client as MongoDB;
use Source\Core\Helpers\MongoDataExtractor;
use Source\Core\Clients\Clickhouse\ClickhouseHelper;

abstract class AbstractDispatcher
{
    protected MongoDataExtractor $dataExtractor;

    public function __construct(MongoDB $mongoDB, ClickhouseDB $clickhouse)
    {
        $this->dataExtractor = $this->setDataExtractor($mongoDB);

        ClickhouseHelper::createDatabase($clickhouse,static::CLICKHOUSE_DATABASE_NAME);

        ClickhouseHelper::createTable($clickhouse,
            static::CLICKHOUSE_DATABASE_NAME,
            static::CLICKHOUSE_TABLE_NAME,
            static::CLICKHOUSE_TABLE_BODY
        );

        ClickhouseHelper::save($clickhouse,
            array_keys(static::CLICKHOUSE_TABLE_BODY),
            $this->collect($mongoDB)
        );
    }

    abstract protected function collect(MongoDB $mongoDB): array;
    abstract protected function setDataExtractor(MongoDB $mongoDB): MongoDataExtractor;
}