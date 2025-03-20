<?php

declare(strict_types=1);

namespace App\Model;

use App\Enum\VehicleType;

class AssociationFee implements Fee
{
    public function calculate(float $basePrice, VehicleType $vehicleType): float
    {
        return match (true) {
            $basePrice > 3000 => 20,
            $basePrice > 1000 => 15,
            $basePrice > 500 => 10,
            default => 5,
        };
    }

    public function getName(): string
    {
        return 'Association fee';
    }
}
