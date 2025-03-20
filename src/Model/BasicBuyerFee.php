<?php

declare(strict_types=1);

namespace App\Model;

use App\Enum\VehicleType;

class BasicBuyerFee implements Fee
{
    public function calculate(float $basePrice, VehicleType $vehicleType): float
    {
        $baseFeeAmount = $basePrice * 0.10;
        
        if ($vehicleType->isCommon()) {
            $feeAmount = max(10, min(50, $baseFeeAmount));
        } else {
            $feeAmount = max(25, min(200, $baseFeeAmount));
        }
        
        return $feeAmount;
    }

    public function getName(): string
    {
        return 'Basic buyer fee';
    }
}
