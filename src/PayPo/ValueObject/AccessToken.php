<?php

declare(strict_types=1);

namespace Answear\PayPo\ValueObject;

class AccessToken
{
    private static ?\DateTimeImmutable $expiresIn;
    private static ?string $token;

    public static function set(int $expiresIn, string $token): void
    {
        self::$expiresIn = new \DateTimeImmutable(sprintf('+ %s seconds', $expiresIn));
        self::$token = $token;
    }

    public static function get(): ?string
    {
        if (self::isExpired()) {
            return null;
        }

        return self::$token;
    }

    public static function reset(): void
    {
        self::$expiresIn = null;
        self::$token = null;
    }

    private static function isExpired(): bool
    {
        return !isset(self::$expiresIn) || null === self::$token || self::$expiresIn->getTimestamp() < time();
    }
}
