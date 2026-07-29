<?php

declare(strict_types=1);

namespace Answear\PayPo\ValueObject;

class RegistrationInfo
{
    public function __construct(
        public ?bool $isRegistered = null,
        public ?string $dateOfRegistration = null,
    ) {
    }
}
