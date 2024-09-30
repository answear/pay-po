<?php

declare(strict_types=1);

namespace Answear\PayPo\Request\Transaction;

use Answear\PayPo\Enum\OrderStatusEnum;

class ConfirmRequest implements RequestInterface
{
    private const HTTP_METHOD = 'PATCH';
    private const ENDPOINT = '/transactions';

    public string $status;

    public function __construct(private readonly string $transactionUuid)
    {
        $this->status = OrderStatusEnum::Completed->value;
    }

    public function getHttpMethod(): string
    {
        return self::HTTP_METHOD;
    }

    public function getUrl(): string
    {
        return self::ENDPOINT . '/' . $this->transactionUuid;
    }
}
