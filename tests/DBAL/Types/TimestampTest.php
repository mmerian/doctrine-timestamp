<?php

namespace DoctrineTimestamp\Test\DBAL\Types;

use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;
use DoctrineTimestamp\DBAL\Types\Timestamp;
use Doctrine\DBAL\Platforms\MySqlPlatform;

class TimestampTest extends TestCase
{
    protected $type;

    public function setUp()
    {
        if (!Type::hasType(Timestamp::TIMESTAMP)) {
            Type::addType(Timestamp::TIMESTAMP, Timestamp::class);
        }
         
        $this->type = Type::getType(Timestamp::TIMESTAMP);
    }

    public function testGetName()
    {
        $this->assertEquals(
            Timestamp::TIMESTAMP,
            $this->type->getName()
        );
    }

    public function testGetSqlDeclaration()
    {
        $platform = $this->createMock('Doctrine\DBAL\Platforms\AbstractPlatform');

        $expected = 'some integer col definition';

        $platform
            ->expects($this->any())
            ->method('getIntegerTypeDeclarationSQL')
            ->will($this->returnValue($expected))
        ;

        $this->assertEquals(
            $expected,
            $this->type->getSqlDeclaration(array(), $platform)
        );
    }

    public function testConvertToDatabaseValueWithNullReturnsNull()
    {
        $this->assertNull(
            $this->type->convertToDatabaseValue(
                null,
                new MySqlPlatform()
            )
        );
    }

    /**
     * @expectedException Doctrine\DBAL\Types\ConversionException
     */
    public function testConvertToDatabaseValueWithInvalidTypeThrowsException()
    {
        $this->type->convertToDatabaseValue(
            'string',
            new MySqlPlatform()
        );
    }

    /**
     * @dataProvider provideSamples
     */
    public function testConvertToDatabaseValue(DateTimeInterface $input, $expected)
    {
        $this->assertEquals(
            $expected,
            $this->type->convertToDatabaseValue(
                $input,
                new MySqlPlatform()
            )
        );
    }

    public function provideSamples()
    {
        return array(
            array(
                new DateTime('1970-01-01 00:00:00 UTC'),
                0,
            ),
            array(
                new DateTime('1970-01-01 00:00:00 +01:00'),
                -3600,
            ),
            array(
                new DateTime('1970-01-01 00:00:00 -01:00'),
                3600,
            ),
        );
    }

    public function testConvertToPHPValueWithNullReturnsNull()
    {
        $this->assertNull(
            $this->type->convertToPHPValue(
                null,
                new MySqlPlatform()
            )
        );
    }

    /**
     * @dataProvider provideSamples
     */
    public function testConvertToPHPValue(DateTimeInterface $expected, $input)
    {
        $res = $this->type->convertToPHPValue(
            $input,
            new MySqlPlatform()
        );

        $this->assertEquals($expected->getTimestamp(), $res->getTimestamp());

        $res = $res->setTimezone($expected->getTimezone());
        $this->assertEquals($expected->format('c'), $res->format('c'));
    }
}
