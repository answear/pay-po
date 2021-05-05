<?php

declare(strict_types=1);

namespace Answear\PayPo\Tests\Integration\Request\Transaction;

use Answear\PayPo\Configuration\PayPoConfiguration;
use Answear\PayPo\Exception\ConfigurationException;
use Answear\PayPo\Service\PayPoClient;

class RefundTest extends AbstractOrderTest
{
    private const TRANSACTION_ID = 'tranaction-id';
    private const AMOUNT = 328264;

    /**
     * @test
     * @dataProvider provideDataForRequest
     */
    public function configurationNotSetException($request): void
    {
        PayPoConfiguration::reset();

        $this->expectException(ConfigurationException::class);

        $client = $this->createMock(\GuzzleHttp\Client::class);
        $this->getOrderService(new PayPoClient($client))->refund(...$request);
    }

    public function provideDataForRequest(): iterable
    {
        $this->setUpConfiguration();

        yield [
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
    }

    protected function sendAndAssert(PayPoClient $client, $request, array $apiResponse): void
    {
        self::assertSame(
            [
                self::TRANSACTION_ID,
                self::AMOUNT,
            ],
            $request
        );

        $response = $this->getOrderService($client)->refund(...$request);

        self::assertSame($apiResponse['code'], $response->code);
        self::assertSame($apiResponse['message'], $response->message);
    }
}
