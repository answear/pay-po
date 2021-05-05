<?php

declare(strict_types=1);

namespace Answear\PayPo\Request\Transaction;

use Answear\PayPo\Enum\OrderStatusEnum;

class RejectRequest implements RequestInterface
{
    private const HTTP_METHOD = 'PATCH';
    private const ENDPOINT = '/transactions';

    public string $status;
    private string $transactionUuid;

    public function __construct(string $transactionUuid)
    {
        $this->status = OrderStatusEnum::REJECTED;
        $this->transactionUuid = $transactionUuid;
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
