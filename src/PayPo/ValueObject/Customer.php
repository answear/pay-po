<?php

declare(strict_types=1);

namespace Answear\PayPo\ValueObject;

class Customer
{
    public function __construct(
        public string $name,
        public string $surname,
        public string $email,
        public string $phone,
    ) {
    }
}
