<?php

declare(strict_types=1);

namespace Answear\PayPo\Tests\Unit\Response;

use Answear\PayPo\Enum\OrderStatusEnum;
use Answear\PayPo\Enum\SettlementStatusEnum;
use Answear\PayPo\Response\Notify;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class NotifyTest extends TestCase
{
    #[Test]
    public function statusChangeNotify(): void
    {
        $notify = Notify::fromRawNotify(
            [
                'merchantId' => '19c692be-a893-468c-a65f-b8de442e5443',
                'referenceId' => 'ord_987654',
                'transactionId' => '00102030',
                'transactionStatus' => 'PENDING',
                'transactionUrl' => 'https://process.paypo.pl/00102030',
                'amount' => 24900,
                'lastUpdate' => '2020-03-05T10:54:02+01:00',
            ]
        );

        self::assertSame('19c692be-a893-468c-a65f-b8de442e5443', $notify->merchantId);
        self::assertSame('ord_987654', $notify->referenceId);
        self::assertSame('00102030', $notify->transactionId);
        self::assertSame(OrderStatusEnum::Pending, $notify->transactionStatus);
        self::assertSame('https://process.paypo.pl/00102030', $notify->transactionUrl);
        self::assertSame(24900, $notify->amount);
        self::assertSame('2020-03-05T10:54:02+01:00', $notify->lastUpdate->format(\DateTimeInterface::RFC3339));
        self::assertNull($notify->shopId);
        self::assertNull($notify->settlementStatus);
        self::assertNull($notify->message);
        self::assertFalse($notify->isSettlementNotify());
    }

    #[Test]
    public function settlementNotify(): void
    {
        $notify = Notify::fromRawNotify(
            [
                'merchantId' => '0e1576d8-e760-4336-8bc4-c20a549ac035',
                'referenceId' => 'QQBF6HAWVGI972291WQQ',
                'transactionId' => '9207c39a-1d1f-4954-a312-fe5dcd1a1f8a',
                'transactionStatus' => 'COMPLETED',
                'amount' => 1212,
                'lastUpdate' => '2021-07-27T18:44:51.000+02:00',
                'settlementStatus' => 'PAID',
                'message' => 'Transaction is settled',
                'shopId' => 'c8847732-b5d7-4528-8a7b-54ede5e43789',
            ]
        );

        self::assertSame(OrderStatusEnum::Completed, $notify->transactionStatus);
        self::assertSame(SettlementStatusEnum::Paid, $notify->settlementStatus);
        self::assertSame('Transaction is settled', $notify->message);
        self::assertSame('c8847732-b5d7-4528-8a7b-54ede5e43789', $notify->shopId);
        self::assertNull($notify->transactionUrl);
        self::assertTrue($notify->isSettlementNotify());
    }
}
