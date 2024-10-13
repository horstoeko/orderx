<?php

namespace horstoeko\orderx\tests\testcases;

use horstoeko\orderx\OrderSettings;
use horstoeko\orderx\tests\TestCase;
use horstoeko\stringmanagement\FileUtils;
use horstoeko\stringmanagement\PathUtils;

class OrderSettingsTest extends TestCase
{
    /**
     * @inheritDoc
     */
    public static function tearDownAfterClass(): void
    {
        OrderSettings::setAmountDecimals(2);
        OrderSettings::setQuantityDecimals(2);
        OrderSettings::setPercentDecimals(2);
        OrderSettings::setDecimalSeparator(".");
        OrderSettings::setThousandsSeparator("");
        OrderSettings::setIccProfileFilename("sRGB_v4_ICC.icc");
    }

    public function testAmountDecimals(): void
    {
        $this->assertEquals(2, OrderSettings::getAmountDecimals());

        OrderSettings::setAmountDecimals(3);

        $this->assertEquals(3, OrderSettings::getAmountDecimals());

        $property = $this->getPrivatePropertyFromClassname(OrderSettings::class, "amountDecimals");

        $this->assertEquals(3, $property->getValue());
    }

    public function testQuantityDecimals(): void
    {
        $this->assertEquals(2, OrderSettings::getQuantityDecimals());

        OrderSettings::setQuantityDecimals(3);

        $this->assertEquals(3, OrderSettings::getQuantityDecimals());

        $property = $this->getPrivatePropertyFromClassname(OrderSettings::class, "quantityDecimals");

        $this->assertEquals(3, $property->getValue());
    }

    public function testPercentDecimals(): void
    {
        $this->assertEquals(2, OrderSettings::getPercentDecimals());

        OrderSettings::setPercentDecimals(3);

        $this->assertEquals(3, OrderSettings::getPercentDecimals());

        $property = $this->getPrivatePropertyFromClassname(OrderSettings::class, "percentDecimals");

        $this->assertEquals(3, $property->getValue());
    }

    public function testDecimalSeparator(): void
    {
        $this->assertEquals(".", OrderSettings::getDecimalSeparator());

        OrderSettings::setDecimalSeparator(",");

        $this->assertEquals(",", OrderSettings::getDecimalSeparator());

        $property = $this->getPrivatePropertyFromClassname(OrderSettings::class, "decimalSeparator");

        $this->assertEquals(",", $property->getValue());
    }

    public function testThousandsSeparator(): void
    {
        $this->assertEquals("", OrderSettings::getThousandsSeparator());

        OrderSettings::setThousandsSeparator(",");

        $this->assertEquals(",", OrderSettings::getThousandsSeparator());

        $property = $this->getPrivatePropertyFromClassname(OrderSettings::class, "thousandsSeparator");

        $this->assertEquals(",", $property->getValue());
    }

    public function testIccProfileFilename(): void
    {
        $this->assertEquals("sRGB_v4_ICC.icc", OrderSettings::getIccProfileFilename());

        OrderSettings::setIccProfileFilename("sRGB_v5_ICC.icc");

        $this->assertEquals("sRGB_v5_ICC.icc", OrderSettings::getIccProfileFilename());

        $property = $this->getPrivatePropertyFromClassname(OrderSettings::class, "iccProfileFilename");
    }

    public function testGetRootDirectory(): void
    {
        $this->assertEquals(
            realpath(dirname(__FILE__) . "/../../"),
            realpath(OrderSettings::getRootDirectory())
        );
    }

    public function testGetSourceDirectory(): void
    {
        $this->assertEquals(
            realpath(dirname(__FILE__) . "/../../src/"),
            realpath(OrderSettings::getSourceDirectory())
        );
    }

    public function testGetAssetDirectory(): void
    {
        $this->assertEquals(
            realpath(dirname(__FILE__) . "/../../src/assets/"),
            realpath(OrderSettings::getAssetDirectory())
        );
    }

    public function testGetYamlDirectory(): void
    {
        $this->assertEquals(
            realpath(dirname(__FILE__) . "/../../src/yaml/"),
            realpath(OrderSettings::getYamlDirectory())
        );
    }

    public function testGetValidationDirectory(): void
    {
        $this->assertEquals(
            realpath(dirname(__FILE__) . "/../../src/validation/"),
            realpath(OrderSettings::getValidationDirectory())
        );
    }

    public function testGetFullIccProfileFilename(): void
    {
        $expected = PathUtils::combinePathWithFile(
            realpath(dirname(__FILE__) . "/../../src/assets/"),
            "sRGB_v5_ICC.icc"
        );
        $actual = PathUtils::combinePathWithFile(
            realpath(FileUtils::getFileDirectory(OrderSettings::getFullIccProfileFilename())),
            "sRGB_v5_ICC.icc"
        );

        $this->assertEquals(
            $expected,
            $actual
        );
    }
}
