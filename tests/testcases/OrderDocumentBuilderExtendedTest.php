<?php

namespace horstoeko\orderx\tests\testcases;

use horstoeko\orderx\codelists\OrderDocumentTypes;
use horstoeko\orderx\OrderDocumentBuilder;
use horstoeko\orderx\OrderProfiles;
use horstoeko\orderx\tests\TestCase;
use horstoeko\orderx\tests\traits\HandlesXmlTests;

class OrderDocumentBuilderExtendedTest extends TestCase
{
    use HandlesXmlTests;

    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$document = OrderDocumentBuilder::CreateNew(OrderProfiles::PROFILE_EXTENDED);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testDocumentProperties(): void
    {
        (self::$document)->initNewDocument();

        $property = $this->getPrivatePropertyFromObject(self::$document, 'orderObject');
        $this->assertNotNull($property->getValue(self::$document));
        $property = $this->getPrivatePropertyFromObject(self::$document, 'headerTradeAgreement');
        $this->assertNotNull($property->getValue(self::$document));
        $property = $this->getPrivatePropertyFromObject(self::$document, 'headerTradeDelivery');
        $this->assertNotNull($property->getValue(self::$document));
        $property = $this->getPrivatePropertyFromObject(self::$document, 'headerTradeSettlement');
        $this->assertNotNull($property->getValue(self::$document));
        $property = $this->getPrivatePropertyFromObject(self::$document, 'headerSupplyChainTradeTransaction');
        $this->assertNotNull($property->getValue(self::$document));
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetContentAsDomDocument(): void
    {
        $this->assertNotNull((self::$document)->getContentAsDomDocument());
        $this->assertEquals("DOMDocument", get_class((self::$document)->getContentAsDomDocument()));
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetContentAsDomXPath(): void
    {
        $this->assertNotNull((self::$document)->getContentAsDomXPath());
        $this->assertEquals("DOMXPath", get_class((self::$document)->getContentAsDomXPath()));
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testDocumentProfile(): void
    {
        $this->assertNotEquals(OrderProfiles::PROFILE_BASIC, self::$document->getProfileId());
        $this->assertNotEquals(OrderProfiles::PROFILE_COMFORT, self::$document->getProfileId());
        $this->assertEquals(OrderProfiles::PROFILE_EXTENDED, self::$document->getProfileId());
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
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:CopyIndicator', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:CopyIndicator', 1);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:CopyIndicator/udt:Indicator', 0, "true");

        (self::$document)->setIsDocumentCopy(false);

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:CopyIndicator', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:CopyIndicator', 1);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:CopyIndicator/udt:Indicator', 0, "false");

        (self::$document)->setIsDocumentCopy(true);

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:CopyIndicator', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:CopyIndicator', 1);
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
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocumentContext/ram:TestIndicator');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocumentContext/ram:TestIndicator/udt:Indicator', 0, "true");

        (self::$document)->setIsTestDocument(false);

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocumentContext/ram:TestIndicator');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocumentContext/ram:TestIndicator/udt:Indicator', 0, "false");

        (self::$document)->setIsTestDocument();

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocumentContext/ram:TestIndicator');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocumentContext/ram:TestIndicator/udt:Indicator', 0, "true");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentInformation(): void
    {
        (self::$document)->setDocumentInformation("471102", OrderDocumentTypes::ORDER, $this->getDummyDateTime(), "EUR", "Purchase Order", "de", $this->getDummyDateTime(), "9", "AC");
        (self::$document)->setDocumentBusinessProcessSpecifiedDocumentContextParameter("A1");

        $this->disableRenderXmlContent();
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocumentContext/ram:BusinessProcessSpecifiedDocumentContextParameter/ram:ID', "A1");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:ID', "471102");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:TypeCode', "220");
        $this->assertXPathValueWithAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:IssueDateTime/udt:DateTimeString', $this->getDummyDateTime()->format("Ymd"), "format", "102");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:OrderCurrencyCode', "EUR");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:Name', "Purchase Order");
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:LanguageID', 0);
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

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:IncludedNote/ram:ContentCode', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:IncludedNote/ram:ContentCode', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocument/ram:IncludedNote/ram:ContentCode', 2);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentSummation(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation', 0);

        (self::$document)->setDocumentSummation(100.0, 119.0, 2.00, 3.00, 100.0, 19.0);

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:LineTotalAmount', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:ChargeTotalAmount', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:AllowanceTotalAmount', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:TaxBasisTotalAmount', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:TaxTotalAmount', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:GrandTotalAmount', 0);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:LineTotalAmount', 0, "100.0");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:ChargeTotalAmount', 0, "2.0");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:AllowanceTotalAmount', 0, "3.0");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:TaxBasisTotalAmount', 0, "100.0");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:TaxTotalAmount', 0, "19.0");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:GrandTotalAmount', 0, "119.0");

        (self::$document)->setDocumentSummation(100.0);

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:LineTotalAmount', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:ChargeTotalAmount', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:AllowanceTotalAmount', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:TaxBasisTotalAmount', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:TaxTotalAmount', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:GrandTotalAmount', 0);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:LineTotalAmount', 0, "100.0");

        (self::$document)->setDocumentSummation(100.0, 119.0);

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:LineTotalAmount', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:ChargeTotalAmount', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:AllowanceTotalAmount', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:TaxBasisTotalAmount', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:TaxTotalAmount', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:GrandTotalAmount', 0);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:LineTotalAmount', 0, "100.0");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:GrandTotalAmount', 0, "119.0");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentBuyerReference(): void
    {
        (self::$document)->setDocumentBuyerReference("buyerref");

        $this->disableRenderXmlContent();
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerReference', "buyerref");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentSeller(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:ID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:Description');

        (self::$document)->setDocumentSeller("", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:ID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:Description');

        (self::$document)->setDocumentSeller("Lieferant GmbH", "549910");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:ID');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:Description');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:ID', "549910");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:Name', "Lieferant GmbH");

        (self::$document)->setDocumentSeller("Lieferant 2 GmbH", "5499102");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:ID');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:Description');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:ID', "5499102");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:Name', "Lieferant 2 GmbH");

        (self::$document)->setDocumentSeller("", "5499103");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:ID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:Description');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:ID', "5499103");

        (self::$document)->setDocumentSeller("Lieferant 2 GmbH", "5499102", "Description");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:ID');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:Name');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:Description');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:ID', "5499102");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:Name', "Lieferant 2 GmbH");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:Description', 'Description');
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentSellerGlobalId(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:GlobalID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:GlobalID', 1);

        (self::$document)->addDocumentSellerGlobalId("4000001123452", "0088");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:GlobalID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:GlobalID', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:GlobalID', 0, "4000001123452", "schemeID", "0088");

        (self::$document)->addDocumentSellerGlobalId("4000001123453", "0088");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:GlobalID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:GlobalID', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:GlobalID', 0, "4000001123452", "schemeID", "0088");
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:GlobalID', 1, "4000001123453", "schemeID", "0088");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentSellerTaxRegistration(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);

        (self::$document)->setDocumentSellerTaxRegistration("", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);

        (self::$document)->setDocumentSellerTaxRegistration("FC", "201/113/40209");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0, "201/113/40209", "schemeID", "FC");

        (self::$document)->addDocumentSellerTaxRegistration("VA", "DE123456789");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0, "201/113/40209", "schemeID", "FC");
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1, "DE123456789", "schemeID", "VA");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentSellerAddress(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:PostcodeCode');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:LineOne');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:CityName');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:CountryID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');

        (self::$document)->setDocumentSellerAddress("", "", "", "", "", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:PostcodeCode');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:LineOne');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:CityName');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:CountryID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');

        (self::$document)->setDocumentSellerAddress("Lieferantenstraße 20", "", "", "80333", "München", "DE");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:PostcodeCode');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:LineOne');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:CityName');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:CountryID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:PostcodeCode', "80333");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:LineOne', "Lieferantenstraße 20");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:CityName', "München");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:CountryID', "DE");

        (self::$document)->setDocumentSellerAddress("Lieferantenstraße 20", "Haus A", "Eingang B", "80333", "München", "DE");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:PostcodeCode');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:LineOne');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:CityName');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:CountryID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:PostcodeCode', "80333");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:LineOne', "Lieferantenstraße 20");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:LineTwo', "Haus A");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:LineThree', "Eingang B");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:CityName', "München");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:CountryID', "DE");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentSellerLegalOrganisation(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', 0);

        (self::$document)->setDocumentSellerLegalOrganisation("", "", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', 0);

        (self::$document)->setDocumentSellerLegalOrganisation("DE12345", "FC", "Lieferant AG");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', 0);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0, "DE12345", "schemeID", "FC");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', "Lieferant AG");

        (self::$document)->setDocumentSellerLegalOrganisation("DE12345", "FC", "");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', 0);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0, "DE12345", "schemeID", "FC");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentSellerContact(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:PersonName', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        (self::$document)->setDocumentSellerContact("Hans Müller", "Financials", "+49-111-2222222", "+49-111-3333333", "info@lieferant.de");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:PersonName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:PersonName', 0, "Hans Müller");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0, "Financials");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0, "+49-111-2222222");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0, "+49-111-3333333");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0, "info@lieferant.de");

        (self::$document)->setDocumentSellerContact("Hans Meier", "Bank", "+49-111-4444444", "+49-111-5555555", "info@meinseller.de");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:PersonName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:PersonName', 0, "Hans Meier");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0, "Bank");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0, "+49-111-4444444");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0, "+49-111-5555555");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0, "info@meinseller.de");

        (self::$document)->setDocumentSellerContact("Hans Müller", "Financials", "+49-111-2222222", "+49-111-3333333", "info@lieferant.de");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentSellerContact(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:PersonName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:PersonName', 0, "Hans Müller");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0, "Financials");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0, "+49-111-2222222");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0, "+49-111-3333333");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0, "info@lieferant.de");

        (self::$document)->addDocumentSellerContact("Hans Meier", "Bank", "+49-111-4444444", "+49-111-5555555", "info@meinseller.de");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:PersonName', 1, "Hans Meier");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1, "Bank");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1, "+49-111-4444444");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1, "+49-111-5555555");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1, "info@meinseller.de");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentSellerElectronicAddress(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:URIUniversalCommunication', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:URIUniversalCommunication/ram:URIID', 0);

        (self::$document)->setDocumentSellerElectronicAddress("", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:URIUniversalCommunication', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:URIUniversalCommunication/ram:URIID', 0);

        (self::$document)->setDocumentSellerElectronicAddress("EM", "someone@nowhere.all");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:URIUniversalCommunication', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:URIUniversalCommunication/ram:URIID', 0);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:URIUniversalCommunication/ram:URIID', 0, "someone@nowhere.all", "schemeID", "EM");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentBuyer(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:ID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:Description');

        (self::$document)->setDocumentSeller("", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:ID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:Description');

        (self::$document)->setDocumentBuyer("Kunden AG Mitte", "549910");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:ID');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:Description');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:ID', "549910");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:Name', "Kunden AG Mitte");

        (self::$document)->setDocumentBuyer("Kunden AG Mitte 2", "5499102");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:ID');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:Description');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:ID', "5499102");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:Name', "Kunden AG Mitte 2");

        (self::$document)->setDocumentBuyer("", "5499103");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:ID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:Description');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:ID', "5499103");

        (self::$document)->setDocumentBuyer("Kunden AG Mitte 2", "5499102", "Description");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:ID');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:Name');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:Description');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:ID', "5499102");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:Name', "Kunden AG Mitte 2");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:Description', 'Description');
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentBuyerGlobalId(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:GlobalID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:GlobalID', 1);

        (self::$document)->addDocumentBuyerGlobalId("4000001123452", "0088");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:GlobalID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:GlobalID', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:GlobalID', 0, "4000001123452", "schemeID", "0088");

        (self::$document)->addDocumentBuyerGlobalId("4000001123453", "0088");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:GlobalID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:GlobalID', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:GlobalID', 0, "4000001123452", "schemeID", "0088");
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:GlobalID', 1, "4000001123453", "schemeID", "0088");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetAndAddDocumentBuyerTaxRegistration(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);

        (self::$document)->setDocumentBuyerTaxRegistration("", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);

        (self::$document)->setDocumentBuyerTaxRegistration("FC", "201/113/40209");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0, "201/113/40209", "schemeID", "FC");

        (self::$document)->addDocumentBuyerTaxRegistration("VA", "DE123456789");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0, "201/113/40209", "schemeID", "FC");
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1, "DE123456789", "schemeID", "VA");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentBuyerAddress(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:PostcodeCode');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:LineOne');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:CityName');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:CountryID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');

        (self::$document)->setDocumentBuyerAddress("", "", "", "", "", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:PostcodeCode');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:LineOne');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:CityName');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:CountryID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');

        (self::$document)->setDocumentBuyerAddress("Kundenstrasse 15", "", "", "69876", "Frankfurt", "DE");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:PostcodeCode');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:LineOne');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:CityName');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:CountryID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:PostcodeCode', "69876");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:LineOne', "Kundenstrasse 15");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:CityName', "Frankfurt");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:CountryID', "DE");

        (self::$document)->setDocumentBuyerAddress("Kundenstrasse 15", "Haus A", "Eingang B", "69876", "Frankfurt", "DE");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:PostcodeCode');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:LineOne');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:CityName');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:CountryID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:PostcodeCode', "69876");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:LineOne', "Kundenstrasse 15");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:LineTwo', "Haus A");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:LineThree', "Eingang B");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:CityName', "Frankfurt");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:CountryID', "DE");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentBuyerLegalOrganisation(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', 0);

        (self::$document)->setDocumentBuyerLegalOrganisation("", "", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', 0);

        (self::$document)->setDocumentBuyerLegalOrganisation("DE12345", "FC", "Lieferant AG");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', 0);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0, "DE12345", "schemeID", "FC");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', "Lieferant AG");

        (self::$document)->setDocumentBuyerLegalOrganisation("DE12345", "FC", "");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', 0);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0, "DE12345", "schemeID", "FC");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentBuyerContact(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:PersonName', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        (self::$document)->setDocumentBuyerContact("Otto Müller", "Financials", "+49-111-2222222", "+49-111-3333333", "info@kunde.de");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:PersonName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:PersonName', 0, "Otto Müller");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0, "Financials");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0, "+49-111-2222222");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0, "+49-111-3333333");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0, "info@kunde.de");

        (self::$document)->setDocumentBuyerContact("Hans Meier", "Bank", "+49-111-4444444", "+49-111-5555555", "info@kunde.de");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:PersonName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:PersonName', 0, "Hans Meier");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0, "Bank");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0, "+49-111-4444444");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0, "+49-111-5555555");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0, "info@kunde.de");

        (self::$document)->setDocumentBuyerContact("Otto Müller", "Financials", "+49-111-2222222", "+49-111-3333333", "info@kunde.de");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentBuyerContact(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:PersonName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:PersonName', 0, "Otto Müller");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0, "Financials");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0, "+49-111-2222222");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0, "+49-111-3333333");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0, "info@kunde.de");

        (self::$document)->addDocumentBuyerContact("Otto Meier", "Bank", "+49-111-4444444", "+49-111-5555555", "info2@kunde2.de");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:PersonName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:PersonName', 1, "Otto Meier");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1, "Bank");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1, "+49-111-4444444");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1, "+49-111-5555555");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1, "info2@kunde2.de");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentBuyerElectronicAddress(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:URIUniversalCommunication', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:URIUniversalCommunication/ram:URIID', 0);

        (self::$document)->setDocumentBuyerElectronicAddress("", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:URIUniversalCommunication', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:URIUniversalCommunication/ram:URIID', 0);

        (self::$document)->setDocumentBuyerElectronicAddress("EM", "someone@nowhere.all");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:URIUniversalCommunication', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:URIUniversalCommunication/ram:URIID', 0);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:URIUniversalCommunication/ram:URIID', 0, "someone@nowhere.all", "schemeID", "EM");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentBuyerRequisitioner(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:ID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:Description');

        (self::$document)->setDocumentBuyerRequisitioner("", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:ID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:Description');

        (self::$document)->setDocumentBuyerRequisitioner("Kunden AG Mitte", "549910");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:ID');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:Description');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:ID', '549910');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:Name', 'Kunden AG Mitte');

        (self::$document)->setDocumentBuyerRequisitioner("Kunden AG Mitte 2", "5499102");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:ID');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:Description');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:ID', '5499102');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:Name', 'Kunden AG Mitte 2');

        (self::$document)->setDocumentBuyerRequisitioner("", "5499103");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:ID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:Description');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:ID', '5499103');

        (self::$document)->setDocumentBuyerRequisitioner("Kunden AG Mitte 2", "5499102", "Description");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:ID');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:Name');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:Description');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:ID', '5499102');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:Name', 'Kunden AG Mitte 2');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:Description', 'Description');
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentBuyerRequisitionerGlobalId(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:GlobalID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:GlobalID', 1);

        (self::$document)->addDocumentBuyerRequisitionerGlobalId("4000001123452", "0088");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:GlobalID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:GlobalID', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:GlobalID', 0, '4000001123452', 'schemeID', '0088');

        (self::$document)->addDocumentBuyerRequisitionerGlobalId("4000001123453", "0088");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:GlobalID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:GlobalID', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:GlobalID', 0, '4000001123452', 'schemeID', '0088');
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:GlobalID', 1, '4000001123453', 'schemeID', '0088');
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetAndAddDocumentBuyerRequisitionerTaxRegistration(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);

        (self::$document)->setDocumentBuyerRequisitionerTaxRegistration("", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);

        (self::$document)->setDocumentBuyerRequisitionerTaxRegistration("FC", "201/113/40209");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0, "201/113/40209", "schemeID", "FC");

        (self::$document)->addDocumentBuyerRequisitionerTaxRegistration("VA", "DE123456789");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0, "201/113/40209", "schemeID", "FC");
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1, "DE123456789", "schemeID", "VA");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentBuyerRequisitionerAddress(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:PostcodeCode');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:LineOne');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:CityName');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:CountryID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');

        (self::$document)->setDocumentBuyerRequisitionerAddress("", "", "", "", "", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:PostcodeCode');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:LineOne');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:CityName');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:CountryID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');

        (self::$document)->setDocumentBuyerRequisitionerAddress("Kundenstrasse 15", "", "", "69876", "Frankfurt", "DE");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:PostcodeCode');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:LineOne');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:CityName');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:CountryID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:PostcodeCode', "69876");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:LineOne', "Kundenstrasse 15");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:CityName', "Frankfurt");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:CountryID', "DE");

        (self::$document)->setDocumentBuyerRequisitionerAddress("Kundenstrasse 15", "Haus A", "Eingang B", "69876", "Frankfurt", "DE");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:PostcodeCode');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:LineOne');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:CityName');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:CountryID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:PostcodeCode', "69876");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:LineOne', "Kundenstrasse 15");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:LineTwo', "Haus A");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:LineThree', "Eingang B");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:CityName', "Frankfurt");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:CountryID', "DE");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentBuyerRequisitionerLegalOrganisation(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', 0);

        (self::$document)->setDocumentBuyerRequisitionerLegalOrganisation("", "", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', 0);

        (self::$document)->setDocumentBuyerRequisitionerLegalOrganisation("DE12345", "FC", "Lieferant AG");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', 0);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0, "DE12345", "schemeID", "FC");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', "Lieferant AG");

        (self::$document)->setDocumentBuyerRequisitionerLegalOrganisation("DE12347", "FC", "");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', 0);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0, "DE12347", "schemeID", "FC");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentBuyerRequisitionerContact(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:PersonName', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        (self::$document)->setDocumentBuyerRequisitionerContact("Otto Müller", "Financials", "+49-111-2222222", "+49-111-3333333", "info@kunde.de");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:PersonName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:PersonName', 0, "Otto Müller");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0, "Financials");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0, "+49-111-2222222");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0, "+49-111-3333333");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0, "info@kunde.de");

        (self::$document)->setDocumentBuyerRequisitionerContact("Hans Meier", "Bank", "+49-111-4444444", "+49-111-5555555", "info@kunde.de");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:PersonName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:PersonName', 0, "Hans Meier");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0, "Bank");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0, "+49-111-4444444");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0, "+49-111-5555555");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0, "info@kunde.de");

        (self::$document)->setDocumentBuyerRequisitionerContact("Otto Müller", "Financials", "+49-111-2222222", "+49-111-3333333", "info@kunde.de");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentBuyerRequisitionerContact(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:PersonName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:PersonName', 0, "Otto Müller");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0, "Financials");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0, "+49-111-2222222");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0, "+49-111-3333333");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0, "info@kunde.de");

        (self::$document)->addDocumentBuyerRequisitionerContact("Otto Meier", "Bank", "+49-111-4444444", "+49-111-5555555", "info2@kunde2.de");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:PersonName', 1, 'Otto Meier');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1, 'Bank');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1, '+49-111-4444444');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1, '+49-111-5555555');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1, 'info2@kunde2.de');
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentBuyerRequisitionerElectronicAddress(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:URIUniversalCommunication', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:URIUniversalCommunication/ram:URIID', 0);

        (self::$document)->setDocumentBuyerRequisitionerElectronicAddress("", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:URIUniversalCommunication', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:URIUniversalCommunication/ram:URIID', 0);

        (self::$document)->setDocumentBuyerRequisitionerElectronicAddress("EM", "someone@nowhere.all");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:URIUniversalCommunication', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:URIUniversalCommunication/ram:URIID', 0);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:URIUniversalCommunication/ram:URIID', 0, "someone@nowhere.all", "schemeID", "EM");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentDeliveryTerms(): void
    {
        (self::$document)->setDocumentDeliveryTerms("term");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ApplicableTradeDeliveryTerms/ram:DeliveryTypeCode');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ApplicableTradeDeliveryTerms/ram:DeliveryTypeCode', 'term');
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentSellerOrderReferencedDocument(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerOrderReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerOrderReferencedDocument/ram:FormattedIssueDateTime', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerOrderReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerOrderReferencedDocument/ram:FormattedIssueDateTime', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 1);

        (self::$document)->setDocumentSellerOrderReferencedDocument('');

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerOrderReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerOrderReferencedDocument/ram:FormattedIssueDateTime', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerOrderReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerOrderReferencedDocument/ram:FormattedIssueDateTime', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 1);

        (self::$document)->setDocumentSellerOrderReferencedDocument('B-1010', $this->getDummyDateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerOrderReferencedDocument/ram:IssuerAssignedID');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerOrderReferencedDocument/ram:FormattedIssueDateTime');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString');
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerOrderReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerOrderReferencedDocument/ram:FormattedIssueDateTime', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerOrderReferencedDocument/ram:IssuerAssignedID', 0, 'B-1010');
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0, $this->getDummyDateTime()->format("Ymd"), "format", "102");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentBuyerOrderReferencedDocument(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument/ram:FormattedIssueDateTime', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument/ram:FormattedIssueDateTime', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 1);

        (self::$document)->setDocumentBuyerOrderReferencedDocument('');

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument/ram:FormattedIssueDateTime', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument/ram:FormattedIssueDateTime', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 1);

        (self::$document)->setDocumentBuyerOrderReferencedDocument('O-2020', $this->getDummyDateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument/ram:FormattedIssueDateTime', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument/ram:FormattedIssueDateTime', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument/ram:IssuerAssignedID', 0, 'O-2020');
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0, $this->getDummyDateTime()->format("Ymd"), "format", "102");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentQuotationReferencedDocument(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:QuotationReferencedDocument/ram:IssuerAssignedID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:QuotationReferencedDocument/ram:FormattedIssueDateTime');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:QuotationReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString');

        (self::$document)->setDocumentQuotationReferencedDocument('');

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:QuotationReferencedDocument/ram:IssuerAssignedID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:QuotationReferencedDocument/ram:FormattedIssueDateTime');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:QuotationReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString');

        (self::$document)->setDocumentQuotationReferencedDocument('Q-2020', $this->getDummyDateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:QuotationReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:QuotationReferencedDocument/ram:FormattedIssueDateTime', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:QuotationReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:QuotationReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:QuotationReferencedDocument/ram:FormattedIssueDateTime', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:QuotationReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument/ram:IssuerAssignedID', 0, 'O-2020');
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0, $this->getDummyDateTime()->format("Ymd"), "format", "102");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentContractReferencedDocument(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:IssuerAssignedID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:FormattedIssueDateTime');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString');

        (self::$document)->setDocumentContractReferencedDocument('');

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:IssuerAssignedID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:FormattedIssueDateTime');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString');

        (self::$document)->setDocumentContractReferencedDocument('C-2020', $this->getDummyDateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:FormattedIssueDateTime', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:FormattedIssueDateTime', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:IssuerAssignedID', 0, 'C-2020');
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0, $this->getDummyDateTime()->format("Ymd"), "format", "102");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentContractReferencedDocument(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathexistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:FormattedIssueDateTime', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:FormattedIssueDateTime', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 1);

        (self::$document)->addDocumentContractReferencedDocument('C-2021', $this->getDummyDateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:IssuerAssignedID', 0, 'C-2020');
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:FormattedIssueDateTime', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:FormattedIssueDateTime', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:IssuerAssignedID', 0, 'C-2020');
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0, $this->getDummyDateTime()->format("Ymd"), "format", "102");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentRequisitionReferencedDocument(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:FormattedIssueDateTime', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:FormattedIssueDateTime', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 1);

        (self::$document)->setDocumentRequisitionReferencedDocument('');

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:FormattedIssueDateTime', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:FormattedIssueDateTime', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 1);

        (self::$document)->setDocumentRequisitionReferencedDocument('REQ-2020', $this->getDummyDateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:FormattedIssueDateTime', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:FormattedIssueDateTime', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:IssuerAssignedID', 0, 'REQ-2020');
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0, $this->getDummyDateTime()->format("Ymd"), "format", "102");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentRequisitionReferencedDocument(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:FormattedIssueDateTime', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:FormattedIssueDateTime', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 1);

        (self::$document)->addDocumentRequisitionReferencedDocument('REQ-2021', $this->getDummyDateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:FormattedIssueDateTime', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:FormattedIssueDateTime', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:IssuerAssignedID', 0, 'REQ-2020');
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0, $this->getDummyDateTime()->format("Ymd"), "format", "102");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddAdditionalReferencedDocument(): void
    {
        (self::$document)->addDocumentAdditionalReferencedDocument('TYPECODE', 'REFID', 'URIID', 'REFNAME', 'REFREFTYPECODE', $this->getDummyDateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:AdditionalReferencedDocument', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:AdditionalReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:AdditionalReferencedDocument/ram:URIID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:AdditionalReferencedDocument/ram:TypeCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:AdditionalReferencedDocument/ram:Name', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:AdditionalReferencedDocument/ram:FormattedIssueDateTime', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:AdditionalReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:AdditionalReferencedDocument/ram:IssuerAssignedID', 0, 'REFID');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:AdditionalReferencedDocument/ram:URIID', 0, 'URIID');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:AdditionalReferencedDocument/ram:TypeCode', 0, 'TYPECODE');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:AdditionalReferencedDocument/ram:Name', 0, 'REFNAME');
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:AdditionalReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0, $this->getDummyDateTime()->format("Ymd"), "format", "102");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentBlanketOrderReferencedDocument(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BlanketOrderReferencedDocument/ram:IssuerAssignedID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BlanketOrderReferencedDocument/ram:FormattedIssueDateTime');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BlanketOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString');

        (self::$document)->setDocumentBlanketOrderReferencedDocument('');

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BlanketOrderReferencedDocument/ram:IssuerAssignedID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BlanketOrderReferencedDocument/ram:FormattedIssueDateTime');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BlanketOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString');

        (self::$document)->setDocumentBlanketOrderReferencedDocument('BO-2020', $this->getDummyDateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BlanketOrderReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BlanketOrderReferencedDocument/ram:FormattedIssueDateTime', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BlanketOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BlanketOrderReferencedDocument/ram:IssuerAssignedID', 0, 'BO-2020');
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BlanketOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0, $this->getDummyDateTime()->format("Ymd"), "format", "102");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentBlanketOrderReferencedDocument(): void
    {
        (self::$document)->addDocumentBlanketOrderReferencedDocument('BO-2021', $this->getDummyDateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BlanketOrderReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BlanketOrderReferencedDocument/ram:FormattedIssueDateTime', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BlanketOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BlanketOrderReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BlanketOrderReferencedDocument/ram:FormattedIssueDateTime', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BlanketOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BlanketOrderReferencedDocument/ram:IssuerAssignedID', 0, 'BO-2020');
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BlanketOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0, $this->getDummyDateTime()->format("Ymd"), "format", "102");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPreviousOrderChangeReferencedDocument(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderChangeReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderChangeReferencedDocument/ram:FormattedIssueDateTime', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderChangeReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0);

        (self::$document)->setDocumentPreviousOrderChangeReferencedDocument('');

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderChangeReferencedDocument/ram:IssuerAssignedID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderChangeReferencedDocument/ram:FormattedIssueDateTime');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderChangeReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString');

        (self::$document)->setDocumentPreviousOrderChangeReferencedDocument('PREV-2020', $this->getDummyDateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderChangeReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderChangeReferencedDocument/ram:FormattedIssueDateTime', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderChangeReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderChangeReferencedDocument/ram:IssuerAssignedID', 0, 'PREV-2020');
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderChangeReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0, $this->getDummyDateTime()->format("Ymd"), "format", "102");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentPreviousOrderChangeReferencedDocument(): void
    {
        (self::$document)->addDocumentPreviousOrderChangeReferencedDocument('PREV-2021', $this->getDummyDateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderChangeReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderChangeReferencedDocument/ram:FormattedIssueDateTime', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderChangeReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderChangeReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderChangeReferencedDocument/ram:FormattedIssueDateTime', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderChangeReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 1);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderChangeReferencedDocument/ram:IssuerAssignedID', 0, 'PREV-2020');
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderChangeReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0, $this->getDummyDateTime()->format("Ymd"), "format", "102");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPreviousOrderResponseReferencedDocument(): void
    {
        (self::$document)->setDocumentPreviousOrderResponseReferencedDocument('');

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderResponseReferencedDocument/ram:IssuerAssignedID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderResponseReferencedDocument/ram:FormattedIssueDateTime');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderResponseReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString');

        (self::$document)->setDocumentPreviousOrderResponseReferencedDocument('PREV-RESP-2020', $this->getDummyDateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderResponseReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderResponseReferencedDocument/ram:FormattedIssueDateTime', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderResponseReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderResponseReferencedDocument/ram:IssuerAssignedID', 0, 'PREV-RESP-2020');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderResponseReferencedDocument/ram:IssuerAssignedID', 0, 'PREV-RESP-2020');
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentPreviousOrderResponseReferencedDocument(): void
    {
        (self::$document)->addDocumentPreviousOrderResponseReferencedDocument('PREV-RESP-2021', $this->getDummyDateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderResponseReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderResponseReferencedDocument/ram:FormattedIssueDateTime', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderResponseReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderResponseReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderResponseReferencedDocument/ram:FormattedIssueDateTime', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderResponseReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 1);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderResponseReferencedDocument/ram:IssuerAssignedID', 0, 'PREV-RESP-2020');
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:PreviousOrderResponseReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0, $this->getDummyDateTime()->format("Ymd"), "format", "102");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentProcuringProject()
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SpecifiedProcuringProject');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SpecifiedProcuringProject/ram:ID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SpecifiedProcuringProject/ram:Name');

        (self::$document)->setDocumentProcuringProject("", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SpecifiedProcuringProject');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SpecifiedProcuringProject/ram:ID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SpecifiedProcuringProject/ram:Name');

        (self::$document)->setDocumentProcuringProject("PRJ-1", "PRJ-NAME");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SpecifiedProcuringProject');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SpecifiedProcuringProject/ram:ID');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SpecifiedProcuringProject/ram:Name');

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SpecifiedProcuringProject/ram:ID', 0, 'PRJ-1');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SpecifiedProcuringProject/ram:Name', 0, 'PRJ-NAME');
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentUltimateCustomerOrderReferencedDocument()
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:UltimateCustomerOrderReferencedDocument');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:IssuerAssignedID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString');

        (self::$document)->setDocumentUltimateCustomerOrderReferencedDocument('ULTCUSTORDER-REF', $this->getDummyDateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:UltimateCustomerOrderReferencedDocument', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:UltimateCustomerOrderReferencedDocument', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 1);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:IssuerAssignedID', 0, 'ULTCUSTORDER-REF');
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0, $this->getDummyDateTime()->format("Ymd"), "format", "102");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentUltimateCustomerOrderReferencedDocument()
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:UltimateCustomerOrderReferencedDocument', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0);

        (self::$document)->addDocumentUltimateCustomerOrderReferencedDocument('ULTCUSTORDER-REF-2', $this->getDummyDateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:UltimateCustomerOrderReferencedDocument', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:UltimateCustomerOrderReferencedDocument', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 1);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:IssuerAssignedID', 0, 'ULTCUSTORDER-REF');
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0, $this->getDummyDateTime()->format("Ymd"), "format", "102");

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:IssuerAssignedID', 1, 'ULTCUSTORDER-REF-2');
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 1, $this->getDummyDateTime()->format("Ymd"), "format", "102");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentShipTo(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:ID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:Description');

        (self::$document)->setDocumentShipTo("", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:ID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:Description');

        (self::$document)->setDocumentShipTo("Name-1", "No-1");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:ID');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:Description');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:ID', "No-1");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:Name', "Name-1");

        (self::$document)->setDocumentShipTo("Name-2", "No-2");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:ID');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:Description');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:ID', "No-2");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:Name', "Name-2");

        (self::$document)->setDocumentShipTo("", "No-3");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:ID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:Description');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:ID', "No-3");

        (self::$document)->setDocumentShipTo("Name-2", "No-2", "Desc-2");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:ID');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:Name');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:Description');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:ID', "No-2");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:Name', "Name-2");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:Description', "Desc-2");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentShipToGlobalId(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:GlobalID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:GlobalID', 1);

        (self::$document)->addDocumentShipToGlobalId("4000001123452", "0088");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:GlobalID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:GlobalID', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:GlobalID', 0, "4000001123452", "schemeID", "0088");

        (self::$document)->addDocumentShipToGlobalId("4000001123453", "0088");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:GlobalID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:GlobalID', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:GlobalID', 0, "4000001123452", "schemeID", "0088");
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:GlobalID', 1, "4000001123453", "schemeID", "0088");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentShipToTaxRegistration(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);

        (self::$document)->setDocumentShipToTaxRegistration("", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);

        (self::$document)->setDocumentShipToTaxRegistration("FC", "201/113/40209");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0, "201/113/40209", "schemeID", "FC");

        (self::$document)->addDocumentShipToTaxRegistration("VA", "DE123456789");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0, "201/113/40209", "schemeID", "FC");
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1, "DE123456789", "schemeID", "VA");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentShipToAddress(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:PostcodeCode');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:LineOne');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:CityName');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:CountryID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');

        (self::$document)->setDocumentShipToAddress("", "", "", "", "", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:PostcodeCode');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:LineOne');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:CityName');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:CountryID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');

        (self::$document)->setDocumentShipToAddress("Street 1", "", "", "Postcode 1", "City 1", "DE");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:PostcodeCode');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:LineOne');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:CityName');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:CountryID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:PostcodeCode', "Postcode 1");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:LineOne', "Street 1");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:CityName', "City 1");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:CountryID', "DE");

        (self::$document)->setDocumentShipToAddress("Street 1", "Haus A", "Eingang B", "Postcode 1", "City 1", "DE");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:PostcodeCode');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:LineOne');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:CityName');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:CountryID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:PostcodeCode', "Postcode 1");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:LineOne', "Street 1");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:LineTwo', "Haus A");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:LineThree', "Eingang B");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:CityName', "City 1");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:PostalTradeAddress/ram:CountryID', "DE");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentShipToLegalOrganisation(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', 0);

        (self::$document)->setDocumentShipToLegalOrganisation("", "", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', 0);

        (self::$document)->setDocumentShipToLegalOrganisation("DE12345", "FC", "Name 1");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', 0);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0, "DE12345", "schemeID", "FC");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', "Name 1");

        (self::$document)->setDocumentShipToLegalOrganisation("DE12346", "FC", "");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', 0);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0, "DE12346", "schemeID", "FC");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentShipToContact(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:PersonName', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        (self::$document)->setDocumentShipToContact("Name 1", "Department 1", "+49-111-2222222", "+49-111-3333333", "mail1@nowhere.all");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:PersonName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:PersonName', 0, "Name 1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0, "Department 1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0, "+49-111-2222222");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0, "+49-111-3333333");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0, "mail1@nowhere.all");

        (self::$document)->setDocumentShipToContact("Name 2", "Department 2", "+49-111-4444444", "+49-111-5555555", "mail2@nowhere.all");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:PersonName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:PersonName', 0, "Name 2");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0, "Department 2");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0, "+49-111-4444444");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0, "+49-111-5555555");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0, "mail2@nowhere.all");

        (self::$document)->setDocumentShipToContact("Name 1", "Department 1", "+49-111-2222222", "+49-111-3333333", "mail1@nowhere.all");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentShipToContact(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:PersonName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:PersonName', 0, "Name 1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0, "Department 1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0, "+49-111-2222222");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0, "+49-111-3333333");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0, "mail1@nowhere.all");

        (self::$document)->addDocumentShipToContact("Name 3", "Department 3", "+49-111-4444444", "+49-111-5555555", "mail3@nowhere.all");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:PersonName', 1, "Name 3");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1, "Department 3");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1, "+49-111-4444444");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1, "+49-111-5555555");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1, "mail3@nowhere.all");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentShipToElectronicAddress(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:URIUniversalCommunication', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:URIUniversalCommunication/ram:URIID', 0);

        (self::$document)->setDocumentShipToElectronicAddress("", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:URIUniversalCommunication', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:URIUniversalCommunication/ram:URIID', 0);

        (self::$document)->setDocumentShipToElectronicAddress("EM", "someone@nowhere.all");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:URIUniversalCommunication', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:URIUniversalCommunication/ram:URIID', 0);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty/ram:URIUniversalCommunication/ram:URIID', 0, "someone@nowhere.all", "schemeID", "EM");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentShipFrom(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:ID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:Description');

        (self::$document)->setDocumentShipFrom("", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:ID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:Description');

        (self::$document)->setDocumentShipFrom("Name-1", "No-1");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:ID');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:Description');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:ID', "No-1");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:Name', "Name-1");

        (self::$document)->setDocumentShipFrom("Name-2", "No-2");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:ID');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:Description');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:ID', "No-2");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:Name', "Name-2");

        (self::$document)->setDocumentShipFrom("", "No-3");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:ID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:Description');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:ID', "No-3");

        (self::$document)->setDocumentShipFrom("Name-2", "No-2", "Desc-2");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:ID');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:Name');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:Description');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:ID', "No-2");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:Name', "Name-2");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:Description', "Desc-2");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentShipFromGlobalId(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:GlobalID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:GlobalID', 1);

        (self::$document)->addDocumentShipFromGlobalId("4000001123452", "0088");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:GlobalID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:GlobalID', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:GlobalID', 0, "4000001123452", "schemeID", "0088");

        (self::$document)->addDocumentShipFromGlobalId("4000001123453", "0088");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:GlobalID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:GlobalID', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:GlobalID', 0, "4000001123452", "schemeID", "0088");
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:GlobalID', 1, "4000001123453", "schemeID", "0088");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentShipFromTaxRegistration(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);

        (self::$document)->setDocumentShipFromTaxRegistration("", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);

        (self::$document)->setDocumentShipFromTaxRegistration("FC", "201/113/40209");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0, "201/113/40209", "schemeID", "FC");

        (self::$document)->addDocumentShipFromTaxRegistration("VA", "DE123456789");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0, "201/113/40209", "schemeID", "FC");
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1, "DE123456789", "schemeID", "VA");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentShipFromAddress(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:PostcodeCode');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:LineOne');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:CityName');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:CountryID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');

        (self::$document)->setDocumentShipFromAddress("", "", "", "", "", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:PostcodeCode');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:LineOne');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:CityName');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:CountryID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');

        (self::$document)->setDocumentShipFromAddress("Street 1", "", "", "Postcode 1", "City 1", "DE");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:PostcodeCode');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:LineOne');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:CityName');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:CountryID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:PostcodeCode', "Postcode 1");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:LineOne', "Street 1");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:CityName', "City 1");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:CountryID', "DE");

        (self::$document)->setDocumentShipFromAddress("Street 1", "Haus A", "Eingang B", "Postcode 1", "City 1", "DE");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:PostcodeCode');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:LineOne');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:CityName');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:CountryID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:PostcodeCode', "Postcode 1");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:LineOne', "Street 1");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:LineTwo', "Haus A");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:LineThree', "Eingang B");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:CityName', "City 1");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:PostalTradeAddress/ram:CountryID', "DE");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentShipFromLegalOrganisation(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', 0);

        (self::$document)->setDocumentShipFromLegalOrganisation("", "", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', 0);

        (self::$document)->setDocumentShipFromLegalOrganisation("DE12345", "FC", "Name 1");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', 0);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0, "DE12345", "schemeID", "FC");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', "Name 1");

        (self::$document)->setDocumentShipFromLegalOrganisation("DE12345", "FC", "");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', 0);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0, "DE12345", "schemeID", "FC");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentShipFromContact(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:PersonName', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        (self::$document)->setDocumentShipFromContact("Name 1", "Department 1", "+49-111-2222222", "+49-111-3333333", "mail1@nowhere.all");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:PersonName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:PersonName', 0, "Name 1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0, "Department 1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0, "+49-111-2222222");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0, "+49-111-3333333");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0, "mail1@nowhere.all");

        (self::$document)->setDocumentShipFromContact("Name 2", "Department 2", "+49-111-4444444", "+49-111-5555555", "mail2@nowhere.all");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:PersonName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:PersonName', 0, "Name 2");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0, "Department 2");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0, "+49-111-4444444");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0, "+49-111-5555555");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0, "mail2@nowhere.all");

        (self::$document)->setDocumentShipFromContact("Name 1", "Department 1", "+49-111-2222222", "+49-111-3333333", "mail1@nowhere.all");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentShipFromContact(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:PersonName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:PersonName', 0, "Name 1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0, "Department 1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0, "+49-111-2222222");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0, "+49-111-3333333");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0, "mail1@nowhere.all");

        (self::$document)->addDocumentShipFromContact("Name 3", "Department 3", "+49-111-4444444", "+49-111-5555555", "mail3@nowhere.all");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:PersonName', 1, "Name 3");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1, "Department 3");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1, "+49-111-4444444");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1, "+49-111-5555555");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1, "mail3@nowhere.all");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentShipFromElectronicAddress(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:URIUniversalCommunication', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:URIUniversalCommunication/ram:URIID', 0);

        (self::$document)->setDocumentShipFromElectronicAddress("", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:URIUniversalCommunication', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:URIUniversalCommunication/ram:URIID', 0);

        (self::$document)->setDocumentShipFromElectronicAddress("EM", "someone@nowhere.all");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:URIUniversalCommunication', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:URIUniversalCommunication/ram:URIID', 0);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipFromTradeParty/ram:URIUniversalCommunication/ram:URIID', 0, "someone@nowhere.all", "schemeID", "EM");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentRequestedDeliverySupplyChainEvent(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceDateTime', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceDateTime/udt:DateTimeString', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod/ram:StartDateTime', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod/ram:StartDateTime/udt:DateTimeString', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod/ram:EndDateTime', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod/ram:EndDateTime/udt:DateTimeString', 0);

        (self::$document)->setDocumentRequestedDeliverySupplyChainEvent($this->getDummyDateTime(), $this->getDummyDateTime(), $this->getDummyDateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceDateTime', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceDateTime/udt:DateTimeString', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod/ram:StartDateTime', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod/ram:StartDateTime/udt:DateTimeString', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod/ram:EndDateTime', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod/ram:EndDateTime/udt:DateTimeString', 0);

        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceDateTime/udt:DateTimeString', 0, $this->getDummyDateTime()->format("Ymd"), "format", "102");
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod/ram:StartDateTime/udt:DateTimeString', 0, $this->getDummyDateTime()->format("Ymd"), "format", "102");
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod/ram:EndDateTime/udt:DateTimeString', 0, $this->getDummyDateTime()->format("Ymd"), "format", "102");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentInvoicee(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:ID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:Description');

        (self::$document)->setDocumentInvoicee("", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:ID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:Description');

        (self::$document)->setDocumentInvoicee("Name-1", "No-1");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:ID');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:Description');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:ID', 'No-1');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:Name', 'Name-1');

        (self::$document)->setDocumentInvoicee("Name-2", "No-2");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:ID');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:Description');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:ID', 'No-2');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:Name', 'Name-2');

        (self::$document)->setDocumentInvoicee("", "No-3");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:ID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:Description');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:ID', 'No-3');

        (self::$document)->setDocumentInvoicee("Name-2", "No-2", "Desc-2");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:ID');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:Name');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:Description');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:ID', 'No-2');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:Name', 'Name-2');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:Description', 'Desc-2');
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentInvoiceeGlobalId(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:GlobalID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:GlobalID', 1);

        (self::$document)->addDocumentInvoiceeGlobalId("4000001123452", "0088");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:GlobalID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:GlobalID', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:GlobalID', 0, "4000001123452", "schemeID", "0088");

        (self::$document)->addDocumentInvoiceeGlobalId("4000001123453", "0088");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:GlobalID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:GlobalID', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:GlobalID', 0, "4000001123452", "schemeID", "0088");
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:GlobalID', 1, "4000001123453", "schemeID", "0088");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentInvoiceeTaxRegistration(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);

        (self::$document)->setDocumentInvoiceeTaxRegistration("", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);

        (self::$document)->setDocumentInvoiceeTaxRegistration("FC", "201/113/40209");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0, "201/113/40209", "schemeID", "FC");

        (self::$document)->addDocumentInvoiceeTaxRegistration("VA", "DE123456789");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0, "201/113/40209", "schemeID", "FC");
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1, "DE123456789", "schemeID", "VA");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentInvoiceeAddress(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:PostcodeCode');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:LineOne');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:CityName');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:CountryID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');

        (self::$document)->setDocumentInvoiceeAddress("", "", "", "", "", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:PostcodeCode');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:LineOne');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:CityName');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:CountryID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');

        (self::$document)->setDocumentInvoiceeAddress("Street 1", "", "", "Postcode 1", "City 1", "DE");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:PostcodeCode');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:LineOne');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:CityName');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:CountryID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:PostcodeCode', "Postcode 1");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:LineOne', "Street 1");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:CityName', "City 1");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:CountryID', "DE");

        (self::$document)->setDocumentInvoiceeAddress("Street 1", "Haus A", "Eingang B", "Postcode 1", "City 1", "DE");

        $this->disableRenderXmlContent();
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:PostcodeCode');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:LineOne');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:CityName');
        $this->assertXPathExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:CountryID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:PostcodeCode', "Postcode 1");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:LineOne', "Street 1");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:LineTwo', "Haus A");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:LineThree', "Eingang B");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:CityName', "City 1");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:PostalTradeAddress/ram:CountryID', "DE");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentInvoiceeLegalOrganisation(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', 0);

        (self::$document)->setDocumentInvoiceeLegalOrganisation("", "", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', 0);

        (self::$document)->setDocumentInvoiceeLegalOrganisation("DE12345", "FC", "Name 1");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', 0);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0, "DE12345", "schemeID", "FC");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', "Name 1");

        (self::$document)->setDocumentInvoiceeLegalOrganisation("DE12346", "FC", "");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', 0);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0, "DE12346", "schemeID", "FC");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentInvoiceeContact(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:PersonName', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        (self::$document)->setDocumentInvoiceeContact("Name 1", "Department 1", "+49-111-2222222", "+49-111-3333333", "mail1@nowhere.all");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:PersonName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:PersonName', 0, "Name 1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0, "Department 1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0, "+49-111-2222222");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0, "+49-111-3333333");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0, "mail1@nowhere.all");

        (self::$document)->setDocumentInvoiceeContact("Name 2", "Department 2", "+49-111-4444444", "+49-111-5555555", "mail2@nowhere.all");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:PersonName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:PersonName', 0, "Name 2");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0, "Department 2");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0, "+49-111-4444444");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0, "+49-111-5555555");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0, "mail2@nowhere.all");

        (self::$document)->setDocumentInvoiceeContact("Name 1", "Department 1", "+49-111-2222222", "+49-111-3333333", "mail1@nowhere.all");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentInvoiceeContact(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:PersonName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:PersonName', 0, "Name 1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0, "Department 1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0, "+49-111-2222222");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0, "mail1@nowhere.all");

        (self::$document)->addDocumentInvoiceeContact("Name 3", "Department 3", "+49-111-4444444", "+49-111-5555555", "mail3@nowhere.all");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:PersonName', 1, "Name 3");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1, "Department 3");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1, "+49-111-4444444");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1, "+49-111-5555555");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1, "mail3@nowhere.all");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentInvoiceeElectronicAddress(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:URIUniversalCommunication', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:URIUniversalCommunication/ram:URIID', 0);

        (self::$document)->setDocumentInvoiceeElectronicAddress("", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:URIUniversalCommunication', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:URIUniversalCommunication/ram:URIID', 0);

        (self::$document)->setDocumentInvoiceeElectronicAddress("EM", "someone@nowhere.all");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:URIUniversalCommunication', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:URIUniversalCommunication/ram:URIID', 0);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceeTradeParty/ram:URIUniversalCommunication/ram:URIID', 0, "someone@nowhere.all", "schemeID", "EM");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPaymentMean(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementPaymentMeans/ram:TypeCode', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementPaymentMeans/ram:Information', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementPaymentMeans/ram:TypeCode', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementPaymentMeans/ram:Information', 1);

        (self::$document)->setDocumentPaymentMean("42", "Paying information");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementPaymentMeans/ram:TypeCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementPaymentMeans/ram:Information', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementPaymentMeans/ram:TypeCode', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementPaymentMeans/ram:Information', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementPaymentMeans/ram:TypeCode', 0, '42');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementPaymentMeans/ram:Information', 0, 'Paying information');
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentPaymentMean(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementPaymentMeans/ram:TypeCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementPaymentMeans/ram:Information', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementPaymentMeans/ram:TypeCode', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementPaymentMeans/ram:Information', 1);

        (self::$document)->addDocumentPaymentMean("43", "Paying information 2");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementPaymentMeans/ram:TypeCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementPaymentMeans/ram:Information', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementPaymentMeans/ram:TypeCode', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementPaymentMeans/ram:Information', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementPaymentMeans/ram:TypeCode', 0, '42');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementPaymentMeans/ram:Information', 0, 'Paying information');
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPaymentTerm(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradePaymentTerms/ram:Description', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradePaymentTerms/ram:Description', 1);

        (self::$document)->setDocumentPaymentTerm("Payment Term 1");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradePaymentTerms', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradePaymentTerms/ram:Description', 0);

        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradePaymentTerms', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradePaymentTerms/ram:Description', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradePaymentTerms/ram:Description', 0, 'Payment Term 1');
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentPaymentTerm(): void
    {
        (self::$document)->addDocumentPaymentTerm("Payment Term 2");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradePaymentTerms', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradePaymentTerms/ram:Description', 0);

        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradePaymentTerms', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradePaymentTerms/ram:Description', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradePaymentTerms/ram:Description', 0, 'Payment Term 1');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradePaymentTerms/ram:Description', 1, 'Payment Term 2');
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentTax(): void
    {
        (self::$document)->addDocumentTax("S", "VAT", 10.0, 20.0, 30.0, "ExcReason-1", "ExcReasonCode-1", 100.0, 200.0, null);

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:CalculatedAmount', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:TypeCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:ExemptionReason', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:BasisAmount', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:LineTotalBasisAmount', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:AllowanceChargeBasisAmount', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:CategoryCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:ExemptionReasonCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:RateApplicablePercent', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:CalculatedAmount', 0, "20.0");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:TypeCode', 0, "VAT");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:ExemptionReason', 0, "ExcReason-1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:BasisAmount', 0, "10.0");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:LineTotalBasisAmount', 0, "100.0");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:AllowanceChargeBasisAmount', 0, "200.0");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:CategoryCode', 0, "S");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:ExemptionReasonCode', 0, "ExcReasonCode-1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:RateApplicablePercent', 0, "30.0");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:CalculatedAmount', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:TypeCode', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:ExemptionReason', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:BasisAmount', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:LineTotalBasisAmount', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:AllowanceChargeBasisAmount', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:CategoryCode', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:ExemptionReasonCode', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:RateApplicablePercent', 1);

        (self::$document)->addDocumentTax("S", "VAT", 100.0, 200.0, 300.0, "ExcReason-2", "ExcReasonCode-2", 1000.0, 2000.0, null);

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:CalculatedAmount', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:TypeCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:ExemptionReason', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:BasisAmount', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:LineTotalBasisAmount', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:AllowanceChargeBasisAmount', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:CategoryCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:ExemptionReasonCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:RateApplicablePercent', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:CalculatedAmount', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:TypeCode', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:ExemptionReason', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:BasisAmount', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:LineTotalBasisAmount', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:AllowanceChargeBasisAmount', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:CategoryCode', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:ExemptionReasonCode', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:RateApplicablePercent', 1);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:CalculatedAmount', 1, "200.0");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:TypeCode', 1, "VAT");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:ExemptionReason', 1, "ExcReason-2");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:BasisAmount', 1, "100.0");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:LineTotalBasisAmount', 1, "1000.0");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:AllowanceChargeBasisAmount', 1, "2000.0");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:CategoryCode', 1, "S");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:ExemptionReasonCode', 1, "ExcReasonCode-2");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:RateApplicablePercent', 1, "300.0");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentAllowanceCharge(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge', 1);

        (self::$document)->setDocumentAllowanceCharge(10, false, "S", "VAT", 1, 1, 10, 100, 1, "C62", "RC", "Reason");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge', 1);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ChargeIndicator', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ChargeIndicator/udt:Indicator', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:SequenceNumeric', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CalculationPercent', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:BasisAmount', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ActualAmount', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ReasonCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:Reason', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CategoryTradeTax', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CategoryTradeTax/ram:TypeCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CategoryTradeTax/ram:CategoryCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CategoryTradeTax/ram:RateApplicablePercent', 0);

        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ChargeIndicator', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ChargeIndicator/udt:Indicator', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:SequenceNumeric', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CalculationPercent', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:BasisAmount', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ActualAmount', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ReasonCode', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:Reason', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CategoryTradeTax', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CategoryTradeTax/ram:TypeCode', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CategoryTradeTax/ram:CategoryCode', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CategoryTradeTax/ram:RateApplicablePercent', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ChargeIndicator/udt:Indicator', 0, 'false');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:SequenceNumeric', 0, '1.0');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CalculationPercent', 0, '10.00');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:BasisAmount', 0, '100.00');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ActualAmount', 0, '10.00');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ReasonCode', 0, 'RC');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:Reason', 0, 'Reason');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CategoryTradeTax/ram:TypeCode', 0, 'VAT');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CategoryTradeTax/ram:CategoryCode', 0, 'S');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CategoryTradeTax/ram:RateApplicablePercent', 0, '1.00');
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentAllowanceCharge(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge', 1);

        (self::$document)->addDocumentAllowanceCharge(10, false, "S", "VAT", 1, 1, 10, 100, 1, "C62", "RC2", "Reason-2");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge', 1);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ChargeIndicator', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ChargeIndicator/udt:Indicator', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:SequenceNumeric', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CalculationPercent', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:BasisAmount', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ActualAmount', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ReasonCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:Reason', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CategoryTradeTax', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CategoryTradeTax/ram:TypeCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CategoryTradeTax/ram:CategoryCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CategoryTradeTax/ram:RateApplicablePercent', 0);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ChargeIndicator', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ChargeIndicator/udt:Indicator', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:SequenceNumeric', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CalculationPercent', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:BasisAmount', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ActualAmount', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ReasonCode', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:Reason', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CategoryTradeTax', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CategoryTradeTax/ram:TypeCode', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CategoryTradeTax/ram:CategoryCode', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CategoryTradeTax/ram:RateApplicablePercent', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ChargeIndicator/udt:Indicator', 0, 'false');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:SequenceNumeric', 0, '1.0');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CalculationPercent', 0, '10.00');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:BasisAmount', 0, '100.00');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ActualAmount', 0, '10.00');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ReasonCode', 0, 'RC');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:Reason', 0, 'Reason');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CategoryTradeTax/ram:TypeCode', 0, 'VAT');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CategoryTradeTax/ram:CategoryCode', 0, 'S');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CategoryTradeTax/ram:RateApplicablePercent', 0, '1.00');

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ChargeIndicator/udt:Indicator', 1, 'false');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:SequenceNumeric', 1, '1.0');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CalculationPercent', 1, '10.00');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:BasisAmount', 1, '100.00');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ActualAmount', 1, '10.00');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ReasonCode', 1, 'RC2');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:Reason', 1, 'Reason-2');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CategoryTradeTax/ram:TypeCode', 1, 'VAT');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CategoryTradeTax/ram:CategoryCode', 1, 'S');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CategoryTradeTax/ram:RateApplicablePercent', 1, '1.00');
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentReceivableSpecifiedTradeAccountingAccount(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ReceivableSpecifiedTradeAccountingAccount', 0);

        (self::$document)->setDocumentReceivableSpecifiedTradeAccountingAccount('ACC', 'ACCTYPE');

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ReceivableSpecifiedTradeAccountingAccount', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ReceivableSpecifiedTradeAccountingAccount/ram:ID', 0);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ReceivableSpecifiedTradeAccountingAccount/ram:ID', 0, 'ACC');
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ReceivableSpecifiedTradeAccountingAccount/ram:TypeCode', 0);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ReceivableSpecifiedTradeAccountingAccount/ram:TypeCode', 0, "ACCTYPE");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddNewPosition(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 1);

        (self::$document)->addNewPosition("1", "LSC");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:AssociatedDocumentLineDocument', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:AssociatedDocumentLineDocument/ram:LineID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:AssociatedDocumentLineDocument/ram:LineStatusCode', 0);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:AssociatedDocumentLineDocument/ram:LineID', 0, "1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:AssociatedDocumentLineDocument/ram:LineStatusCode', 0, "LSC");

        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 1);

        (self::$document)->addNewPosition("2", "LSC2");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:AssociatedDocumentLineDocument', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:AssociatedDocumentLineDocument/ram:LineID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:AssociatedDocumentLineDocument/ram:LineStatusCode', 0);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:AssociatedDocumentLineDocument/ram:LineID', 0, "1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:AssociatedDocumentLineDocument/ram:LineStatusCode', 0, "LSC");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:AssociatedDocumentLineDocument', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:AssociatedDocumentLineDocument/ram:LineID', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:AssociatedDocumentLineDocument/ram:LineStatusCode', 1);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:AssociatedDocumentLineDocument/ram:LineID', 1, "2");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:AssociatedDocumentLineDocument/ram:LineStatusCode', 1, "LSC2");

        (self::$document)->removeLatestPosition();

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 1);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPositionNote(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);

        (self::$document)->setDocumentPositionNote("Content", "CC", "SC");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:AssociatedDocumentLineDocument', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:AssociatedDocumentLineDocument/ram:IncludedNote', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:AssociatedDocumentLineDocument/ram:IncludedNote/ram:Content', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:AssociatedDocumentLineDocument/ram:IncludedNote/ram:SubjectCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:AssociatedDocumentLineDocument/ram:IncludedNote/ram:ContentCode', 0);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:AssociatedDocumentLineDocument/ram:IncludedNote/ram:Content', 0, "Content");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:AssociatedDocumentLineDocument/ram:IncludedNote/ram:SubjectCode', 0, "SC");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:AssociatedDocumentLineDocument/ram:IncludedNote/ram:ContentCode', 0, "CC");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPositionProductDetails(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct', 1);

        (self::$document)->setDocumentPositionProductDetails("name", "description", "sellerid", "buyerid", "0088", "12345", "batchid", "brandname");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct', 1);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:GlobalID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:SellerAssignedID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:BuyerAssignedID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:Name', 0);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:GlobalID', 0, "12345", "schemeID", "0088");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:SellerAssignedID', 0, "sellerid");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:BuyerAssignedID', 0, "buyerid");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:Name', 0, "name");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPositionProductCharacteristic(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic', 1);

        (self::$document)->setDocumentPositionProductCharacteristic('desc-1', 'value-1', 'TC1', 1, "C62");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic', 1);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic/ram:TypeCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic/ram:Description', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic/ram:Value', 0);

        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic/ram:TypeCode', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic/ram:Description', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic/ram:Value', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic/ram:TypeCode', 0, "TC1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic/ram:Description', 0, "desc-1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic/ram:Value', 0, "value-1");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentPositionProductCharacteristic(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic', 1);

        (self::$document)->addDocumentPositionProductCharacteristic('desc-2', 'value-2', 'TC2', 1, "C62");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic', 1);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic/ram:TypeCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic/ram:Description', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic/ram:Value', 0);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic/ram:TypeCode', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic/ram:Description', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic/ram:Value', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic/ram:TypeCode', 0, "TC1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic/ram:Description', 0, "desc-1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic/ram:Value', 0, "value-1");

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic/ram:TypeCode', 1, "TC2");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic/ram:Description', 1, "desc-2");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic/ram:Value', 1, "value-2");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPositionProductClassification(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:DesignatedProductClassification', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:DesignatedProductClassification', 1);

        (self::$document)->setDocumentPositionProductClassification("CC1", "Classname-1", 'LIST1', 'LISTVERDION1');

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:DesignatedProductClassification', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:DesignatedProductClassification', 1);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:DesignatedProductClassification/ram:ClassCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:DesignatedProductClassification/ram:ClassName', 0);

        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:DesignatedProductClassification/ram:ClassCode', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:DesignatedProductClassification/ram:ClassName', 1);

        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:DesignatedProductClassification/ram:ClassCode', 0, "CC1", "listID", "LIST1");
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:DesignatedProductClassification/ram:ClassCode', 0, "CC1", "listVersionID", "LISTVERDION1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:DesignatedProductClassification/ram:ClassName', 0, "Classname-1");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentPositionProductClassification(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:DesignatedProductClassification', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:DesignatedProductClassification', 1);

        (self::$document)->addDocumentPositionProductClassification("CC2", "Classname-2", 'LIST2', 'LISTVERDION2');

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:DesignatedProductClassification', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:DesignatedProductClassification', 1);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:DesignatedProductClassification/ram:ClassCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:DesignatedProductClassification/ram:ClassName', 0);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:DesignatedProductClassification/ram:ClassCode', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:DesignatedProductClassification/ram:ClassName', 1);

        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:DesignatedProductClassification/ram:ClassCode', 0, "CC1", "listID", "LIST1");
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:DesignatedProductClassification/ram:ClassCode', 0, "CC1", "listVersionID", "LISTVERDION1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:DesignatedProductClassification/ram:ClassName', 0, "Classname-1");

        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:DesignatedProductClassification/ram:ClassCode', 1, "CC2", "listID", "LIST2");
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:DesignatedProductClassification/ram:ClassCode', 1, "CC2", "listVersionID", "LISTVERDION2");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:DesignatedProductClassification/ram:ClassName', 1, "Classname-2");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPositionProductInstance(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:IndividualTradeProductInstance', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:IndividualTradeProductInstance', 1);

        (self::$document)->setDocumentPositionProductInstance("BATCH1", "SERIAL1");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:IndividualTradeProductInstance', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:IndividualTradeProductInstance', 1);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:IndividualTradeProductInstance/ram:BatchID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:IndividualTradeProductInstance/ram:SerialID', 0);

        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:IndividualTradeProductInstance/ram:BatchID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:IndividualTradeProductInstance/ram:SerialID', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:IndividualTradeProductInstance/ram:BatchID', 0, "BATCH1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:IndividualTradeProductInstance/ram:SerialID', 0, "SERIAL1");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentPositionProductInstance(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:IndividualTradeProductInstance', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:IndividualTradeProductInstance', 1);

        (self::$document)->addDocumentPositionProductInstance("BATCH2", "SERIAL2");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:IndividualTradeProductInstance', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:IndividualTradeProductInstance', 1);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:IndividualTradeProductInstance/ram:BatchID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:IndividualTradeProductInstance/ram:SerialID', 0);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:IndividualTradeProductInstance/ram:BatchID', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:IndividualTradeProductInstance/ram:SerialID', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:IndividualTradeProductInstance/ram:BatchID', 0, "BATCH1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:IndividualTradeProductInstance/ram:SerialID', 0, "SERIAL1");

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:IndividualTradeProductInstance/ram:BatchID', 1, "BATCH2");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:IndividualTradeProductInstance/ram:SerialID', 1, "SERIAL2");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPositionApplicableSupplyChainPackaging(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableSupplyChainPackaging', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableSupplyChainPackaging', 1);

        (self::$document)->setDocumentPositionSupplyChainPackaging("TC-1", 1, "MTR", 2, "MTR", 3, "MTR");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableSupplyChainPackaging', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableSupplyChainPackaging', 1);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableSupplyChainPackaging/ram:TypeCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableSupplyChainPackaging/ram:LinearSpatialDimension', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableSupplyChainPackaging/ram:LinearSpatialDimension/ram:WidthMeasure', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableSupplyChainPackaging/ram:LinearSpatialDimension/ram:LengthMeasure', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableSupplyChainPackaging/ram:LinearSpatialDimension/ram:HeightMeasure', 0);

        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableSupplyChainPackaging/ram:TypeCode', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableSupplyChainPackaging/ram:LinearSpatialDimension', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableSupplyChainPackaging/ram:WidthMeasure', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableSupplyChainPackaging/ram:LengthMeasure', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableSupplyChainPackaging/ram:HeightMeasure', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableSupplyChainPackaging/ram:TypeCode', 0, "TC-1");
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableSupplyChainPackaging/ram:LinearSpatialDimension/ram:WidthMeasure', 0, "1", "unitCode", "MTR");
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableSupplyChainPackaging/ram:LinearSpatialDimension/ram:LengthMeasure', 0, "2", "unitCode", "MTR");
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:ApplicableSupplyChainPackaging/ram:LinearSpatialDimension/ram:HeightMeasure', 0, "3", "unitCode", "MTR");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPositionProductOriginTradeCountry(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:OriginTradeCountry', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:OriginTradeCountry/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:OriginTradeCountry', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:OriginTradeCountry/ram:ID', 1);

        (self::$document)->setDocumentPositionProductOriginTradeCountry("DE");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:OriginTradeCountry', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:OriginTradeCountry/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:OriginTradeCountry', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:OriginTradeCountry/ram:ID', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:OriginTradeCountry/ram:ID', 0, 'DE');
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPositionProductReferencedDocument(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument', 1);

        (self::$document)->setDocumentPositionProductReferencedDocument("ID1", "TC1", "URIID1", "Name-1");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument', 1);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:URIID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:LineID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:TypeCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:Name', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:ReferenceTypeCode', 0);

        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:URIID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:LineID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:TypeCode', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:Name', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:ReferenceTypeCode', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:IssuerAssignedID', 0, "ID1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:URIID', 0, "URIID1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:TypeCode', 0, "TC1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:Name', 0, "Name-1");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentPositionProductReferencedDocument(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument', 1);

        (self::$document)->addDocumentPositionProductReferencedDocument("ID2", "TC2", "URIID2", "Name-2");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument', 1);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:URIID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:LineID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:TypeCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:Name', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:ReferenceTypeCode', 0);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:URIID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:LineID', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:TypeCode', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:Name', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:ReferenceTypeCode', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:IssuerAssignedID', 0, "ID1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:URIID', 0, "URIID1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:TypeCode', 0, "TC1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:Name', 0, "Name-1");

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:IssuerAssignedID', 1, "ID2");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:URIID', 1, "URIID2");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:TypeCode', 1, "TC2");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedTradeProduct/ram:AdditionalReferenceReferencedDocument/ram:Name', 1, "Name-2");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentPositionAdditionalReferencedDocument(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument', 1);

        (self::$document)->addDocumentPositionAdditionalReferencedDocument("ID1", "TC1", "URIID1", "1", "Name-1", "REFTC-1", $this->getDummyDateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument', 1);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:URIID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:LineID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:TypeCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:Name', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:ReferenceTypeCode', 0);

        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:URIID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:LineID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:TypeCode', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:Name', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:ReferenceTypeCode', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:IssuerAssignedID', 0, "ID1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:URIID', 0, "URIID1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:LineID', 0, "1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:TypeCode', 0, "TC1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:Name', 0, "Name-1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:ReferenceTypeCode', 0, "REFTC-1");

        (self::$document)->addDocumentPositionAdditionalReferencedDocument("ID2", "TC2", "URIID2", "2", "Name-2", "REFTC-2", $this->getDummyDateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument', 1);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:URIID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:LineID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:TypeCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:Name', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:ReferenceTypeCode', 0);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:URIID', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:LineID', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:TypeCode', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:Name', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:ReferenceTypeCode', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:IssuerAssignedID', 0, "ID1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:URIID', 0, "URIID1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:LineID', 0, "1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:TypeCode', 0, "TC1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:Name', 0, "Name-1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:ReferenceTypeCode', 0, "REFTC-1");

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:IssuerAssignedID', 1, "ID2");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:URIID', 1, "URIID2");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:LineID', 1, "2");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:TypeCode', 1, "TC2");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:Name', 1, "Name-2");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:AdditionalReferencedDocument/ram:ReferenceTypeCode', 1, "REFTC-2");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPositionBuyerOrderReferencedDocument(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:BuyerOrderReferencedDocument', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:BuyerOrderReferencedDocument', 1);

        (self::$document)->setDocumentPositionBuyerOrderReferencedDocument("1");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:BuyerOrderReferencedDocument', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:BuyerOrderReferencedDocument/ram:LineID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:BuyerOrderReferencedDocument', 1);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:BuyerOrderReferencedDocument/ram:LineID', 0, "1");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPositionQuotationReferencedDocument(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:QuotationReferencedDocument', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:QuotationReferencedDocument', 1);

        (self::$document)->setDocumentPositionQuotationReferencedDocument("QUOTE-1", "1");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:QuotationReferencedDocument', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:QuotationReferencedDocument', 1);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:QuotationReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:QuotationReferencedDocument/ram:LineID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:QuotationReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:QuotationReferencedDocument/ram:LineID', 1);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:QuotationReferencedDocument/ram:IssuerAssignedID', 0, "QUOTE-1");
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:QuotationReferencedDocument/ram:LineID', 0);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPositionGrossPrice(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice', 1);

        (self::$document)->setDocumentPositionGrossPrice(100.0, 1, "C62");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice', 1);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice/ram:ChargeAmount', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice/ram:BasisQuantity', 0);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice/ram:ChargeAmount', 0, "100.0");
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice/ram:BasisQuantity', 0, "1.0", "unitCode", "C62");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentPositionGrossPriceAllowanceCharge(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice/ram:AppliedTradeAllowanceCharge', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice/ram:AppliedTradeAllowanceCharge', 1);

        (self::$document)->addDocumentPositionGrossPriceAllowanceCharge(10, false, 0, 0, "Reason-1", null, null, null, null, null, null, "RC1");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice/ram:AppliedTradeAllowanceCharge', 0);

        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice/ram:AppliedTradeAllowanceCharge', 1);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice/ram:AppliedTradeAllowanceCharge/ram:ChargeIndicator', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice/ram:AppliedTradeAllowanceCharge/ram:ChargeIndicator/udt:Indicator', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice/ram:AppliedTradeAllowanceCharge/ram:ActualAmount', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice/ram:AppliedTradeAllowanceCharge/ram:ReasonCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice/ram:AppliedTradeAllowanceCharge/ram:Reason', 0);

        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice/ram:AppliedTradeAllowanceCharge/ram:ChargeIndicator/udt:Indicator', 0, "false");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice/ram:AppliedTradeAllowanceCharge/ram:ActualAmount', 0, "10.00");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice/ram:AppliedTradeAllowanceCharge/ram:ReasonCode', 0, "RC1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice/ram:AppliedTradeAllowanceCharge/ram:Reason', 0, "Reason-1");

        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice/ram:AppliedTradeAllowanceCharge/ram:ChargeIndicator', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice/ram:AppliedTradeAllowanceCharge/ram:ChargeIndicator/udt:Indicator', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice/ram:AppliedTradeAllowanceCharge/ram:ActualAmount', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice/ram:AppliedTradeAllowanceCharge/ram:ReasonCode', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice/ram:AppliedTradeAllowanceCharge/ram:Reason', 1);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPositionNetPrice(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice', 1);

        (self::$document)->setDocumentPositionNetPrice(100.0, 1, "C62");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice', 1);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:ChargeAmount', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:BasisQuantity', 0);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:ChargeAmount', 0, "100.00");
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:BasisQuantity', 0, "1.00", "unitCode", "C62");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPositionNetPriceTax(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax', 1);

        (self::$document)->setDocumentPositionNetPriceTax("S", "VAT", 19.0, 1.90, "Reason-1", "RC1");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax/ram:CalculatedAmount', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax/ram:TypeCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax/ram:ExemptionReason', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax/ram:CategoryCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax/ram:ExemptionReasonCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax/ram:RateApplicablePercent', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax', 1);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax/ram:CalculatedAmount', 0, "1.90");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax/ram:TypeCode', 0, "VAT");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax/ram:ExemptionReason', 0, "Reason-1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax/ram:CategoryCode', 0, "S");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax/ram:ExemptionReasonCode', 0, "RC1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax/ram:RateApplicablePercent', 0, "19.00");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentPositionNetPriceTax(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax/ram:CalculatedAmount', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax/ram:TypeCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax/ram:ExemptionReason', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax/ram:CategoryCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax/ram:ExemptionReasonCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax/ram:RateApplicablePercent', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax', 1);

        (self::$document)->addDocumentPositionNetPriceTax("S", "VAT", 19.0, 1.90, "Reason-2", "RC2");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax', 1);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax/ram:CalculatedAmount', 1, "1.90");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax/ram:TypeCode', 1, "VAT");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax/ram:ExemptionReason', 1, "Reason-2");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax/ram:CategoryCode', 1, "S");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax/ram:ExemptionReasonCode', 1, "RC2");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice/ram:IncludedTradeTax/ram:RateApplicablePercent', 1, "19.00");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPositionCatalogueReferencedDocument(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:CatalogueReferencedDocument', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:CatalogueReferencedDocument', 1);

        (self::$document)->setDocumentPositionCatalogueReferencedDocument("REFID-1", "REDLINEID-1", $this->getDummyDateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:CatalogueReferencedDocument', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:CatalogueReferencedDocument', 1);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:CatalogueReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:CatalogueReferencedDocument/ram:LineID', 0);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:CatalogueReferencedDocument/ram:IssuerAssignedID', 0, "REFID-1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:CatalogueReferencedDocument/ram:LineID', 0, "REDLINEID-1");

        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:CatalogueReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:CatalogueReferencedDocument/ram:LineID', 1);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentPositionCatalogueReferencedDocument(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:CatalogueReferencedDocument', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:CatalogueReferencedDocument', 1);

        (self::$document)->addDocumentPositionCatalogueReferencedDocument("REFID-2", "REDLINEID-2", $this->getDummyDateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:CatalogueReferencedDocument', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:CatalogueReferencedDocument', 1);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:CatalogueReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:CatalogueReferencedDocument/ram:LineID', 0);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:CatalogueReferencedDocument/ram:IssuerAssignedID', 0, "REFID-1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:CatalogueReferencedDocument/ram:LineID', 0, "REDLINEID-1");

        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:CatalogueReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:CatalogueReferencedDocument/ram:LineID', 1);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPositionBlanketOrderReferencedDocument(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:BlanketOrderReferencedDocument', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:BlanketOrderReferencedDocument', 1);

        (self::$document)->setDocumentPositionBlanketOrderReferencedDocument("10");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:BlanketOrderReferencedDocument', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:BlanketOrderReferencedDocument/ram:LineID', 0);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:BlanketOrderReferencedDocument/ram:LineID', 0, "10");
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:BlanketOrderReferencedDocument', 1);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPositionUltimateCustomerOrderReferencedDocument(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:UltimateCustomerOrderReferencedDocument');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:IssuerAssignedID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:LineID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString');

        (self::$document)->setDocumentPositionUltimateCustomerOrderReferencedDocument('ULTCUSTORDER-REF-2', "100", $this->getDummyDateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:UltimateCustomerOrderReferencedDocument', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:LineID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:UltimateCustomerOrderReferencedDocument', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:LineID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 1);

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:IssuerAssignedID', 0, 'ULTCUSTORDER-REF-2');
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:LineID', 0, '100');
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0, $this->getDummyDateTime()->format("Ymd"), "format", "102");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentPositionUltimateCustomerOrderReferencedDocument(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:UltimateCustomerOrderReferencedDocument', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:LineID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0);

        (self::$document)->addDocumentPositionUltimateCustomerOrderReferencedDocument('ULTCUSTORDER-REF-2', "100", $this->getDummyDateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:UltimateCustomerOrderReferencedDocument', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:LineID', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 0);

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:UltimateCustomerOrderReferencedDocument', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:LineID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeAgreement/ram:UltimateCustomerOrderReferencedDocument/ram:FormattedIssueDateTime/qdt:DateTimeString', 1);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPositionPartialDelivery(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:PartialDeliveryAllowedIndicator', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:PartialDeliveryAllowedIndicator', 1);

        (self::$document)->setDocumentPositionPartialDelivery(true);

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:PartialDeliveryAllowedIndicator', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:PartialDeliveryAllowedIndicator/udt:Indicator', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:PartialDeliveryAllowedIndicator', 1);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:PartialDeliveryAllowedIndicator/udt:Indicator', 0, "true");

        (self::$document)->setDocumentPositionPartialDelivery(false);

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:PartialDeliveryAllowedIndicator', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:PartialDeliveryAllowedIndicator/udt:Indicator', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:PartialDeliveryAllowedIndicator', 1);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:PartialDeliveryAllowedIndicator/udt:Indicator', 0, "false");

        (self::$document)->setDocumentPositionPartialDelivery();

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:PartialDeliveryAllowedIndicator', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:PartialDeliveryAllowedIndicator/udt:Indicator', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:PartialDeliveryAllowedIndicator', 1);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:PartialDeliveryAllowedIndicator/udt:Indicator', 0, "false");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPositionDeliverReqQuantity(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedQuantity', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedQuantity', 1);

        (self::$document)->setDocumentPositionDeliverReqQuantity(10.0, "C62");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedQuantity', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedQuantity', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedQuantity', 0, "10.0", "unitCode", "C62");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPositionDeliverPackageQuantity(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:PackageQuantity', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:PackageQuantity', 1);

        (self::$document)->setDocumentPositionDeliverPackageQuantity(1.0, "C62");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:PackageQuantity', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:PackageQuantity', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:PackageQuantity', 0, "1.0", "unitCode", "C62");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPositionDeliverPerPackageQuantity(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:PerPackageUnitQuantity', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:PerPackageUnitQuantity', 1);

        (self::$document)->setDocumentPositionDeliverPerPackageQuantity(1.0, "C62");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:PerPackageUnitQuantity', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:PerPackageUnitQuantity', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:PerPackageUnitQuantity', 0, "1.0", "unitCode", "C62");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPositionDeliverAgreedQuantity(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:AgreedQuantity', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:AgreedQuantity', 1);

        (self::$document)->setDocumentPositionDeliverAgreedQuantity(1.0, "C62");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:AgreedQuantity', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:AgreedQuantity', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:AgreedQuantity', 0, "1.0", "unitCode", "C62");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPositionRequestedDeliverySupplyChainEvent(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent', 1);

        (self::$document)->setDocumentPositionRequestedDeliverySupplyChainEvent($this->getDummyDateTime(), $this->getDummyDateTime(), $this->getDummyDateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent', 1);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceDateTime', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceDateTime/udt:DateTimeString', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod/ram:StartDateTime', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod/ram:StartDateTime/udt:DateTimeString', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod/ram:EndDateTime', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod/ram:EndDateTime/udt:DateTimeString', 0);

        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceDateTime', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceDateTime/udt:DateTimeString', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod/ram:StartDateTime', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod/ram:StartDateTime/udt:DateTimeString', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod/ram:EndDateTime', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod/ram:EndDateTime/udt:DateTimeString', 1);

        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceDateTime/udt:DateTimeString', 0, $this->getDummyDateTime()->format('Ymd'), "format", "102");
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod/ram:StartDateTime/udt:DateTimeString', 0, $this->getDummyDateTime()->format('Ymd'), "format", "102");
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod/ram:EndDateTime/udt:DateTimeString', 0, $this->getDummyDateTime()->format('Ymd'), "format", "102");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentPositionRequestedDeliverySupplyChainEvent(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent', 1);

        (self::$document)->addDocumentPositionRequestedDeliverySupplyChainEvent($this->getDummyDateTime(), $this->getDummyDateTime(), $this->getDummyDateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent', 1);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceDateTime', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceDateTime/udt:DateTimeString', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod/ram:StartDateTime', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod/ram:StartDateTime/udt:DateTimeString', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod/ram:EndDateTime', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod/ram:EndDateTime/udt:DateTimeString', 0);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceDateTime', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceDateTime/udt:DateTimeString', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod/ram:StartDateTime', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod/ram:StartDateTime/udt:DateTimeString', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod/ram:EndDateTime', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeDelivery/ram:RequestedDeliverySupplyChainEvent/ram:OccurrenceSpecifiedPeriod/ram:EndDateTime/udt:DateTimeString', 1);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPositionTax(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax', 1);

        (self::$document)->setDocumentPositionTax("S", "VAT", 19.00, 10.00, "Reason-1", "RC1");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax', 1);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax/ram:TypeCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax/ram:CategoryCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax/ram:RateApplicablePercent', 0);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax/ram:TypeCode', 0, "VAT");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax/ram:CategoryCode', 0, "S");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax/ram:RateApplicablePercent', 0, "19.0");

        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax/ram:TypeCode', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax/ram:CategoryCode', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax/ram:RateApplicablePercent', 1);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentPositionTax(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax', 1);

        (self::$document)->addDocumentPositionTax("S", "VAT", 19.00, 10.00, "Reason-2", "RC2");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax', 1);

        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax/ram:TypeCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax/ram:CategoryCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax/ram:RateApplicablePercent', 0);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax/ram:TypeCode', 0, "VAT");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax/ram:CategoryCode', 0, "S");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax/ram:RateApplicablePercent', 0, "19.0");

        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax/ram:TypeCode', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax/ram:CategoryCode', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax/ram:RateApplicablePercent', 1);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPositionAllowanceCharge(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge', 1);

        (self::$document)->setDocumentPositionAllowanceCharge(1.00, true, 1.00, 100.0, "RC1", "Reason-1");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge', 1);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ChargeIndicator/udt:Indicator', 0, "true");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CalculationPercent', 0, "1.0");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:BasisAmount', 0, "100.0");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ActualAmount', 0, "1.0");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ReasonCode', 0, "RC1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:Reason', 0, "Reason-1");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentPositionAllowanceCharge(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge', 1);

        (self::$document)->addDocumentPositionAllowanceCharge(1.00, true, 1.00, 100.0, "RC2", "Reason-2");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ChargeIndicator/udt:Indicator', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CalculationPercent', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:BasisAmount', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ActualAmount', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ReasonCode', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:Reason', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ChargeIndicator/udt:Indicator', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CalculationPercent', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:BasisAmount', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ActualAmount', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ReasonCode', 1);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:Reason', 1);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ChargeIndicator/udt:Indicator', 0, "true");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CalculationPercent', 0, "1.0");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:BasisAmount', 0, "100.0");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ActualAmount', 0, "1.0");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ReasonCode', 0, "RC1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:Reason', 0, "Reason-1");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ChargeIndicator/udt:Indicator', 1, "true");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:CalculationPercent', 1, "1.0");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:BasisAmount', 1, "100.0");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ActualAmount', 1, "1.0");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:ReasonCode', 1, "RC2");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge/ram:Reason', 1, "Reason-2");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPositionLineSummation(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeSettlementLineMonetarySummation', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeSettlementLineMonetarySummation', 1);

        (self::$document)->setDocumentPositionLineSummation(10.0, 20.0);

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeSettlementLineMonetarySummation', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeSettlementLineMonetarySummation/ram:LineTotalAmount', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeSettlementLineMonetarySummation', 1);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeSettlementLineMonetarySummation/ram:LineTotalAmount', 0, "10.0");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentPositionReceivableTradeAccountingAccount(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ReceivableSpecifiedTradeAccountingAccount', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ReceivableSpecifiedTradeAccountingAccount', 1);

        (self::$document)->setDocumentPositionReceivableTradeAccountingAccount("ID1", "TC1");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ReceivableSpecifiedTradeAccountingAccount', 0);
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ReceivableSpecifiedTradeAccountingAccount/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ReceivableSpecifiedTradeAccountingAccount', 1);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem/ram:SpecifiedLineTradeSettlement/ram:ReceivableSpecifiedTradeAccountingAccount/ram:ID', 0, "ID1");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testWriteFile(): void
    {
        $filename = getcwd() . "/testWriteFile.xml";

        $this->registerFileForTestMethodTeardown($filename);

        (self::$document)->writeFile($filename);

        $this->assertFileExists($filename);
        $this->assertNotFalse(simplexml_load_file($filename));
    }

    /**
     * This must be the last test
     *
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testRemoveLatestPosition(): void
    {
        (self::$document)->removeLatestPosition();

        $this->disableRenderXmlContent();

        $property = $this->getPrivatePropertyFromObject(self::$document, 'currentPosition');
        $this->assertNull($property->getValue(self::$document));

        (self::$document)->removeLatestPosition();

        $this->disableRenderXmlContent();

        $property = $this->getPrivatePropertyFromObject(self::$document, 'currentPosition');
        $this->assertNull($property->getValue(self::$document));
    }
}
