<?php
namespace Source\Collectors\ChannelHandlers;

use Carpenstar\ByBitAPI\WebSockets\Objects\Channels\DefaultChannelHandler;
use MongoDB\Client;
use MongoDB\Driver\ServerApi;
use MongoDB\Exception\Exception;
use Source\Core\Interfaces\ICollectorInterface;
use Source\Core\Interfaces\IMongoCollectionEnum;

class TickersCollectHandler extends DefaultChannelHandler implements ICollectorInterface
{
    private Client $client;

    public function __construct(Client $mongo)
    {
        $this->client = $mongo;
    }

    public  function handle($data): void
    {
        try {
            echo (new \DateTime())->format("Y-m-d H:i:s") . " - " . $data . PHP_EOL;

            $data = json_decode($data);

            if (isset($data->op) || empty($data->data->xp)) {
                return;
            }

            $collection = $this->client->selectCollection(IMongoCollectionEnum::MONGO_COLLECTION_TICKERS, $data->data->s);

            $data = [
                'exec_time' => $data->ts,
                'timestamp' => $data->data->t,
                'symbol' => $data->data->s,
                'open' => $data->data->o,
                'close' => $data->data->c,
                'high' => $data->data->h,
                'low' => $data->data->l,
                'volume' => $data->data->v,
                'trading_volume_quote' => $data->data->qv,
                'change' => $data->data->m,
                'index_price' => $data->data->xp
            ];

            $collection->insertOne($data);
        } catch (Exception $e) {
            printf($e->getMessage());
        }
    }
}