<?php
namespace Source\Collectors\ChannelHandlers;

use Carpenstar\ByBitAPI\WebSockets\Objects\Channels\DefaultChannelHandler;
use MongoDB\Client;
use MongoDB\Exception\Exception;
use Source\Core\Interfaces\ICollectorInterface;
use Source\Core\Interfaces\IMongoCollectionEnum;

class OrderBookCollectHandler extends DefaultChannelHandler implements ICollectorInterface
{
    private Client $client;

    public function __construct(Client $mongo)
    {
        $this->client = $mongo;
    }

    public  function handle($data): void
    {
        try {
            echo (new \DateTime())->format("Y-m-d H:i:s") . PHP_EOL;

            $data = json_decode($data);

            if (isset($data->op)) {
                return;
            }

            $collection = $this->client->selectCollection(IMongoCollectionEnum::MONGO_COLLECTION_ORDERBOOK, $data->data->s);

            $data = [
                'exec_time' => $data->ts,
                'timestamp' => $data->data->t,
                'symbol' => $data->data->s,
                'bid' => json_encode($data->data->b),
                'ask' => json_encode($data->data->a)
            ];

            $collection->insertOne($data);
        } catch (Exception $e) {
            printf($e->getMessage());
        }
    }
}