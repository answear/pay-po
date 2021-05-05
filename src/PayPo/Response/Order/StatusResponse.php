<?php

declare(strict_types=1);

namespace Answear\PayPo\Response\Order;

use Answear\PayPo\Enum\OrderStatusEnum;
use Answear\PayPo\Enum\SettlementStatusEnum;

class StatusResponse
{
    public string $merchantId;
    public string $referenceId;
    public string $transactionId;
    public OrderStatusEnum $transactionStatus;
    public ?string $transactionUrl = null;
    public int $amount;
    public SettlementStatusEnum $settlementStatus;
    public \DateTimeImmutable $lastUpdate;

    public function __construct(
        string $merchantId,
        string $referenceId,
        string $transactionId,
        string $transactionStatus,
        int $amount,
        string $settlementStatus,
        string $lastUpdate,
        ?string $transactionUrl = null
    ) {
        $this->merchantId = $merchantId;
        $this->referenceId = $referenceId;
        $this->transactionId = $transactionId;
        $this->transactionStatus = OrderStatusEnum::get($transactionStatus);
        $this->amount = $amount;
        $this->settlementStatus = SettlementStatusEnum::get($settlementStatus);
        $this->lastUpdate = new \DateTimeImmutable($lastUpdate);
        $this->transactionUrl = $transactionUrl;
    }
}
