<?php

declare(strict_types=1);

namespace Answear\PayPo\Response;

use Answear\PayPo\Enum\OrderStatusEnum;

class Notify
{
    public string $merchantId;
    public ?string $shopId;
    public string $referenceId;
    public string $transactionId;
    public OrderStatusEnum $transactionStatus;
    public int $amount;
    public \DateTimeImmutable $lastUpdate;

    private function __construct(
        string $merchantId,
        string $referenceId,
        string $transactionId,
        string $transactionStatus,
        int $amount,
        string $lastUpdate
    ) {
        $this->merchantId = $merchantId;
        $this->referenceId = $referenceId;
        $this->transactionId = $transactionId;
        $this->transactionStatus = OrderStatusEnum::get($transactionStatus);
        $this->amount = $amount;
        $this->lastUpdate = new \DateTimeImmutable($lastUpdate);
    }

    public static function fromRawNotify(array $notifyData): self
    {
        $self = new self(
            $notifyData['merchantId'],
            $notifyData['referenceId'],
            $notifyData['transactionId'],
            $notifyData['transactionStatus'],
            $notifyData['amount'],
            $notifyData['lastUpdate']
        );

        $self->shopId = empty($notifyData['shopId']) ? null : $notifyData['shopId'];

        return $self;
    }
}
