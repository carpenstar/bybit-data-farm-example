<?php
namespace Source\Dispatchers\DataExtractor;

use Source\Core\Helpers\MongoDataExtractor;
use Source\Core\Interfaces\IDataExtractorInterface;
use Source\Core\Interfaces\IMongoCollectionEnum;

class OrderBookDataExtractor extends MongoDataExtractor
{
    public function get(): array
    {
        $data = [];
        $database = $this->client->selectDatabase(IMongoCollectionEnum::MONGO_COLLECTION_ORDERBOOK);

        foreach ($database->listCollectionNames() as $collectionName) {

            $oldCollectionName = $collectionName;
            $collectionName = $collectionName . IDataExtractorInterface::SUFFIX_BLOCKED_COLLECTION;
            $database->renameCollection($oldCollectionName, $collectionName, IMongoCollectionEnum::MONGO_COLLECTION_ORDERBOOK);

            $collection = $database->selectCollection($collectionName);

            foreach ($collection->find()->toArray() as $dataItem) {
                $data[] = [
                    'exec_time' => $dataItem['exec_time'],
                    'symbol' => $dataItem['symbol'],
                    'bid' => $dataItem['bid'],
                    'ask' => $dataItem['ask'],
                ];
            }

            $collection->drop();
        }

        return $data;
    }
}