<?php
/**
 * This file is part of the browscap package.
 *
 * Copyright (c) 1998-2017, Browser Capabilities Project
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);
namespace BrowscapTest\Formatter;

use Browscap\Formatter\CsvFormatter;

/**
 * Class CsvFormatterTest
 *
 * @category   BrowscapTest
 *
 * @author     Thomas Müller <mimmi20@live.de>
 */
class CsvFormatterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Browscap\Formatter\CsvFormatter
     */
    private $object;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    public function setUp() : void
    {
        $this->object = new CsvFormatter();
    }

    /**
     * tests getter for the formatter type
     *
     * @group formatter
     * @group sourcetest
     */
    public function testGetType() : void
    {
        self::assertSame('csv', $this->object->getType());
    }

    /**
     * tests setter and getter for the filter
     *
     * @group formatter
     * @group sourcetest
     */
    public function testSetGetFilter() : void
    {
        $mockFilter = $this->createMock(\Browscap\Filter\StandardFilter::class);

        $this->object->setFilter($mockFilter);
        self::assertSame($mockFilter, $this->object->getFilter());
    }

    /**
     * tests formatting a property name
     *
     * @group formatter
     * @group sourcetest
     */
    public function testFormatPropertyName() : void
    {
        self::assertSame('"text"', $this->object->formatPropertyName('text'));
    }

    /**
     * Data Provider for the test testGetPropertyType
     *
     * @return array[]
     */
    public function propertyNameTypeDataProvider()
    {
        return [
            ['Comment', 'test', '"test"'],
            ['Browser', 'test', '"test"'],
            ['Platform', 'test', '"test"'],
            ['Platform_Description', 'test', '"test"'],
            ['Device_Name', 'test', '"test"'],
            ['Device_Maker', 'test', '"test"'],
            ['RenderingEngine_Name', 'test', '"test"'],
            ['RenderingEngine_Description', 'test', '"test"'],
            ['Parent', 'test', '"test"'],
            ['Platform_Version', 'test', '"test"'],
            ['RenderingEngine_Version', 'test', '"test"'],
            ['Version', 'test', '"test"'],
            ['MajorVer', 'test', '"test"'],
            ['MinorVer', 'test', '"test"'],
            ['CssVersion', 'test', '"test"'],
            ['AolVersion', 'test', '"test"'],
            ['Alpha', 'true', '"true"'],
            ['Beta', 'false', '"false"'],
            ['Win16', 'test', '""'],
            ['Browser_Type', 'Browser', '"Browser"'],
            ['Device_Type', 'Tablet', '"Tablet"'],
            ['Device_Pointing_Method', 'mouse', '"mouse"'],
        ];
    }

    /**
     * tests formatting a property value
     *
     * @dataProvider propertyNameTypeDataProvider
     *
     * @param string $propertyName
     * @param string $inputValue
     * @param string $expectedValue
     *
     * @group formatter
     * @group sourcetest
     */
    public function testFormatPropertyValue($propertyName, $inputValue, $expectedValue) : void
    {
        $actualValue = $this->object->formatPropertyValue($inputValue, $propertyName);
        self::assertSame($expectedValue, $actualValue, "Property {$propertyName} should be {$expectedValue} (was {$actualValue})");
    }
}
