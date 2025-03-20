<?php

declare(strict_types=1);

namespace App\Tests\Model;

use App\Model\CalculatedFee;
use PHPUnit\Framework\TestCase;

class CalculatedFeeTest extends TestCase
{
    public function testConstructor(): void
    {
        $fee = new CalculatedFee('Test Fee', 100.0);
        
        $this->assertEquals('Test Fee', $fee->name);
        $this->assertEquals(100.0, $fee->amount);
    }
} 