<?php

declare(strict_types=1);

namespace Answear\PayPo\Service;

use Answear\PayPo\Configuration\PayPoConfiguration;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class Validation
{
    public function __construct(private ?ClientInterface $client = null)
    {
    }

    public function isOnline(): bool
    {
        try {
            $this->getClient()->request('GET', PayPoConfiguration::getApiUrl(), []);
        } catch (\Throwable $e) {
            return false;
        }

        return true;
    }

    private function getClient(): ClientInterface
    {
        if (null === $this->client) {
            $this->client = new Client(['timeout' => PayPoClient::TIMEOUT, 'connect_timeout' => PayPoClient::CONNECTION_TIMEOUT]);
        }

        return $this->client;
    }
}
