<?php

declare(strict_types=1);

namespace App\Tests\Model;

use App\Enum\VehicleType;
use App\Model\SellerSpecialFee;
use PHPUnit\Framework\TestCase;

class SellerSpecialFeeTest extends TestCase
{
    private SellerSpecialFee $fee;

    protected function setUp(): void
    {
        $this->fee = new SellerSpecialFee();
    }

    public function testCalculateForCommonVehicle(): void
    {
        $basePrice = 1000.0;
        $expectedFee = $basePrice * SellerSpecialFee::COMMON_VEHICLE_PERCENTAGE;

        $this->assertEquals(
            $expectedFee,
            $this->fee->calculate($basePrice, VehicleType::COMMON)
        );
    }

    public function testCalculateForLuxuryVehicle(): void
    {
        $basePrice = 1000.0;
        $expectedFee = $basePrice * SellerSpecialFee::LUXURY_VEHICLE_PERCENTAGE;

        $this->assertEquals(
            $expectedFee,
            $this->fee->calculate($basePrice, VehicleType::LUXURY)
        );
    }

    public function testGetName(): void
    {
        $this->assertEquals('Seller special fee', $this->fee->getName());
    }
} 