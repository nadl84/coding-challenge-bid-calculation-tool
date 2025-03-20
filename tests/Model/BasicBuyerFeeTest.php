<?php

declare(strict_types=1);

namespace App\Tests\Model;

use App\Enum\VehicleType;
use App\Model\BasicBuyerFee;
use PHPUnit\Framework\TestCase;

class BasicBuyerFeeTest extends TestCase
{
    private BasicBuyerFee $fee;

    protected function setUp(): void
    {
        $this->fee = new BasicBuyerFee();
    }

    /**
     * @dataProvider commonVehicleFeeProvider
     */
    public function testCalculateForCommonVehicle(float $basePrice, float $expectedFee): void
    {
        $this->assertEquals(
            $expectedFee,
            $this->fee->calculate($basePrice, VehicleType::COMMON)
        );
    }

    /**
     * @dataProvider luxuryVehicleFeeProvider
     */
    public function testCalculateForLuxuryVehicle(float $basePrice, float $expectedFee): void
    {
        $this->assertEquals(
            $expectedFee,
            $this->fee->calculate($basePrice, VehicleType::LUXURY)
        );
    }

    public function commonVehicleFeeProvider(): array
    {
        return [
            'minimum fee' => [50.0, 10.0],
            'maximum fee' => [1000.0, 50.0],
            'middle range' => [300.0, 30.0],
        ];
    }

    public function luxuryVehicleFeeProvider(): array
    {
        return [
            'minimum fee' => [200.0, 25.0],
            'maximum fee' => [3000.0, 200.0],
            'middle range' => [1000.0, 100.0],
        ];
    }

    public function testGetName(): void
    {
        $this->assertEquals('Basic buyer fee', $this->fee->getName());
    }
} 