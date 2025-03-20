<?php

declare(strict_types=1);

namespace App\Model;

class CalculatedFee
{
    public function __construct(public readonly string $name, public readonly float $amount)
    {
    }
}
