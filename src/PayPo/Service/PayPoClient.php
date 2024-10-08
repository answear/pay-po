<?php

declare(strict_types=1);

namespace Answear\PayPo\Service;

use Answear\PayPo\Configuration\PayPoConfiguration;
use Answear\PayPo\Request\Transaction\RequestInterface;
use Answear\PayPo\Util\AuthenticationUtil;
use Answear\PayPo\ValueObject\AccessToken;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;

class PayPoClient
{
    public const CONNECTION_TIMEOUT = 10;
    public const TIMEOUT = 30;

    public function __construct(
        private ?ClientInterface $client = null,
        private ?Authorization $authorizationService = null,
    ) {
    }

    /**
     * @throws GuzzleException
     */
    public function send(RequestInterface $request, ?string $body): ResponseInterface
    {
        try {
            if (null === AccessToken::get()) {
                $this->getAuthorizationService()->authorize();
            }

            return $this->sendData($request, $body);
        } catch (GuzzleException $e) {
            if (401 === $e->getCode()) {
                $this->getAuthorizationService()->authorize();

                return $this->sendData($request, $body);
            }

            throw $e;
        }
    }

    /**
     * @throws GuzzleException
     */
    private function sendData(RequestInterface $request, ?string $body): ResponseInterface
    {
        return $this->getClient()->send(
            new Request(
                $request->getHttpMethod(),
                new Uri(PayPoConfiguration::getApiUrl() . $request->getUrl()),
                AuthenticationUtil::getAuthorizationHeaders(),
                $body
            )
        );
    }

    private function getClient(): ClientInterface
    {
        if (null === $this->client) {
            $this->client = new Client(['timeout' => self::TIMEOUT, 'connect_timeout' => self::CONNECTION_TIMEOUT]);
        }

        return $this->client;
    }

    private function getAuthorizationService(): Authorization
    {
        if (null === $this->authorizationService) {
            $this->authorizationService = new Authorization($this->getClient());
        }

        return $this->authorizationService;
    }
}
