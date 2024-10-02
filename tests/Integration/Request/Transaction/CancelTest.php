<?php

declare(strict_types=1);

namespace Answear\PayPo\Tests\Integration\Request\Transaction;

use Answear\PayPo\Configuration\PayPoConfiguration;
use Answear\PayPo\Exception\ConfigurationException;
use Answear\PayPo\Service\PayPoClient;
use GuzzleHttp\Client;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class CancelTest extends AbstractOrder
{
    private const TRANSACTION_UUID = 'transaction-uuid';

    #[Test]
    #[DataProvider('provideDataForRequest')]
    public function configurationNotSetException(string $transactionUuid): void
    {
        PayPoConfiguration::reset();

        $this->expectException(ConfigurationException::class);

        $client = $this->createMock(Client::class);
        $this->getOrderService(new PayPoClient($client))->cancel($transactionUuid);
    }

    public static function provideDataForRequest(): iterable
    {
        self::setUpConfiguration();

        yield [
            self::TRANSACTION_UUID,
            '{"status":"CANCELED"}',
            [
                'code' => '200',
                'message' => 'Transaction updated successfully',
            ],
        ];
    }

    protected function sendAndAssert(PayPoClient $client, $request, array $apiResponse): void
    {
        self::assertSame(self::TRANSACTION_UUID, $request);

        $response = $this->getOrderService($client)->cancel($request);

        self::assertSame($apiResponse['code'], $response->code);
        self::assertSame($apiResponse['message'], $response->message);
    }
}
