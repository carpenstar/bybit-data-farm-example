<?php
namespace Source\Dispatchers\DataExtractor;

use Source\Core\Helpers\MongoDataExtractor;
use Source\Core\Interfaces\IDataExtractorInterface;
use Source\Core\Interfaces\IMongoCollectionEnum;

class TickersDataExtractor extends MongoDataExtractor
{
    public function get(): array
    {
        $data = [];
        $database = $this->client->selectDatabase(IMongoCollectionEnum::MONGO_COLLECTION_TICKERS);

        foreach ($database->listCollectionNames() as $collectionName) {

            $oldCollectionName = $collectionName;
            $collectionName = $collectionName . IDataExtractorInterface::SUFFIX_BLOCKED_COLLECTION;
            $database->renameCollection($oldCollectionName, $collectionName, IMongoCollectionEnum::MONGO_COLLECTION_TICKERS);

            $collection = $database->selectCollection($collectionName);

            foreach ($collection->find()->toArray() as $dataItem) {
                $data[] = [
                    'exec_time' => $dataItem['exec_time'],
                    'timestamp' => $dataItem['timestamp'],
                    'symbol' => $dataItem['symbol'],
                    'open' => $dataItem['open'],
                    'high' => $dataItem['high'],
                    'low' => $dataItem['low'],
                    'close' => $dataItem['close'],
                    'volume' => $dataItem['volume'],
                    'trading_volume_quote' => $dataItem['trading_volume_quote'],
                    'change' => $dataItem['change'],
                    'index_price' => $dataItem['index_price']
                ];
            }
            $collection->drop();
        }

        return $data;
    }
}