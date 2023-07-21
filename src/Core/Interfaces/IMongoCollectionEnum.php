<?php
namespace Source\Core\Interfaces;


interface IMongoCollectionEnum
{
    const MONGO_COLLECTION_KLINE = 'spot-kline';
    const MONGO_COLLECTION_ORDERBOOK = 'spot-orderbook';
    const MONGO_COLLECTION_TICKERS = 'spot-tickers';
}
