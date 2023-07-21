<?php
namespace Source\Core\Helpers;

use MongoDB\Client;
use Source\Core\Interfaces\IDataExtractorInterface;

abstract class MongoDataExtractor implements IDataExtractorInterface
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    abstract public function get(): array;
}