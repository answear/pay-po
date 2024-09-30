<?php

declare(strict_types=1);

namespace Answear\PayPo\Exception;

use Psr\Http\Message\ResponseInterface;

class BadResponseException extends \RuntimeException
{
    public function __construct(
        public readonly ResponseInterface $response,
        ?\Throwable $throwable,
    ) {
        parent::__construct('Response error.', 0, $throwable);
    }
}
