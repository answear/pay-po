<?php

declare(strict_types=1);

namespace Answear\PayPo\Tests\Integration\Request\Transaction;

use Answear\PayPo\Configuration\PayPoConfiguration;
use Answear\PayPo\Exception\ConfigurationException;
use Answear\PayPo\Request\Transaction\CreateRequest;
use Answear\PayPo\Service\PayPoClient;
use Answear\PayPo\ValueObject\Address;
use Answear\PayPo\ValueObject\Configuration;
use Answear\PayPo\ValueObject\Customer;
use Answear\PayPo\ValueObject\Order;

class CreateTest extends AbstractOrderTest
{
    /**
     * @test
     *
     * @dataProvider provideDataForRequest
     */
    public function configurationNotSetException(CreateRequest $request): void
    {
        PayPoConfiguration::reset();

        $this->expectException(ConfigurationException::class);

        $client = $this->createMock(\GuzzleHttp\Client::class);
        $this->getOrderService(new PayPoClient($client))->create($request);
    }

    public function provideDataForRequest(): iterable
    {
        $this->setUpConfiguration();

        yield [
            new CreateRequest(
                new Order(
                    'ref-id-02',
                    2531,
                    'Description of data',
                    new Address(
                        'billing street',
                        'houseNumber',
                        'apartmentNumber',
                        'postal',
                        'city',
                        'country'
                    ),
                    new Address(
                        'shipping street',
                        'houseNumber',
                        'apartmentNumber',
                        'postal',
                        'city',
                        'country'
                    )
                ),
                new Customer(
                    'name',
                    'surname',
                    'email',
                    'phone'
                ),
                new Configuration(
                    'returnUrl',
                    'notifyUrl',
                    null
                )
            ),
            '{"merchantId":"e626aba7-598c-4746-9da7-03a9290bddfc","order":{"referenceId":"ref-id-02","amount":2531,"description":"Description of data","billingAddress":{"street":"billing street","building":"houseNumber","flat":"apartmentNumber","zip":"postal","city":"city","country":"country"},"shippingAddress":{"street":"shipping street","building":"houseNumber","flat":"apartmentNumber","zip":"postal","city":"city","country":"country"}},"customer":{"name":"name","surname":"surname","email":"email","phone":"phone"},"configuration":{"returnUrl":"returnUrl","notifyUrl":"notifyUrl"}}',
            [
                'transactionId' => '201',
                'redirectUrl' => 'https://redirect.url',
            ],
        ];
    }

    protected function sendAndAssert(PayPoClient $client, $request, array $apiResponse): void
    {
        if (!$request instanceof CreateRequest) {
            self::fail('Invalid argument');
        }

        $response = $this->getOrderService($client)->create($request);

        self::assertSame($apiResponse['transactionId'], $response->transactionId);
        self::assertSame($apiResponse['redirectUrl'], $response->redirectUrl);
    }
}
