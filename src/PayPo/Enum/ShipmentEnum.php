<?php

declare(strict_types=1);

namespace Answear\PayPo\Enum;

use MabeEnum\Enum;

class ShipmentEnum extends Enum
{
    public const COURIER = 0;
    public const PICKUP_POINT = 1;
    public const PARCEL_LOCKER = 2;
    public const RUCH_PACK = 3;
    public const CLICK_COLLECT = 4;

    public static function clickCollect(): self
    {
        return static::get(static::CLICK_COLLECT);
    }

    public static function ruchPack(): self
    {
        return static::get(static::RUCH_PACK);
    }

    public static function parcelLocker(): self
    {
        return static::get(static::PARCEL_LOCKER);
    }

    public static function pickupPoint(): self
    {
        return static::get(static::PICKUP_POINT);
    }

    public static function courier(): self
    {
        return static::get(static::COURIER);
    }
}
