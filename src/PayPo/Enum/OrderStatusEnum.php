<?php

declare(strict_types=1);

namespace Answear\PayPo\Enum;

enum OrderStatusEnum: string
{
    case New = 'NEW';
    case Pending = 'PENDING';
    case Accepted = 'ACCEPTED';
    case Completed = 'COMPLETED';
    case Rejected = 'REJECTED';
    case Canceled = 'CANCELED';

    public function isFinal(): bool
    {
        return self::Completed === $this || self::Rejected === $this || self::Canceled === $this;
    }
}
