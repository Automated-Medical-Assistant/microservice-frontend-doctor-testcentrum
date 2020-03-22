<?php declare(strict_types=1);


namespace App\MessageHandler;


use App\Redis\RedisServiceInterface;
use MessageInfo\RoleDataProvider;
use MessageInfo\UserListAPIDataProvider;

class UserListHandler
{
    private RedisServiceInterface $redisService;

    /**
     * @param \App\Redis\RedisServiceInterface $redisService
     */
    public function __construct(\App\Redis\RedisServiceInterface $redisService)
    {
        $this->redisService = $redisService;
    }

    public function __invoke(UserListAPIDataProvider $userListAPIDataProvider)
    {
        $users = $userListAPIDataProvider->getUsers();
        $doctorRole = (new RoleDataProvider())->getDoctor();
        $centerRole = (new RoleDataProvider())->getTestCenter();
        foreach ($users as $user) {
            if(in_array($user->getRole(), [$doctorRole, $centerRole], true)){
                $this->redisService->set( 'user:' . $user->getEmail(), json_encode($user->toArray(), JSON_THROW_ON_ERROR, 512));
            }
        }
    }
}
