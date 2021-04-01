<?php

declare(strict_types=1);

namespace Answear\PayPo\Util;

use Answear\PayPo\Exception\SignatureNotValidException;
use Answear\PayPo\ValueObject\AccessToken;

class AuthenticationUtil
{
    private const DELIMITER = '+';
    private const CONTENT_TYPE = 'application/json; charset=utf-8';

    public static function getAuthorizationHeaders(): array
    {
        return [
            'Content-Type' => self::CONTENT_TYPE,
            'Authorization' => 'Bearer ' . AccessToken::get(),
        ];
    }

    /**
     * @throws SignatureNotValidException
     */
    public static function assertSignatureValid(
        ?string $providedSignature,
        string $httpMethod,
        string $endpoint,
        array $payload,
        string $secretKey
    ): void {
        $json = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $data = implode(
            self::DELIMITER,
            [
                $httpMethod,
                $endpoint,
                $json,
            ]
        );

        $expectedSignature = self::hash($data, $secretKey);
        if (null === $providedSignature || trim($providedSignature) !== $expectedSignature) {
            throw new SignatureNotValidException($expectedSignature, $providedSignature);
        }
    }

    private static function hash(string $data, string $secretKey): string
    {
        return trim(base64_encode(hash_hmac('sha256', $data, $secretKey, true)));
    }
}
