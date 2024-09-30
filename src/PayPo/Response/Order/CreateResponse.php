<?php

declare(strict_types=1);

namespace Answear\PayPo\Response\Order;

readonly class CreateResponse
{
    public function __construct(
        public string $transactionId,
        public string $redirectUrl,
    ) {
    }
}
