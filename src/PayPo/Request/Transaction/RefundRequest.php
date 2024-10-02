<?php

declare(strict_types=1);

namespace Answear\PayPo\Request\Transaction;

class RefundRequest implements RequestInterface
{
    private const HTTP_METHOD = 'POST';
    private const ENDPOINT = '/transactions';

    public function __construct(
        private readonly string $transactionUuid,
        public readonly int $amount,
    ) {
    }

    public function getHttpMethod(): string
    {
        return self::HTTP_METHOD;
    }

    public function getUrl(): string
    {
        return self::ENDPOINT . '/' . $this->transactionUuid . '/refunds';
    }
}
