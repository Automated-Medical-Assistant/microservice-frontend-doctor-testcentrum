<?php

namespace App\Redis;

use Predis\Client;

class RedisBusinessFactory implements RedisBusinessFactoryInterface
{
    /**
     * @var string
     */
    private $uri;

    private $client;

    /**
     * RedisBusinessFactory constructor.
     * @param string $uri
     */
    public function __construct(string $uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        if ($this->client === null) {
            $this->createClient();
        }
        return $this->client;
    }

    /**
     * @return void
     */
    private function createClient(): void
    {
        $this->client = new Client($this->uri);
    }
}
