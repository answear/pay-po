<?php

declare(strict_types=1);

namespace Answear\PayPo\Response\Error;

readonly class ApiError
{
    /**
     * @param ErrorDetail[] $errors
     */
    public function __construct(
        public ?int $code,
        public ?string $message,
        public array $errors = [],
    ) {
    }

    public static function fromResponseBody(string $body): ?self
    {
        $data = json_decode($body, true);
        if (!\is_array($data)) {
            return null;
        }

        return new self(
            isset($data['code']) ? (int) $data['code'] : null,
            isset($data['message']) ? (string) $data['message'] : null,
            self::parseErrors($data['errors'] ?? []),
        );
    }

    /**
     * @return ErrorDetail[]
     */
    private static function parseErrors(mixed $errors): array
    {
        if (!\is_array($errors)) {
            return [];
        }

        $parsed = [];
        foreach ($errors as $error) {
            if (!\is_array($error) || !isset($error['message'])) {
                continue;
            }

            $parsed[] = new ErrorDetail(
                isset($error['path']) ? (string) $error['path'] : null,
                (string) $error['message'],
            );
        }

        return $parsed;
    }
}
