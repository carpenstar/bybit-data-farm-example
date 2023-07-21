<?php
namespace Source;

use Carpenstar\ByBitAPI\BybitAPI;
use Source\Collectors\ChannelComponents\Argument;
use Source\Collectors\ChannelComponents\Channel;
use Source\Collectors\ChannelComponents\Handler;
use Source\Core\Clients\MongoDB\MongoDB;
use Source\Core\Interfaces\IChannelAliasEnum;
use Source\Core\Settings;
use Source\Dispatchers\KlineDispatcher;
use Source\Core\Clients\Clickhouse\Clickhouse;
use Source\Dispatchers\OrderBookDispatcher;
use Source\Dispatchers\TickersDispatcher;

class Process
{
    public static function startCollector(string $alias): void
    {
        (new BybitAPI(
            Settings::getSetting("BYBIT_HOST"),
            Settings::getSetting("BYBIT_APIKEY"),
            Settings::getSetting("BYBIT_SECRET"))
        )->websocket(
                Channel::getClassname($alias),
                Argument::getInstance($alias),
                Handler::getInstance($alias, MongoDB::getInstance())
        );
    }

    public static function startDispatcher(string $alias): void
    {
        switch ($alias) {
            case IChannelAliasEnum::KLINE_ALIAS:
                new KlineDispatcher(MongoDB::getInstance(), Clickhouse::getInstance());
                break;
            case IChannelAliasEnum::ORDERBOOK_ALIAS:
                new OrderBookDispatcher(MongoDB::getInstance(), Clickhouse::getInstance());
                break;
            case IChannelAliasEnum::TICKERS_ALIAS:
                new TickersDispatcher(MongoDB::getInstance(), Clickhouse::getInstance());
                break;
        }

    }
}