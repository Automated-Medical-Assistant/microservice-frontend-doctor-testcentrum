<?php

declare(strict_types=1);

namespace App\Service\NumberGenerator;


interface NumberGeneratorInterface
{
    public function generate(): string;
}
