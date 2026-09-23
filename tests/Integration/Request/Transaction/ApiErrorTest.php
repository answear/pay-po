<?php

declare(strict_types=1);

namespace Answear\PayPo\Tests\Integration\Request\Transaction;

use Answear\PayPo\Configuration\PayPoConfiguration;
use Answear\PayPo\Exception\ApiErrorException;
use Answear\PayPo\Exception\ServiceUnavailable;
use Answear\PayPo\Service\Order;
use Answear\PayPo\Service\PayPoClient;
use Answear\PayPo\ValueObject\AccessToken;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ApiErrorTest extends TestCase
{
    private const TRANSACTION_UUID = 'transaction-uuid';

    protected function tearDown(): void
    {
        PayPoConfiguration::reset();
        AccessToken::reset();
    }

    #[Test]
    public function badRequestExposesValidationErrors(): void
    {
        $body = '{"code":400,"message":"Bad request","errors":[{"path":"order.referenceId","message":"This value should not be blank."}]}';
        $orderService = $this->getOrderService(new ClientException('Bad request', new Request('POST', '/'), new Response(400, [], $body)));

        try {
            $orderService->refund(self::TRANSACTION_UUID, 100, 'refund-id');
            self::fail('ApiErrorException expected.');
        } catch (ApiErrorException $exception) {
            self::assertSame(400, $exception->statusCode);
            self::assertSame(400, $exception->getCode());
            self::assertSame('400', $exception->error->code);
            self::assertSame('Bad request', $exception->error->message);
            self::assertCount(1, $exception->error->errors);
            self::assertSame('order.referenceId', $exception->error->errors[0]->path);
            self::assertSame('This value should not be blank.', $exception->error->errors[0]->message);
        }
    }

    #[Test]
    public function refundAmountTooHighKeepsApiMessage(): void
    {
        $body = '{"code":400,"message":"Refund amount 2000 can not be greater than order amount 1000."}';
        $orderService = $this->getOrderService(new ClientException('Bad request', new Request('POST', '/'), new Response(400, [], $body)));

        try {
            $orderService->refund(self::TRANSACTION_UUID, 2000);
            self::fail('ApiErrorException expected.');
        } catch (ApiErrorException $exception) {
            self::assertSame('Refund amount 2000 can not be greater than order amount 1000.', $exception->error->message);
            self::assertSame([], $exception->error->errors);
        }
    }

    #[Test]
    public function conflictIsReportedAsApiError(): void
    {
        $orderService = $this->getOrderService(new ClientException('Conflict', new Request('PATCH', '/'), new Response(409, [], '')));

        try {
            $orderService->cancel(self::TRANSACTION_UUID);
            self::fail('ApiErrorException expected.');
        } catch (ApiErrorException $exception) {
            self::assertSame(409, $exception->statusCode);
            self::assertSame(409, $exception->getCode());
            self::assertNull($exception->error);
        }
    }

    #[Test]
    public function serverErrorStaysServiceUnavailable(): void
    {
        $orderService = $this->getOrderService(new ServerException('Service unavailable', new Request('GET', '/'), new Response(503, [], '')));

        try {
            $orderService->getStatusDetails(self::TRANSACTION_UUID);
            self::fail('ServiceUnavailable expected.');
        } catch (ServiceUnavailable $exception) {
            self::assertNotInstanceOf(ApiErrorException::class, $exception);
        }
    }

    #[Test]
    public function connectionProblemStaysServiceUnavailable(): void
    {
        $orderService = $this->getOrderService(new ConnectException('Connection refused', new Request('GET', '/')));

        try {
            $orderService->getStatusDetails(self::TRANSACTION_UUID);
            self::fail('ServiceUnavailable expected.');
        } catch (ServiceUnavailable $exception) {
            self::assertNotInstanceOf(ApiErrorException::class, $exception);
        }
    }

    private function getOrderService(\Throwable $sendException): Order
    {
        PayPoConfiguration::setForSandbox('e626aba7-598c-4746-9da7-03a9290bddfc', 'apiKey', 'api.sandbox.paypo.pl');

        $client = $this->createMock(Client::class);
        $client->method('request')
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
        $client->method('send')->willThrowException($sendException);

        return new Order(new PayPoClient($client));
    }
}
