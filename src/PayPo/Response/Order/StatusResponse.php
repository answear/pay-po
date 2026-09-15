<?php

declare(strict_types=1);

namespace Answear\PayPo\Response\Order;

use Answear\PayPo\Enum\OrderStatusEnum;
use Answear\PayPo\Enum\SettlementStatusEnum;

readonly class StatusResponse
{
    public OrderStatusEnum $transactionStatus;
    public SettlementStatusEnum $settlementStatus;
    public \DateTimeImmutable $lastUpdate;

    /**
     * @var Refund[]
     */
    public array $refunds;

    /**
     * @param array<mixed> $refunds
     */
    public function __construct(
        public string $merchantId,
        public string $referenceId,
        public string $transactionId,
        string $transactionStatus,
        public int $amount,
        string $settlementStatus,
        string $lastUpdate,
        public ?string $transactionUrl = null,
        array $refunds = [],
    ) {
        $this->transactionStatus = OrderStatusEnum::from($transactionStatus);
        $this->settlementStatus = SettlementStatusEnum::from($settlementStatus);
        $this->lastUpdate = new \DateTimeImmutable($lastUpdate);

        $parsedRefunds = [];
        foreach ($refunds as $refund) {
            if (\is_array($refund)) {
                $parsedRefunds[] = Refund::fromArray($refund);
            }
        }
        $this->refunds = $parsedRefunds;
    }

    public function findRefund(string $referenceRefundId): ?Refund
    {
        foreach ($this->refunds as $refund) {
            if ($referenceRefundId === $refund->referenceRefundId) {
                return $refund;
            }
        }

        return null;
    }
}
