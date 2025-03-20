<?php

declare(strict_types=1);

namespace App\Tests\Model;

use App\Enum\VehicleType;
use App\Model\StorageFee;
use PHPUnit\Framework\TestCase;

class StorageFeeTest extends TestCase
{
    private StorageFee $fee;

    protected function setUp(): void
    {
        $this->fee = new StorageFee();
    }

    public function testCalculate(): void
    {
        $this->assertEquals(
            100.0,
            $this->fee->calculate(1000.0, VehicleType::COMMON)
        );
    }

    public function testGetName(): void
    {
        $this->assertEquals('Storage fee', $this->fee->getName());
    }
} 