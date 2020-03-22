<?php

declare(strict_types=1);

namespace App\Service\NumberGenerator;

class NumberGenerator implements NumberGeneratorInterface
{

    public function generate(): string
    {
        return
            $this->getStateIso().
            $this->getDatTimeString().
            $this->getRandomCode();
    }

    private function getDatTimeString(): string
    {
        $date = new \DateTime();
        return (string)date_format($date, 'dmYHis');
    }

    private function getStateIso(): string
    {
        return 'NW';
    }

    private function getRandomCode(): string
    {
        return 'ABCDE';
    }
}
