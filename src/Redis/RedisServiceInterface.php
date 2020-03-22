<?php declare(strict_types=1);


namespace App\Redis;


interface RedisServiceInterface
{
    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, $value): void;

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key);

    /**
     * @param string $key
     * @return mixed
     */
    public function delete(string $key);

    public function getAll();
}
