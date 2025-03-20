<?php

declare(strict_types=1);

namespace App\Enum;

enum VehicleType: string
{
    case COMMON = 'common';
    case LUXURY = 'luxury';

    public function isCommon(): bool
    {
        return $this === self::COMMON;
    }

    public function isLuxury(): bool
    {
        return $this === self::LUXURY;
    }
}
