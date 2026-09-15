<?php

declare(strict_types=1);

namespace Answear\PayPo\Tests\Integration\Request\Transaction;

use Answear\PayPo\Configuration\PayPoConfiguration;
use Answear\PayPo\Exception\ConfigurationException;
use Answear\PayPo\Service\PayPoClient;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class RefundTest extends AbstractOrder
{
    private const TRANSACTION_ID = 'tranaction-id';
    private const AMOUNT = 328264;
    private const REFERENCE_REFUND_ID = '3e12a361-d193-4f3a-88b5-b8fda405a529';

    #[Test]
    #[DataProvider('provideDataForRequest')]
    public function configurationNotSetException($request): void
    {
        PayPoConfiguration::reset();

        $this->expectException(ConfigurationException::class);

        $client = $this->createMock(\GuzzleHttp\Client::class);
        $this->getOrderService(new PayPoClient($client))->refund(...$request);
    }

    public static function provideDataForRequest(): iterable
    {
        self::setUpConfiguration();

        yield 'without referenceRefundId' => [
            [
                self::TRANSACTION_ID,
                self::AMOUNT,
            ],
            '{"amount":328264}',
            [
                'code' => '200',
                'message' => 'Refund request accepted',
            ],
        ];

        yield 'with referenceRefundId' => [
            [
                self::TRANSACTION_ID,
                self::AMOUNT,
                self::REFERENCE_REFUND_ID,
            ],
            '{"amount":328264,"referenceRefundId":"' . self::REFERENCE_REFUND_ID . '"}',
            [
                'code' => 201,
                'message' => 'Refund created successfully',
            ],
        ];
    }

    protected function sendAndAssert(PayPoClient $client, $request, array $apiResponse): void
    {
        self::assertSame(self::TRANSACTION_ID, $request[0]);
        self::assertSame(self::AMOUNT, $request[1]);

        $response = $this->getOrderService($client)->refund(...$request);

        self::assertSame((string) $apiResponse['code'], $response->code);
        self::assertSame($apiResponse['message'], $response->message);
    }
}
