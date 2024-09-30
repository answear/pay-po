<?php

declare(strict_types=1);

namespace Answear\PayPo\Tests\Integration\Request\Transaction;

use Answear\PayPo\Configuration\PayPoConfiguration;
use Answear\PayPo\Service\Order;
use Answear\PayPo\Service\PayPoClient;
use Answear\PayPo\ValueObject\AccessToken;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

abstract class AbstractOrder extends TestCase
{
    protected function tearDown(): void
    {
        PayPoConfiguration::reset();
        AccessToken::reset();
    }

    #[Test]
    #[DataProvider('provideDataForRequest')]
    public function successfullySend($request, string $expectedRequest, array $apiResponse): void
    {
        self::setUpConfiguration();

        $clientResponse = new Response(200, [], json_encode($apiResponse, JSON_THROW_ON_ERROR));

        $client = $this->createMock(\GuzzleHttp\Client::class);
        $client->expects(self::once())
            ->method('send')
            ->with(
                self::callback(
                    static function (\GuzzleHttp\Psr7\Request $request) use ($expectedRequest) {
                        self::assertSame(
                            $expectedRequest,
                            $request->getBody()->getContents()
                        );

                        return true;
                    }
                )
            )
            ->willReturn($clientResponse);
        $client->expects(self::once())
            ->method('request')
            ->willReturn(
                new Response(
                    200,
                    [],
                    json_encode(
                        [
                            'token_type' => 'Bearer',
                            'expires_in' => 1800,
                            'access_token' => 'access-token',
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            );

        $this->sendAndAssert(new PayPoClient($client), $request, $apiResponse);
    }

    protected function getOrderService(?PayPoClient $client): Order
    {
        return new Order($client);
    }

    abstract public static function provideDataForRequest(): iterable;

    abstract protected function sendAndAssert(
        PayPoClient $client,
        $request,
        array $apiResponse,
    ): void;

    protected static function setUpConfiguration(): void
    {
        PayPoConfiguration::setForSandbox('e626aba7-598c-4746-9da7-03a9290bddfc', 'apiKey');
    }
}
