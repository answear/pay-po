<?php

declare(strict_types=1);

namespace Answear\PayPo\ValueObject;

class TransactionsInfo
{
    public function __construct(
        public ?string $numberOfTransactions = null,
        public ?int $sumOfTransactions = null,
    ) {
    }
}
