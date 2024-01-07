<?php

namespace horstoeko\orderx\tests\testcases;

use \horstoeko\orderx\tests\TestCase;
use \horstoeko\orderx\OrderDocumentJsonExporter;
use \horstoeko\orderx\OrderDocumentReader;

class OrderDocumentJsonExporterTest extends TestCase
{
    /**
     * @var OrderDocumentReader
     */
    protected static $document;

    public static function setUpBeforeClass(): void
    {
        self::$document = OrderDocumentReader::readAndGuessFromFile(dirname(__FILE__) . '/../assets/reader-order-x-comfort.xml');
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentJsonExporter::toJsonString
     */
    public function testToJsonString(): void
    {
        $exporter = new OrderDocumentJsonExporter(static::$document);
        $jsonString = $exporter->toJsonString();

        $this->assertStringStartsWith('{"ExchangedDocumentContext"', $jsonString);
        $this->assertStringContainsString('},"GuidelineSpecifiedDocumentContextParameter"', $jsonString);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentJsonExporter::toPrettyJsonString
     */
    public function testToPrettyJsonString(): void
    {
        $exporter = new OrderDocumentJsonExporter(static::$document);
        $jsonString = $exporter->toPrettyJsonString();

        $this->assertStringStartsWith("{\n    \"ExchangedDocumentContext\":", $jsonString);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentJsonExporter::toJsonObject
     */
    public function testToJsonObject(): void
    {
        $exporter = new OrderDocumentJsonExporter(static::$document);
        $jsonObject = $exporter->toJsonObject();

        $this->assertInstanceOf("stdClass", $jsonObject);
        $this->assertTrue(isset($jsonObject->ExchangedDocumentContext));
        $this->assertTrue(isset($jsonObject->ExchangedDocumentContext->GuidelineSpecifiedDocumentContextParameter));
    }
}
