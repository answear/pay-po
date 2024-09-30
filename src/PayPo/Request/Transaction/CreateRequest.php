<?php

declare(strict_types=1);

namespace Answear\PayPo\Request\Transaction;

use Answear\PayPo\Configuration\PayPoConfiguration;
use Answear\PayPo\ValueObject\Configuration;
use Answear\PayPo\ValueObject\Customer;
use Answear\PayPo\ValueObject\Order;

class CreateRequest implements RequestInterface
{
    private const HTTP_METHOD = 'POST';
    private const ENDPOINT = '/transactions';

    public string $merchantId;
    public ?string $shopId = null;

    public function __construct(
        public readonly Order $order,
        public readonly Customer $customer,
        public readonly Configuration $configuration,
    ) {
        $this->merchantId = PayPoConfiguration::getClientId();
    }

    public function getHttpMethod(): string
    {
        return self::HTTP_METHOD;
    }

    public function getUrl(): string
    {
        return self::ENDPOINT;
    }

    public function setShopId(string $shopId): void
    {
        $this->shopId = $shopId;
    }
}
