<?php

declare(strict_types=1);

namespace App\Tests\Model;

use App\Enum\VehicleType;
use App\Model\AssociationFee;
use PHPUnit\Framework\TestCase;

class AssociationFeeTest extends TestCase
{
    private AssociationFee $fee;

    protected function setUp(): void
    {
        $this->fee = new AssociationFee();
    }

    /**
     * @dataProvider feeCalculationProvider
     */
    public function testCalculate(float $basePrice, float $expectedFee): void
    {
        $this->assertEquals(
            $expectedFee,
            $this->fee->calculate($basePrice, VehicleType::COMMON)
        );
    }

    public function feeCalculationProvider(): array
    {
        return [
            'price below 500' => [400.0, 5.0],
            'price between 500 and 1000' => [750.0, 10.0],
            'price between 1000 and 3000' => [2000.0, 15.0],
            'price above 3000' => [4000.0, 20.0],
        ];
    }

    public function testGetName(): void
    {
        $this->assertEquals('Association fee', $this->fee->getName());
    }
} 