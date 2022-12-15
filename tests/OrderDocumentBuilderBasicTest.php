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
}
