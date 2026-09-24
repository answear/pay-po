<?php

declare(strict_types=1);

namespace Answear\PayPo\Tests\Integration\Request\Transaction;

use Answear\PayPo\Configuration\PayPoConfiguration;
use Answear\PayPo\Enum\OrderStatusEnum;
use Answear\PayPo\Enum\SettlementStatusEnum;
use Answear\PayPo\Exception\BadResponseException;
use Answear\PayPo\Exception\ConfigurationException;
use Answear\PayPo\Service\PayPoClient;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class StatusDetailsTest extends AbstractOrder
{
    private const TRANSACTION_UUID = 'transaction-uuid';

    #[Test]
    #[DataProvider('provideDataForRequest')]
    public function configurationNotSetException(string $transactionUuid): void
    {
        PayPoConfiguration::reset();

        $this->expectException(ConfigurationException::class);

        $client = $this->createMock(\GuzzleHttp\Client::class);
        $this->getOrderService(new PayPoClient($client))->getStatusDetails($transactionUuid);
    }

    #[Test]
    public function incompleteRefundEntryIsBadResponse(): void
    {
        self::setUpConfiguration();

        $apiResponse = self::basicResponse();
        $apiResponse['refunds'] = [['referenceRefundId' => 'refund-id']];

        $client = $this->createMock(\GuzzleHttp\Client::class);
        $client->method('send')->willReturn(new Response(200, [], json_encode($apiResponse, JSON_THROW_ON_ERROR)));
        $client->method('request')->willReturn(
            new Response(
                200,
                [],
                json_encode(['token_type' => 'Bearer', 'expires_in' => 1800, 'access_token' => 'access-token'], JSON_THROW_ON_ERROR)
            )
        );

        $this->expectException(BadResponseException::class);

        $this->getOrderService(new PayPoClient($client))->getStatusDetails(self::TRANSACTION_UUID);
    }

    public static function provideDataForRequest(): iterable
    {
        self::setUpConfiguration();

        yield 'basic response' => [
            self::TRANSACTION_UUID,
            '',
            self::basicResponse(),
        ];

        yield 'extended response without refunds' => [
            self::TRANSACTION_UUID,
            '',
            self::basicResponse() + ['refunds' => []],
        ];

        yield 'extended response with refunds' => [
            self::TRANSACTION_UUID,
            '',
            self::basicResponse() + [
                'refunds' => [
                    [
                        'referenceRefundId' => '3e12a361-d193-4f3a-88b5-b8fda405a529',
                        'amount' => 8655,
                        'created' => '2023-09-08T13:46:04+02:00',
                    ],
                    [
                        'referenceRefundId' => null,
                        'amount' => 855,
                        'created' => '2023-09-08T13:46:04+02:00',
                    ],
                ],
            ],
        ];
    }

    private static function basicResponse(): array
    {
        return [
            'merchantId' => '19c692be-a893-468c-a65f-b8de442e5443',
            'referenceId' => 'ord_987654',
            'transactionId' => 'cd975bc6-a755-4141-b7a0-d7e8f7a308ef',
            'transactionStatus' => 'COMPLETED',
            'transactionUrl' => 'https://transaction-url.fake',
            'amount' => 24900,
            'settlementStatus' => 'PAID',
            'lastUpdate' => '2020-03-05T10:54:02',
        ];
    }

    protected function sendAndAssert(PayPoClient $client, $request, array $apiResponse): void
    {
        self::assertSame(self::TRANSACTION_UUID, $request);

        $response = $this->getOrderService($client)->getStatusDetails($request);

        self::assertSame($apiResponse['merchantId'], $response->merchantId);
        self::assertSame($apiResponse['referenceId'], $response->referenceId);
        self::assertSame($apiResponse['transactionId'], $response->transactionId);
        self::assertSame($response->transactionStatus, OrderStatusEnum::Completed);
        self::assertSame($apiResponse['transactionUrl'], $response->transactionUrl);
        self::assertSame($apiResponse['amount'], $response->amount);
        self::assertSame($response->settlementStatus, SettlementStatusEnum::Paid);
        self::assertSame(
            $apiResponse['lastUpdate'] . '+00:00',
            $response->lastUpdate->format(\DateTimeInterface::RFC3339)
        );

        if (!\array_key_exists('refunds', $apiResponse)) {
            self::assertNull($response->refunds);
            self::assertNull($response->findRefund('3e12a361-d193-4f3a-88b5-b8fda405a529'));

            return;
        }

        $expectedRefunds = $apiResponse['refunds'];
        self::assertCount(\count($expectedRefunds), $response->refunds);

        foreach ($expectedRefunds as $key => $expectedRefund) {
            self::assertSame($expectedRefund['referenceRefundId'], $response->refunds[$key]->referenceRefundId);
            self::assertSame($expectedRefund['amount'], $response->refunds[$key]->amount);
            self::assertSame(
                $expectedRefund['created'],
                $response->refunds[$key]->created->format(\DateTimeInterface::RFC3339)
            );
        }

        if ([] !== $expectedRefunds) {
            self::assertSame(8655, $response->findRefund('3e12a361-d193-4f3a-88b5-b8fda405a529')?->amount);
            self::assertNull($response->findRefund('not-existing-refund-id'));
        }
    }
}
