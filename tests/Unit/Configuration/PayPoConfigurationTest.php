<?php

declare(strict_types=1);

namespace Answear\PayPo\Tests\Unit\Configuration;

use Answear\PayPo\Configuration\PayPoConfiguration;
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
}
