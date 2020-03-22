<?php declare(strict_types=1);


namespace App\Controller;


use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class Test extends AbstractController
{
    public function test(): JsonResponse
    {
        return $this->json(['say' => 'HI!'])->setEncodingOptions(JSON_UNESCAPED_SLASHES);
    }
}
