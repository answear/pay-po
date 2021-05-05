<?php

declare(strict_types=1);

namespace Answear\PayPo\Service\Normalizer;

use Symfony\Component\Serializer\Normalizer\PropertyNormalizer as BasePropertyNormalizer;

class PropertyNormalizer extends BasePropertyNormalizer
{
    protected function isAllowedAttribute($classOrObject, $attribute, $format = null, array $context = []): bool
    {
        if (!parent::isAllowedAttribute($classOrObject, $attribute, $format, $context)) {
            return false;
        }

        try {
            $reflectionProperty = $this->getReflectionProperty($classOrObject, $attribute);
            if ($reflectionProperty->isPrivate()) {
                return false;
            }
        } catch (\ReflectionException $reflectionException) {
            return false;
        }

        return true;
    }

    /**
     * @param string|object $classOrObject
     *
     * @throws \ReflectionException
     */
    private function getReflectionProperty($classOrObject, string $attribute): \ReflectionProperty
    {
        $reflectionClass = new \ReflectionClass($classOrObject);
        while (true) {
            try {
                return $reflectionClass->getProperty($attribute);
            } catch (\ReflectionException $e) {
                if (!$reflectionClass = $reflectionClass->getParentClass()) {
                    throw $e;
                }
            }
        }
    }
}
