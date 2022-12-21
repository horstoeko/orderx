<?php

namespace horstoeko\orderx\tests\testcases;

use horstoeko\orderx\codelists\OrderDocumentTypes;
use horstoeko\orderx\OrderDocumentReader;
use horstoeko\orderx\OrderProfiles;
use horstoeko\orderx\tests\TestCase;

class OrderDocumentReaderExtendedTest extends TestCase
{
    /**
     * @var \horstoeko\orderx\OrderDocumentReader
     */
    protected static $document;

    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$document = OrderDocumentReader::readAndGuessFromFile(dirname(__FILE__) . '/../assets/reader-order-x-extended.xml');
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testDocumentProperties(): void
    {
        $property = $this->getPrivatePropertyFromObject(self::$document, 'orderObject');
        $this->assertNotNull($property->getValue(self::$document));
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testDocumentProfile(): void
    {
        $this->assertNotEquals(OrderProfiles::PROFILE_BASIC, self::$document->getProfileId());
        $this->assertNotEquals(OrderProfiles::PROFILE_COMFORT, self::$document->getProfileId());
        $this->assertEquals(OrderProfiles::PROFILE_EXTENDED, self::$document->getProfileId());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentInformation(): void
    {
        self::$document->getDocumentInformation(
            $documentNo,
            $documentTypeCode,
            $documentDate,
            $documentCurrency,
            $documentName,
            $documentLanguageId,
            $documentEffectiveSpecifiedPeriod,
            $documentPurposeCode,
            $documentRequestedResponseTypeCode
        );

        $this->assertEquals($documentNo, "PO123456789");
        $this->assertEquals($documentTypeCode, OrderDocumentTypes::ORDER);
        $this->assertEquals($documentDate->format('d.m.Y'), "21.12.2022");
        $this->assertEquals($documentCurrency, "EUR");
        $this->assertEquals($documentName, "Doc Name");
        $this->assertEmpty($documentLanguageId);
        $this->assertNull($documentEffectiveSpecifiedPeriod);
        $this->assertEquals($documentPurposeCode, "9");
        $this->assertEquals($documentRequestedResponseTypeCode, "AC");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetIsDocumentCopy(): void
    {
        self::$document->getIsDocumentCopy($documentIsCopy);

        $this->assertNotNull($documentIsCopy);
        $this->assertTrue($documentIsCopy);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetIsTestDocument(): void
    {
        self::$document->getIsTestDocument($documentIsTest);

        $this->assertNotNull($documentIsTest);
        $this->assertFalse($documentIsTest);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentNote(): void
    {
        $this->assertTrue(self::$document->firstDocumentNote());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentNote(): void
    {
        $this->assertFalse(self::$document->nextDocumentNote());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentNote(): void
    {
        self::$document->firstDocumentNote();
        self::$document->getDocumentNote($content, $subjectCode, $contentCode);

        $this->assertIsArray($content);
        $this->assertArrayHasKey(0, $content);
        $this->assertEquals("Content of Note", $content[0]);
        $this->assertEquals("AAI", $subjectCode);
        $this->assertEquals("AAI", $contentCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentNoteNoNext(): void
    {
        $this->expectException(\OutOfRangeException::class);

        self::$document->nextDocumentNote();
        self::$document->getDocumentNote($content, $subjectCode, $contentCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentSummation(): void
    {
        self::$document->getDocumentSummation(
            $lineTotalAmount,
            $grandTotalAmount,
            $chargeTotalAmount,
            $allowanceTotalAmount,
            $taxBasisTotalAmount,
            $taxTotalAmount
        );

        $this->assertEquals(310.00, $lineTotalAmount);
        $this->assertEquals(360.00, $grandTotalAmount);
        $this->assertEquals(21.00, $chargeTotalAmount);
        $this->assertEquals(31.00, $allowanceTotalAmount);
        $this->assertEquals(300.00, $taxBasisTotalAmount);
        $this->assertEquals(60.00, $taxTotalAmount);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentBuyerReference(): void
    {
        self::$document->getDocumentBuyerReference($buyerReference);

        $this->assertEquals("BUYER_REF_BU123", $buyerReference);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentSeller(): void
    {
        self::$document->getDocumentSeller($name, $id, $description);

        $this->assertEquals("SELLER_NAME", $name);
        $this->assertIsArray($id);
        $this->assertArrayHasKey(0, $id);
        $this->assertEquals("SUPPLIER_ID_321654", $id[0]);
        $this->assertEquals("SELLER_ADD_LEGAL_INFORMATION", $description);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentSellerGlobalId(): void
    {
        self::$document->getDocumentSellerGlobalId($globalids);

        $this->assertIsArray($globalids);
        $this->assertArrayHasKey("0088", $globalids);
        $this->assertEquals("123654879", $globalids["0088"]);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentSellerTaxRegistration(): void
    {
        self::$document->getDocumentSellerTaxRegistration($taxreg);

        $this->assertIsArray($taxreg);
        $this->assertArrayHasKey("VA", $taxreg);
        $this->assertArrayHasKey("FC", $taxreg);
        $this->assertEquals("FR 32 123 456 789", $taxreg["VA"]);
        $this->assertEquals("SELLER_TAX_ID", $taxreg["FC"]);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentSellerAddress(): void
    {
        self::$document->getDocumentSellerAddress($lineone, $linetwo, $linethree, $postcode, $city, $country, $subdivision);

        $this->assertEquals("SELLER_ADDR_1", $lineone);
        $this->assertEquals("SELLER_ADDR_2", $linetwo);
        $this->assertEquals("SELLER_ADDR_3", $linethree);
        $this->assertEquals("75001", $postcode);
        $this->assertEquals("SELLER_CITY", $city);
        $this->assertEquals("FR", $country);
        $this->assertEquals("", $subdivision);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentSellerLegalOrganisation(): void
    {
        self::$document->getDocumentSellerLegalOrganisation($legalorgid, $legalorgtype, $legalorgname);

        $this->assertEquals("123456789", $legalorgid);
        $this->assertEquals("0002", $legalorgtype);
        $this->assertEquals("SELLER_TRADING_NAME", $legalorgname);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentSellerContact(): void
    {
        $this->assertTrue(self::$document->firstDocumentSellerContact());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentSellerContact(): void
    {
        $this->assertFalse(self::$document->nextDocumentSellerContact());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentSellerContact(): void
    {
        self::$document->firstDocumentSellerContact();
        self::$document->getDocumentSellerContact(
            $contactpersonname,
            $contactdepartmentname,
            $contactphoneno,
            $contactfaxno,
            $contactemailadd,
            $contacttypecode
        );

        $this->assertEquals("SELLER_CONTACT_NAME", $contactpersonname);
        $this->assertEquals("SELLER_CONTACT_DEP", $contactdepartmentname);
        $this->assertEquals("+33 6 25 64 98 75", $contactphoneno);
        $this->assertEquals("", $contactfaxno);
        $this->assertEquals("contact@seller.com", $contactemailadd);
        $this->assertEquals("SR", $contacttypecode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentSellerContactNoNext(): void
    {
        $this->expectException(\OutOfRangeException::class);

        self::$document->nextDocumentSellerContact();
        self::$document->getDocumentSellerContact(
            $contactpersonname,
            $contactdepartmentname,
            $contactphoneno,
            $contactfaxno,
            $contactemailadd,
            $contacttypecode
        );
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentSellerElectronicAddress(): void
    {
        self::$document->getDocumentSellerElectronicAddress($uriType, $uriId);

        $this->assertEquals("EM", $uriType);
        $this->assertEquals("sales@seller.com", $uriId);
    }
}
