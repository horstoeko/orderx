<?php

namespace horstoeko\orderx\tests\testcases;

use horstoeko\orderx\codelists\OrderDocumentTypes;
use horstoeko\orderx\exception\OrderNoValidAttachmentFoundInPdfException;
use horstoeko\orderx\OrderDocumentPdfReader;
use horstoeko\orderx\OrderProfiles;
use horstoeko\orderx\tests\TestCase;

class OrderDocumentPdfReaderExtendedTest extends TestCase
{
    /**
     * @var \horstoeko\orderx\OrderDocumentReader
     */
    protected static $document;

    /**
     * @covers \horstoeko\orderx\OrderDocumentPdfReader
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testPdfReadInvalid(): void
    {
        $this->expectException(OrderNoValidAttachmentFoundInPdfException::class);
        self::$document = OrderDocumentPdfReader::readAndGuessFromFile(dirname(__FILE__) . '/../assets/reader-invalid.pdf');
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentPdfReader
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testPdfRead(): void
    {
        self::$document = OrderDocumentPdfReader::readAndGuessFromFile(dirname(__FILE__) . '/../assets/reader-order-x-extended.pdf');
        $this->assertNotNull(self::$document);
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
        $this->assertFalse($documentIsCopy);
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
    public function testGetDocumentReceivableSpecifiedTradeAccountingAccount(): void
    {
        self::$document->getDocumentReceivableSpecifiedTradeAccountingAccount($id, $typeCode);

        $this->assertEquals("BUYER_ACCOUNT_REF", $id);
        $this->assertEquals("", $typeCode);
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

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionGenerals(): void
    {
        self::$document->firstDocumentPosition();
        self::$document->getDocumentPositionGenerals($lineid, $lineStatusCode);

        $this->assertEquals("1", $lineid);
        $this->assertEquals("", $lineStatusCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentPositionNotePos1(): void
    {
        self::$document->firstDocumentPosition();
        $this->assertTrue(self::$document->firstDocumentPositionNote());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentPositionNotePos1(): void
    {
        $this->assertFalse(self::$document->nextDocumentPositionNote());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentPositionNotePos2(): void
    {
        self::$document->nextDocumentPosition();
        $this->assertTrue(self::$document->firstDocumentPositionNote());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentPositionNotePos2(): void
    {
        $this->assertFalse(self::$document->nextDocumentPositionNote());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentPositionNotePos3(): void
    {
        self::$document->nextDocumentPosition();
        $this->assertTrue(self::$document->firstDocumentPositionNote());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentPositionNotePos3(): void
    {
        $this->assertFalse(self::$document->nextDocumentPositionNote());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionNotePos1(): void
    {
        self::$document->firstDocumentPosition();
        self::$document->getDocumentPositionNote($content, $contentCode, $subjectCode);

        $this->assertEquals("WEEE Tax of 0,50 euros per item included", $content);
        $this->assertEquals("", $contentCode);
        $this->assertEquals("TXD", $subjectCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionNotePos2(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionNote($content, $contentCode, $subjectCode);

        $this->assertEquals("WEEE Tax of 0,50 euros per item included", $content);
        $this->assertEquals("", $contentCode);
        $this->assertEquals("TXD", $subjectCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionNotePos3(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionNote($content, $contentCode, $subjectCode);

        $this->assertEquals("Content of Note", $content);
        $this->assertEquals("", $contentCode);
        $this->assertEquals("AAI", $subjectCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionProductDetailsPos1(): void
    {
        self::$document->firstDocumentPosition();
        self::$document->getDocumentPositionProductDetails(
            $name,
            $description,
            $sellerAssignedID,
            $buyerAssignedID,
            $globalID,
            $batchId,
            $brandName
        );

        $this->assertEquals("Product Name", $name);
        $this->assertEquals("Product Description", $description);
        $this->assertEquals("987654321", $sellerAssignedID);
        $this->assertEquals("654987321", $buyerAssignedID);
        $this->assertIsArray($globalID);
        $this->assertNotEmpty($globalID);
        $this->assertArrayHasKey("0160", $globalID);
        $this->assertEquals("1234567890123", $globalID["0160"]);
        $this->assertEquals("Product Batch ID (lot ID)", $batchId);
        $this->assertEquals("Product Brand Name", $brandName);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionProductDetailsPos2(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionProductDetails(
            $name,
            $description,
            $sellerAssignedID,
            $buyerAssignedID,
            $globalID,
            $batchId,
            $brandName
        );

        $this->assertEquals("Product Name", $name);
        $this->assertEquals("Product Description", $description);
        $this->assertEquals("598632147", $sellerAssignedID);
        $this->assertEquals("698569856", $buyerAssignedID);
        $this->assertIsArray($globalID);
        $this->assertNotEmpty($globalID);
        $this->assertArrayHasKey("0160", $globalID);
        $this->assertEquals("548796523", $globalID["0160"]);
        $this->assertEquals("Product Batch ID (lot ID)", $batchId);
        $this->assertEquals("Product Brand Name", $brandName);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionProductDetailsPos3(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionProductDetails(
            $name,
            $description,
            $sellerAssignedID,
            $buyerAssignedID,
            $globalID,
            $batchId,
            $brandName
        );

        $this->assertEquals("Product Name", $name);
        $this->assertEquals("Product Description", $description);
        $this->assertEquals("698325417", $sellerAssignedID);
        $this->assertEquals("598674321", $buyerAssignedID);
        $this->assertIsArray($globalID);
        $this->assertNotEmpty($globalID);
        $this->assertArrayHasKey("0160", $globalID);
        $this->assertEquals("854721548", $globalID["0160"]);
        $this->assertEquals("Product Batch ID (lot ID)", $batchId);
        $this->assertEquals("Product Brand Name", $brandName);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentPositionProductCharacteristicPos1(): void
    {
        self::$document->firstDocumentPosition();

        $this->assertTrue(self::$document->firstDocumentPositionProductCharacteristic());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentPositionProductCharacteristicPos1(): void
    {
        $this->assertFalse(self::$document->nextDocumentPositionProductCharacteristic());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentPositionProductCharacteristicPos2(): void
    {
        self::$document->nextDocumentPosition();

        $this->assertTrue(self::$document->firstDocumentPositionProductCharacteristic());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentPositionProductCharacteristicPos2(): void
    {
        $this->assertFalse(self::$document->nextDocumentPositionProductCharacteristic());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentPositionProductCharacteristicPos3(): void
    {
        self::$document->nextDocumentPosition();

        $this->assertTrue(self::$document->firstDocumentPositionProductCharacteristic());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentPositionProductCharacteristicPos3(): void
    {
        $this->assertFalse(self::$document->nextDocumentPositionProductCharacteristic());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionProductCharacteristicPos1(): void
    {
        self::$document->firstDocumentPosition();
        self::$document->firstDocumentPositionProductCharacteristic();
        self::$document->getDocumentPositionProductCharacteristic($description, $values, $typecode, $measureValue, $measureUnitCode);

        $this->assertEquals("Characteristic Description", $description);
        $this->assertIsArray($values);
        $this->assertNotEmpty($values);
        $this->assertArrayHasKey(0, $values);
        $this->assertArrayNotHasKey(1, $values);
        $this->assertEquals("5 meters", $values[0]);
        $this->assertEquals("Characteristic_Code", $typecode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionProductCharacteristicPos2(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->firstDocumentPositionProductCharacteristic();
        self::$document->getDocumentPositionProductCharacteristic($description, $values, $typecode, $measureValue, $measureUnitCode);

        $this->assertEquals("Characteristic Description", $description);
        $this->assertIsArray($values);
        $this->assertNotEmpty($values);
        $this->assertArrayHasKey(0, $values);
        $this->assertArrayNotHasKey(1, $values);
        $this->assertEquals("3 meters", $values[0]);
        $this->assertEquals("Characteristic_Code", $typecode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionProductCharacteristicPos3(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->firstDocumentPositionProductCharacteristic();
        self::$document->getDocumentPositionProductCharacteristic($description, $values, $typecode, $measureValue, $measureUnitCode);

        $this->assertEquals("Characteristic Description", $description);
        $this->assertIsArray($values);
        $this->assertNotEmpty($values);
        $this->assertArrayHasKey(0, $values);
        $this->assertArrayNotHasKey(1, $values);
        $this->assertEquals("3 meters", $values[0]);
        $this->assertEquals("Characteristic_Code", $typecode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentPositionProductClassificationPos1(): void
    {
        self::$document->firstDocumentPosition();

        $this->assertTrue(self::$document->firstDocumentPositionProductClassification());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentPositionProductClassificationPos1(): void
    {
        $this->assertFalse(self::$document->nextDocumentPositionProductClassification());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentPositionProductClassificationPos2(): void
    {
        self::$document->nextDocumentPosition();

        $this->assertTrue(self::$document->firstDocumentPositionProductClassification());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentPositionProductClassificationPos2(): void
    {
        $this->assertFalse(self::$document->nextDocumentPositionProductClassification());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentPositionProductClassificationPos3(): void
    {
        self::$document->nextDocumentPosition();

        $this->assertTrue(self::$document->firstDocumentPositionProductClassification());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentPositionProductClassificationPos3(): void
    {
        $this->assertFalse(self::$document->nextDocumentPositionProductClassification());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionProductClassificationPos1(): void
    {
        self::$document->firstDocumentPosition();
        self::$document->firstDocumentPositionProductClassification();
        self::$document->getDocumentPositionProductClassification($classCode, $className, $listID, $listVersionID);

        $this->assertEquals("Class_code", $classCode);
        $this->assertEquals("Name Class Codification", $className);
        $this->assertEquals("TST", $listID);
        $this->assertEquals("", $listVersionID);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionProductClassificationPos2(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->firstDocumentPositionProductClassification();
        self::$document->getDocumentPositionProductClassification($classCode, $className, $listID, $listVersionID);

        $this->assertEquals("Class_code", $classCode);
        $this->assertEquals("Name Class Codification", $className);
        $this->assertEquals("TST", $listID);
        $this->assertEquals("", $listVersionID);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionProductClassificationPos3(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->firstDocumentPositionProductClassification();
        self::$document->getDocumentPositionProductClassification($classCode, $className, $listID, $listVersionID);

        $this->assertEquals("Class_code", $classCode);
        $this->assertEquals("Name Class Codification", $className);
        $this->assertEquals("TST", $listID);
        $this->assertEquals("", $listVersionID);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentPositionProductInstancePos1(): void
    {
        self::$document->firstDocumentPosition();

        $this->assertTrue(self::$document->firstDocumentPositionProductInstance());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentPositionProductInstancePos1(): void
    {
        $this->assertFalse(self::$document->nextDocumentPositionProductInstance());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentPositionProductInstancePos2(): void
    {
        self::$document->nextDocumentPosition();

        $this->assertTrue(self::$document->firstDocumentPositionProductInstance());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentPositionProductInstancePos2(): void
    {
        $this->assertFalse(self::$document->nextDocumentPositionProductInstance());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentPositionProductInstancePos3(): void
    {
        self::$document->nextDocumentPosition();

        $this->assertTrue(self::$document->firstDocumentPositionProductInstance());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentPositionProductInstancePos3(): void
    {
        $this->assertFalse(self::$document->nextDocumentPositionProductInstance());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionProductInstancePos1(): void
    {
        self::$document->firstDocumentPosition();
        self::$document->firstDocumentPositionProductInstance();
        self::$document->getDocumentPositionProductInstance($batchID, $serialId);

        $this->assertEquals("Product Instances Batch ID", $batchID);
        $this->assertEquals("Product Instances Supplier Serial ID", $serialId);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionProductInstancePos2(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->firstDocumentPositionProductInstance();
        self::$document->getDocumentPositionProductInstance($batchID, $serialId);

        $this->assertEquals("Product Instances Batch ID", $batchID);
        $this->assertEquals("Product Instances Supplier Serial ID", $serialId);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionProductInstancePos3(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->firstDocumentPositionProductInstance();
        self::$document->getDocumentPositionProductInstance($batchID, $serialId);

        $this->assertEquals("Product Instances Batch ID", $batchID);
        $this->assertEquals("Product Instances Supplier Serial ID", $serialId);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionApplicableSupplyChainPackagingPos1(): void
    {
        self::$document->firstDocumentPosition();
        self::$document->getDocumentPositionSupplyChainPackaging(
            $typeCode,
            $width,
            $widthUnitCode,
            $length,
            $lengthUnitCode,
            $height,
            $heightUnitCode
        );

        $this->assertEquals("7B", $typeCode);
        $this->assertEquals(5.0, $width);
        $this->assertEquals("MTR", $widthUnitCode);
        $this->assertEquals(3.0, $length);
        $this->assertEquals("MTR", $lengthUnitCode);
        $this->assertEquals(1.0, $height);
        $this->assertEquals("MTR", $heightUnitCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionApplicableSupplyChainPackagingPos2(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionSupplyChainPackaging(
            $typeCode,
            $width,
            $widthUnitCode,
            $length,
            $lengthUnitCode,
            $height,
            $heightUnitCode
        );

        $this->assertEquals("7B", $typeCode);
        $this->assertEquals(2.0, $width);
        $this->assertEquals("MTR", $widthUnitCode);
        $this->assertEquals(1.0, $length);
        $this->assertEquals("MTR", $lengthUnitCode);
        $this->assertEquals(3.0, $height);
        $this->assertEquals("MTR", $heightUnitCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionApplicableSupplyChainPackagingPos3(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionSupplyChainPackaging(
            $typeCode,
            $width,
            $widthUnitCode,
            $length,
            $lengthUnitCode,
            $height,
            $heightUnitCode
        );

        $this->assertEquals("7B", $typeCode);
        $this->assertEquals(2.0, $width);
        $this->assertEquals("MTR", $widthUnitCode);
        $this->assertEquals(1.0, $length);
        $this->assertEquals("MTR", $lengthUnitCode);
        $this->assertEquals(3.0, $height);
        $this->assertEquals("MTR", $heightUnitCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionProductOriginTradeCountryPos1(): void
    {
        self::$document->firstDocumentPosition();
        self::$document->getDocumentPositionProductOriginTradeCountry($country);

        $this->assertEquals("FR", $country);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionProductOriginTradeCountryPos2(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionProductOriginTradeCountry($country);

        $this->assertEquals("FR", $country);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionProductOriginTradeCountryPos3(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionProductOriginTradeCountry($country);

        $this->assertEquals("FR", $country);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentPositionProductReferencedDocumentPos1(): void
    {
        self::$document->firstDocumentPosition();

        $this->assertTrue(self::$document->firstDocumentPositionProductReferencedDocument());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentPositionProductReferencedDocumentPos1(): void
    {
        $this->assertFalse(self::$document->nextDocumentPositionProductReferencedDocument());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentPositionProductReferencedDocumentPos2(): void
    {
        self::$document->nextDocumentPosition();

        $this->assertTrue(self::$document->firstDocumentPositionProductReferencedDocument());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentPositionProductReferencedDocumentPos2(): void
    {
        $this->assertFalse(self::$document->nextDocumentPositionProductReferencedDocument());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentPositionProductReferencedDocumentPos3(): void
    {
        self::$document->nextDocumentPosition();

        $this->assertTrue(self::$document->firstDocumentPositionProductReferencedDocument());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentPositionProductReferencedDocumentPos3(): void
    {
        $this->assertFalse(self::$document->nextDocumentPositionProductReferencedDocument());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionProductReferencedDocumentPos1(): void
    {
        self::$document->firstDocumentPosition();
        self::$document->firstDocumentPositionProductReferencedDocument();
        self::$document->getDocumentPositionProductReferencedDocument(
            $issuerassignedid,
            $typecode,
            $uriid,
            $lineid,
            $name,
            $reftypecode,
            $issueddate
        );

        $this->assertEquals("ADD_REF_PROD_ID", $issuerassignedid);
        $this->assertEquals("6", $typecode);
        $this->assertEquals("ADD_REF_PROD_URIID", $uriid);
        $this->assertEquals("", $lineid);
        $this->assertEquals("ADD_REF_PROD_Desc", $name);
        $this->assertEquals("", $reftypecode);
        $this->assertNull($issueddate);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionProductReferencedDocumentPos2(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->firstDocumentPositionProductReferencedDocument();
        self::$document->getDocumentPositionProductReferencedDocument(
            $issuerassignedid,
            $typecode,
            $uriid,
            $lineid,
            $name,
            $reftypecode,
            $issueddate
        );

        $this->assertEquals("ADD_REF_PROD_ID", $issuerassignedid);
        $this->assertEquals("6", $typecode);
        $this->assertEquals("ADD_REF_PROD_URIID", $uriid);
        $this->assertEquals("", $lineid);
        $this->assertEquals("ADD_REF_PROD_Desc", $name);
        $this->assertEquals("", $reftypecode);
        $this->assertNull($issueddate);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionProductReferencedDocumentPos3(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->firstDocumentPositionProductReferencedDocument();
        self::$document->getDocumentPositionProductReferencedDocument(
            $issuerassignedid,
            $typecode,
            $uriid,
            $lineid,
            $name,
            $reftypecode,
            $issueddate
        );

        $this->assertEquals("ADD_REF_PROD_ID", $issuerassignedid);
        $this->assertEquals("6", $typecode);
        $this->assertEquals("ADD_REF_PROD_URIID", $uriid);
        $this->assertEquals("", $lineid);
        $this->assertEquals("ADD_REF_PROD_Desc", $name);
        $this->assertEquals("", $reftypecode);
        $this->assertNull($issueddate);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionBuyerOrderReferencedDocumentPos1(): void
    {
        self::$document->firstDocumentPosition();
        self::$document->getDocumentPositionBuyerOrderReferencedDocument($buyerOrderRefLineId);

        $this->assertEquals("1", $buyerOrderRefLineId);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionBuyerOrderReferencedDocumentPos2(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionBuyerOrderReferencedDocument($buyerOrderRefLineId);

        $this->assertEquals("3", $buyerOrderRefLineId);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionBuyerOrderReferencedDocumentPos3(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionBuyerOrderReferencedDocument($buyerOrderRefLineId);

        $this->assertEquals("4", $buyerOrderRefLineId);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionQuotationReferencedDocumentPos1(): void
    {
        self::$document->firstDocumentPosition();
        self::$document->getDocumentPositionQuotationReferencedDocument($quotationRefId, $quotationRefLineId, $quotationRefDate);

        $this->assertEquals("QUOT_125487", $quotationRefId);
        $this->assertEquals("3", $quotationRefLineId);
        $this->assertNull($quotationRefDate);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionQuotationReferencedDocumentPos2(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionQuotationReferencedDocument($quotationRefId, $quotationRefLineId, $quotationRefDate);

        $this->assertEquals("QUOT_125487", $quotationRefId);
        $this->assertEquals("2", $quotationRefLineId);
        $this->assertNull($quotationRefDate);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionQuotationReferencedDocumentPos3(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionQuotationReferencedDocument($quotationRefId, $quotationRefLineId, $quotationRefDate);

        $this->assertEquals("QUOT_125487", $quotationRefId);
        $this->assertEquals("1", $quotationRefLineId);
        $this->assertNull($quotationRefDate);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentPositionAdditionalReferencedDocumentPos1(): void
    {
        self::$document->firstDocumentPosition();

        $this->assertTrue(self::$document->firstDocumentPositionAdditionalReferencedDocument());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentPositionAdditionalReferencedDocumentPos1(): void
    {
        $this->assertTrue(self::$document->nextDocumentPositionAdditionalReferencedDocument());
        $this->assertFalse(self::$document->nextDocumentPositionAdditionalReferencedDocument());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentPositionAdditionalReferencedDocumentPos2(): void
    {
        self::$document->nextDocumentPosition();

        $this->assertTrue(self::$document->firstDocumentPositionAdditionalReferencedDocument());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentPositionAdditionalReferencedDocumentPos2(): void
    {
        $this->assertTrue(self::$document->nextDocumentPositionAdditionalReferencedDocument());
        $this->assertFalse(self::$document->nextDocumentPositionAdditionalReferencedDocument());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentPositionAdditionalReferencedDocumentPos3(): void
    {
        self::$document->nextDocumentPosition();

        $this->assertTrue(self::$document->firstDocumentPositionAdditionalReferencedDocument());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentPositionAdditionalReferencedDocumentPos3(): void
    {
        $this->assertTrue(self::$document->nextDocumentPositionAdditionalReferencedDocument());
        $this->assertFalse(self::$document->nextDocumentPositionAdditionalReferencedDocument());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionAdditionalReferencedDocumentPos1(): void
    {
        self::$document->firstDocumentPosition();

        self::$document->firstDocumentPositionAdditionalReferencedDocument();
        self::$document->getDocumentPositionAdditionalReferencedDocument(
            $issuerassignedid,
            $typecode,
            $uriid,
            $lineid,
            $name,
            $reftypecode,
            $issueddate
        );

        $this->assertEquals("ADD_REF_DOC_ID", $issuerassignedid);
        $this->assertEquals("916", $typecode);
        $this->assertEquals("ADD_REF_DOC_URIID", $uriid);
        $this->assertEquals("5", $lineid);
        $this->assertEquals("ADD_REF_DOC_Desc", $name);
        $this->assertEquals("", $reftypecode);
        $this->assertNull($issueddate);

        self::$document->nextDocumentPositionAdditionalReferencedDocument();
        self::$document->getDocumentPositionAdditionalReferencedDocument(
            $issuerassignedid,
            $typecode,
            $uriid,
            $lineid,
            $name,
            $reftypecode,
            $issueddate
        );

        $this->assertEquals("OBJECT_125487", $issuerassignedid);
        $this->assertEquals("130", $typecode);
        $this->assertEquals("", $uriid);
        $this->assertEquals("", $lineid);
        $this->assertEquals("", $name);
        $this->assertEquals("AWV", $reftypecode);
        $this->assertNull($issueddate);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionAdditionalReferencedDocumentPos2(): void
    {
        self::$document->nextDocumentPosition();

        self::$document->firstDocumentPositionAdditionalReferencedDocument();
        self::$document->getDocumentPositionAdditionalReferencedDocument(
            $issuerassignedid,
            $typecode,
            $uriid,
            $lineid,
            $name,
            $reftypecode,
            $issueddate
        );

        $this->assertEquals("ADD_REF_DOC_ID", $issuerassignedid);
        $this->assertEquals("916", $typecode);
        $this->assertEquals("ADD_REF_DOC_URIID", $uriid);
        $this->assertEquals("5", $lineid);
        $this->assertEquals("ADD_REF_DOC_Desc", $name);
        $this->assertEquals("", $reftypecode);
        $this->assertNull($issueddate);

        self::$document->nextDocumentPositionAdditionalReferencedDocument();
        self::$document->getDocumentPositionAdditionalReferencedDocument(
            $issuerassignedid,
            $typecode,
            $uriid,
            $lineid,
            $name,
            $reftypecode,
            $issueddate
        );

        $this->assertEquals("OBJECT_125487", $issuerassignedid);
        $this->assertEquals("130", $typecode);
        $this->assertEquals("", $uriid);
        $this->assertEquals("", $lineid);
        $this->assertEquals("", $name);
        $this->assertEquals("AWV", $reftypecode);
        $this->assertNull($issueddate);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionAdditionalReferencedDocumentPos3(): void
    {
        self::$document->nextDocumentPosition();

        self::$document->firstDocumentPositionAdditionalReferencedDocument();
        self::$document->getDocumentPositionAdditionalReferencedDocument(
            $issuerassignedid,
            $typecode,
            $uriid,
            $lineid,
            $name,
            $reftypecode,
            $issueddate
        );

        $this->assertEquals("ADD_REF_DOC_ID", $issuerassignedid);
        $this->assertEquals("916", $typecode);
        $this->assertEquals("ADD_REF_DOC_URIID", $uriid);
        $this->assertEquals("5", $lineid);
        $this->assertEquals("ADD_REF_DOC_Desc", $name);
        $this->assertEquals("", $reftypecode);
        $this->assertNull($issueddate);

        self::$document->nextDocumentPositionAdditionalReferencedDocument();
        self::$document->getDocumentPositionAdditionalReferencedDocument(
            $issuerassignedid,
            $typecode,
            $uriid,
            $lineid,
            $name,
            $reftypecode,
            $issueddate
        );

        $this->assertEquals("OBJECT_125487", $issuerassignedid);
        $this->assertEquals("130", $typecode);
        $this->assertEquals("", $uriid);
        $this->assertEquals("", $lineid);
        $this->assertEquals("", $name);
        $this->assertEquals("AWV", $reftypecode);
        $this->assertNull($issueddate);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionGrossPricePos1(): void
    {
        self::$document->firstDocumentPosition();
        self::$document->getDocumentPositionGrossPrice($chargeAmount, $basisQuantity, $basisQuantityUnitCode);

        $this->assertEquals(10.50, $chargeAmount);
        $this->assertEquals(1.00, $basisQuantity);
        $this->assertEquals("C62", $basisQuantityUnitCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentPositionGrossPriceAllowanceChargePos1(): void
    {
        self::$document->firstDocumentPosition();
        $this->assertTrue(self::$document->firstDocumentPositionGrossPriceAllowanceCharge());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentPositionGrossPriceAllowanceChargePos1(): void
    {
        $this->assertTrue(self::$document->nextDocumentPositionGrossPriceAllowanceCharge());
        $this->assertFalse(self::$document->nextDocumentPositionGrossPriceAllowanceCharge());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentPositionGrossPriceAllowanceChargePos2(): void
    {
        self::$document->nextDocumentPosition();
        $this->assertTrue(self::$document->firstDocumentPositionGrossPriceAllowanceCharge());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentPositionGrossPriceAllowanceChargePos2(): void
    {
        $this->assertFalse(self::$document->nextDocumentPositionGrossPriceAllowanceCharge());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentPositionGrossPriceAllowanceChargePos3(): void
    {
        self::$document->nextDocumentPosition();
        $this->assertTrue(self::$document->firstDocumentPositionGrossPriceAllowanceCharge());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentPositionGrossPriceAllowanceChargePos3(): void
    {
        $this->assertFalse(self::$document->nextDocumentPositionGrossPriceAllowanceCharge());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionGrossPriceAllowanceChargePos1(): void
    {
        self::$document->firstDocumentPosition();
        self::$document->firstDocumentPositionGrossPriceAllowanceCharge();
        self::$document->getDocumentPositionGrossPriceAllowanceCharge($actualAmount, $isCharge, $calculationPercent, $basisAmount, $reason, $taxTypeCode, $taxCategoryCode, $rateApplicablePercent, $sequence, $basisQuantity, $basisQuantityUnitCode, $reasonCode);

        $this->assertEquals(1.00, $actualAmount);
        $this->assertEquals(false, $isCharge);
        $this->assertEquals(0, $calculationPercent);
        $this->assertEquals(0, $basisAmount);
        $this->assertEquals("DISCOUNT", $reason);
        $this->assertEquals("", $taxTypeCode);
        $this->assertEquals("", $taxCategoryCode);
        $this->assertEquals(0.0, $rateApplicablePercent);
        $this->assertEquals(0, $sequence);
        $this->assertEquals(0, $basisQuantity);
        $this->assertEquals("", $basisQuantityUnitCode);
        $this->assertEquals("95", $reasonCode);

        self::$document->nextDocumentPositionGrossPriceAllowanceCharge();
        self::$document->getDocumentPositionGrossPriceAllowanceCharge($actualAmount, $isCharge, $calculationPercent, $basisAmount, $reason, $taxTypeCode, $taxCategoryCode, $rateApplicablePercent, $sequence, $basisQuantity, $basisQuantityUnitCode, $reasonCode);

        $this->assertEquals(0.50, $actualAmount);
        $this->assertEquals(true, $isCharge);
        $this->assertEquals(0, $calculationPercent);
        $this->assertEquals(0, $basisAmount);
        $this->assertEquals("WEEE", $reason);
        $this->assertEquals("", $taxTypeCode);
        $this->assertEquals("", $taxCategoryCode);
        $this->assertEquals(0.0, $rateApplicablePercent);
        $this->assertEquals(0, $sequence);
        $this->assertEquals(0, $basisQuantity);
        $this->assertEquals("", $basisQuantityUnitCode);
        $this->assertEquals("AEW", $reasonCode);

        $this->assertFalse(self::$document->nextDocumentPositionGrossPriceAllowanceCharge());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionGrossPriceAllowanceChargePos2(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->firstDocumentPositionGrossPriceAllowanceCharge();
        self::$document->getDocumentPositionGrossPriceAllowanceCharge($actualAmount, $isCharge, $calculationPercent, $basisAmount, $reason, $taxTypeCode, $taxCategoryCode, $rateApplicablePercent, $sequence, $basisQuantity, $basisQuantityUnitCode, $reasonCode);

        $this->assertEquals(0.50, $actualAmount);
        $this->assertEquals(true, $isCharge);
        $this->assertEquals(0, $calculationPercent);
        $this->assertEquals(0, $basisAmount);
        $this->assertEquals("WEEE TAX", $reason);
        $this->assertEquals("", $taxTypeCode);
        $this->assertEquals("", $taxCategoryCode);
        $this->assertEquals(0.0, $rateApplicablePercent);
        $this->assertEquals(0, $sequence);
        $this->assertEquals(0, $basisQuantity);
        $this->assertEquals("", $basisQuantityUnitCode);
        $this->assertEquals("AEW", $reasonCode);

        $this->assertFalse(self::$document->nextDocumentPositionGrossPriceAllowanceCharge());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionGrossPriceAllowanceChargePos3(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->firstDocumentPositionGrossPriceAllowanceCharge();
        self::$document->getDocumentPositionGrossPriceAllowanceCharge($actualAmount, $isCharge, $calculationPercent, $basisAmount, $reason, $taxTypeCode, $taxCategoryCode, $rateApplicablePercent, $sequence, $basisQuantity, $basisQuantityUnitCode, $reasonCode);

        $this->assertEquals(5.00, $actualAmount);
        $this->assertEquals(false, $isCharge);
        $this->assertEquals(0, $calculationPercent);
        $this->assertEquals(0, $basisAmount);
        $this->assertEquals("", $reason);
        $this->assertEquals("", $taxTypeCode);
        $this->assertEquals("", $taxCategoryCode);
        $this->assertEquals(0.0, $rateApplicablePercent);
        $this->assertEquals(0, $sequence);
        $this->assertEquals(0, $basisQuantity);
        $this->assertEquals("", $basisQuantityUnitCode);
        $this->assertEquals("", $reasonCode);

        $this->assertFalse(self::$document->nextDocumentPositionGrossPriceAllowanceCharge());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionNetPricePos1(): void
    {
        self::$document->firstDocumentPosition();
        self::$document->getDocumentPositionNetPrice($amount, $basisQuantity, $basisQuantityUnitCode);

        $this->assertEquals(10.00, $amount);
        $this->assertEquals(1.00, $basisQuantity);
        $this->assertEquals("C62", $basisQuantityUnitCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionNetPricePos2(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionNetPrice($amount, $basisQuantity, $basisQuantityUnitCode);

        $this->assertEquals(20.00, $amount);
        $this->assertEquals(2.00, $basisQuantity);
        $this->assertEquals("C62", $basisQuantityUnitCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionNetPricePos3(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionNetPrice($amount, $basisQuantity, $basisQuantityUnitCode);

        $this->assertEquals(25.00, $amount);
        $this->assertEquals(1.00, $basisQuantity);
        $this->assertEquals("C62", $basisQuantityUnitCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionNetPriceTaxPos1(): void
    {
        self::$document->firstDocumentPosition();
        self::$document->getDocumentPositionNetPriceTax(
            $categoryCode,
            $typeCode,
            $rateApplicablePercent,
            $calculatedAmount,
            $exemptionReason,
            $exemptionReasonCode
        );

        $this->assertEquals("", $categoryCode);
        $this->assertEquals("", $typeCode);
        $this->assertEquals(0.00, $rateApplicablePercent);
        $this->assertEquals(0.00, $calculatedAmount);
        $this->assertEquals("", $exemptionReason);
        $this->assertEquals("", $exemptionReasonCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionNetPriceTaxPos2(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionNetPriceTax(
            $categoryCode,
            $typeCode,
            $rateApplicablePercent,
            $calculatedAmount,
            $exemptionReason,
            $exemptionReasonCode
        );

        $this->assertEquals("", $categoryCode);
        $this->assertEquals("", $typeCode);
        $this->assertEquals(0.00, $rateApplicablePercent);
        $this->assertEquals(0.00, $calculatedAmount);
        $this->assertEquals("", $exemptionReason);
        $this->assertEquals("", $exemptionReasonCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionNetPriceTaxPos3(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionNetPriceTax(
            $categoryCode,
            $typeCode,
            $rateApplicablePercent,
            $calculatedAmount,
            $exemptionReason,
            $exemptionReasonCode
        );

        $this->assertEquals("", $categoryCode);
        $this->assertEquals("", $typeCode);
        $this->assertEquals(0.00, $rateApplicablePercent);
        $this->assertEquals(0.00, $calculatedAmount);
        $this->assertEquals("", $exemptionReason);
        $this->assertEquals("", $exemptionReasonCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionCatalogueReferencedDocumentPos1(): void
    {
        self::$document->firstDocumentPosition();
        self::$document->getDocumentPositionCatalogueReferencedDocument($catalogueRefId, $catalogueRefLineId, $catalogueRefDate);

        $this->assertEquals("CATALOG_REF_ID", $catalogueRefId);
        $this->assertEquals("2", $catalogueRefLineId);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionCatalogueReferencedDocumentPos2(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionCatalogueReferencedDocument($catalogueRefId, $catalogueRefLineId, $catalogueRefDate);

        $this->assertEquals("CATALOG_REF_ID", $catalogueRefId);
        $this->assertEquals("2", $catalogueRefLineId);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionCatalogueReferencedDocumentPos3(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionCatalogueReferencedDocument($catalogueRefId, $catalogueRefLineId, $catalogueRefDate);

        $this->assertEquals("CATALOG_REF_ID", $catalogueRefId);
        $this->assertEquals("5", $catalogueRefLineId);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionBlanketOrderReferencedDocumentPos1(): void
    {
        self::$document->firstDocumentPosition();
        self::$document->getDocumentPositionBlanketOrderReferencedDocument($blanketOrderRefLineId);

        $this->assertEquals("2", $blanketOrderRefLineId);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionBlanketOrderReferencedDocumentPos2(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionBlanketOrderReferencedDocument($blanketOrderRefLineId);

        $this->assertEquals("3", $blanketOrderRefLineId);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionBlanketOrderReferencedDocumentPos3(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionBlanketOrderReferencedDocument($blanketOrderRefLineId);

        $this->assertEquals("4", $blanketOrderRefLineId);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionPartialDeliveryPos1(): void
    {
        self::$document->firstDocumentPosition();
        self::$document->getDocumentPositionPartialDelivery($partialDelivery);

        $this->assertTrue($partialDelivery);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionPartialDeliveryPos2(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionPartialDelivery($partialDelivery);

        $this->assertTrue($partialDelivery);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionPartialDeliveryPos3(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionPartialDelivery($partialDelivery);

        $this->assertTrue($partialDelivery);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionDeliverReqQuantityPos1(): void
    {
        self::$document->firstDocumentPosition();
        self::$document->getDocumentPositionDeliverReqQuantity($requestedQuantity, $requestedQuantityUnitCode);

        $this->assertEquals(6.0, $requestedQuantity);
        $this->assertEquals("C62", $requestedQuantityUnitCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionDeliverReqQuantityPos2(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionDeliverReqQuantity($requestedQuantity, $requestedQuantityUnitCode);

        $this->assertEquals(10.0, $requestedQuantity);
        $this->assertEquals("C62", $requestedQuantityUnitCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionDeliverReqQuantityPos3(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionDeliverReqQuantity($requestedQuantity, $requestedQuantityUnitCode);

        $this->assertEquals(6.0, $requestedQuantity);
        $this->assertEquals("C62", $requestedQuantityUnitCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionDeliverPackageQuantityPos1(): void
    {
        self::$document->firstDocumentPosition();
        self::$document->getDocumentPositionDeliverPackageQuantity($packageQuantity, $packageQuantityUnitCode);

        $this->assertEquals(3.0, $packageQuantity);
        $this->assertEquals("C62", $packageQuantityUnitCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionDeliverPackageQuantityPos2(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionDeliverPackageQuantity($packageQuantity, $packageQuantityUnitCode);

        $this->assertEquals(5.0, $packageQuantity);
        $this->assertEquals("C62", $packageQuantityUnitCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionDeliverPackageQuantityPos3(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionDeliverPackageQuantity($packageQuantity, $packageQuantityUnitCode);

        $this->assertEquals(3.0, $packageQuantity);
        $this->assertEquals("C62", $packageQuantityUnitCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionDeliverPerPackageQuantityPos1(): void
    {
        self::$document->firstDocumentPosition();
        self::$document->getDocumentPositionDeliverPerPackageQuantity($perPackageQuantity, $perPackageQuantityUnitCode);

        $this->assertEquals(2.0, $perPackageQuantity);
        $this->assertEquals("C62", $perPackageQuantityUnitCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionDeliverPerPackageQuantityPos2(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionDeliverPerPackageQuantity($perPackageQuantity, $perPackageQuantityUnitCode);

        $this->assertEquals(2.0, $perPackageQuantity);
        $this->assertEquals("C62", $perPackageQuantityUnitCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionDeliverPerPackageQuantityPos3(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionDeliverPerPackageQuantity($perPackageQuantity, $perPackageQuantityUnitCode);

        $this->assertEquals(2.0, $perPackageQuantity);
        $this->assertEquals("C62", $perPackageQuantityUnitCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionDeliverAgreedQuantityPos1(): void
    {
        self::$document->firstDocumentPosition();
        self::$document->getDocumentPositionDeliverAgreedQuantity($agreedQuantity, $agreedQuantityUnitCode);

        $this->assertEquals(0.0, $agreedQuantity);
        $this->assertEquals("", $agreedQuantityUnitCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionDeliverAgreedQuantityPos2(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionDeliverAgreedQuantity($agreedQuantity, $agreedQuantityUnitCode);

        $this->assertEquals(0.0, $agreedQuantity);
        $this->assertEquals("", $agreedQuantityUnitCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionDeliverAgreedQuantityPos3(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionDeliverAgreedQuantity($agreedQuantity, $agreedQuantityUnitCode);

        $this->assertEquals(0.0, $agreedQuantity);
        $this->assertEquals("", $agreedQuantityUnitCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentPositionRequestedDeliverySupplyChainEventPos1(): void
    {
        self::$document->firstDocumentPosition();
        $this->assertTrue(self::$document->firstDocumentPositionRequestedDeliverySupplyChainEvent());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentPositionRequestedDeliverySupplyChainEventPos1(): void
    {
        $this->assertFalse(self::$document->nextDocumentPositionRequestedDeliverySupplyChainEvent());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentPositionRequestedDeliverySupplyChainEventPos2(): void
    {
        self::$document->nextDocumentPosition();
        $this->assertTrue(self::$document->firstDocumentPositionRequestedDeliverySupplyChainEvent());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentPositionRequestedDeliverySupplyChainEventPos2(): void
    {
        $this->assertFalse(self::$document->nextDocumentPositionRequestedDeliverySupplyChainEvent());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentPositionRequestedDeliverySupplyChainEventPos3(): void
    {
        self::$document->nextDocumentPosition();
        $this->assertTrue(self::$document->firstDocumentPositionRequestedDeliverySupplyChainEvent());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentPositionRequestedDeliverySupplyChainEventPos3(): void
    {
        $this->assertFalse(self::$document->nextDocumentPositionRequestedDeliverySupplyChainEvent());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionRequestedDeliverySupplyChainEventPos1(): void
    {
        self::$document->firstDocumentPosition();
        self::$document->getDocumentPositionRequestedDeliverySupplyChainEvent($occurrenceDateTime, $startDateTime, $endDateTime);

        $this->assertNull($occurrenceDateTime);
        $this->assertEquals("21.12.2022", $startDateTime->format('d.m.Y'));
        $this->assertEquals("21.12.2022", $endDateTime->format('d.m.Y'));
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionRequestedDeliverySupplyChainEventPos2(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionRequestedDeliverySupplyChainEvent($occurrenceDateTime, $startDateTime, $endDateTime);

        $this->assertEquals("21.12.2022", $occurrenceDateTime->format('d.m.Y'));
        $this->assertNull($startDateTime);
        $this->assertNull($endDateTime);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionRequestedDeliverySupplyChainEventPos3(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionRequestedDeliverySupplyChainEvent($occurrenceDateTime, $startDateTime, $endDateTime);

        $this->assertNull($occurrenceDateTime);
        $this->assertEquals("21.12.2022", $startDateTime->format('d.m.Y'));
        $this->assertEquals("21.12.2022", $endDateTime->format('d.m.Y'));
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentPositionAllowanceChargePos1(): void
    {
        self::$document->firstDocumentPosition();

        $this->assertTrue(self::$document->firstDocumentPositionAllowanceCharge());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentPositionAllowanceChargePos1(): void
    {
        $this->assertTrue(self::$document->nextDocumentPositionAllowanceCharge());
        $this->assertFalse(self::$document->nextDocumentPositionAllowanceCharge());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentPositionAllowanceChargePos2(): void
    {
        self::$document->nextDocumentPosition();

        $this->assertTrue(self::$document->firstDocumentPositionAllowanceCharge());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentPositionAllowanceChargePos2(): void
    {
        $this->assertTrue(self::$document->nextDocumentPositionAllowanceCharge());
        $this->assertFalse(self::$document->nextDocumentPositionAllowanceCharge());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testFirstDocumentPositionAllowanceChargePos3(): void
    {
        self::$document->nextDocumentPosition();

        $this->assertTrue(self::$document->firstDocumentPositionAllowanceCharge());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testNextDocumentPositionAllowanceChargePos3(): void
    {
        $this->assertTrue(self::$document->nextDocumentPositionAllowanceCharge());
        $this->assertFalse(self::$document->nextDocumentPositionAllowanceCharge());
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionAllowanceChargePos1(): void
    {
        self::$document->firstDocumentPosition();

        self::$document->getDocumentPositionAllowanceCharge(
            $actualAmount,
            $isCharge,
            $calculationPercent,
            $basisAmount,
            $reasonCode,
            $reason
        );

        $this->assertEquals(6.00, $actualAmount);
        $this->assertFalse($isCharge);
        $this->assertEquals(10.00, $calculationPercent);
        $this->assertEquals(60.00, $basisAmount);
        $this->assertEquals("SPECIAL AGREEMENT", $reason);

        self::$document->nextDocumentPositionAllowanceCharge();
        self::$document->getDocumentPositionAllowanceCharge(
            $actualAmount,
            $isCharge,
            $calculationPercent,
            $basisAmount,
            $reasonCode,
            $reason
        );

        $this->assertEquals(6.00, $actualAmount);
        $this->assertTrue($isCharge);
        $this->assertEquals(10.00, $calculationPercent);
        $this->assertEquals(60.00, $basisAmount);
        $this->assertEquals("FREIGHT SERVICES", $reason);
        $this->assertEquals("FC", $reasonCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionAllowanceChargePos2(): void
    {
        self::$document->nextDocumentPosition();

        self::$document->getDocumentPositionAllowanceCharge(
            $actualAmount,
            $isCharge,
            $calculationPercent,
            $basisAmount,
            $reasonCode,
            $reason
        );

        $this->assertEquals(1.00, $actualAmount);
        $this->assertFalse($isCharge);
        $this->assertEquals(1.00, $calculationPercent);
        $this->assertEquals(100.00, $basisAmount);
        $this->assertEquals("SPECIAL AGREEMENT", $reason);
        $this->assertEquals("64", $reasonCode);

        self::$document->nextDocumentPositionAllowanceCharge();
        self::$document->getDocumentPositionAllowanceCharge(
            $actualAmount,
            $isCharge,
            $calculationPercent,
            $basisAmount,
            $reasonCode,
            $reason
        );

        $this->assertEquals(1.00, $actualAmount);
        $this->assertTrue($isCharge);
        $this->assertEquals(1.00, $calculationPercent);
        $this->assertEquals(100.00, $basisAmount);
        $this->assertEquals("FREIGHT SERVICES", $reason);
        $this->assertEquals("FC", $reasonCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionAllowanceChargePos3(): void
    {
        self::$document->nextDocumentPosition();

        self::$document->getDocumentPositionAllowanceCharge(
            $actualAmount,
            $isCharge,
            $calculationPercent,
            $basisAmount,
            $reasonCode,
            $reason
        );

        $this->assertEquals(15.00, $actualAmount);
        $this->assertFalse($isCharge);
        $this->assertEquals(10.00, $calculationPercent);
        $this->assertEquals(150.00, $basisAmount);
        $this->assertEquals("SPECIAL AGREEMENT", $reason);
        $this->assertEquals("64", $reasonCode);

        self::$document->nextDocumentPositionAllowanceCharge();
        self::$document->getDocumentPositionAllowanceCharge(
            $actualAmount,
            $isCharge,
            $calculationPercent,
            $basisAmount,
            $reasonCode,
            $reason
        );

        $this->assertEquals(15.00, $actualAmount);
        $this->assertTrue($isCharge);
        $this->assertEquals(10.00, $calculationPercent);
        $this->assertEquals(150.00, $basisAmount);
        $this->assertEquals("FREIGHT SERVICES", $reason);
        $this->assertEquals("FC", $reasonCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionLineSummationPos1(): void
    {
        self::$document->firstDocumentPosition();
        self::$document->getDocumentPositionLineSummation($lineTotalAmount, $totalAllowanceChargeAmount);

        $this->assertEquals(60.00, $lineTotalAmount);
        $this->assertEquals(0.00, $totalAllowanceChargeAmount);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionLineSummationPos2(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionLineSummation($lineTotalAmount, $totalAllowanceChargeAmount);

        $this->assertEquals(100.00, $lineTotalAmount);
        $this->assertEquals(0.00, $totalAllowanceChargeAmount);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionLineSummationPos3(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionLineSummation($lineTotalAmount, $totalAllowanceChargeAmount);

        $this->assertEquals(150.00, $lineTotalAmount);
        $this->assertEquals(0.00, $totalAllowanceChargeAmount);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionReceivableTradeAccountingAccountPos1(): void
    {
        self::$document->firstDocumentPosition();
        self::$document->getDocumentPositionReceivableTradeAccountingAccount($id, $typeCode);

        $this->assertEquals("BUYER_ACCOUNTING_REF", $id);
        $this->assertEquals("", $typeCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionReceivableTradeAccountingAccountPos2(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionReceivableTradeAccountingAccount($id, $typeCode);

        $this->assertEquals("BUYER_ACCOUNTING_REF", $id);
        $this->assertEquals("", $typeCode);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentReader
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDocumentPositionReceivableTradeAccountingAccountPos3(): void
    {
        self::$document->nextDocumentPosition();
        self::$document->getDocumentPositionReceivableTradeAccountingAccount($id, $typeCode);

        $this->assertEquals("BUYER_ACCOUNTING_REF", $id);
        $this->assertEquals("", $typeCode);
    }
}
