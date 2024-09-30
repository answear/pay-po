<?php

declare(strict_types=1);

namespace Answear\PayPo\Response\Order;

readonly class ConfirmResponse
{
    public function __construct(
        public string $code,
        public string $statusDescription,
    ) {
    }
}
