<?php

declare(strict_types=1);

namespace Answear\PayPo\Exception;

use Answear\PayPo\Response\Error\ApiError;
use Psr\Http\Message\ResponseInterface;

class ApiErrorException extends ServiceUnavailable
{
    public function __construct(
        public readonly int $statusCode,
        public readonly ?ApiError $error,
        public readonly ResponseInterface $response,
        ?\Throwable $previous = null,
    ) {
        parent::__construct(
            sprintf(
                'PayPo API error. Response code: %d.%s',
                $statusCode,
                null === $error?->message ? '' : ' ' . $error->message
            ),
            $statusCode,
            $previous
        );
    }
}
