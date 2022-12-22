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

        $this->assertEquals("PO123456789", $documentNo);
        $this->assertEquals(OrderDocumentTypes::ORDER, $documentTypeCode);
        $this->assertEquals("21.12.2022", $documentDate->format('d.m.Y'));
        $this->assertEquals("EUR", $documentCurrency);
        $this->assertEquals("Doc Name", $documentName);
        $this->assertEmpty($documentLanguageId);
        $this->assertNull($documentEffectiveSpecifiedPeriod);
        $this->assertEquals("9", $documentPurposeCode);
        $this->assertEquals("AC", $documentRequestedResponseTypeCode);
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

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentBuyer(): void
    {
        self::$document->getDocumentBuyer($name, $id, $description);

        $this->assertEquals("BUYER_NAME", $name);
        $this->assertIsArray($id);
        $this->assertArrayHasKey(0, $id);
        $this->assertEquals("BY_ID_9587456", $id[0]);
        $this->assertEquals("", $description);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentBuyerGlobalId(): void
    {
        self::$document->getDocumentBuyerGlobalId($globalids);

        $this->assertIsArray($globalids);
        $this->assertArrayHasKey("0088", $globalids);
        $this->assertEquals("98765432179", $globalids["0088"]);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentBuyerTaxRegistration(): void
    {
        self::$document->getDocumentBuyerTaxRegistration($taxreg);

        $this->assertIsArray($taxreg);
        $this->assertArrayHasKey("VA", $taxreg);
        $this->assertArrayNotHasKey("FC", $taxreg);
        $this->assertEquals("FR 05 987 654 321", $taxreg["VA"]);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentBuyerAddress(): void
    {
        self::$document->getDocumentBuyerAddress($lineone, $linetwo, $linethree, $postcode, $city, $country, $subdivision);

        $this->assertEquals("BUYER_ADDR_1", $lineone);
        $this->assertEquals("BUYER_ADDR_2", $linetwo);
        $this->assertEquals("BUYER_ADDR_3", $linethree);
        $this->assertEquals("69001", $postcode);
        $this->assertEquals("BUYER_CITY", $city);
        $this->assertEquals("FR", $country);
        $this->assertEquals("", $subdivision);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentBuyerLegalOrganisation(): void
    {
        self::$document->getDocumentBuyerLegalOrganisation($legalorgid, $legalorgtype, $legalorgname);

        $this->assertEquals("987654321", $legalorgid);
        $this->assertEquals("0002", $legalorgtype);
        $this->assertEquals("BUYER_TRADING_NAME", $legalorgname);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentBuyerContact(): void
    {
        $this->assertTrue(self::$document->firstDocumentBuyerContact());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentBuyerContact(): void
    {
        $this->assertFalse(self::$document->nextDocumentBuyerContact());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentBuyerContact(): void
    {
        self::$document->firstDocumentBuyerContact();
        self::$document->getDocumentBuyerContact(
            $contactpersonname,
            $contactdepartmentname,
            $contactphoneno,
            $contactfaxno,
            $contactemailadd,
            $contacttypecode
        );

        $this->assertEquals("BUYER_CONTACT_NAME", $contactpersonname);
        $this->assertEquals("BUYER_CONTACT_DEP", $contactdepartmentname);
        $this->assertEquals("+33 6 65 98 75 32", $contactphoneno);
        $this->assertEquals("", $contactfaxno);
        $this->assertEquals("contact@buyer.com", $contactemailadd);
        $this->assertEquals("LB", $contacttypecode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentBuyerContactNoNext(): void
    {
        $this->expectException(\OutOfRangeException::class);

        self::$document->nextDocumentBuyerContact();
        self::$document->getDocumentBuyerContact(
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
    public function testGetDocumentBuyerElectronicAddress(): void
    {
        self::$document->getDocumentBuyerElectronicAddress($uriType, $uriId);

        $this->assertEquals("EM", $uriType);
        $this->assertEquals("operation@buyer.com", $uriId);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentBuyerRequisitioner(): void
    {
        self::$document->getDocumentBuyerRequisitioner($name, $id, $description);

        $this->assertEquals("BUYER_REQ_NAME", $name);
        $this->assertIsArray($id);
        $this->assertArrayHasKey(0, $id);
        $this->assertEquals("BUYER_REQ_ID_25987", $id[0]);
        $this->assertEquals("", $description);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentBuyerRequisitionerGlobalId(): void
    {
        self::$document->getDocumentBuyerRequisitionerGlobalId($globalids);

        $this->assertIsArray($globalids);
        $this->assertArrayHasKey("0088", $globalids);
        $this->assertEquals("654987321", $globalids["0088"]);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentBuyerRequisitionerTaxRegistration(): void
    {
        self::$document->getDocumentBuyerRequisitionerTaxRegistration($taxreg);

        $this->assertIsArray($taxreg);
        $this->assertArrayHasKey("VA", $taxreg);
        $this->assertArrayNotHasKey("FC", $taxreg);
        $this->assertEquals("FR 92 654 987 321", $taxreg["VA"]);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentBuyerRequisitionerAddress(): void
    {
        self::$document->getDocumentBuyerRequisitionerAddress($lineone, $linetwo, $linethree, $postcode, $city, $country, $subdivision);

        $this->assertEquals("BUYER_REQ_ADDR_1", $lineone);
        $this->assertEquals("BUYER_REQ_ADDR_2", $linetwo);
        $this->assertEquals("BUYER_REQ_ADDR_3", $linethree);
        $this->assertEquals("69001", $postcode);
        $this->assertEquals("BUYER_REQ_CITY", $city);
        $this->assertEquals("FR", $country);
        $this->assertEquals("", $subdivision);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentBuyerRequisitionerLegalOrganisation(): void
    {
        self::$document->getDocumentBuyerRequisitionerLegalOrganisation($legalorgid, $legalorgtype, $legalorgname);

        $this->assertEquals("654987321", $legalorgid);
        $this->assertEquals("0022", $legalorgtype);
        $this->assertEquals("BUYER_REQ_TRADING_NAME", $legalorgname);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentBuyerRequisitionerContact(): void
    {
        $this->assertTrue(self::$document->firstDocumentBuyerRequisitionerContact());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentBuyerRequisitionerContact(): void
    {
        $this->assertFalse(self::$document->nextDocumentBuyerRequisitionerContact());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentBuyerRequisitionerContact(): void
    {
        self::$document->firstDocumentBuyerRequisitionerContact();
        self::$document->getDocumentBuyerRequisitionerContact(
            $contactpersonname,
            $contactdepartmentname,
            $contactphoneno,
            $contactfaxno,
            $contactemailadd,
            $contacttypecode
        );

        $this->assertEquals("BUYER_REQ_CONTACT_NAME", $contactpersonname);
        $this->assertEquals("BUYER_REQ_CONTACT_DEP", $contactdepartmentname);
        $this->assertEquals("+33 6 54 98 65 32", $contactphoneno);
        $this->assertEquals("", $contactfaxno);
        $this->assertEquals("requisitioner@buyer.com", $contactemailadd);
        $this->assertEquals("PD", $contacttypecode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentBuyerRequisitionerContactNoNext(): void
    {
        $this->expectException(\OutOfRangeException::class);

        self::$document->nextDocumentBuyerRequisitionerContact();
        self::$document->getDocumentBuyerRequisitionerContact(
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
    public function testGetDocumentBuyerRequisitionerElectronicAddress(): void
    {
        self::$document->getDocumentBuyerRequisitionerElectronicAddress($uriType, $uriId);

        $this->assertEquals("EM", $uriType);
        $this->assertEquals("purchase@buyer.com", $uriId);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentDeliveryTerms(): void
    {
        self::$document->getDocumentDeliveryTerms($code, $description, $functionCode, $locationId, $locationName);

        $this->assertEquals("FCA", $code);
        $this->assertEquals("Free Carrier", $description);
        $this->assertEquals("7", $functionCode);
        $this->assertEquals("DEL_TERMS_LOC_ID", $locationId);
        $this->assertEquals("DEL_TERMS_LOC_Name", $locationName);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentSellerOrderReferencedDocument(): void
    {
        self::$document->getDocumentSellerOrderReferencedDocument($sellerOrderRefId, $sellerOrderRefDate);

        $this->assertEquals("SALES_REF_ID_459875", $sellerOrderRefId);
        $this->assertNull($sellerOrderRefDate);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentBuyerOrderReferencedDocument(): void
    {
        self::$document->getDocumentBuyerOrderReferencedDocument($buyerOrderRefId, $buyerOrderRefDate);

        $this->assertEquals("PO123456789", $buyerOrderRefId);
        $this->assertNull($buyerOrderRefDate);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentQuotationReferencedDocument(): void
    {
        self::$document->getDocumentQuotationReferencedDocument($quotationRefId, $quotationRefDate);

        $this->assertEquals("QUOT_125487", $quotationRefId);
        $this->assertNull($quotationRefDate);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentContractReferencedDocument(): void
    {
        self::$document->getDocumentContractReferencedDocument($contractRefId, $contractRefDate);

        $this->assertEquals("CONTRACT_2020-25987", $contractRefId);
        $this->assertNull($contractRefDate);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetGetDocumentRequisitionReferencedDocument(): void
    {
        self::$document->getDocumentRequisitionReferencedDocument($requisitionRefId, $requisitionRefDate);

        $this->assertEquals("REQ_875498", $requisitionRefId);
        $this->assertNull($requisitionRefDate);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentAdditionalReferencedDocument(): void
    {
        $this->assertTrue(self::$document->firstDocumentAdditionalReferencedDocument());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentAdditionalReferencedDocument(): void
    {
        $this->assertTrue(self::$document->nextDocumentAdditionalReferencedDocument());
        $this->assertTrue(self::$document->nextDocumentAdditionalReferencedDocument());
        $this->assertFalse(self::$document->nextDocumentAdditionalReferencedDocument());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentAdditionalReferencedDocument(): void
    {
        $this->assertTrue(self::$document->firstDocumentAdditionalReferencedDocument());

        self::$document->getDocumentAdditionalReferencedDocument($additionalRefTypeCode, $additionalRefId, $additionalRefURIID, $additionalRefName, $additionalRefRefTypeCode, $additionalRefDate);

        $this->assertEquals("916", $additionalRefTypeCode);
        $this->assertEquals("ADD_REF_DOC_ID", $additionalRefId);
        $this->assertEquals("ADD_REF_DOC_URIID", $additionalRefURIID);
        $this->assertEquals("ADD_REF_DOC_Desc", $additionalRefName);
        $this->assertEquals("", $additionalRefRefTypeCode);
        $this->assertNull($additionalRefDate);

        $this->assertTrue(self::$document->nextDocumentAdditionalReferencedDocument());

        self::$document->getDocumentAdditionalReferencedDocument($additionalRefTypeCode, $additionalRefId, $additionalRefURIID, $additionalRefName, $additionalRefRefTypeCode, $additionalRefDate);

        $this->assertEquals("50", $additionalRefTypeCode);
        $this->assertEquals("TENDER_ID", $additionalRefId);
        $this->assertEquals("", $additionalRefURIID);
        $this->assertEquals("", $additionalRefName);
        $this->assertEquals("", $additionalRefRefTypeCode);
        $this->assertNull($additionalRefDate);

        $this->assertTrue(self::$document->nextDocumentAdditionalReferencedDocument());

        self::$document->getDocumentAdditionalReferencedDocument($additionalRefTypeCode, $additionalRefId, $additionalRefURIID, $additionalRefName, $additionalRefRefTypeCode, $additionalRefDate);

        $this->assertEquals("130", $additionalRefTypeCode);
        $this->assertEquals("OBJECT_ID", $additionalRefId);
        $this->assertEquals("", $additionalRefURIID);
        $this->assertEquals("", $additionalRefName);
        $this->assertEquals("AWV", $additionalRefRefTypeCode);
        $this->assertNull($additionalRefDate);

        $this->assertFalse(self::$document->nextDocumentAdditionalReferencedDocument());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentAdditionalReferencedDocumentBinaryData(): void
    {
        self::$document->setBinaryDataDirectory(dirname(__FILE__));

        self::$document->firstDocumentAdditionalReferencedDocument();
        self::$document->getDocumentAdditionalReferencedDocumentBinaryData($additionalBinaryFilename);

        $this->assertEmpty($additionalBinaryFilename);

        self::$document->nextDocumentAdditionalReferencedDocument();
        self::$document->getDocumentAdditionalReferencedDocumentBinaryData($additionalBinaryFilename);

        $this->assertEmpty($additionalBinaryFilename);

        self::$document->nextDocumentAdditionalReferencedDocument();
        self::$document->getDocumentAdditionalReferencedDocumentBinaryData($additionalBinaryFilename);

        $this->assertEmpty($additionalBinaryFilename);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentBlanketOrderReferencedDocument(): void
    {
        self::$document->getDocumentBlanketOrderReferencedDocument($blanketOrderRefId, $blanketOrderRefDate);

        $this->assertEquals("BLANKET_ORDER_OD", $blanketOrderRefId);
        $this->assertNotNull($blanketOrderRefDate);
        $this->assertEquals($blanketOrderRefDate->format("d.m.Y"), "21.12.2022");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPreviousOrderChangeReferencedDocument(): void
    {
        self::$document->getDocumentPreviousOrderChangeReferencedDocument($prevOrderChangeRefId, $prevOrderChangeRefDate);

        $this->assertEquals("PREV_ORDER_C_ID", $prevOrderChangeRefId);
        $this->assertNotNull($prevOrderChangeRefDate);
        $this->assertEquals($prevOrderChangeRefDate->format("d.m.Y"), "21.12.2022");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPreviousOrderResponseReferencedDocument(): void
    {
        self::$document->getDocumentPreviousOrderResponseReferencedDocument($prevOrderResponseRefId, $prevOrderResponseRefDate);

        $this->assertEquals("PREV_ORDER_R_ID", $prevOrderResponseRefId);
        $this->assertNotNull($prevOrderResponseRefDate);
        $this->assertEquals($prevOrderResponseRefDate->format("d.m.Y"), "21.12.2022");
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentProcuringProject(): void
    {
        self::$document->getDocumentProcuringProject($procuringProjectId, $procuringProjectName);

        $this->assertEquals("PROJECT_ID", $procuringProjectId);
        $this->assertEquals("Project Reference", $procuringProjectName);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentShipTo(): void
    {
        self::$document->getDocumentShipTo($name, $id, $description);

        $this->assertEquals("SHIP_TO_NAME", $name);
        $this->assertIsArray($id);
        $this->assertArrayHasKey(0, $id);
        $this->assertEquals("SHIP_TO_ID", $id[0]);
        $this->assertEquals("", $description);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentShipToGlobalId(): void
    {
        self::$document->getDocumentShipToGlobalId($globalids);

        $this->assertIsArray($globalids);
        $this->assertArrayHasKey("0088", $globalids);
        $this->assertEquals("5897546912", $globalids["0088"]);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentShipToTaxRegistration(): void
    {
        self::$document->getDocumentShipToTaxRegistration($taxreg);

        $this->assertIsArray($taxreg);
        $this->assertArrayHasKey("VA", $taxreg);
        $this->assertArrayNotHasKey("FC", $taxreg);
        $this->assertEquals("FR 66 951 632 874", $taxreg["VA"]);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentShipToAddress(): void
    {
        self::$document->getDocumentShipToAddress($lineone, $linetwo, $linethree, $postcode, $city, $country, $subdivision);

        $this->assertEquals("SHIP_TO_ADDR_1", $lineone);
        $this->assertEquals("SHIP_TO_ADDR_2", $linetwo);
        $this->assertEquals("SHIP_TO_ADDR_3", $linethree);
        $this->assertEquals("69003", $postcode);
        $this->assertEquals("SHIP_TO_CITY", $city);
        $this->assertEquals("FR", $country);
        $this->assertEquals("", $subdivision);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentShipToLegalOrganisation(): void
    {
        self::$document->getDocumentShipToLegalOrganisation($legalorgid, $legalorgtype, $legalorgname);

        $this->assertEquals("951632874", $legalorgid);
        $this->assertEquals("0002", $legalorgtype);
        $this->assertEquals("SHIP_TO_TRADING_NAME", $legalorgname);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentShipToContact(): void
    {
        $this->assertTrue(self::$document->firstDocumentShipToContact());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentShipToContact(): void
    {
        $this->assertFalse(self::$document->nextDocumentShipToContact());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentShipToContact(): void
    {
        self::$document->firstDocumentShipToContact();
        self::$document->getDocumentShipToContact(
            $contactpersonname,
            $contactdepartmentname,
            $contactphoneno,
            $contactfaxno,
            $contactemailadd,
            $contacttypecode
        );

        $this->assertEquals("SHIP_TO_CONTACT_NAME", $contactpersonname);
        $this->assertEquals("SHIP_TO_CONTACT_DEP", $contactdepartmentname);
        $this->assertEquals("+33 6 85 96 32 41", $contactphoneno);
        $this->assertEquals("", $contactfaxno);
        $this->assertEquals("shipto@customer.com", $contactemailadd);
        $this->assertEquals("SD", $contacttypecode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentShipToContactNoNext(): void
    {
        $this->expectException(\OutOfRangeException::class);

        self::$document->nextDocumentShipToContact();
        self::$document->getDocumentShipToContact(
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
    public function testGetDocumentShipToElectronicAddress(): void
    {
        self::$document->getDocumentShipToElectronicAddress($uriType, $uriId);

        $this->assertEquals("EM", $uriType);
        $this->assertEquals("delivery@buyer.com", $uriId);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentShipFrom(): void
    {
        self::$document->getDocumentShipFrom($name, $id, $description);

        $this->assertEquals("SHIP_FROM_NAME", $name);
        $this->assertIsArray($id);
        $this->assertArrayHasKey(0, $id);
        $this->assertEquals("SHIP_FROM_ID", $id[0]);
        $this->assertEquals("", $description);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentShipFromGlobalId(): void
    {
        self::$document->getDocumentShipFromGlobalId($globalids);

        $this->assertIsArray($globalids);
        $this->assertArrayHasKey("0088", $globalids);
        $this->assertEquals("875496123", $globalids["0088"]);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentShipFromTaxRegistration(): void
    {
        self::$document->getDocumentShipFromTaxRegistration($taxreg);

        $this->assertIsArray($taxreg);
        $this->assertArrayHasKey("VA", $taxreg);
        $this->assertArrayNotHasKey("FC", $taxreg);
        $this->assertEquals("FR 16 548 963 127", $taxreg["VA"]);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentShipFromAddress(): void
    {
        self::$document->getDocumentShipFromAddress($lineone, $linetwo, $linethree, $postcode, $city, $country, $subdivision);

        $this->assertEquals("SHIP_FROM_ADDR_1", $lineone);
        $this->assertEquals("SHIP_FROM_ADDR_2", $linetwo);
        $this->assertEquals("SHIP_FROM_ADDR_3", $linethree);
        $this->assertEquals("75003", $postcode);
        $this->assertEquals("SHIP_FROM_CITY", $city);
        $this->assertEquals("FR", $country);
        $this->assertEquals("", $subdivision);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentShipFromLegalOrganisation(): void
    {
        self::$document->getDocumentShipFromLegalOrganisation($legalorgid, $legalorgtype, $legalorgname);

        $this->assertEquals("548963127", $legalorgid);
        $this->assertEquals("0002", $legalorgtype);
        $this->assertEquals("SHIP_FROM_TRADING_NAME", $legalorgname);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentShipFromContact(): void
    {
        $this->assertTrue(self::$document->firstDocumentShipFromContact());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentShipFromContact(): void
    {
        $this->assertFalse(self::$document->nextDocumentShipFromContact());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentShipFromContact(): void
    {
        self::$document->firstDocumentShipFromContact();
        self::$document->getDocumentShipFromContact(
            $contactpersonname,
            $contactdepartmentname,
            $contactphoneno,
            $contactfaxno,
            $contactemailadd,
            $contacttypecode
        );

        $this->assertEquals("SHIP_FROM_CONTACT_NAME", $contactpersonname);
        $this->assertEquals("SHIP_FROM_CONTACT_DEP", $contactdepartmentname);
        $this->assertEquals("+33 6 85 96 32 41", $contactphoneno);
        $this->assertEquals("", $contactfaxno);
        $this->assertEquals("shipfrom@seller.com", $contactemailadd);
        $this->assertEquals("SD", $contacttypecode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentShipFromContactNoNext(): void
    {
        $this->expectException(\OutOfRangeException::class);

        self::$document->nextDocumentShipFromContact();
        self::$document->getDocumentShipFromContact(
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
    public function testGetDocumentShipFromElectronicAddress(): void
    {
        self::$document->getDocumentShipFromElectronicAddress($uriType, $uriId);

        $this->assertEquals("EM", $uriType);
        $this->assertEquals("warehouse@seller.com", $uriId);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentRequestedDeliverySupplyChainEvent(): void
    {
        $this->assertTrue(self::$document->firstDocumentRequestedDeliverySupplyChainEvent());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentRequestedDeliverySupplyChainEvent(): void
    {
        $this->assertFalse(self::$document->nextDocumentRequestedDeliverySupplyChainEvent());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentRequestedDeliverySupplyChainEvent(): void
    {
        self::$document->firstDocumentRequestedDeliverySupplyChainEvent();
        self::$document->getDocumentRequestedDeliverySupplyChainEvent($occurrenceDateTime, $startDateTime, $endDateTime);

        $this->assertNotNull($occurrenceDateTime);
        $this->assertNotNull($startDateTime);
        $this->assertNotNull($endDateTime);
        $this->assertEquals("21.12.2022", $occurrenceDateTime->format('d.m.Y'));
        $this->assertEquals("21.12.2022", $startDateTime->format('d.m.Y'));
        $this->assertEquals("21.12.2022", $endDateTime->format('d.m.Y'));
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentRequestedDeliverySupplyChainEventNoNext(): void
    {
        $this->expectException(\OutOfRangeException::class);

        self::$document->nextDocumentRequestedDeliverySupplyChainEvent();
        self::$document->getDocumentRequestedDeliverySupplyChainEvent($occurrenceDateTime, $startDateTime, $endDateTime);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentInvoicee(): void
    {
        self::$document->getDocumentInvoicee($name, $id, $description);

        $this->assertEquals("INVOICEE_NAME", $name);
        $this->assertIsArray($id);
        $this->assertArrayHasKey(0, $id);
        $this->assertEquals("INVOICEE_ID_9587456", $id[0]);
        $this->assertEquals("", $description);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentInvoiceeGlobalId(): void
    {
        self::$document->getDocumentInvoiceeGlobalId($globalids);

        $this->assertIsArray($globalids);
        $this->assertArrayHasKey("0088", $globalids);
        $this->assertEquals("98765432179", $globalids["0088"]);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentInvoiceeTaxRegistration(): void
    {
        self::$document->getDocumentInvoiceeTaxRegistration($taxreg);

        $this->assertIsArray($taxreg);
        $this->assertArrayHasKey("VA", $taxreg);
        $this->assertArrayHasKey("FC", $taxreg);
        $this->assertEquals("FR 05 987 654 321", $taxreg["VA"]);
        $this->assertEquals("INVOICEE_TAX_ID", $taxreg["FC"]);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentInvoiceeAddress(): void
    {
        self::$document->getDocumentInvoiceeAddress($lineone, $linetwo, $linethree, $postcode, $city, $country, $subdivision);

        $this->assertEquals("INVOICEE_ADDR_1", $lineone);
        $this->assertEquals("INVOICEE_ADDR_2", $linetwo);
        $this->assertEquals("INVOICEE_ADDR_3", $linethree);
        $this->assertEquals("69001", $postcode);
        $this->assertEquals("INVOICEE_CITY", $city);
        $this->assertEquals("FR", $country);
        $this->assertEquals("", $subdivision);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentInvoiceeLegalOrganisation(): void
    {
        self::$document->getDocumentInvoiceeLegalOrganisation($legalorgid, $legalorgtype, $legalorgname);

        $this->assertEquals("987654321", $legalorgid);
        $this->assertEquals("0002", $legalorgtype);
        $this->assertEquals("INVOICEE_TRADING_NAME", $legalorgname);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentInvoiceeContact(): void
    {
        $this->assertTrue(self::$document->firstDocumentInvoiceeContact());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentInvoiceeContact(): void
    {
        $this->assertFalse(self::$document->nextDocumentInvoiceeContact());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentInvoiceeContact(): void
    {
        self::$document->firstDocumentInvoiceeContact();
        self::$document->getDocumentInvoiceeContact(
            $contactpersonname,
            $contactdepartmentname,
            $contactphoneno,
            $contactfaxno,
            $contactemailadd,
            $contacttypecode
        );

        $this->assertEquals("INVOICEE_CONTACT_NAME", $contactpersonname);
        $this->assertEquals("INVOICEE_CONTACT_DEP", $contactdepartmentname);
        $this->assertEquals("+33 6 65 98 75 32", $contactphoneno);
        $this->assertEquals("", $contactfaxno);
        $this->assertEquals("invoicee@buyer.com", $contactemailadd);
        $this->assertEquals("LB", $contacttypecode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentInvoiceeContactNoNext(): void
    {
        $this->expectException(\OutOfRangeException::class);

        self::$document->nextDocumentInvoiceeContact();
        self::$document->getDocumentInvoiceeContact(
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
    public function testGetDocumentInvoiceeElectronicAddress(): void
    {
        self::$document->getDocumentInvoiceeElectronicAddress($uriType, $uriId);

        $this->assertEquals("EM", $uriType);
        $this->assertEquals("invoicee@buyer.com", $uriId);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstGetDocumentPaymentMeans(): void
    {
        $this->assertTrue(self::$document->firstDocumentPaymentMeans());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextGetDocumentPaymentMeans(): void
    {
        $this->assertFalse(self::$document->nextDocumentPaymentMeans());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPaymentMeans(): void
    {
        self::$document->firstDocumentPaymentMeans();
        self::$document->getDocumentPaymentMeans($paymentMeansCode, $paymentMeansInformation);

        $this->assertEquals("30", $paymentMeansCode);
        $this->assertEquals("Credit Transfer", $paymentMeansInformation);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentAllowanceCharge(): void
    {
        $this->assertTrue(self::$document->firstDocumentAllowanceCharge());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentAllowanceCharge(): void
    {
        $this->assertTrue(self::$document->nextDocumentAllowanceCharge());
        $this->assertFalse(self::$document->nextDocumentAllowanceCharge());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentAllowanceCharge(): void
    {
        self::$document->firstDocumentAllowanceCharge();
        self::$document->getDocumentAllowanceCharge(
            $actualAmount,
            $isCharge,
            $taxCategoryCode,
            $taxTypeCode,
            $rateApplicablePercent,
            $sequence,
            $calculationPercent,
            $basisAmount,
            $basisQuantity,
            $basisQuantityUnitCode,
            $reasonCode,
            $reason
        );

        $this->assertEquals(31.00, $actualAmount);
        $this->assertEquals(false, $isCharge);
        $this->assertEquals("S", $taxCategoryCode);
        $this->assertEquals("VAT", $taxTypeCode);
        $this->assertEquals(20.00, $rateApplicablePercent);
        $this->assertEquals(0.0, $sequence);
        $this->assertEquals(10.00, $calculationPercent);
        $this->assertEquals(310.00, $basisAmount);
        $this->assertEquals(0.0, $basisQuantity);
        $this->assertEquals("", $basisQuantityUnitCode);
        $this->assertEquals("64", $reasonCode);
        $this->assertEquals("SPECIAL AGREEMENT", $reason);

        self::$document->nextDocumentAllowanceCharge();
        self::$document->getDocumentAllowanceCharge(
            $actualAmount,
            $isCharge,
            $taxCategoryCode,
            $taxTypeCode,
            $rateApplicablePercent,
            $sequence,
            $calculationPercent,
            $basisAmount,
            $basisQuantity,
            $basisQuantityUnitCode,
            $reasonCode,
            $reason
        );

        $this->assertEquals(21.00, $actualAmount);
        $this->assertEquals(true, $isCharge);
        $this->assertEquals("S", $taxCategoryCode);
        $this->assertEquals("VAT", $taxTypeCode);
        $this->assertEquals(20.00, $rateApplicablePercent);
        $this->assertEquals(0.0, $sequence);
        $this->assertEquals(10.00, $calculationPercent);
        $this->assertEquals(210.00, $basisAmount);
        $this->assertEquals(0.0, $basisQuantity);
        $this->assertEquals("", $basisQuantityUnitCode);
        $this->assertEquals("FC", $reasonCode);
        $this->assertEquals("FREIGHT SERVICES", $reason);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentAllowanceChargeNoNext(): void
    {
        $this->expectException(\OutOfRangeException::class);

        self::$document->nextDocumentAllowanceCharge();
        self::$document->getDocumentAllowanceCharge(
            $actualAmount,
            $isCharge,
            $taxCategoryCode,
            $taxTypeCode,
            $rateApplicablePercent,
            $sequence,
            $calculationPercent,
            $basisAmount,
            $basisQuantity,
            $basisQuantityUnitCode,
            $reasonCode,
            $reason
        );
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentPaymentTerms(): void
    {
        $this->assertTrue(self::$document->firstDocumentPaymentTerms());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentPaymentTerms(): void
    {
        $this->assertFalse(self::$document->nextDocumentPaymentTerms());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPaymentTerm(): void
    {
        self::$document->firstDocumentPaymentTerms();
        self::$document->getDocumentPaymentTerm($paymentTermsDescription);

        $this->assertEquals("PAYMENT_TERMS_DESC", $paymentTermsDescription);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentPosition(): void
    {
        $this->assertTrue(self::$document->firstDocumentPosition());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentPosition(): void
    {
        $this->assertTrue(self::$document->nextDocumentPosition());
        $this->assertTrue(self::$document->nextDocumentPosition());
        $this->assertFalse(self::$document->nextDocumentPosition());
    }
}
