<?php

declare(strict_types=1);

namespace Answear\PayPo\Response\Error;

readonly class ErrorDetail
{
    public function __construct(
        public ?string $path,
        public string $message,
    ) {
    }
}
