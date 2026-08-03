<?php

declare(strict_types=1);

namespace Answear\PayPo\Tests\Unit\Configuration;

use Answear\PayPo\Configuration\PayPoConfiguration;
use Answear\PayPo\Exception\ConfigurationException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class PayPoConfigurationTest extends TestCase
{
    protected function tearDown(): void
    {
        PayPoConfiguration::reset();
    }

    #[Test]
    public function usesProvidedHostForProduction(): void
    {
        PayPoConfiguration::setForProduction('clientId', 'clientSecret', 'api.paypo.ro');

        self::assertSame('https://api.paypo.ro/v3', PayPoConfiguration::getApiUrl());
    }

    #[Test]
    public function usesProvidedHostForSandbox(): void
    {
        PayPoConfiguration::setForSandbox('clientId', 'clientSecret', 'api.sandbox.paypo.ro');

        self::assertSame('https://api.sandbox.paypo.ro/v3', PayPoConfiguration::getApiUrl());
    }

    #[Test]
    #[DataProvider('provideNotNormalizedHosts')]
    public function normalizesProvidedHost(string $host, string $expectedApiUrl): void
    {
        PayPoConfiguration::setForProduction('clientId', 'clientSecret', $host);

        self::assertSame($expectedApiUrl, PayPoConfiguration::getApiUrl());
    }

    public static function provideNotNormalizedHosts(): iterable
    {
        yield 'with scheme' => ['https://api.paypo.pl', 'https://api.paypo.pl/v3'];
        yield 'with insecure scheme' => ['http://api.paypo.pl', 'https://api.paypo.pl/v3'];
        yield 'with trailing slash' => ['api.paypo.pl/', 'https://api.paypo.pl/v3'];
        yield 'with scheme and trailing slash' => ['https://api.paypo.pl/', 'https://api.paypo.pl/v3'];
        yield 'with surrounding whitespaces' => [' api.paypo.pl ', 'https://api.paypo.pl/v3'];
    }

    #[Test]
    #[DataProvider('provideEmptyHosts')]
    public function throwsOnEmptyHost(string $host): void
    {
        $this->expectException(ConfigurationException::class);

        PayPoConfiguration::setForProduction('clientId', 'clientSecret', $host);
    }

    public static function provideEmptyHosts(): iterable
    {
        yield 'empty string' => [''];
        yield 'whitespaces only' => ['   '];
        yield 'scheme only' => ['https://'];
    }
}
