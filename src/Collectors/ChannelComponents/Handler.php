<?php
namespace Source\Collectors\ChannelComponents;

use Carpenstar\ByBitAPI\WebSockets\Interfaces\IChannelHandlerInterface;
use MongoDB\Client;
use Source\Collectors\ChannelHandlers\KlineCollectHandler;
use Source\Collectors\ChannelHandlers\OrderBookCollectHandler;
use Source\Collectors\ChannelHandlers\TickersCollectHandler;
use Source\Core\Interfaces\IChannelAliasEnum;

class Handler
{
    private static array $instances = [];

    private function __construct() {}

    public static function getInstance(string $alias, Client $mongo): IChannelHandlerInterface
    {
        if (empty(self::$instances[$alias])) {

            switch ($alias) {
                case IChannelAliasEnum::KLINE_ALIAS:
                    self::$instances[$alias] = new KlineCollectHandler($mongo);
                    break;
                case IChannelAliasEnum::ORDERBOOK_ALIAS:
                    self::$instances[$alias] = new OrderBookCollectHandler($mongo);
                    break;
                case IChannelAliasEnum::TICKERS_ALIAS:
                    self::$instances[$alias] = new TickersCollectHandler($mongo);
                    break;
                default:
                    throw new \Exception("Wrong CollectHandler alias - " . $alias);
            }
        }

        return self::$instances[$alias];
    }
}