<?php

declare(strict_types=1);

namespace Answear\PayPo\Enum;

enum SettlementStatusEnum: string
{
    case New = 'NEW';
    case Confirmed = 'CONFIRMED';
    case Paid = 'PAID';
}
