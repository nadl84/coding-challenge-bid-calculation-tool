<?php

declare(strict_types=1);

namespace App\Model;

use App\Enum\VehicleType;

class SellerSpecialFee implements Fee
{
    public const COMMON_VEHICLE_PERCENTAGE = 0.02;
    public const LUXURY_VEHICLE_PERCENTAGE = 0.04;

    public function calculate(float $basePrice, VehicleType $vehicleType): float
    {
        $percentage = $vehicleType->isCommon() ? self::COMMON_VEHICLE_PERCENTAGE : self::LUXURY_VEHICLE_PERCENTAGE;

        return $basePrice * $percentage;
    }

    public function getName(): string
    {
        return 'Seller special fee';
    }
}
