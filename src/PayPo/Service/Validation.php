<?php

declare(strict_types=1);

namespace Answear\PayPo\Service;

use Answear\PayPo\Configuration\PayPoConfiguration;

class Validation
{
    private ?\GuzzleHttp\ClientInterface $client;

    public function __construct(?\GuzzleHttp\ClientInterface $client = null)
    {
        $this->client = $client;
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

    private function getClient(): \GuzzleHttp\ClientInterface
    {
        if (null === $this->client) {
            $this->client = new \GuzzleHttp\Client();
        }

        return $this->client;
    }
}
