<?php

namespace App\Redis;

use Predis\Client;

interface RedisBusinessFactoryInterface
{
    /**
     * @return Client
     */
    public function getClient(): Client;
}
