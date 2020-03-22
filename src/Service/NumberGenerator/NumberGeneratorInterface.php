<?php

declare(strict_types=1);

namespace App\Service\NumberGenerator;


use MessageInfo\UserAPIDataProvider;

interface NumberGeneratorInterface
{
    public function generate(UserAPIDataProvider $dataProvider): string;
}
