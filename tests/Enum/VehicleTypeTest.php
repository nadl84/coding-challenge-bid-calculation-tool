<?php

declare(strict_types=1);

namespace App\Tests\Enum;

use App\Enum\VehicleType;
use PHPUnit\Framework\TestCase;

class VehicleTypeTest extends TestCase
{
    public function testEnumValues(): void
    {
        $this->assertContains('common', array_column(VehicleType::cases(), 'value'));
        $this->assertContains('luxury', array_column(VehicleType::cases(), 'value'));
    }

    public function testIsCommon(): void
    {
        $this->assertTrue(VehicleType::COMMON->isCommon());
    }

    public function testIsLuxury(): void
    {
        $this->assertTrue(VehicleType::LUXURY->isLuxury());
    }
}
