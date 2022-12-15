<?php

namespace horstoeko\orderx\tests;

use DateTime;
use horstoeko\orderx\codelists\OrderDocumentTypes;
use horstoeko\orderx\OrderProfiles;
use horstoeko\orderx\tests\TestCaseXml;
use horstoeko\orderx\OrderDocumentBuilder;

class OrderDocumentBuilderBasicTest extends TestCaseXml
{
    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$document = OrderDocumentBuilder::CreateNew(OrderProfiles::PROFILE_BASIC);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testDocumentProfile(): void
    {
        $this->assertEquals(OrderProfiles::PROFILE_BASIC, self::$document->getProfileId());
        $this->assertNotEquals(OrderProfiles::PROFILE_COMFORT, self::$document->getProfileId());
        $this->assertNotEquals(OrderProfiles::PROFILE_EXTENDED, self::$document->getProfileId());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testXmlGenerals(): void
    {
        $xml = $this->getXml();
        $namespaces = $xml->getNamespaces(true);

        $this->disableRenderXmlContent();
        $this->assertArrayHasKey("rsm", $namespaces);
        $this->assertArrayHasKey("ram", $namespaces);
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocumentContext/ram:GuidelineSpecifiedDocumentContextParameter/ram:ID', (self::$document)->getProfileDefinition()["contextparameter"]);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetIsDocumentCopy(): void
    {
        (self::$document)->setIsDocumentCopy();

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:CopyIndicator');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:CopyIndicator/udt:Indicator', 0, "true");

        (self::$document)->setIsDocumentCopy(false);

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:CopyIndicator');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:CopyIndicator/udt:Indicator', 0, "false");

        (self::$document)->setIsDocumentCopy(true);

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:CopyIndicator');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:CopyIndicator/udt:Indicator', 0, "true");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetIsTestDocument(): void
    {
        (self::$document)->setIsTestDocument();

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:CrossIndustryInvoice/rsm:ExchangedDocumentContext/ram:TestIndicator/udt:Indicator');
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentInformation(): void
    {
        (self::$document)->setDocumentInformation("471102", OrderDocumentTypes::ORDER, DateTime::createFromFormat("Ymd", "20180305"), "EUR", "Purchase Order", "de", new \DateTime(), "9", "AC");
        (self::$document)->setDocumentBusinessProcessSpecifiedDocumentContextParameter("A1");

        $this->disableRenderXmlContent();
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocumentContext/ram:BusinessProcessSpecifiedDocumentContextParameter/ram:ID', "A1");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:ID', "471102");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:TypeCode', "220");
        $this->assertXPathValueWithAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:IssueDateTime/udt:DateTimeString', "20180305", "format", "102");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:OrderCurrencyCode', "EUR");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:Name', "Purchase Order");
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:LanguageID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:StartDateTime', 0);
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:PurposeCode', "9");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:RequestedResponseTypeCode', "AC");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentNote(): void
    {
        (self::$document)->addDocumentNote('Rechnung gemäß Bestellung vom 01.03.2018.');
        (self::$document)->addDocumentNote('Lieferant GmbH', 'REG');
        (self::$document)->addDocumentNote('Additional Note', 'REG', 'CC');

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:IncludedNote/ram:Content', 0, "Rechnung gemäß Bestellung vom 01.03.2018.");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:IncludedNote/ram:Content', 1, "Lieferant GmbH");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:IncludedNote/ram:Content', 2, "Additional Note");

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:IncludedNote/ram:SubjectCode', 0, "REG");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:IncludedNote/ram:SubjectCode', 1, "REG");
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:IncludedNote/ram:SubjectCode', 2);

        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:IncludedNote/ram:ContentCode', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:IncludedNote/ram:ContentCode', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:IncludedNote/ram:ContentCode', 2);
    }
}
