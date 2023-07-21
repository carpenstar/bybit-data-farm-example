<?php
namespace Source\Collectors\ChannelComponents;

use Carpenstar\ByBitAPI\WebSockets\Channels\Spot\PublicChannels\Kline\Argument\KlineArgument;
use Carpenstar\ByBitAPI\WebSockets\Channels\Spot\PublicChannels\OrderBook\Argument\OrderBookArgument;
use Carpenstar\ByBitAPI\WebSockets\Channels\Spot\PublicChannels\Tickers\Argument\TickersArgument;
use Carpenstar\ByBitAPI\WebSockets\Interfaces\IWebSocketArgumentInterface;
use Source\Core\Interfaces\IChannelAliasEnum;
use Source\Core\Settings;

class Argument
{
    private static array $instances = [];

    private function __construct() {}

    public static function getInstance(string $alias): IWebSocketArgumentInterface
    {
        if (empty(self::$instances[$alias])) {
            switch ($alias) {
                case IChannelAliasEnum::KLINE_ALIAS:
                    self::$instances[$alias] = new KlineArgument(Settings::getSetting("SYMBOL"), Settings::getSetting("INTERVAL"));
                    break;
                case IChannelAliasEnum::ORDERBOOK_ALIAS:
                    self::$instances[$alias] = (new OrderBookArgument(Settings::getSetting("SYMBOL")))
                        ->setDepth(Settings::getSetting("ORDERBOOK_DEPTH"));
                    break;
                case IChannelAliasEnum::TICKERS_ALIAS:
                    self::$instances[$alias] = new TickersArgument(Settings::getSetting("SYMBOL"));
                    break;
                default:
                    throw new \Exception("Wrong CollectHandler alias - " . $alias);
            }
        }

        return self::$instances[$alias];
    }
}