<?php

declare(strict_types=1);

namespace Answear\PayPo\Response\Order;

readonly class Refund
{
    public \DateTimeImmutable $created;

    public function __construct(
        public ?string $referenceRefundId,
        public int $amount,
        string $created,
    ) {
        $this->created = new \DateTimeImmutable($created);
    }

    /**
     * @param array<string, mixed> $refund
     */
    public static function fromArray(array $refund): self
    {
        if (!isset($refund['amount'], $refund['created'])) {
            throw new \InvalidArgumentException('Refund entry requires amount and created.');
        }

        return new self(
            isset($refund['referenceRefundId']) ? (string) $refund['referenceRefundId'] : null,
            (int) $refund['amount'],
            (string) $refund['created'],
        );
    }
}
