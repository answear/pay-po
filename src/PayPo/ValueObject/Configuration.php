<?php

declare(strict_types=1);

namespace Answear\PayPo\ValueObject;

class Configuration
{
    public function __construct(
        public string $returnUrl,
        public string $notifyUrl,
        public ?string $cancelUrl,
    ) {
    }
}
