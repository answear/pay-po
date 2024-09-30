<?php

declare(strict_types=1);

namespace Answear\PayPo\Request\Transaction;

class StatusDetailsRequest implements RequestInterface
{
    private const HTTP_METHOD = 'GET';
    private const ENDPOINT = '/transactions';

    public function __construct(private readonly string $transactionId)
    {
    }

    public function getHttpMethod(): string
    {
        return self::HTTP_METHOD;
    }

    public function getUrl(): string
    {
        return self::ENDPOINT . '/' . $this->transactionId;
    }
}
