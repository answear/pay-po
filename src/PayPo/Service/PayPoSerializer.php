<?php

declare(strict_types=1);

namespace Answear\PayPo\Service;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer;
use Symfony\Component\Serializer\Serializer;

class PayPoSerializer
{
    private const SERIALIZE_FORMAT = 'json';

    private ?Serializer $serializer = null;

    public function serialize($request): string
    {
        return $this->getSerializer()->serialize(
            $request,
            self::SERIALIZE_FORMAT,
            [Normalizer\AbstractObjectNormalizer::SKIP_NULL_VALUES => true]
        );
    }

    public function unserialize(string $serializedData, string $type): object
    {
        return $this->getSerializer()->deserialize(
            $serializedData,
            $type,
            self::SERIALIZE_FORMAT
        );
    }

    private function getSerializer(): Serializer
    {
        if (null === $this->serializer) {
            $encoders = [new JsonEncoder()];

            $normalizers = [
                new Normalizer\PropertyNormalizer(
                    null,
                    null,
                    null,
                    null,
                    null,
                    [
                        Normalizer\PropertyNormalizer::NORMALIZE_VISIBILITY => Normalizer\PropertyNormalizer::NORMALIZE_PUBLIC | Normalizer\PropertyNormalizer::NORMALIZE_PROTECTED,
                    ]
                ),
                new Normalizer\DateTimeNormalizer(),
            ];

            $this->serializer = new Serializer($normalizers, $encoders);
        }

        return $this->serializer;
    }
}
