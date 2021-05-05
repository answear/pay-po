<?php

declare(strict_types=1);

namespace Answear\PayPo\Tests\Integration\Request\Transaction;

use Answear\PayPo\Configuration\PayPoConfiguration;
use Answear\PayPo\Exception\ConfigurationException;
use Answear\PayPo\Service\PayPoClient;

class RejectTest extends AbstractOrderTest
{
    private const TRANSACTION_UUID = 'transaction-uuid';

    /**
     * @test
     * @dataProvider provideDataForRequest
     */
    public function configurationNotSetException(string $transactionUuid): void
    {
        PayPoConfiguration::reset();

        $this->expectException(ConfigurationException::class);

        $client = $this->createMock(\GuzzleHttp\Client::class);
        $this->getOrderService(new PayPoClient($client))->reject($transactionUuid);
    }

    public function provideDataForRequest(): iterable
    {
        $this->setUpConfiguration();

        yield [
            self::TRANSACTION_UUID,
            '{"status":"REJECTED"}',
            [
                'code' => '200',
                'statusDescription' => 'Transaction updated successfully',
            ],
        ];
    }

    protected function sendAndAssert(PayPoClient $client, $request, array $apiResponse): void
    {
        self::assertSame(self::TRANSACTION_UUID, $request);

        $response = $this->getOrderService($client)->reject($request);

        self::assertSame($apiResponse['code'], $response->code);
        self::assertSame($apiResponse['statusDescription'], $response->statusDescription);
    }
}
