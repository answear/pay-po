<?php

declare(strict_types=1);

namespace Answear\PayPo\ValueObject;

class Address
{
    public function __construct(
        public string $street,
        public ?string $building,
        public ?string $flat,
        public string $zip,
        public string $city,
        public ?string $country = null,
    ) {
    }
}
