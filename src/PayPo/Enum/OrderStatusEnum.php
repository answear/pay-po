<?php

declare(strict_types=1);

namespace Answear\PayPo\Enum;

use MabeEnum\Enum;

class OrderStatusEnum extends Enum
{
    public const NEW = 'NEW';
    public const PENDING = 'PENDING';
    public const ACCEPTED = 'ACCEPTED';
    public const COMPLETED = 'COMPLETED';
    public const REJECTED = 'REJECTED';
    public const CANCELED = 'CANCELED';

    public static function new(): self
    {
        return static::get(static::NEW);
    }

    public static function pending(): self
    {
        return static::get(static::PENDING);
    }

    public static function accepted(): self
    {
        return static::get(static::ACCEPTED);
    }

    public static function completed(): self
    {
        return static::get(static::COMPLETED);
    }

    public static function rejected(): self
    {
        return static::get(static::REJECTED);
    }

    public static function canceled(): self
    {
        return static::get(static::CANCELED);
    }
}
