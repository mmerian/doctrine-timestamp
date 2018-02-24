<?php

namespace DoctrineTimestamp\DBAL\Types;

use DateTime;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Timestamp type for the Doctrine 2 ORM
 */
class Timestamp extends Type
{
    /**
     * Type name
     *
     * @var string
     */
    const TIMESTAMP = 'timestamp';

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return self::TIMESTAMP;
    }

    /**
     * @inheritDoc
     */
    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getIntegerTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * @inheritDoc
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof DateTime) {
            return $value->getTimestamp();
        }

        return is_null($value) ? $value : (int)$value;
    }

    /**
     * @inheritDoc
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (is_null($value)) {
            return null;
        }

        $dt = new DateTime();
        $dt->setTimestamp($value);

        return $dt;
    }
}
