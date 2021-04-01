<?php

declare(strict_types=1);

namespace Answear\PayPo\Exception;

class SignatureNotValidException extends \InvalidArgumentException
{
    private string $expectedSignature;
    private ?string $providedSignature;

    public function __construct(string $expectedSignature, ?string $providedSignature)
    {
        $this->expectedSignature = $expectedSignature;
        $this->providedSignature = $providedSignature;

        parent::__construct('Signature is not valid');
    }

    public function getExpectedSignature(): string
    {
        return $this->expectedSignature;
    }

    public function getProvidedSignature(): ?string
    {
        return $this->providedSignature;
    }
}
