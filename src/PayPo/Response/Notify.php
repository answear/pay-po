<?php

declare(strict_types=1);

namespace Answear\PayPo\Response;

use Answear\PayPo\Enum\OrderStatusEnum;

class Notify
{
    public ?string $shopId;
    public readonly OrderStatusEnum $transactionStatus;
    public readonly \DateTimeImmutable $lastUpdate;

    private function __construct(
        public readonly string $merchantId,
        public readonly string $referenceId,
        public readonly string $transactionId,
        string $transactionStatus,
        public readonly int $amount,
        string $lastUpdate,
    ) {
        $this->transactionStatus = OrderStatusEnum::from($transactionStatus);
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
