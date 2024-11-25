<?php

declare(strict_types=1);

namespace App\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

abstract class AbstractEnumType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'TEXT';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return $value;
        }

        if ($value instanceof \BackedEnum) {
            return $value->value;
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->lookupName($this), ['null', self::class]);
    }

    /**
     * @param ?string $value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return $value;
        }

        if (true === enum_exists($this::getEnumsClass(), true)) {
            // 🔥 https://www.php.net/manual/en/backedenum.tryfrom.php
            return $this::getEnumsClass()::tryFrom($value);
        }

        throw ConversionException::conversionFailedFormat($value, $this->lookupName($this), $platform->getDateFormatString());
    }

    /**
     * @return class-string<\BackedEnum>
     */
    abstract public static function getEnumsClass(): string;
}
