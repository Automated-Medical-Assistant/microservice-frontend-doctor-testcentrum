<?php declare(strict_types=1);

namespace App\Console;

use App\DataTransferObject\TestDataProvider;
use App\MessageHandler\NumberListHandler;
use App\MessageHandler\UserListHandler;
use App\Redis\RedisServiceInterface;
use MessageInfo\NumberAPIDataProvider;
use MessageInfo\NumberListAPIDataProvider;
use MessageInfo\UserListAPIDataProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class DummyData extends Command
{
    protected static $defaultName = 'dummy:data';

    /**
     * @var \App\MessageHandler\NumberListHandler
     */
    private NumberListHandler $numberListHandler;

    /**
     * @var \App\MessageHandler\UserListHandler
     */
    private UserListHandler $userListHandler;

    /**
     * @param \App\MessageHandler\NumberListHandler $numberListHandler
     * @param \App\MessageHandler\UserListHandler $userListHandler
     */
    public function __construct(\App\MessageHandler\NumberListHandler $numberListHandler, \App\MessageHandler\UserListHandler $userListHandler)
    {
        $this->numberListHandler = $numberListHandler;
        $this->userListHandler = $userListHandler;

        parent::__construct();
    }


    protected function configure()
    {
        $this->setDescription('Test message');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->userListHandler->__invoke($this->getUsers());
        $this->numberListHandler->__invoke($this->getNumbers());

        return 0;
    }

    private function getUsers(): UserListAPIDataProvider
    {
        $users = json_decode(
            '{"Users":[{"userId":1,"email":"test1@doctor.com","role":"doctor","stateIso":"NW"},{"userId":2,"email":"test2@doctor.com","role":"doctor","stateIso":"BY"},{"userId":3,"email":"test1@testcenter.com","role":"testCenter","stateIso":"NW"},{"userId":4,"role":"testCenter","stateIso":"BY"},{"userId":5,"email":"test1@labor.com","role":"labor","stateIso":"NW"},{"userId":6,"email":"test2@labor.com","role":"labor","stateIso":"BY"}]}',
            true
        );
        $userListAPIDataProvider = new UserListAPIDataProvider();
        $userListAPIDataProvider->fromArray($users);

        return $userListAPIDataProvider;
    }

    private function getNumbers(): NumberListAPIDataProvider
    {
        $numberList = [
            [
                'doctorId' => 1,
                'number' => 'NW202003202237371DTAHDT',
                'creationDate' => '2020-03-20 20:21:22',
                'modifiedStateDate' => null,
                'status' => null,
            ],
            [
                'doctorId' => 2,
                'number' => 'BY201902202237371ZUCYTD',
                'creationDate' => '2020-03-20 20:21:22',
                'modifiedStateDate' => '2020-03-20 20:21:22',
                'status' => true,
            ],
            [
                'doctorId' => 3,
                'number' => 'BY202001202237371HDSAZ',
                'creationDate' => '2020-02-18 17:11:41',
                'modifiedStateDate' => '2020-02-18 17:11:41',
                'status' => false,
            ],
        ];
        $numberListAPIDataProvider = new NumberListAPIDataProvider();

        foreach ( $numberList as $number) {
            $numberDataProvider = new NumberAPIDataProvider();
            $numberDataProvider->fromArray($number);
            $numberListAPIDataProvider->addNumber($numberDataProvider);
        }

        return $numberListAPIDataProvider;
    }
}
