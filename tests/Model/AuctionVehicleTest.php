<?php

declare(strict_types=1);

namespace App\Tests\Model;

use App\Enum\VehicleType;
use App\Model\AuctionVehicle;
use App\Model\CalculatedFee;
use PHPUnit\Framework\TestCase;

class AuctionVehicleTest extends TestCase
{
    private AuctionVehicle $vehicle;

    protected function setUp(): void
    {
        $this->vehicle = new AuctionVehicle(
            basePrice: 1000.0,
            type: VehicleType::COMMON
        );
    }

    public function testAddCalculatedFee(): void
    {
        $fee = new CalculatedFee('Test Fee', 100.0);
        $this->vehicle->addCalculatedFee($fee);

        $this->assertCount(1, $this->vehicle->calculatedFees);
        $this->assertSame($fee, $this->vehicle->calculatedFees[0]);
    }
} 