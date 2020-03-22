<?php

declare(strict_types=1);

namespace App\Service\NumberGenerator;

use MessageInfo\UserAPIDataProvider;

class NumberGenerator implements NumberGeneratorInterface
{
    public function generate(UserAPIDataProvider $dataProvider): string
    {
        return
            $this->getStateIso($dataProvider).
            $this->getDatTimeString().
            $this->getRandomCode();
    }

    private function getDatTimeString(): string
    {
        $date = new \DateTime();
        return (string)date_format($date, 'dmYHis');
    }

    private function getStateIso(UserAPIDataProvider $dataProvider): string
    {
        return $dataProvider->getStateIso();
    }

    private function getRandomCode(): string
    {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i <= 5; $i++) {
            $randomString .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $randomString;
    }
}
