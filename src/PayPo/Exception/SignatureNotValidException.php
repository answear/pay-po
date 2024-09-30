<?php

declare(strict_types=1);

namespace Answear\PayPo\Exception;

class SignatureNotValidException extends \InvalidArgumentException
{
    public function __construct(
        public readonly string $expectedSignature,
        public readonly ?string $providedSignature,
    ) {
        parent::__construct('Signature is not valid');
    }
}
