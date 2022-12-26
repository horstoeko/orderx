<?php

namespace horstoeko\orderx\tests\testcases;

use horstoeko\orderx\OrderProfiles;
use horstoeko\orderx\tests\TestCase;
use horstoeko\orderx\OrderObjectHelper;
use horstoeko\stringmanagement\FileUtils;
use horstoeko\orderx\exception\OrderMimeTypeNotSupportedException;

class OrderObjectHelperExtendedTest extends TestCase
{
    /**
     * @var \horstoeko\orderx\OrderObjectHelper;
     */
    protected static $objectHelper;

    /**
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testConstruct(): void
    {
        self::$objectHelper = new OrderObjectHelper(OrderProfiles::PROFILE_EXTENDED);

        $this->assertNotNull(self::$objectHelper);

        $property = $this->getPrivatePropertyFromObject(self::$objectHelper, "profile");
        $this->assertEquals(OrderProfiles::PROFILE_EXTENDED, $property->getValue(self::$objectHelper));

        $property = $this->getPrivatePropertyFromObject(self::$objectHelper, "profiledef");
        $this->assertIsArray($property->getValue(self::$objectHelper));
        $this->assertArrayHasKey("name", $property->getValue(self::$objectHelper));
        $this->assertArrayHasKey("altname", $property->getValue(self::$objectHelper));
        $this->assertArrayHasKey("description", $property->getValue(self::$objectHelper));
        $this->assertArrayHasKey("contextparameter", $property->getValue(self::$objectHelper));
        $this->assertArrayHasKey("attachmentfilename", $property->getValue(self::$objectHelper));
        $this->assertArrayHasKey("xmpname", $property->getValue(self::$objectHelper));
        $this->assertArrayHasKey("xsdfilename", $property->getValue(self::$objectHelper));
        $this->assertArrayHasKey("schematronfilename", $property->getValue(self::$objectHelper));
        $this->assertEquals("extended", $property->getValue(self::$objectHelper)['name']);
        $this->assertEquals("EXTENDED", $property->getValue(self::$objectHelper)['altname']);
    }

    /**
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetIdType(): void
    {
        $this->assertNull(self::$objectHelper->getIdType());
        $this->assertNull(self::$objectHelper->getIdType(null));
        $this->assertNull(self::$objectHelper->getIdType(""));
        $this->assertNull(self::$objectHelper->getIdType(null, "scheme"));
        $this->assertNull(self::$objectHelper->getIdType("", "scheme"));
        $this->assertNotNull(self::$objectHelper->getIdType("123456789", "scheme"));
        $this->assertNotNull(self::$objectHelper->getIdType("123456789"));

        $this->assertEquals("123456789", self::$objectHelper->getIdType("123456789", "scheme")->value());
        $this->assertEquals("scheme", self::$objectHelper->getIdType("123456789", "scheme")->getSchemeID());
        $this->assertEquals("123456789", self::$objectHelper->getIdType("123456789")->value());
        $this->assertEquals("", self::$objectHelper->getIdType("123456789")->getSchemeID());
    }

    /**
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetTextType(): void
    {
        $this->assertNull(self::$objectHelper->getTextType());
        $this->assertNull(self::$objectHelper->getTextType(null));
        $this->assertNull(self::$objectHelper->getTextType(""));
        $this->assertNotNull(self::$objectHelper->getTextType("Text"));

        $this->assertEquals("Text", self::$objectHelper->getTextType("Text")->value());
    }

    /**
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetCodeType(): void
    {
        $this->assertNull(self::$objectHelper->getCodeType());
        $this->assertNull(self::$objectHelper->getCodeType(null));
        $this->assertNull(self::$objectHelper->getCodeType(""));
        $this->assertNotNull(self::$objectHelper->getCodeType("Code"));

        $this->assertEquals("Code", self::$objectHelper->getCodeType("Code")->value());
    }

    /**
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetCodeType2(): void
    {
        $this->assertNull(self::$objectHelper->getCodeType2());
        $this->assertNull(self::$objectHelper->getCodeType2(null));
        $this->assertNull(self::$objectHelper->getCodeType2(""));
        $this->assertNotNull(self::$objectHelper->getCodeType2(null, "listid"));
        $this->assertNotNull(self::$objectHelper->getCodeType2(null, null, "listversionid"));
        $this->assertNotNull(self::$objectHelper->getCodeType("Code"));

        $this->assertEquals("Code", self::$objectHelper->getCodeType2("Code")->value());
        $this->assertEquals("", self::$objectHelper->getCodeType2("Code")->getListID());
        $this->assertEquals("", self::$objectHelper->getCodeType2("Code")->getListVersionID());

        $this->assertEquals("Code", self::$objectHelper->getCodeType2("Code", "listid")->value());
        $this->assertEquals("listid", self::$objectHelper->getCodeType2("Code", "listid")->getListID());
        $this->assertEquals("", self::$objectHelper->getCodeType2("Code", "listid")->getListVersionID());

        $this->assertEquals("Code", self::$objectHelper->getCodeType2("Code", "listid", "listversionid")->value());
        $this->assertEquals("listid", self::$objectHelper->getCodeType2("Code", "listid", "listversionid")->getListID());
        $this->assertEquals("listversionid", self::$objectHelper->getCodeType2("Code", "listid", "listversionid")->getListVersionID());

        $this->assertEquals("", self::$objectHelper->getCodeType2("", "listid", "listversionid")->value());
        $this->assertEquals("listid", self::$objectHelper->getCodeType2("", "listid", "listversionid")->getListID());
        $this->assertEquals("listversionid", self::$objectHelper->getCodeType2("", "listid", "listversionid")->getListVersionID());
    }

    /**
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetIndicatorType(): void
    {
        $this->assertNull(self::$objectHelper->getIndicatorType());
        $this->assertNotNull(self::$objectHelper->getIndicatorType(true));
        $this->assertNotNull(self::$objectHelper->getIndicatorType(false));

        $this->assertTrue(self::$objectHelper->getIndicatorType(true)->getIndicator());
        $this->assertFalse(self::$objectHelper->getIndicatorType(false)->getIndicator());
    }

    /**
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetNoteType(): void
    {
        $this->assertNull(self::$objectHelper->getNoteType());
        $this->assertNull(self::$objectHelper->getNoteType(null, "CC", "SC"));
        $this->assertNull(self::$objectHelper->getNoteType("", "CC", "SC"));
        $this->assertNotNull(self::$objectHelper->getNoteType("Content", "CC", "SC"));
        $this->assertNotNull(self::$objectHelper->getNoteType("Content"));
        $this->assertNotNull(self::$objectHelper->getNoteType("Content", null));
        $this->assertNotNull(self::$objectHelper->getNoteType("Content", null, null));
        $this->assertNotNull(self::$objectHelper->getNoteType("Content", ""));
        $this->assertNotNull(self::$objectHelper->getNoteType("Content", "", ""));

        $this->assertIsArray(self::$objectHelper->getNoteType("Content", "CC", "SC")->getContent());
        $this->assertArrayHasKey(0, self::$objectHelper->getNoteType("Content", "CC", "SC")->getContent());
        $this->assertEquals("Content", self::$objectHelper->getNoteType("Content", "CC", "SC")->getContent()[0]);
        $this->assertEquals("CC", self::$objectHelper->getNoteType("Content", "CC", "SC")->getContentCode());
        $this->assertEquals("SC", self::$objectHelper->getNoteType("Content", "CC", "SC")->getSubjectCode());
        $this->assertEquals("", self::$objectHelper->getNoteType("Content", "", "SC")->getContentCode());
        $this->assertEquals("SC", self::$objectHelper->getNoteType("Content", "", "SC")->getSubjectCode());
        $this->assertEquals("CC", self::$objectHelper->getNoteType("Content", "CC", "")->getContentCode());
        $this->assertEquals("", self::$objectHelper->getNoteType("Content", "CC", "")->getSubjectCode());
        $this->assertEquals("", self::$objectHelper->getNoteType("Content", null, "SC")->getContentCode());
        $this->assertEquals("SC", self::$objectHelper->getNoteType("Content", null, "SC")->getSubjectCode());
        $this->assertEquals("CC", self::$objectHelper->getNoteType("Content", "CC", null)->getContentCode());
        $this->assertEquals("", self::$objectHelper->getNoteType("Content", "CC", null)->getSubjectCode());
    }

    /**
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetFormattedDateTimeType(): void
    {
        $dt = new \DateTime();

        $this->assertNull(self::$objectHelper->getFormattedDateTimeType());
        $this->assertNotNull(self::$objectHelper->getFormattedDateTimeType($dt));

        $this->assertEquals($dt->format("Ymd"), self::$objectHelper->getFormattedDateTimeType($dt)->getDateTimeString());
        $this->assertEquals("102", self::$objectHelper->getFormattedDateTimeType($dt)->getDateTimeString()->getFormat());
    }

    /**
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetDateTimeType(): void
    {
        $dt = new \DateTime();

        $this->assertNull(self::$objectHelper->getDateTimeType());
        $this->assertNotNull(self::$objectHelper->getDateTimeType($dt));

        $this->assertEquals($dt->format("Ymd"), self::$objectHelper->getDateTimeType($dt)->getDateTimeString());
        $this->assertEquals("102", self::$objectHelper->getDateTimeType($dt)->getDateTimeString()->getFormat());
    }

    /**
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetAmountType(): void
    {
        $this->assertNull(self::$objectHelper->getAmountType(null));
        $this->assertNull(self::$objectHelper->getAmountType(null, "EUR"));
        $this->assertNotNull(self::$objectHelper->getAmountType(100.0));
        $this->assertNotNull(self::$objectHelper->getAmountType(100.0, "EUR"));

        $this->assertEquals(100.0, self::$objectHelper->getAmountType(100.0)->value());
        $this->assertEquals("", self::$objectHelper->getAmountType(100.0)->getCurrencyID());
        $this->assertEquals(200.0, self::$objectHelper->getAmountType(200.0, "EUR")->value());
        $this->assertEquals("EUR", self::$objectHelper->getAmountType(200.0, "EUR")->getCurrencyID());
    }

    /**
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetPercentType(): void
    {
        $this->assertNull(self::$objectHelper->getPercentType(null));
        $this->assertNotNull(self::$objectHelper->getPercentType(100.0));

        $this->assertEquals(100.0, self::$objectHelper->getPercentType(100.0)->value());
        $this->assertEquals(200.0, self::$objectHelper->getPercentType(200.0)->value());
    }

    /**
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetQuantityType(): void
    {
        $this->assertNull(self::$objectHelper->getQuantityType(null));
        $this->assertNull(self::$objectHelper->getQuantityType(null, "C62"));
        $this->assertNotNull(self::$objectHelper->getQuantityType(100.0));
        $this->assertNotNull(self::$objectHelper->getQuantityType(100.0, "C62"));

        $this->assertEquals(100.0, self::$objectHelper->getQuantityType(100.0)->value());
        $this->assertEquals("", self::$objectHelper->getQuantityType(100.0)->getUnitCode());
        $this->assertEquals(200.0, self::$objectHelper->getQuantityType(200.0, "C62")->value());
        $this->assertEquals("C62", self::$objectHelper->getQuantityType(200.0, "C62")->getUnitCode());
    }

    /**
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetMeasureType(): void
    {
        $this->assertNull(self::$objectHelper->getMeasureType(null));
        $this->assertNull(self::$objectHelper->getMeasureType(null, "C62"));
        $this->assertNotNull(self::$objectHelper->getMeasureType(100.0));
        $this->assertNotNull(self::$objectHelper->getMeasureType(100.0, "C62"));

        $this->assertEquals(100.0, self::$objectHelper->getMeasureType(100.0)->value());
        $this->assertEquals("", self::$objectHelper->getMeasureType(100.0)->getUnitCode());
        $this->assertEquals(200.0, self::$objectHelper->getMeasureType(200.0, "C62")->value());
        $this->assertEquals("C62", self::$objectHelper->getMeasureType(200.0, "C62")->getUnitCode());
    }

    /**
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetNumericType(): void
    {
        $this->assertNull(self::$objectHelper->getNumericType());
        $this->assertNull(self::$objectHelper->getNumericType(null));
        $this->assertNotNull(self::$objectHelper->getNumericType(100.0));

        $this->assertEquals(100.0, self::$objectHelper->getNumericType(100.0)->value());
        $this->assertEquals(200.0, self::$objectHelper->getNumericType(200.0)->value());
    }

    /**
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetTaxCategoryCodeType(): void
    {
        $this->assertNull(self::$objectHelper->getTaxCategoryCodeType());
        $this->assertNull(self::$objectHelper->getTaxCategoryCodeType(null));
        $this->assertNotNull(self::$objectHelper->getTaxCategoryCodeType("CODE"));

        $this->assertEquals("CODE", self::$objectHelper->getTaxCategoryCodeType("CODE")->value());
    }

    /**
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetTaxTypeCodeType(): void
    {
        $this->assertNull(self::$objectHelper->getTaxTypeCodeType());
        $this->assertNull(self::$objectHelper->getTaxTypeCodeType(null));
        $this->assertNotNull(self::$objectHelper->getTaxTypeCodeType("CODE"));

        $this->assertEquals("CODE", self::$objectHelper->getTaxTypeCodeType("CODE")->value());
    }

    /**
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetTimeReferenceCodeType(): void
    {
        $this->assertNull(self::$objectHelper->getTimeReferenceCodeType());
        $this->assertNull(self::$objectHelper->getTimeReferenceCodeType(null));
        $this->assertNotNull(self::$objectHelper->getTimeReferenceCodeType("CODE"));

        $this->assertEquals("CODE", self::$objectHelper->getTimeReferenceCodeType("CODE")->value());
    }

    /**
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetSpecifiedPeriodType(): void
    {
        $dt = new \DateTime();

        $this->assertNull(self::$objectHelper->getSpecifiedPeriodType());
        $this->assertNull(self::$objectHelper->getSpecifiedPeriodType(null));
        $this->assertNull(self::$objectHelper->getSpecifiedPeriodType(null, null));
        $this->assertNotNull(self::$objectHelper->getSpecifiedPeriodType($dt, null));
        $this->assertNotNull(self::$objectHelper->getSpecifiedPeriodType($dt, $dt));
        $this->assertNotNull(self::$objectHelper->getSpecifiedPeriodType(null, $dt));

        $this->assertEquals($dt->format("Ymd"), self::$objectHelper->getSpecifiedPeriodType($dt)->getStartDateTime()->getDateTimeString()->value());
        $this->assertNull(self::$objectHelper->getSpecifiedPeriodType($dt)->getEndDateTime());

        $this->assertNull(self::$objectHelper->getSpecifiedPeriodType(null, $dt)->getStartDateTime());
        $this->assertEquals($dt->format("Ymd"), self::$objectHelper->getSpecifiedPeriodType(null, $dt)->getEndDateTime()->getDateTimeString()->value());

        $this->assertEquals($dt->format("Ymd"), self::$objectHelper->getSpecifiedPeriodType($dt, $dt)->getStartDateTime()->getDateTimeString()->value());
        $this->assertEquals($dt->format("Ymd"), self::$objectHelper->getSpecifiedPeriodType($dt, $dt)->getEndDateTime()->getDateTimeString()->value());
    }

    /**
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetBinaryObjectType(): void
    {
        $this->assertNull(self::$objectHelper->getBinaryObjectType());
        $this->assertNull(self::$objectHelper->getBinaryObjectType(null));
        $this->assertNull(self::$objectHelper->getBinaryObjectType(null, null));
        $this->assertNull(self::$objectHelper->getBinaryObjectType(null, null, null));
        $this->assertNull(self::$objectHelper->getBinaryObjectType(""));
        $this->assertNull(self::$objectHelper->getBinaryObjectType("", ""));
        $this->assertNull(self::$objectHelper->getBinaryObjectType("", "", ""));
        $this->assertNull(self::$objectHelper->getBinaryObjectType("data"));
        $this->assertNull(self::$objectHelper->getBinaryObjectType("data", "image/jpeg"));
        $this->assertNotNull(self::$objectHelper->getBinaryObjectType("data", "image/jpeg", "image.jpg"));

        $this->assertEquals("data", self::$objectHelper->getBinaryObjectType("data", "image/jpeg", "image.jpg")->value());
        $this->assertEquals("image/jpeg", self::$objectHelper->getBinaryObjectType("data", "image/jpeg", "image.jpg")->getMimeCode());
        $this->assertEquals("image.jpg", self::$objectHelper->getBinaryObjectType("data", "image/jpeg", "image.jpg")->getFilename());
    }

    /**
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetReferencedDocumentType(): void
    {
        $binaryDataFilenameValid = dirname(__FILE__) . "/../assets/reader-invalid.pdf";
        $binaryDataFilenameInValid = dirname(__FILE__) . "/../assets/reader-invalid-mimetype.json";

        $refDoc1 = self::$objectHelper->getReferencedDocumentType("ID");
        $refDoc2 = self::$objectHelper->getReferencedDocumentType("ID", null, null, null, null, null, null, $binaryDataFilenameValid);

        $this->assertNull(self::$objectHelper->getReferencedDocumentType());
        $this->assertNotNull($refDoc1);
        $this->assertNotNull($refDoc2);

        $this->assertEquals("ID", $refDoc1->getIssuerAssignedID()->value());
        $this->assertEquals("ID", $refDoc2->getIssuerAssignedID()->value());
        $this->assertEquals("application/pdf", $refDoc2->getAttachmentBinaryObject()->getMimeCode());
        $this->assertEquals("reader-invalid.pdf", $refDoc2->getAttachmentBinaryObject()->getFilename());
        $this->assertEquals(FileUtils::fileToBase64($binaryDataFilenameValid), $refDoc2->getAttachmentBinaryObject()->value());

        $this->expectException(OrderMimeTypeNotSupportedException::class);

        $refDoc3 = self::$objectHelper->getReferencedDocumentType("ID", null, null, null, null, null, null, $binaryDataFilenameInValid);
    }

    /**
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetCountryIDType(): void
    {
        $this->assertNull(self::$objectHelper->getCountryIDType(null));
        $this->assertNotNull(self::$objectHelper->getCountryIDType("DE"));

        $this->assertEquals("DE", self::$objectHelper->getCountryIDType("DE")->value());
    }

    /**
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetTradeCountryType(): void
    {
        $this->assertNull(self::$objectHelper->getTradeCountryType(null));
        $this->assertNotNull(self::$objectHelper->getTradeCountryType("DE"));

        $this->assertEquals("DE", self::$objectHelper->getTradeCountryType("DE")->getID()->value());
    }

    /**
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetOrderX(): void
    {
        $oderx = self::$objectHelper->getOrderX();

        $this->assertNotNull($oderx);

        $this->assertNotNull($oderx->getExchangedDocument());

        $this->assertNotNull($oderx->getExchangedDocumentContext());
        $this->assertNotNull($oderx->getExchangedDocumentContext()->getGuidelineSpecifiedDocumentContextParameter());

        $this->assertNotNull($oderx->getSupplyChainTradeTransaction());
        $this->assertNotNull($oderx->getSupplyChainTradeTransaction()->getApplicableHeaderTradeAgreement());
        $this->assertNotNull($oderx->getSupplyChainTradeTransaction()->getApplicableHeaderTradeDelivery());
        $this->assertNotNull($oderx->getSupplyChainTradeTransaction()->getApplicableHeaderTradeSettlement());
    }

    /**
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetTradeParty(): void
    {
        $this->assertNull(self::$objectHelper->getTradeParty());
        $this->assertNull(self::$objectHelper->getTradeParty(null));
        $this->assertNull(self::$objectHelper->getTradeParty(null, null));
        $this->assertNull(self::$objectHelper->getTradeParty(null, null, null));
        $this->assertNull(self::$objectHelper->getTradeParty(""));
        $this->assertNull(self::$objectHelper->getTradeParty("", ""));
        $this->assertNull(self::$objectHelper->getTradeParty("", "", ""));

        $this->assertNotNull(self::$objectHelper->getTradeParty("NAME", null, null));

        $this->assertEquals("NAME", self::$objectHelper->getTradeParty("NAME", null, null)->getName()->value());

        $this->assertEquals("NAME", self::$objectHelper->getTradeParty("NAME", "ID", null)->getName()->value());
        $this->assertEquals("ID", self::$objectHelper->getTradeParty("NAME", "ID", null)->getID()->value());

        $this->assertEquals("NAME", self::$objectHelper->getTradeParty("NAME", "ID", "DESC")->getName()->value());
        $this->assertEquals("ID", self::$objectHelper->getTradeParty("NAME", "ID", "DESC")->getID()->value());
        $this->assertEquals("DESC", self::$objectHelper->getTradeParty("NAME", "ID", "DESC")->getDescription()->value());
    }

    /**
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetTradeLocation(): void
    {
        $this->assertNull(self::$objectHelper->getTradeLocation());
        $this->assertNull(self::$objectHelper->getTradeLocation(null));
        $this->assertNull(self::$objectHelper->getTradeLocation(null, null));
        $this->assertNull(self::$objectHelper->getTradeLocation(""));
        $this->assertNull(self::$objectHelper->getTradeLocation("", ""));

        $this->assertNotNull(self::$objectHelper->getTradeLocation("NAME", null));
        $this->assertNotNull(self::$objectHelper->getTradeLocation(null, "ID"));
        $this->assertNotNull(self::$objectHelper->getTradeLocation("NAME", "ID"));

        $this->assertNotNull(self::$objectHelper->getTradeLocation("NAME", ""));
        $this->assertNotNull(self::$objectHelper->getTradeLocation("", "ID"));
        $this->assertNotNull(self::$objectHelper->getTradeLocation("NAME", "ID"));

        $this->assertEquals("NAME", self::$objectHelper->getTradeLocation(null, "NAME")->getName()->value());
        $this->assertEquals("NAME", self::$objectHelper->getTradeLocation("ID", "NAME")->getName()->value());
        $this->assertEquals("ID", self::$objectHelper->getTradeLocation("ID", "NAME")->getID()->value());
    }

    /**
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetTradeAddress(): void
    {
        $this->assertNull(self::$objectHelper->getTradeAddress());
        $this->assertNull(self::$objectHelper->getTradeAddress(null));
        $this->assertNull(self::$objectHelper->getTradeAddress(null, null));
        $this->assertNull(self::$objectHelper->getTradeAddress(null, null, null));
        $this->assertNull(self::$objectHelper->getTradeAddress(null, null, null, null));
        $this->assertNull(self::$objectHelper->getTradeAddress(null, null, null, null, null));
        $this->assertNull(self::$objectHelper->getTradeAddress(null, null, null, null, null, null));
        $this->assertNull(self::$objectHelper->getTradeAddress(null, null, null, null, null, null, null));
        $this->assertNull(self::$objectHelper->getTradeAddress(""));
        $this->assertNull(self::$objectHelper->getTradeAddress("", ""));
        $this->assertNull(self::$objectHelper->getTradeAddress("", "", ""));
        $this->assertNull(self::$objectHelper->getTradeAddress("", "", "", ""));
        $this->assertNull(self::$objectHelper->getTradeAddress("", "", "", "", ""));
        $this->assertNull(self::$objectHelper->getTradeAddress("", "", "", "", "", ""));
        $this->assertNull(self::$objectHelper->getTradeAddress("", "", "", "", "", "", ""));

        $this->assertNotNull(self::$objectHelper->getTradeAddress("LINE1"));
        $this->assertNotNull(self::$objectHelper->getTradeAddress(null, "LINE2"));
        $this->assertNotNull(self::$objectHelper->getTradeAddress(null, null, "LINE3"));
        $this->assertNotNull(self::$objectHelper->getTradeAddress(null, null, null, "PC"));
        $this->assertNotNull(self::$objectHelper->getTradeAddress(null, null, null, null, "CITY"));
        $this->assertNotNull(self::$objectHelper->getTradeAddress(null, null, null, null, null, "COUNTRY"));
        $this->assertNotNull(self::$objectHelper->getTradeAddress(null, null, null, null, null, null, "SUBDIV"));

        $address = self::$objectHelper->getTradeAddress("LINE1", "LINE2", "LINE3", "PC", "CITY", "COUNTRY", "SUBDIV");

        $this->assertEquals("LINE1", $address->getLineOne()->value());
        $this->assertEquals("LINE2", $address->getLineTwo()->value());
        $this->assertEquals("LINE3", $address->getLineThree()->value());
        $this->assertEquals("PC", $address->getPostcodeCode()->value());
        $this->assertEquals("CITY", $address->getCityName()->value());
        $this->assertEquals("COUNTRY", $address->getCountryID()->value());
        $this->assertEquals("SUBDIV", $address->getCountrySubDivisionName()->value());
    }

    /**
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetLegalOrganization(): void
    {
        $this->assertNull(self::$objectHelper->getLegalOrganization());
        $this->assertNull(self::$objectHelper->getLegalOrganization(null));
        $this->assertNull(self::$objectHelper->getLegalOrganization(null, null));
        $this->assertNull(self::$objectHelper->getLegalOrganization(null, null, null));
        $this->assertNull(self::$objectHelper->getLegalOrganization(""));
        $this->assertNull(self::$objectHelper->getLegalOrganization("", ""));
        $this->assertNull(self::$objectHelper->getLegalOrganization("", "", ""));

        $this->assertNotNull(self::$objectHelper->getTradeAddress("ID"));
        $this->assertNotNull(self::$objectHelper->getTradeAddress("ID", "TYPE"));
        $this->assertNotNull(self::$objectHelper->getTradeAddress("ID", "TYPE", "NAME"));

        $legalOrganization = self::$objectHelper->getLegalOrganization("ID", "TYPE", "NAME");

        $this->assertEquals("ID", $legalOrganization->getID()->value());
        $this->assertEquals("TYPE", $legalOrganization->getID()->getSchemeID());
        $this->assertEquals("NAME", $legalOrganization->getTradingBusinessName()->value());
    }

    /**
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetTradeContact(): void
    {
        $this->assertNull(self::$objectHelper->getTradeContact());
        $this->assertNull(self::$objectHelper->getTradeContact(null));
        $this->assertNull(self::$objectHelper->getTradeContact(null, null));
        $this->assertNull(self::$objectHelper->getTradeContact(null, null, null));
        $this->assertNull(self::$objectHelper->getTradeContact(null, null, null, null));
        $this->assertNull(self::$objectHelper->getTradeContact(null, null, null, null, null));
        $this->assertNull(self::$objectHelper->getTradeContact(null, null, null, null, null, null));
        $this->assertNull(self::$objectHelper->getTradeContact(""));
        $this->assertNull(self::$objectHelper->getTradeContact("", ""));
        $this->assertNull(self::$objectHelper->getTradeContact("", "", ""));
        $this->assertNull(self::$objectHelper->getTradeContact("", "", "", ""));
        $this->assertNull(self::$objectHelper->getTradeContact("", "", "", "", ""));
        $this->assertNull(self::$objectHelper->getTradeContact("", "", "", "", "", ""));

        $this->assertNotNull(self::$objectHelper->getTradeContact("PERSON", "DEP", "PHONE", "FAX", "EMAIL", "TYPE"));

        $contact = self::$objectHelper->getTradeContact("PERSON", "DEP", "PHONE", "FAX", "EMAIL", "TYPE");

        $this->assertEquals("PERSON", $contact->getPersonName()->value());
        $this->assertEquals("DEP", $contact->getDepartmentName()->value());
        $this->assertEquals("PHONE", $contact->getTelephoneUniversalCommunication()->getCompleteNumber());
        $this->assertEquals("FAX", $contact->getFaxUniversalCommunication()->getCompleteNumber());
        $this->assertEquals("EMAIL", $contact->getEmailURIUniversalCommunication()->getURIID()->value());
        $this->assertNull($contact->getEmailURIUniversalCommunication()->getURIID()->getSchemeID());
    }

    /**
     * @covers \horstoeko\orderx\OrderObjectHelper
     */
    public function testGetUniversalCommunicationType(): void
    {
        $this->assertNull(self::$objectHelper->getUniversalCommunicationType());
        $this->assertNull(self::$objectHelper->getUniversalCommunicationType(null));
        $this->assertNull(self::$objectHelper->getUniversalCommunicationType(null, null));
        $this->assertNull(self::$objectHelper->getUniversalCommunicationType(null, null, null));
        $this->assertNull(self::$objectHelper->getUniversalCommunicationType(""));
        $this->assertNull(self::$objectHelper->getUniversalCommunicationType("", ""));
        $this->assertNull(self::$objectHelper->getUniversalCommunicationType("", "", ""));

        $this->assertNotNull(self::$objectHelper->getUniversalCommunicationType("NUMBER", "URI", "URITYPE"));

        $comm = self::$objectHelper->getUniversalCommunicationType("NUMBER", "URI", "URITYPE");

        $this->assertEquals("NUMBER", $comm->getCompleteNumber());
        $this->assertEquals("URI", $comm->getURIID()->value());
        $this->assertEquals("URITYPE", $comm->getURIID()->getSchemeID());
    }
}
