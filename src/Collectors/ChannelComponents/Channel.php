<?php
namespace Source\Collectors\ChannelComponents;

use Carpenstar\ByBitAPI\WebSockets\Channels\Spot\PublicChannels\Kline\KlineChannel;
use Carpenstar\ByBitAPI\WebSockets\Channels\Spot\PublicChannels\OrderBook\OrderBookChannel;
use Carpenstar\ByBitAPI\WebSockets\Channels\Spot\PublicChannels\Tickers\TickersChannel;
use Source\Core\Interfaces\IChannelAliasEnum;

class Channel
{
    private function __construct() {}

    public static function getClassname(string $alias): string
    {
        switch ($alias) {
            case IChannelAliasEnum::KLINE_ALIAS:
                return KlineChannel::class;
            case IChannelAliasEnum::ORDERBOOK_ALIAS:
                return OrderBookChannel::class;
            case IChannelAliasEnum::TICKERS_ALIAS:
               return TickersChannel::class;
            default:
                throw new \Exception("Wrong CollectHandler alias - " . $alias);
        }
    }
}