<?php

declare(strict_types=1);

namespace Answear\PayPo\ValueObject;

class Configuration
{
    public string $returnUrl;
    public string $notifyUrl;
    public ?string $cancelUrl;

    public function __construct(string $returnUrl, string $notifyUrl, ?string $cancelUrl)
    {
        $this->returnUrl = $returnUrl;
        $this->notifyUrl = $notifyUrl;
        $this->cancelUrl = $cancelUrl;
    }
}
