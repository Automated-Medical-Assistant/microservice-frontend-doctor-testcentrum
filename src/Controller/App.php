<?php declare(strict_types=1);


namespace App\Controller;

use App\Redis\RedisServiceInterface;
use App\Service\NumberGenerator\NumberGeneratorInterface;
use MessageInfo\NumberCreationRequestAPIDataProvider;
use MessageInfo\UserAPIDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class App extends AbstractController
{

    /**
     * @var \App\Redis\RedisServiceInterface
     */
    private RedisServiceInterface $redisService;
    /**
     * @var \Symfony\Component\Messenger\MessageBusInterface
     */
    private MessageBusInterface $messageBus;
    /**
     * @var \App\Service\NumberGenerator\NumberGeneratorInterface
     */
    private NumberGeneratorInterface $numberGenerator;

    /**
     * @param \App\Redis\RedisServiceInterface $redisService
     */
    public function __construct(RedisServiceInterface $redisService, MessageBusInterface $messageBus, NumberGeneratorInterface $numberGenerator)
    {
        $this->redisService = $redisService;
        $this->messageBus = $messageBus;
        $this->numberGenerator = $numberGenerator;
    }

    /**
     * @Route("/", name="home", methods={"GET","POST"})
     */
    public function home(): Response
    {
        return $this->render('app/home.html.twig', [
        ]);
    }


    /**
     * @Route("/status/create", name="status_create", methods={"GET","POST"})
     */
    public function createResult(): Response
    {
        $user = json_decode($this->redisService->get('user:'.$this->getUser()->getUsername()), true, 512, JSON_THROW_ON_ERROR);
        $userTransfer = new UserAPIDataProvider();
        $userTransfer->fromArray($user);

        $number = $this->numberGenerator->generate($userTransfer);
        $numberCreate = new NumberCreationRequestAPIDataProvider();
        $numberCreate->setNumber($number);
        $numberCreate->setCreationDate(date_format(new \DateTime(), 'Y-m-d h:i:s'));
        $numberCreate->setDoctorId(1);

        $this->redisService->set('number:' . $number, json_encode($numberCreate->toArray(), JSON_THROW_ON_ERROR, 512));
        $this->messageBus->dispatch($numberCreate);

        return $this->render('app/create.html.twig', [
            'number' => $number
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {

    }
}
