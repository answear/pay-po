<?php

declare(strict_types=1);

namespace Answear\PayPo\Request\Transaction;

class StatusDetailsRequest implements RequestInterface
{
    private const HTTP_METHOD = 'GET';
    private const ENDPOINT = '/transactions';

    private string $transactionId;

    public function __construct(string $transactionId)
    {
        $this->transactionId = $transactionId;
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
