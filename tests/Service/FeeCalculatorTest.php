<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Enum\VehicleType;
use App\Model\AuctionVehicle;
use App\Service\FeeCalculator;
use PHPUnit\Framework\TestCase;

class FeeCalculatorTest extends TestCase
{
    private FeeCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new FeeCalculator();
    }

    /**
     * @dataProvider vehicleDataProvider
     */
    public function testCalculateFees(VehicleType $type, float $basePrice, array $expectedFees): void
    {
        // Arrange
        $vehicle = new AuctionVehicle(
            basePrice: $basePrice,
            type: $type
        );

        // Act
        $this->calculator->calculateFees($vehicle);

        // Assert
        $this->assertCount(4, $vehicle->calculatedFees, 'Should have exactly 4 fees calculated');
        
        foreach ($vehicle->calculatedFees as $index => $calculatedFee) {
            $this->assertEquals(
                $expectedFees[$index]['name'],
                $calculatedFee->name,
                "Fee name mismatch for {$calculatedFee->name}"
            );
            $this->assertEqualsWithDelta(
                $expectedFees[$index]['amount'],
                $calculatedFee->amount,
                0.01,
                "Fee amount mismatch for {$calculatedFee->name}"
            );
        }
    }

    public function vehicleDataProvider(): array
    {
        return [
            'common vehicle 398.00' => [
                'type' => VehicleType::COMMON,
                'basePrice' => 398.00,
                'expectedFees' => [
                    ['name' => 'Basic buyer fee', 'amount' => 39.80],  // Basic fee
                    ['name' => 'Seller special fee', 'amount' => 7.96],   // Special fee (2%)
                    ['name' => 'Association fee', 'amount' => 5.00],      // Association fee for <500
                    ['name' => 'Storage fee', 'amount' => 100.00],        // Fixed storage fee
                ]
            ],
            'common vehicle 501.00' => [
                'type' => VehicleType::COMMON,
                'basePrice' => 501.00,
                'expectedFees' => [
                    ['name' => 'Basic buyer fee', 'amount' => 50.00],  // Basic fee (max for common)
                    ['name' => 'Seller special fee', 'amount' => 10.02],  // Special fee (2%)
                    ['name' => 'Association fee', 'amount' => 10.00],     // Association fee for >500
                    ['name' => 'Storage fee', 'amount' => 100.00],        // Fixed storage fee
                ]
            ],
            'common vehicle 57.00' => [
                'type' => VehicleType::COMMON,
                'basePrice' => 57.00,
                'expectedFees' => [
                    ['name' => 'Basic buyer fee', 'amount' => 10.00],  // Basic fee (min)
                    ['name' => 'Seller special fee', 'amount' => 1.14],   // Special fee (2%)
                    ['name' => 'Association fee', 'amount' => 5.00],      // Association fee for <500
                    ['name' => 'Storage fee', 'amount' => 100.00],        // Fixed storage fee
                ]
            ],
            'luxury vehicle 1800.00' => [
                'type' => VehicleType::LUXURY,
                'basePrice' => 1800.00,
                'expectedFees' => [
                    ['name' => 'Basic buyer fee', 'amount' => 180.00], // Basic fee
                    ['name' => 'Seller special fee', 'amount' => 72.00],  // Special fee (4%)
                    ['name' => 'Association fee', 'amount' => 15.00],     // Association fee for >1000
                    ['name' => 'Storage fee', 'amount' => 100.00],        // Fixed storage fee
                ]
            ],
            'common vehicle 1100.00' => [
                'type' => VehicleType::COMMON,
                'basePrice' => 1100.00,
                'expectedFees' => [
                    ['name' => 'Basic buyer fee', 'amount' => 50.00],  // Basic fee (max for common)
                    ['name' => 'Seller special fee', 'amount' => 22.00],  // Special fee (2%)
                    ['name' => 'Association fee', 'amount' => 15.00],     // Association fee for >1000
                    ['name' => 'Storage fee', 'amount' => 100.00],        // Fixed storage fee
                ]
            ],
            'luxury vehicle 1000000.00' => [
                'type' => VehicleType::LUXURY,
                'basePrice' => 1000000.00,
                'expectedFees' => [
                    ['name' => 'Basic buyer fee', 'amount' => 200.00],    // Basic fee (max for luxury)
                    ['name' => 'Seller special fee', 'amount' => 40000.00],  // Special fee (4%)
                    ['name' => 'Association fee', 'amount' => 20.00],        // Association fee for >3000
                    ['name' => 'Storage fee', 'amount' => 100.00],           // Fixed storage fee
                ]
            ],
        ];
    }
} 