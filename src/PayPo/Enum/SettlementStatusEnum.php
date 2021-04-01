<?php

declare(strict_types=1);

namespace Answear\PayPo\Enum;

use MabeEnum\Enum;

class SettlementStatusEnum extends Enum
{
    public const NEW = 'NEW';
    public const CONFIRMED = 'CONFIRMED';
    public const PAID = 'PAID';

    public static function new(): self
    {
        return static::get(static::NEW);
    }

    public static function confirmed(): self
    {
        return static::get(static::CONFIRMED);
    }

    public static function paid(): self
    {
        return static::get(static::PAID);
    }
}
