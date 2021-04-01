<?php

declare(strict_types=1);

namespace Answear\PayPo\Configuration;

use Answear\PayPo\Exception\ConfigurationException;

class PayPoConfiguration
{
    private const PROD_URL = 'https://api.paypo.pl/v3';
    private const SANDBOX_URL = 'https://api.sandbox.paypo.pl/v3';

    private static ?string $apiUrl;
    private static ?string $clientId;
    private static ?string $clientSecret;

    public static function setForProduction(string $clientId, string $clientSecret): void
    {
        self::setUp(self::PROD_URL, $clientId, $clientSecret);
    }

    public static function setForSandbox(string $clientId, string $clientSecret): void
    {
        self::setUp(self::SANDBOX_URL, $clientId, $clientSecret);
    }

    public static function getApiUrl(): string
    {
        self::validateConfig();

        return self::$apiUrl;
    }

    public static function getClientId(): string
    {
        self::validateConfig();

        return self::$clientId;
    }

    public static function getClientSecret(): string
    {
        self::validateConfig();

        return self::$clientSecret;
    }

    public static function reset(): void
    {
        self::$apiUrl = null;
        self::$clientId = null;
        self::$clientSecret = null;
    }

    private static function setUp(string $apiUrl, string $clientId, string $clientSecret): void
    {
        self::$apiUrl = $apiUrl;
        self::$clientId = $clientId;
        self::$clientSecret = $clientSecret;
    }

    private static function validateConfig(): void
    {
        if (empty(self::$apiUrl)
            || empty(self::$clientSecret)
            || empty(self::$clientId)
        ) {
            throw new ConfigurationException(
                'PayPoConfiguration has not been set. Please set up PayPoConfiguration before proceeding.'
            );
        }
    }
}
