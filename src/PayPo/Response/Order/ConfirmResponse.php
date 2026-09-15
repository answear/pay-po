<?php

declare(strict_types=1);

namespace Answear\PayPo\Response\Order;

readonly class ConfirmResponse
{
    public ?string $statusDescription;
    public ?string $message;

    public function __construct(
        public string $code,
        ?string $statusDescription = null,
        ?string $message = null,
    ) {
        $this->statusDescription = $statusDescription ?? $message;
        $this->message = $message ?? $statusDescription;
    }
}
