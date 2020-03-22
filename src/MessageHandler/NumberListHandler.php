<?php declare(strict_types=1);


namespace App\MessageHandler;


use App\Redis\RedisServiceInterface;
use MessageInfo\NumberListAPIDataProvider;

class NumberListHandler
{
    private RedisServiceInterface $redisService;

    /**
     * @param \App\Redis\RedisServiceInterface $redisService
     */
    public function __construct(\App\Redis\RedisServiceInterface $redisService)
    {
        $this->redisService = $redisService;
    }

    public function __invoke(NumberListAPIDataProvider $numberListAPIDataProvider)
    {
        $numbers = $numberListAPIDataProvider->getNumbers();
        foreach ($numbers as $number) {
            $this->redisService->set( 'number:' . $number->getNumber(), json_encode($number->toArray()));
        }
    }
}
