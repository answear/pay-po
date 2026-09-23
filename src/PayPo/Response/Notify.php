<?php

declare(strict_types=1);

namespace Answear\PayPo\Response;

use Answear\PayPo\Enum\OrderStatusEnum;
use Answear\PayPo\Enum\SettlementStatusEnum;

class Notify
{
    public const SIGNATURE_HEADER = 'X-PayPo-Signature';

    public ?string $shopId;
    public readonly OrderStatusEnum $transactionStatus;
    public readonly ?SettlementStatusEnum $settlementStatus;
    public readonly \DateTimeImmutable $lastUpdate;
    private readonly bool $settlementNotify;

    private function __construct(
        public readonly string $merchantId,
        public readonly string $referenceId,
        public readonly string $transactionId,
        string $transactionStatus,
        public readonly int $amount,
        string $lastUpdate,
        public readonly ?string $transactionUrl = null,
        ?string $settlementStatus = null,
        public readonly ?string $message = null,
    ) {
        $this->transactionStatus = OrderStatusEnum::from($transactionStatus);
        $this->settlementNotify = null !== $settlementStatus;
        $this->settlementStatus = null === $settlementStatus ? null : SettlementStatusEnum::tryFrom($settlementStatus);
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
            $notifyData['lastUpdate'],
            empty($notifyData['transactionUrl']) ? null : $notifyData['transactionUrl'],
            empty($notifyData['settlementStatus']) ? null : $notifyData['settlementStatus'],
            empty($notifyData['message']) ? null : $notifyData['message'],
        );

        $self->shopId = empty($notifyData['shopId']) ? null : $notifyData['shopId'];

        return $self;
    }

    public function isSettlementNotify(): bool
    {
        return $this->settlementNotify;
    }
}
