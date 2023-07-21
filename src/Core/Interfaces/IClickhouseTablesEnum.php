<?php
namespace Source\Core\Interfaces;

interface IClickhouseTablesEnum
{
    const CLICKHOUSE_TABLE_TICKERS = "tickers";
    const CLICKHOUSE_TABLE_KLINE = "kline";
    const CLICKHOUSE_TABLE_ORDERBOOK = "orderbook";
}