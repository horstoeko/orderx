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
    public function testDocumentProperties(): void
    {
        (self::$document)->initNewDocument();

        $property = $this->getPrivatePropertyFromObject(self::$document, 'invoiceObject');
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
        (self::$document)->setDocumentSeller("", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:ID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:Name');

        (self::$document)->setDocumentSeller("Lieferant GmbH", "549910");

        $this->disableRenderXmlContent();
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:ID', "549910");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:Name', "Lieferant GmbH");

        (self::$document)->setDocumentSeller("Lieferant 2 GmbH", "5499102");

        $this->disableRenderXmlContent();
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:ID', "5499102");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:Name', "Lieferant 2 GmbH");

        (self::$document)->setDocumentSeller("", "5499103");

        $this->disableRenderXmlContent();
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:ID', "5499103");
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:Name');

        (self::$document)->setDocumentSeller("Lieferant 2 GmbH", "5499102", "Description");

        $this->disableRenderXmlContent();
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:ID', "5499102");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:Name', "Lieferant 2 GmbH");
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:Description');
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentSellerGlobalId(): void
    {
        (self::$document)->addDocumentSellerGlobalId("4000001123452", "0088");

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:GlobalID', 0, "4000001123452", "schemeID", "0088");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentSellerTaxRegistration(): void
    {
        (self::$document)->setDocumentSellerTaxRegistration("", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);

        (self::$document)->setDocumentSellerTaxRegistration("FC", "201/113/40209");

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0, "201/113/40209", "schemeID", "FC");

        (self::$document)->addDocumentSellerTaxRegistration("VA", "DE123456789");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0, "201/113/40209", "schemeID", "FC");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentSellerAddress(): void
    {
        (self::$document)->setDocumentSellerAddress("", "", "", "", "", "");
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:PostcodeCode');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:LineOne');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:CityName');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:CountryID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');

        (self::$document)->setDocumentSellerAddress("Lieferantenstraße 20", "", "", "80333", "München", "DE");

        $this->disableRenderXmlContent();
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:PostcodeCode', "80333");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:LineOne', "Lieferantenstraße 20");
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:CityName', "München");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:CountryID', "DE");
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');

        (self::$document)->setDocumentSellerAddress("Lieferantenstraße 20", "Haus A", "Eingang B", "80333", "München", "DE");

        $this->disableRenderXmlContent();
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:PostcodeCode', "80333");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:LineOne', "Lieferantenstraße 20");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:LineTwo', "Haus A");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:LineThree', "Eingang B");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:CityName', "München");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:CountryID', "DE");
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentSellerLegalOrganisation(): void
    {
        (self::$document)->setDocumentSellerLegalOrganisation("", "", "");

        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', 0);

        (self::$document)->setDocumentSellerLegalOrganisation("DE12345", "FC", "Lieferant AG");

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0, "DE12345", "schemeID", "FC");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', "Lieferant AG");

        (self::$document)->setDocumentSellerLegalOrganisation("DE12345", "FC", "");

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0, "DE12345", "schemeID", "FC");
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', 0);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentSellerContact(): void
    {
        (self::$document)->setDocumentSellerContact("Hans Müller", "Financials", "+49-111-2222222", "+49-111-3333333", "info@lieferant.de");

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:PersonName', 0, "Hans Müller");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0, "Financials");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0, "+49-111-2222222");
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0, "info@lieferant.de");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        (self::$document)->setDocumentSellerContact("Hans Meier", "Bank", "+49-111-4444444", "+49-111-5555555", "info@meinseller.de");

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:PersonName', 0, "Hans Meier");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0, "Bank");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0, "+49-111-4444444");
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0, "info@meinseller.de");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        (self::$document)->setDocumentSellerContact("Hans Müller", "Financials", "+49-111-2222222", "+49-111-3333333", "info@lieferant.de");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentSellerContact(): void
    {
        (self::$document)->addDocumentSellerContact("Hans Meier", "Bank", "+49-111-4444444", "+49-111-5555555", "info@meinseller.de");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentSellerElectronicAddress(): void
    {
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
        (self::$document)->setDocumentSeller("", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:ID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:Name');

        (self::$document)->setDocumentBuyer("Kunden AG Mitte", "549910");

        $this->disableRenderXmlContent();
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:ID', "549910");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:Name', "Kunden AG Mitte");

        (self::$document)->setDocumentBuyer("Kunden AG Mitte 2", "5499102");

        $this->disableRenderXmlContent();
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:ID', "5499102");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:Name', "Kunden AG Mitte 2");

        (self::$document)->setDocumentBuyer("", "5499103");

        $this->disableRenderXmlContent();
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:ID', "5499103");
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:Name');

        (self::$document)->setDocumentBuyer("Kunden AG Mitte 2", "5499102", "Description");

        $this->disableRenderXmlContent();
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:ID', "5499102");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:Name', "Kunden AG Mitte 2");
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:Description');
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentBuyerGlobalId(): void
    {
        (self::$document)->addDocumentBuyerGlobalId("4000001123452", "0088");

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:GlobalID', 0, "4000001123452", "schemeID", "0088");

        (self::$document)->addDocumentBuyerGlobalId("4000001123453", "0088");

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:GlobalID', 1, "4000001123453", "schemeID", "0088");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetAndAddDocumentBuyerTaxRegistration(): void
    {
        (self::$document)->setDocumentBuyerTaxRegistration("", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);

        (self::$document)->setDocumentBuyerTaxRegistration("FC", "201/113/40209");

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0, "201/113/40209", "schemeID", "FC");

        (self::$document)->addDocumentBuyerTaxRegistration("VA", "DE123456789");

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0, "201/113/40209", "schemeID", "FC");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentBuyerAddress(): void
    {
        (self::$document)->setDocumentBuyerAddress("", "", "", "", "", "");
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:PostcodeCode');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:LineOne');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:CityName');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:CountryID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');

        (self::$document)->setDocumentBuyerAddress("Kundenstrasse 15", "", "", "69876", "Frankfurt", "DE");

        $this->disableRenderXmlContent();
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:PostcodeCode', "69876");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:LineOne', "Kundenstrasse 15");
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:CityName', "Frankfurt");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:CountryID', "DE");
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');

        (self::$document)->setDocumentBuyerAddress("Kundenstrasse 15", "Haus A", "Eingang B", "69876", "Frankfurt", "DE");

        $this->disableRenderXmlContent();
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:PostcodeCode', "69876");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:LineOne', "Kundenstrasse 15");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:LineTwo', "Haus A");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:LineThree', "Eingang B");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:CityName', "Frankfurt");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:CountryID', "DE");
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentBuyerLegalOrganisation(): void
    {
        (self::$document)->setDocumentBuyerLegalOrganisation("", "", "");

        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', 0);

        (self::$document)->setDocumentBuyerLegalOrganisation("DE12345", "FC", "Lieferant AG");

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0, "DE12345", "schemeID", "FC");
        $this->assertXPathValue('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', "Lieferant AG");

        (self::$document)->setDocumentBuyerLegalOrganisation("DE12345", "FC", "");

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndexAndAttribute('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0, "DE12345", "schemeID", "FC");
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', 0);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentBuyerContact(): void
    {
        (self::$document)->setDocumentBuyerContact("Otto Müller", "Financials", "+49-111-2222222", "+49-111-3333333", "info@kunde.de");

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:PersonName', 0, "Otto Müller");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0, "Financials");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0, "+49-111-2222222");
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0, "info@kunde.de");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        (self::$document)->setDocumentBuyerContact("Hans Meier", "Bank", "+49-111-4444444", "+49-111-5555555", "info@kunde.de");

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:PersonName', 0, "Hans Meier");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0, "Bank");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0, "+49-111-4444444");
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0, "info@kunde.de");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);

        (self::$document)->setDocumentBuyerContact("Otto Müller", "Financials", "+49-111-2222222", "+49-111-3333333", "info@kunde.de");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentBuyerContact(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:PersonName', 0, "Otto Müller");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0, "Financials");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0, "+49-111-2222222");
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0, "info@kunde.de");

        (self::$document)->addDocumentBuyerContact("Otto Meier", "Bank", "+49-111-4444444", "+49-111-5555555", "info2@kunde2.de");

        $this->disableRenderXmlContent();
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:PersonName', 0, "Otto Müller");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0, "Financials");
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0, "+49-111-2222222");
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0, "info@kunde.de");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:PersonName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 1);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentBuyerElectronicAddress(): void
    {
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
        (self::$document)->setDocumentSeller("", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:ID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:Name');

        (self::$document)->setDocumentBuyerRequisitioner("Kunden AG Mitte", "549910");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:ID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:Name');

        (self::$document)->setDocumentBuyerRequisitioner("Kunden AG Mitte 2", "5499102");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:ID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:Name');

        (self::$document)->setDocumentBuyerRequisitioner("", "5499103");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:ID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:Name');

        (self::$document)->setDocumentBuyerRequisitioner("Kunden AG Mitte 2", "5499102", "Description");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:ID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:Name');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:Description');
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentBuyerRequisitionerGlobalId(): void
    {
        (self::$document)->addDocumentBuyerRequisitionerGlobalId("4000001123452", "0088");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:GlobalID');

        (self::$document)->addDocumentBuyerRequisitionerGlobalId("4000001123453", "0088");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:GlobalID');
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetAndAddDocumentBuyerRequisitionerTaxRegistration(): void
    {
        (self::$document)->setDocumentBuyerRequisitionerTaxRegistration("", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);

        (self::$document)->setDocumentBuyerRequisitionerTaxRegistration("FC", "201/113/40209");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);

        (self::$document)->addDocumentBuyerRequisitionerTaxRegistration("VA", "DE123456789");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedTaxRegistration/ram:ID', 1);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentBuyerRequisitionerAddress(): void
    {
        (self::$document)->setDocumentBuyerRequisitionerAddress("", "", "", "", "", "");
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:PostcodeCode');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:LineOne');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:CityName');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:CountryID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');

        (self::$document)->setDocumentBuyerRequisitionerAddress("Kundenstrasse 15", "", "", "69876", "Frankfurt", "DE");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:PostcodeCode');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:LineOne');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:CityName');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:CountryID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');

        (self::$document)->setDocumentBuyerRequisitionerAddress("Kundenstrasse 15", "Haus A", "Eingang B", "69876", "Frankfurt", "DE");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:PostcodeCode');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:LineOne');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:LineTwo');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:LineThree');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:CityName');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:CountryID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:PostalTradeAddress/ram:CountrySubDivisionName');
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentBuyerRequisitionerLegalOrganisation(): void
    {
        (self::$document)->setDocumentBuyerRequisitionerLegalOrganisation("", "", "");

        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedLegalOrganization/ram:TradingBusinessName', 0);

        (self::$document)->setDocumentBuyerRequisitionerLegalOrganisation("DE12345", "FC", "Lieferant AG");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0);

        (self::$document)->setDocumentBuyerRequisitionerLegalOrganisation("DE12345", "FC", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:SpecifiedLegalOrganization/ram:ID', 0);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentBuyerRequisitionerContact(): void
    {
        (self::$document)->setDocumentBuyerRequisitionerContact("Otto Müller", "Financials", "+49-111-2222222", "+49-111-3333333", "info@kunde.de");

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

        (self::$document)->setDocumentBuyerRequisitionerContact("Hans Meier", "Bank", "+49-111-4444444", "+49-111-5555555", "info@kunde.de");

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
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentBuyerRequisitionerContact(): void
    {
        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:PersonName', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:DepartmentName', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:FaxUniversalCommunication/ram:CompleteNumber', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID', 0);

        (self::$document)->addDocumentBuyerRequisitionerContact("Otto Meier", "Bank", "+49-111-4444444", "+49-111-5555555", "info2@kunde2.de");

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
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentBuyerRequisitionerElectronicAddress(): void
    {
        (self::$document)->setDocumentBuyerRequisitionerElectronicAddress("", "");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:URIUniversalCommunication', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:URIUniversalCommunication/ram:URIID', 0);

        (self::$document)->setDocumentBuyerRequisitionerElectronicAddress("EM", "someone@nowhere.all");

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:URIUniversalCommunication', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerRequisitionerTradeParty/ram:URIUniversalCommunication/ram:URIID', 0);
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
        (self::$document)->setDocumentSellerOrderReferencedDocument('');

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerOrderReferencedDocument/ram:IssuerAssignedID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerOrderReferencedDocument/ram:FormattedIssueDateTime/ram:DateTimeString');

        (self::$document)->setDocumentSellerOrderReferencedDocument('B-1010', new DateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerOrderReferencedDocument/ram:IssuerAssignedID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerOrderReferencedDocument/ram:FormattedIssueDateTime/ram:DateTimeString');
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentBuyerOrderReferencedDocument(): void
    {
        (self::$document)->setDocumentBuyerOrderReferencedDocument('');

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument/ram:IssuerAssignedID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument/ram:FormattedIssueDateTime/ram:DateTimeString');

        (self::$document)->setDocumentBuyerOrderReferencedDocument('O-2020', new DateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument/ram:IssuerAssignedID', 0, 'O-2020');
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument/ram:FormattedIssueDateTime/ram:DateTimeString', 0, 'O-2020');
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument/ram:FormattedIssueDateTime/ram:DateTimeString', 1);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentQuotationReferencedDocument(): void
    {
        (self::$document)->setDocumentQuotationReferencedDocument('');

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:QuotationReferencedDocument/ram:IssuerAssignedID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:QuotationReferencedDocument/ram:FormattedIssueDateTime/ram:DateTimeString');

        (self::$document)->setDocumentQuotationReferencedDocument('Q-2020', new DateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:QuotationReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:QuotationReferencedDocument/ram:IssuerAssignedID', 0, 'Q-2020');
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:QuotationReferencedDocument/ram:FormattedIssueDateTime/ram:DateTimeString', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:QuotationReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:QuotationReferencedDocument/ram:FormattedIssueDateTime/ram:DateTimeString', 1);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentContractReferencedDocument(): void
    {
        (self::$document)->setDocumentContractReferencedDocument('');

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:IssuerAssignedID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:FormattedIssueDateTime/ram:DateTimeString');

        (self::$document)->setDocumentContractReferencedDocument('C-2020', new DateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:IssuerAssignedID', 0, 'C-2020');
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:FormattedIssueDateTime/ram:DateTimeString', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:FormattedIssueDateTime/ram:DateTimeString', 1);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentContractReferencedDocument(): void
    {
        (self::$document)->addDocumentContractReferencedDocument('C-2021', new DateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathValueWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:IssuerAssignedID', 0, 'C-2020');
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:FormattedIssueDateTime/ram:DateTimeString', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument/ram:FormattedIssueDateTime/ram:DateTimeString', 1);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testSetDocumentRequisitionReferencedDocument(): void
    {
        (self::$document)->setDocumentRequisitionReferencedDocument('');

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:IssuerAssignedID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:FormattedIssueDateTime/ram:DateTimeString');

        (self::$document)->setDocumentRequisitionReferencedDocument('REQ-2020', new DateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:IssuerAssignedID');
        $this->assertXPathNotExists('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:FormattedIssueDateTime/ram:DateTimeString');
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddDocumentRequisitionReferencedDocument(): void
    {
        (self::$document)->addDocumentRequisitionReferencedDocument('REQ-2020', new DateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:FormattedIssueDateTime/ram:DateTimeString', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:IssuerAssignedID', 1);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:RequisitionReferencedDocument/ram:FormattedIssueDateTime/ram:DateTimeString', 1);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentBuilder
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testAddAdditionalReferencedDocument(): void
    {
        (self::$document)->addDocumentAdditionalReferencedDocument('TYPECODE', 'REFID', 'URIID', 'REFNAME', 'REFREFTYPECODE', new \DateTime());

        $this->disableRenderXmlContent();
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:AdditionalReferencedDocument/ram:IssuerAssignedID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:AdditionalReferencedDocument/ram:URIID', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:AdditionalReferencedDocument/ram:TypeCode', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:AdditionalReferencedDocument/ram:Name', 0);
        $this->assertXPathNotExistsWithIndex('/rsm:SCRDMCCBDACIOMessageStructure/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:AdditionalReferencedDocument/ram:FormattedIssueDateTime/ram:DateTimeString', 0);
    }
}
