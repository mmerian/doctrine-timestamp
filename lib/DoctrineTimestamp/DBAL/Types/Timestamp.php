<?php
/**
 * Definition of the timestamp type for Doctrine 2
 */

namespace DoctrineTimestamp\DBAL\Types;

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
     *
     * @return string
     */
    public function getName()
    {
        return self::TIMESTAMP;
    }

    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        // Doctrine handle "version" fields as integer timestamp, and uses the
        // timestamp field type if available. So using this behaviour should be
        // the safest way to have a TIMESTAMP type field.
        $fieldDeclaration['version'] = true;

        return $platform->getDateTimeTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * Converts the timestamp to a value for database insertion
     *
     * @param mixed $value
     * @param AbstractPlatform $platform
     *
     * @return int
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof \DateTime) {
            return $value->getTimestamp();
        }
        return is_null($value) ? $value : (int)$value;
    }

    /**
     * Converts a value loaded from the database to a DateTime instance
     *
     * @param int $value
     * @param AbstractPlatform $platform
     *
     * @return \DateTime
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
	if (is_null($value)) {
		return null;
	}
        $dt = new \DateTime();
        $dt->setTimestamp($value);
        return $dt;
    }
}
