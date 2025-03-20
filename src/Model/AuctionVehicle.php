<?php

declare(strict_types=1);

namespace App\Model;

use App\Enum\VehicleType;

class AuctionVehicle
{
    public function __construct(
        public float $basePrice,
        public VehicleType $type,
        public array $calculatedFees = [],
    ) {
    }

    public function addCalculatedFee(CalculatedFee $fee): void
    {
        // We could have added a defensive programming here 
        // to check if the fee is already in the list
        // but we'll trust the caller to do it
        $this->calculatedFees[] = $fee;
    }

    public function getTotalPrice(): float
    {
        return $this->basePrice + $this->getTotalFees();
    }

    public function getTotalFees(): float
    {
        return array_sum(array_map(fn (CalculatedFee $fee) => $fee->amount, $this->calculatedFees));
    }
}
