<?php

declare(strict_types=1);

namespace Answear\PayPo\Response\Order;

readonly class Response
{
    public function __construct(
        public string $code,
        public string $message,
    ) {
    }
}
