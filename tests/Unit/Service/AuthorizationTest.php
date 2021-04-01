<?php

declare(strict_types=1);

namespace Answear\PayPo\Tests\Unit\Service;

use Answear\PayPo\Configuration\PayPoConfiguration;
use Answear\PayPo\Service\Authorization;
use Answear\PayPo\ValueObject\AccessToken;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class AuthorizationTest extends TestCase
{
    private const TOKEN = 'token-';

    /**
     * @test
     */
    public function accessTokenSetTest(): void
    {
        PayPoConfiguration::setForSandbox('clientId', 'clientSecret');

        $this->getService()->authorize();

        self::assertSame(self::TOKEN, AccessToken::get());
    }

    private function getService(): Authorization
    {
        $client = $this->createMock(ClientInterface::class);
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
                            'access_token' => self::TOKEN,
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            );

        return new Authorization($client);
    }
}
