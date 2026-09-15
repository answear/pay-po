<?php

declare(strict_types=1);

namespace Answear\PayPo\Tests\Integration\Request\Transaction;

use Answear\PayPo\Configuration\PayPoConfiguration;
use Answear\PayPo\Exception\ConfigurationException;
use Answear\PayPo\Service\PayPoClient;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class ConfirmTest extends AbstractOrder
{
    private const TRANSACTION_UUID = 'transaction-uuid';

    #[Test]
    #[DataProvider('provideDataForRequest')]
    public function configurationNotSetException(string $transactionUuid): void
    {
        PayPoConfiguration::reset();

        $this->expectException(ConfigurationException::class);

        $client = $this->createMock(\GuzzleHttp\Client::class);
        $this->getOrderService(new PayPoClient($client))->confirm($transactionUuid);
    }

    public static function provideDataForRequest(): iterable
    {
        self::setUpConfiguration();

        yield 'response as documented' => [
            self::TRANSACTION_UUID,
            '{"status":"COMPLETED"}',
            [
                'code' => 200,
                'message' => 'Transaction updated successfully',
            ],
        ];

        yield 'response with statusDescription' => [
            self::TRANSACTION_UUID,
            '{"status":"COMPLETED"}',
            [
                'code' => '200',
                'statusDescription' => 'Transaction updated successfully',
            ],
        ];
    }

    protected function sendAndAssert(PayPoClient $client, $request, array $apiResponse): void
    {
        self::assertSame(self::TRANSACTION_UUID, $request);

        $response = $this->getOrderService($client)->confirm($request);

        $expectedMessage = $apiResponse['message'] ?? $apiResponse['statusDescription'];

        self::assertSame((string) $apiResponse['code'], $response->code);
        self::assertSame($expectedMessage, $response->message);
        self::assertSame($expectedMessage, $response->statusDescription);
    }
}
