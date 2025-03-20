<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\AssociationFee;
use App\Model\AuctionVehicle;
use App\Model\BasicBuyerFee;
use App\Model\CalculatedFee;
use App\Model\Fee;
use App\Model\SellerSpecialFee;
use App\Model\StorageFee;

final class FeeCalculator
{
    /**
     * @var Fee[]
     */
    private array $fees;

    public function __construct()
    {
        // It could have been a dynamic list of fees
        // But for now, we'll just hardcode them
        $this->fees = [
            new BasicBuyerFee(),
            new SellerSpecialFee(),
            new AssociationFee(),
            new StorageFee()
        ];
    }

    public function calculateFees(AuctionVehicle $vehicle): void
    {
        /** @var Fee $fee */
        foreach ($this->fees as $fee) {
            $feeAmount = $fee->calculate($vehicle->basePrice, $vehicle->type);

            $vehicle->addCalculatedFee(new CalculatedFee($fee->getName(), $feeAmount));
        }
    }
}
