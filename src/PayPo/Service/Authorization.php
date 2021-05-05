<?php

declare(strict_types=1);

namespace Answear\PayPo\Service;

use Answear\PayPo\Configuration\PayPoConfiguration;
use Answear\PayPo\ValueObject\AccessToken;
use GuzzleHttp\RequestOptions;

class Authorization
{
    private const AUTH_URI = '/oauth/tokens';
    private \GuzzleHttp\ClientInterface $client;

    public function __construct(\GuzzleHttp\ClientInterface $client)
    {
        $this->client = $client;
    }

    public function authorize(): void
    {
        $response = $this->client->request(
            'POST',
            PayPoConfiguration::getApiUrl() . self::AUTH_URI,
            [
                RequestOptions::FORM_PARAMS => [
                    'grant_type' => 'client_credentials',
                    'client_id' => PayPoConfiguration::getClientId(),
                    'client_secret' => PayPoConfiguration::getClientSecret(),
                ],
            ]
        );

        if ($response->getBody()->isSeekable()) {
            $response->getBody()->rewind();
        }
        $data = json_decode($response->getBody()->getContents(), true);

        if (empty($data['expires_in']) || empty($data['access_token'])) {
            throw new \RuntimeException('No access token on auth response.');
        }

        AccessToken::set($data['expires_in'], $data['access_token']);
    }
}
