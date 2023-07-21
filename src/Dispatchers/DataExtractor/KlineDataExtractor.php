<?php
namespace Source\Dispatchers\DataExtractor;

use Source\Core\Helpers\MongoDataExtractor;
use Source\Core\Interfaces\IDataExtractorInterface;
use Source\Core\Interfaces\IMongoCollectionEnum;

class KlineDataExtractor extends MongoDataExtractor
{
    public function get(): array
    {
        $data = [];
        $database = $this->client->selectDatabase(IMongoCollectionEnum::MONGO_COLLECTION_KLINE);

        foreach ($database->listCollectionNames() as $collectionName) {

            $oldCollectionName = $collectionName;
            $collectionName = $collectionName . IDataExtractorInterface::SUFFIX_BLOCKED_COLLECTION;
            $database->renameCollection($oldCollectionName, $collectionName, IMongoCollectionEnum::MONGO_COLLECTION_KLINE);

            $collection = $database->selectCollection($collectionName);

            foreach ($collection->find()->toArray() as $dataItem) {
                $data[] = [
                    'execute_time' => $dataItem['exec_time'],
                    'timestamp' => $dataItem['timestamp'],
                    'symbol' => $dataItem['symbol'],
                    'open' => $dataItem['open'],
                    'close' => $dataItem['close'],
                    'high' => $dataItem['high'],
                    'low' => $dataItem['low'],
                    'volume' => $dataItem['volume']
                ];
            }

            $collection->drop();
        }

        return $data;
    }
}