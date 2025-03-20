<?php

declare(strict_types=1);

namespace App\Model;

use App\Enum\VehicleType;

class StorageFee implements Fee
{
    public function calculate(float $basePrice, VehicleType $vehicleType): float
    {
        return 100;
    }

    public function getName(): string
    {
        return 'Storage fee';
    }
}
