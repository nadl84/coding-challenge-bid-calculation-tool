<?php

declare(strict_types=1);

namespace App\Model;

use App\Enum\VehicleType;

interface Fee
{
    public function calculate(float $basePrice, VehicleType $vehicleType): float;

    public function getName(): string;
}
