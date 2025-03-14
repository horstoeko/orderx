<?php

namespace horstoeko\orderx\tests\testcases;

use InvalidArgumentException;
use horstoeko\orderx\codelists\OrderDocumentTypes;
use horstoeko\orderx\exception\OrderFileNotFoundException;
use horstoeko\orderx\exception\OrderUnknownMimetype;
use horstoeko\orderx\OrderDocumentPdfBuilder;
use horstoeko\orderx\OrderDocumentPdfBuilderAbstract;
use horstoeko\orderx\OrderSettings;
use horstoeko\orderx\tests\TestCase;
use horstoeko\orderx\tests\traits\HandlesCreateTestDocument;
use horstoeko\stringmanagement\FileUtils;
use horstoeko\stringmanagement\PathUtils;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\StreamReader;
use Smalot\PdfParser\Parser as PdfParser;

class OrderDocumentPdfBuilderTest extends TestCase
{
    use HandlesCreateTestDocument;

    public function testExtractOrderInformationsAsOrder(): void
    {
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");
        $destinationPdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "final.pdf");

        $this->registerFileForTestMethodTeardown($destinationPdfFilename);

        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER);
        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilename);

        $method = $this->getPrivateMethodFromObject($orderDocumentPdfBuilder, "extractOrderInformations");
        $methodResult = $method->invokeArgs($orderDocumentPdfBuilder, []);

        $this->assertIsArray($methodResult);

        $this->assertArrayHasKey("orderId", $methodResult);
        $this->assertArrayHasKey("docTypeName", $methodResult);
        $this->assertArrayHasKey("seller", $methodResult);
        $this->assertArrayHasKey("date", $methodResult);

        $this->assertEquals('PO123456789', $methodResult['orderId']);
        $this->assertEquals('Order', $methodResult['docTypeName']);
        $this->assertEquals('SELLER_NAME', $methodResult['seller']);
        $this->assertEquals('2022-12-31T00:00:00+00:00', $methodResult['date']);
    }

    public function testExtractOrderInformationsAsOrderChange(): void
    {
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");
        $destinationPdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "final.pdf");

        $this->registerFileForTestMethodTeardown($destinationPdfFilename);

        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER_CHANGE);
        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilename);

        $method = $this->getPrivateMethodFromObject($orderDocumentPdfBuilder, "extractOrderInformations");
        $methodResult = $method->invokeArgs($orderDocumentPdfBuilder, []);

        $this->assertIsArray($methodResult);

        $this->assertArrayHasKey("orderId", $methodResult);
        $this->assertArrayHasKey("docTypeName", $methodResult);
        $this->assertArrayHasKey("seller", $methodResult);
        $this->assertArrayHasKey("date", $methodResult);

        $this->assertEquals('PO123456789', $methodResult['orderId']);
        $this->assertEquals('Order Change', $methodResult['docTypeName']);
        $this->assertEquals('SELLER_NAME', $methodResult['seller']);
        $this->assertEquals('2022-12-31T00:00:00+00:00', $methodResult['date']);
    }

    public function testExtractOrderInformationsAsOrderResponse(): void
    {
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");
        $destinationPdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "final.pdf");

        $this->registerFileForTestMethodTeardown($destinationPdfFilename);

        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER_RESPONSE);
        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilename);

        $method = $this->getPrivateMethodFromObject($orderDocumentPdfBuilder, "extractOrderInformations");
        $methodResult = $method->invokeArgs($orderDocumentPdfBuilder, []);

        $this->assertIsArray($methodResult);

        $this->assertArrayHasKey("orderId", $methodResult);
        $this->assertArrayHasKey("docTypeName", $methodResult);
        $this->assertArrayHasKey("seller", $methodResult);
        $this->assertArrayHasKey("date", $methodResult);

        $this->assertEquals('PO123456789', $methodResult['orderId']);
        $this->assertEquals('Order Response', $methodResult['docTypeName']);
        $this->assertEquals('SELLER_NAME', $methodResult['seller']);
        $this->assertEquals('2022-12-31T00:00:00+00:00', $methodResult['date']);
    }

    public function testExtractOrderInformationsAsUnknownDocumentType(): void
    {
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");
        $destinationPdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "final.pdf");

        $this->registerFileForTestMethodTeardown($destinationPdfFilename);

        $orderDocument = $this->createTestDocument("000");
        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilename);

        $method = $this->getPrivateMethodFromObject($orderDocumentPdfBuilder, "extractOrderInformations");
        $methodResult = $method->invokeArgs($orderDocumentPdfBuilder, []);

        $this->assertIsArray($methodResult);

        $this->assertArrayHasKey("orderId", $methodResult);
        $this->assertArrayHasKey("docTypeName", $methodResult);
        $this->assertArrayHasKey("seller", $methodResult);
        $this->assertArrayHasKey("date", $methodResult);

        $this->assertEquals('PO123456789', $methodResult['orderId']);
        $this->assertEquals('Order', $methodResult['docTypeName']);
        $this->assertEquals('SELLER_NAME', $methodResult['seller']);
        $this->assertEquals('2022-12-31T00:00:00+00:00', $methodResult['date']);
    }

    public function testPreparePdfMetadataAsOrder(): void
    {
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");
        $destinationPdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "final.pdf");

        $this->registerFileForTestMethodTeardown($destinationPdfFilename);

        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER);
        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilename);

        $method = $this->getPrivateMethodFromObject($orderDocumentPdfBuilder, "preparePdfMetadata");
        $methodResult = $method->invokeArgs($orderDocumentPdfBuilder, []);

        $this->assertIsArray($methodResult);

        $this->assertArrayHasKey("author", $methodResult);
        $this->assertArrayHasKey("keywords", $methodResult);
        $this->assertArrayHasKey("title", $methodResult);
        $this->assertArrayHasKey("subject", $methodResult);
        $this->assertArrayHasKey("createdDate", $methodResult);
        $this->assertArrayHasKey("modifiedDate", $methodResult);

        $this->assertEquals('SELLER_NAME', $methodResult['author']);
        $this->assertEquals('Order, Order-X', $methodResult['keywords']);
        $this->assertEquals('SELLER_NAME, Order PO123456789', $methodResult['title']);
        $this->assertEquals('Order-X Order PO123456789 dated 2022-12-31 issued by SELLER_NAME', $methodResult['subject']);
        $this->assertEquals('2022-12-31T00:00:00+00:00', $methodResult['createdDate']);
    }

    public function testPreparePdfMetadataAsOrderChange(): void
    {
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");
        $destinationPdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "final.pdf");

        $this->registerFileForTestMethodTeardown($destinationPdfFilename);

        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER_CHANGE);
        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilename);

        $method = $this->getPrivateMethodFromObject($orderDocumentPdfBuilder, "preparePdfMetadata");
        $methodResult = $method->invokeArgs($orderDocumentPdfBuilder, []);

        $this->assertIsArray($methodResult);

        $this->assertArrayHasKey("author", $methodResult);
        $this->assertArrayHasKey("keywords", $methodResult);
        $this->assertArrayHasKey("title", $methodResult);
        $this->assertArrayHasKey("subject", $methodResult);
        $this->assertArrayHasKey("createdDate", $methodResult);
        $this->assertArrayHasKey("modifiedDate", $methodResult);

        $this->assertEquals('SELLER_NAME', $methodResult['author']);
        $this->assertEquals('Order Change, Order-X', $methodResult['keywords']);
        $this->assertEquals('SELLER_NAME, Order Change PO123456789', $methodResult['title']);
        $this->assertEquals('Order-X Order Change PO123456789 dated 2022-12-31 issued by SELLER_NAME', $methodResult['subject']);
        $this->assertEquals('2022-12-31T00:00:00+00:00', $methodResult['createdDate']);
    }

    public function testPreparePdfMetadataAsOrderResponse(): void
    {
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");
        $destinationPdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "final.pdf");

        $this->registerFileForTestMethodTeardown($destinationPdfFilename);

        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER_RESPONSE);
        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilename);

        $method = $this->getPrivateMethodFromObject($orderDocumentPdfBuilder, "preparePdfMetadata");
        $methodResult = $method->invokeArgs($orderDocumentPdfBuilder, []);

        $this->assertIsArray($methodResult);

        $this->assertArrayHasKey("author", $methodResult);
        $this->assertArrayHasKey("keywords", $methodResult);
        $this->assertArrayHasKey("title", $methodResult);
        $this->assertArrayHasKey("subject", $methodResult);
        $this->assertArrayHasKey("createdDate", $methodResult);
        $this->assertArrayHasKey("modifiedDate", $methodResult);

        $this->assertEquals('SELLER_NAME', $methodResult['author']);
        $this->assertEquals('Order Response, Order-X', $methodResult['keywords']);
        $this->assertEquals('SELLER_NAME, Order Response PO123456789', $methodResult['title']);
        $this->assertEquals('Order-X Order Response PO123456789 dated 2022-12-31 issued by SELLER_NAME', $methodResult['subject']);
        $this->assertEquals('2022-12-31T00:00:00+00:00', $methodResult['createdDate']);
    }

    public function testPreparePdfMetadataAsUnknownDocumentType(): void
    {
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");
        $destinationPdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "final.pdf");

        $this->registerFileForTestMethodTeardown($destinationPdfFilename);

        $orderDocument = $this->createTestDocument("000");
        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilename);

        $method = $this->getPrivateMethodFromObject($orderDocumentPdfBuilder, "preparePdfMetadata");
        $methodResult = $method->invokeArgs($orderDocumentPdfBuilder, []);

        $this->assertIsArray($methodResult);

        $this->assertArrayHasKey("author", $methodResult);
        $this->assertArrayHasKey("keywords", $methodResult);
        $this->assertArrayHasKey("title", $methodResult);
        $this->assertArrayHasKey("subject", $methodResult);
        $this->assertArrayHasKey("createdDate", $methodResult);
        $this->assertArrayHasKey("modifiedDate", $methodResult);

        $this->assertEquals('SELLER_NAME', $methodResult['author']);
        $this->assertEquals('Order, Order-X', $methodResult['keywords']);
        $this->assertEquals('SELLER_NAME, Order PO123456789', $methodResult['title']);
        $this->assertEquals('Order-X Order PO123456789 dated 2022-12-31 issued by SELLER_NAME', $methodResult['subject']);
        $this->assertEquals('2022-12-31T00:00:00+00:00', $methodResult['createdDate']);
    }

    public function testPdfSavingFromFileBasedPdf(): void
    {
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");
        $destinationPdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "final.pdf");

        $this->registerFileForTestMethodTeardown($destinationPdfFilename);

        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER);

        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilename);
        $orderDocumentPdfBuilder->generateDocument();
        $orderDocumentPdfBuilder->saveDocument($destinationPdfFilename);

        $this->assertFileExists($destinationPdfFilename);
    }

    public function testPdfSavingFromStringBasedPdf(): void
    {
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");
        $sourcePdfFilenameContent = base64_decode(FileUtils::fileToBase64($sourcePdfFilename));
        $destinationPdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "final.pdf");

        $this->registerFileForTestMethodTeardown($destinationPdfFilename);

        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER);

        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilenameContent);
        $orderDocumentPdfBuilder->generateDocument();
        $orderDocumentPdfBuilder->saveDocument($destinationPdfFilename);

        $this->assertFileExists($destinationPdfFilename);
    }

    public function testFromPdfFile(): void
    {
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");
        $destinationPdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "final.pdf");
        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER);

        $pdfBuilder = OrderDocumentPdfBuilder::fromPdfFile($orderDocument, $sourcePdfFilename);
        $pdfBuilder->generateDocument();
        $pdfContent = $pdfBuilder->downloadString($destinationPdfFilename);

        $this->assertIsString($pdfContent);
        $this->assertNotEquals('', $pdfContent);
        $this->assertStringStartsNotWith('%PDF-1.4', $pdfContent);
    }

    public function testFromNotExistingPdfFile(): void
    {
        $this->expectException(OrderFileNotFoundException::class);

        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER);

        $pdfBuilder = OrderDocumentPdfBuilder::fromPdfFile($orderDocument, '/tmp/anonexisting.pdf');
    }

    public function testFromPdfString(): void
    {
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");
        $destinationPdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "final.pdf");
        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER);

        $pdfString = file_get_contents($sourcePdfFilename);

        $pdfBuilder = OrderDocumentPdfBuilder::fromPdfString($orderDocument, $pdfString);
        $pdfBuilder->generateDocument();
        $pdfContent = $pdfBuilder->downloadString($destinationPdfFilename);

        $this->assertIsString($pdfContent);
        $this->assertNotEquals('', $pdfContent);
        $this->assertStringStartsNotWith('%PDF-1.4', $pdfContent);
    }

    public function testFromPdfStringWhichIsInvalid(): void
    {
        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER);
        $destinationPdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "final.pdf");

        $this->expectException(PdfParserException::class);
        $this->expectExceptionMessage('Unable to find PDF file header.');

        $pdfString = 'this_is_not_a_pdf_string';

        $pdfBuilder = OrderDocumentPdfBuilder::fromPdfString($orderDocument, $pdfString);
        $pdfBuilder->generateDocument();
        $pdfContent = $pdfBuilder->downloadString($destinationPdfFilename);

        $this->assertIsString($pdfContent);
        $this->assertNotEquals('', $pdfContent);
        $this->assertStringStartsNotWith('%PDF-1.4', $pdfContent);
    }

    public function testPdfMetaData(): void
    {
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");
        $destinationPdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "final.pdf");

        $this->registerFileForTestMethodTeardown($destinationPdfFilename);

        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER);
        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilename);
        $orderDocumentPdfBuilder->generateDocument();
        $orderDocumentPdfBuilder->saveDocument($destinationPdfFilename);

        $this->assertFileExists($destinationPdfFilename);

        $pdfParser = new PdfParser();
        $pdfParsed = $pdfParser->parseFile($destinationPdfFilename);
        $pdfDetails = $pdfParsed->getDetails();

        $this->assertIsArray($pdfDetails);
        $this->assertArrayHasKey("Producer", $pdfDetails); //"FPDF 1.84"
        $this->assertArrayHasKey("CreationDate", $pdfDetails); //"2020-12-09T05:19:39+00:00"
        $this->assertArrayHasKey("Pages", $pdfDetails); //"1"
        $this->assertStringContainsString('FPDF', $pdfDetails["Producer"]);
        $this->assertStringContainsString(date("Y-m-d"), $pdfDetails["CreationDate"]);
        $this->assertEquals("1", $pdfDetails["Pages"]);
    }

    public function testSetAdditionalCreatorTool(): void
    {
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");

        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER);
        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilename);
        $orderDocumentPdfBuilder->setAdditionalCreatorTool('Dummy');

        $this->assertStringStartsWith('Dummy / Order-X PHP library', $orderDocumentPdfBuilder->getCreatorToolName());
    }

    public function testSetAttachmentRelationshipTypeToUnknown(): void
    {
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");

        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER);
        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilename);
        $orderDocumentPdfBuilder->setAttachmentRelationshipType('unknown');

        $this->assertEquals(OrderDocumentPdfBuilder::AF_RELATIONSHIP_DATA, $orderDocumentPdfBuilder->getAttachmentRelationshipType());
    }

    public function testSetAttachmentRelationshipTypeToData(): void
    {
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");

        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER);
        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilename);
        $orderDocumentPdfBuilder->setAttachmentRelationshipType('Data');

        $this->assertEquals(OrderDocumentPdfBuilder::AF_RELATIONSHIP_DATA, $orderDocumentPdfBuilder->getAttachmentRelationshipType());
    }

    public function testSetAttachmentRelationshipTypeToAlternative(): void
    {
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");

        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER);
        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilename);
        $orderDocumentPdfBuilder->setAttachmentRelationshipType('Alternative');

        $this->assertEquals(OrderDocumentPdfBuilder::AF_RELATIONSHIP_ALTERNATIVE, $orderDocumentPdfBuilder->getAttachmentRelationshipType());
    }

    public function testSetAttachmentRelationshipTypeToSource(): void
    {
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");

        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER);
        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilename);
        $orderDocumentPdfBuilder->setAttachmentRelationshipType('Source');

        $this->assertEquals(OrderDocumentPdfBuilder::AF_RELATIONSHIP_SOURCE, $orderDocumentPdfBuilder->getAttachmentRelationshipType());
    }

    public function testSetAttachmentRelationshipTypeToDataDirect(): void
    {
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");

        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER);
        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilename);
        $orderDocumentPdfBuilder->setAttachmentRelationshipTypeToData();

        $this->assertEquals(OrderDocumentPdfBuilder::AF_RELATIONSHIP_DATA, $orderDocumentPdfBuilder->getAttachmentRelationshipType());
    }

    public function testSetAttachmentRelationshipTypeToAlternativeDirect(): void
    {
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");

        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER);
        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilename);
        $orderDocumentPdfBuilder->setAttachmentRelationshipTypeToAlternative();

        $this->assertEquals(OrderDocumentPdfBuilder::AF_RELATIONSHIP_ALTERNATIVE, $orderDocumentPdfBuilder->getAttachmentRelationshipType());
    }

    public function testSetAttachmentRelationshipTypeToSourceDirect(): void
    {
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");

        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER);
        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilename);
        $orderDocumentPdfBuilder->setAttachmentRelationshipTypeToSource();

        $this->assertEquals(OrderDocumentPdfBuilder::AF_RELATIONSHIP_SOURCE, $orderDocumentPdfBuilder->getAttachmentRelationshipType());
    }

    public function testAttachAdditionalFileFileDoesNotExist(): void
    {
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");
        $filename = dirname(__FILE__) . '/unknown.txt';

        $this->expectException(OrderFileNotFoundException::class);
        $this->expectExceptionMessage(sprintf("The file %s was not found", $filename));

        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER);
        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilename);
        $orderDocumentPdfBuilder->attachAdditionalFileByRealFile($filename);
    }

    public function testAttachAdditionalFileFileIsEmpty(): void
    {
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("You must specify a filename for the content to attach");

        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER);
        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilename);
        $orderDocumentPdfBuilder->attachAdditionalFileByRealFile("");
    }

    public function testAttachAdditionalFileMimetypeUnknown(): void
    {
        $filename = dirname(__FILE__) . "/../assets/dummy_attachment_1.dummy";
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");

        $this->expectException(OrderUnknownMimetype::class);
        $this->expectExceptionMessage("No mimetype found");

        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER);
        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilename);
        $orderDocumentPdfBuilder->attachAdditionalFileByRealFile($filename);
    }

    public function testAttachAdditionalFileInvalidRelationShip(): void
    {
        $filename = dirname(__FILE__) . "/../assets/txt_addattachment_1.txt";
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");

        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER);
        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilename);
        $orderDocumentPdfBuilder->attachAdditionalFileByRealFile($filename, "", "Dummy");

        $property = $this->getPrivatePropertyFromClassname(OrderDocumentPdfBuilderAbstract::class, "additionalFilesToAttach");

        $this->assertIsArray($property->getValue($orderDocumentPdfBuilder));
        $this->assertIsArray($property->getValue($orderDocumentPdfBuilder)[0]);
        $this->assertEquals(OrderDocumentPdfBuilder::AF_RELATIONSHIP_SUPPLEMENT, $property->getValue($orderDocumentPdfBuilder)[0][3]);
    }

    public function testAttachAdditionalFileValidRelationShip(): void
    {
        $filename = dirname(__FILE__) . "/../assets/txt_addattachment_1.txt";
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");

        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER);
        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilename);
        $orderDocumentPdfBuilder->attachAdditionalFileByRealFile($filename, "", "Alternative");

        $property = $this->getPrivatePropertyFromClassname(OrderDocumentPdfBuilderAbstract::class, "additionalFilesToAttach");

        $this->assertIsArray($property->getValue($orderDocumentPdfBuilder));
        $this->assertIsArray($property->getValue($orderDocumentPdfBuilder)[0]);
        $this->assertEquals(OrderDocumentPdfBuilder::AF_RELATIONSHIP_ALTERNATIVE, $property->getValue($orderDocumentPdfBuilder)[0][3]);
    }

    public function testAttachAdditionalFileFinalResult(): void
    {
        $filename = dirname(__FILE__) . "/../assets/txt_addattachment_1.txt";
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");

        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER);
        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilename);
        $orderDocumentPdfBuilder->attachAdditionalFileByRealFile($filename, "", "Alternative");

        $property = $this->getPrivatePropertyFromClassname(OrderDocumentPdfBuilderAbstract::class, "additionalFilesToAttach");

        $this->assertIsArray($property->getValue($orderDocumentPdfBuilder));
        $this->assertIsArray($property->getValue($orderDocumentPdfBuilder)[0]);
        $this->assertInstanceOf(StreamReader::class, $property->getValue($orderDocumentPdfBuilder)[0][0]);
        $this->assertEquals("txt_addattachment_1.txt", $property->getValue($orderDocumentPdfBuilder)[0][1]);
        $this->assertEquals("txt_addattachment_1.txt", $property->getValue($orderDocumentPdfBuilder)[0][2]);
        $this->assertEquals(OrderDocumentPdfBuilder::AF_RELATIONSHIP_ALTERNATIVE, $property->getValue($orderDocumentPdfBuilder)[0][3]);

        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER);
        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilename);
        $orderDocumentPdfBuilder->attachAdditionalFileByRealFile($filename, "An Attachment", "Alternative");

        $property = $this->getPrivatePropertyFromClassname(OrderDocumentPdfBuilderAbstract::class, "additionalFilesToAttach");

        $this->assertIsArray($property->getValue($orderDocumentPdfBuilder));
        $this->assertIsArray($property->getValue($orderDocumentPdfBuilder)[0]);
        $this->assertInstanceOf(StreamReader::class, $property->getValue($orderDocumentPdfBuilder)[0][0]);
        $this->assertEquals("txt_addattachment_1.txt", $property->getValue($orderDocumentPdfBuilder)[0][1]);
        $this->assertEquals("An Attachment", $property->getValue($orderDocumentPdfBuilder)[0][2]);
        $this->assertEquals(OrderDocumentPdfBuilder::AF_RELATIONSHIP_ALTERNATIVE, $property->getValue($orderDocumentPdfBuilder)[0][3]);
    }

    public function testAdditionalFilesAreEmbedded(): void
    {
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");
        $destinationPdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "final.pdf");

        $this->registerFileForTestMethodTeardown($destinationPdfFilename);

        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER);
        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilename);
        $orderDocumentPdfBuilder->attachAdditionalFileByRealFile(dirname(__FILE__) . "/../assets/txt_addattachment_1.txt");
        $orderDocumentPdfBuilder->generateDocument();
        $orderDocumentPdfBuilder->saveDocument($destinationPdfFilename);

        $pdfParser = new PdfParser();
        $pdfParsed = $pdfParser->parseFile($destinationPdfFilename);
        $pdfFilespecs = $pdfParsed->getObjectsByType('Filespec');

        $this->assertIsArray($pdfFilespecs);
        $this->assertEquals(2, count($pdfFilespecs));
        $this->assertArrayHasKey("8_0", $pdfFilespecs);
        $this->assertArrayHasKey("10_0", $pdfFilespecs);

        $pdfFilespec = $pdfFilespecs["8_0"];
        $pdfFilespecDetails = $pdfFilespec->getDetails();

        $this->assertIsArray($pdfFilespecDetails);
        $this->assertArrayHasKey("F", $pdfFilespecDetails);
        $this->assertArrayHasKey("Type", $pdfFilespecDetails);
        $this->assertArrayHasKey("UF", $pdfFilespecDetails);
        $this->assertArrayHasKey("AFRelationship", $pdfFilespecDetails);
        $this->assertArrayHasKey("Desc", $pdfFilespecDetails);
        $this->assertArrayHasKey("EF", $pdfFilespecDetails);
        $this->assertEquals("order-x.xml", $pdfFilespecDetails["F"]);
        $this->assertEquals("Filespec", $pdfFilespecDetails["Type"]);
        $this->assertEquals("order-x.xml", $pdfFilespecDetails["UF"]);
        $this->assertEquals(OrderDocumentPdfBuilder::AF_RELATIONSHIP_DATA, $pdfFilespecDetails["AFRelationship"]);
        $this->assertEquals("Order-X XML File", $pdfFilespecDetails["Desc"]);

        $pdfFilespec = $pdfFilespecs["10_0"];
        $pdfFilespecDetails = $pdfFilespec->getDetails();

        $this->assertIsArray($pdfFilespecDetails);
        $this->assertArrayHasKey("F", $pdfFilespecDetails);
        $this->assertArrayHasKey("Type", $pdfFilespecDetails);
        $this->assertArrayHasKey("UF", $pdfFilespecDetails);
        $this->assertArrayHasKey("AFRelationship", $pdfFilespecDetails);
        $this->assertArrayHasKey("Desc", $pdfFilespecDetails);
        $this->assertArrayHasKey("EF", $pdfFilespecDetails);
        $this->assertEquals("txt_addattachment_1.txt", $pdfFilespecDetails["F"]);
        $this->assertEquals("Filespec", $pdfFilespecDetails["Type"]);
        $this->assertEquals("txt_addattachment_1.txt", $pdfFilespecDetails["UF"]);
        $this->assertEquals(OrderDocumentPdfBuilder::AF_RELATIONSHIP_SUPPLEMENT, $pdfFilespecDetails["AFRelationship"]);
        $this->assertEquals("txt_addattachment_1.txt", $pdfFilespecDetails["Desc"]);

        $pdfFilespecDetailsEF = $pdfFilespecDetails["EF"];
        $this->assertIsArray($pdfFilespecDetailsEF);
        $this->assertArrayHasKey("F", $pdfFilespecDetailsEF);
        $this->assertArrayHasKey("UF", $pdfFilespecDetailsEF);

        $pdfFilespecDetailsEF_F = $pdfFilespecDetailsEF["F"];
        $this->assertIsArray($pdfFilespecDetailsEF_F);
        $this->assertArrayHasKey("Filter", $pdfFilespecDetailsEF_F);
        $this->assertArrayHasKey("Subtype", $pdfFilespecDetailsEF_F);
        $this->assertArrayHasKey("Type", $pdfFilespecDetailsEF_F);
        $this->assertArrayHasKey("Length", $pdfFilespecDetailsEF_F);
        $this->assertEquals("FlateDecode", $pdfFilespecDetailsEF_F["Filter"]);
        $this->assertEquals("text/plain", $pdfFilespecDetailsEF_F["Subtype"]);
        $this->assertEquals("EmbeddedFile", $pdfFilespecDetailsEF_F["Type"]);
        $this->assertEquals(195, $pdfFilespecDetailsEF_F["Length"]);

        $pdfFilespecDetailsEF_UF = $pdfFilespecDetailsEF["UF"];
        $this->assertIsArray($pdfFilespecDetailsEF_UF);
        $this->assertArrayHasKey("Filter", $pdfFilespecDetailsEF_UF);
        $this->assertArrayHasKey("Subtype", $pdfFilespecDetailsEF_UF);
        $this->assertArrayHasKey("Type", $pdfFilespecDetailsEF_UF);
        $this->assertArrayHasKey("Length", $pdfFilespecDetailsEF_UF);
        $this->assertEquals("FlateDecode", $pdfFilespecDetailsEF_UF["Filter"]);
        $this->assertEquals("text/plain", $pdfFilespecDetailsEF_UF["Subtype"]);
        $this->assertEquals("EmbeddedFile", $pdfFilespecDetailsEF_UF["Type"]);
        $this->assertEquals(195, $pdfFilespecDetailsEF_UF["Length"]);
    }

    public function testAttachAdditionalFileByContentEmptyContent(): void
    {
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("You must specify a content to attach");

        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER);
        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilename);
        $orderDocumentPdfBuilder->attachAdditionalFileByContent("", "", "", "");
    }

    public function testAttachAdditionalFileByContentEmptyFilename(): void
    {
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");
        $filename = dirname(__FILE__) . "/../assets/txt_addattachment_1.txt";

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("You must specify a filename for the content to attach");

        $content = file_get_contents($filename);

        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER);
        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilename);
        $orderDocumentPdfBuilder->attachAdditionalFileByContent($content, "", "", "");
    }

    public function testAttachAdditionalFileByContentAllValid(): void
    {
        $sourcePdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "empty.pdf");
        $destinationPdfFilename = PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), "final.pdf");
        $filename = dirname(__FILE__) . "/../assets/txt_addattachment_1.txt";

        $content = file_get_contents($filename);

        $this->registerFileForTestMethodTeardown($destinationPdfFilename);

        $orderDocument = $this->createTestDocument(OrderDocumentTypes::ORDER);
        $orderDocumentPdfBuilder = new OrderDocumentPdfBuilder($orderDocument, $sourcePdfFilename);
        $orderDocumentPdfBuilder->attachAdditionalFileByContent($content, "file.txt", "A file attachment");
        $orderDocumentPdfBuilder->generateDocument();
        $orderDocumentPdfBuilder->saveDocument($destinationPdfFilename);

        $pdfParser = new PdfParser();
        $pdfParsed = $pdfParser->parseFile($destinationPdfFilename);
        $pdfFilespecs = $pdfParsed->getObjectsByType('Filespec');

        $this->assertIsArray($pdfFilespecs);
        $this->assertEquals(2, count($pdfFilespecs));
        $this->assertArrayHasKey("8_0", $pdfFilespecs);
        $this->assertArrayHasKey("10_0", $pdfFilespecs);

        $pdfFilespec = $pdfFilespecs["8_0"];
        $pdfFilespecDetails = $pdfFilespec->getDetails();

        $this->assertIsArray($pdfFilespecDetails);
        $this->assertArrayHasKey("F", $pdfFilespecDetails);
        $this->assertArrayHasKey("Type", $pdfFilespecDetails);
        $this->assertArrayHasKey("UF", $pdfFilespecDetails);
        $this->assertArrayHasKey("AFRelationship", $pdfFilespecDetails);
        $this->assertArrayHasKey("Desc", $pdfFilespecDetails);
        $this->assertArrayHasKey("EF", $pdfFilespecDetails);
        $this->assertEquals("order-x.xml", $pdfFilespecDetails["F"]);
        $this->assertEquals("Filespec", $pdfFilespecDetails["Type"]);
        $this->assertEquals("order-x.xml", $pdfFilespecDetails["UF"]);
        $this->assertEquals(OrderDocumentPdfBuilder::AF_RELATIONSHIP_DATA, $pdfFilespecDetails["AFRelationship"]);
        $this->assertEquals("Order-X XML File", $pdfFilespecDetails["Desc"]);

        $pdfFilespec = $pdfFilespecs["10_0"];
        $pdfFilespecDetails = $pdfFilespec->getDetails();

        $this->assertIsArray($pdfFilespecDetails);
        $this->assertArrayHasKey("F", $pdfFilespecDetails);
        $this->assertArrayHasKey("Type", $pdfFilespecDetails);
        $this->assertArrayHasKey("UF", $pdfFilespecDetails);
        $this->assertArrayHasKey("AFRelationship", $pdfFilespecDetails);
        $this->assertArrayHasKey("Desc", $pdfFilespecDetails);
        $this->assertArrayHasKey("EF", $pdfFilespecDetails);
        $this->assertEquals("file.txt", $pdfFilespecDetails["F"]);
        $this->assertEquals("Filespec", $pdfFilespecDetails["Type"]);
        $this->assertEquals("file.txt", $pdfFilespecDetails["UF"]);
        $this->assertEquals(OrderDocumentPdfBuilder::AF_RELATIONSHIP_SUPPLEMENT, $pdfFilespecDetails["AFRelationship"]);
        $this->assertEquals("A file attachment", $pdfFilespecDetails["Desc"]);

        $pdfFilespecDetailsEF = $pdfFilespecDetails["EF"];
        $this->assertIsArray($pdfFilespecDetailsEF);
        $this->assertArrayHasKey("F", $pdfFilespecDetailsEF);
        $this->assertArrayHasKey("UF", $pdfFilespecDetailsEF);

        $pdfFilespecDetailsEF_F = $pdfFilespecDetailsEF["F"];
        $this->assertIsArray($pdfFilespecDetailsEF_F);
        $this->assertArrayHasKey("Filter", $pdfFilespecDetailsEF_F);
        $this->assertArrayHasKey("Subtype", $pdfFilespecDetailsEF_F);
        $this->assertArrayHasKey("Type", $pdfFilespecDetailsEF_F);
        $this->assertArrayHasKey("Length", $pdfFilespecDetailsEF_F);
        $this->assertEquals("FlateDecode", $pdfFilespecDetailsEF_F["Filter"]);
        $this->assertEquals("text/plain", $pdfFilespecDetailsEF_F["Subtype"]);
        $this->assertEquals("EmbeddedFile", $pdfFilespecDetailsEF_F["Type"]);
        $this->assertEquals(195, $pdfFilespecDetailsEF_F["Length"]);

        $pdfFilespecDetailsEF_UF = $pdfFilespecDetailsEF["UF"];
        $this->assertIsArray($pdfFilespecDetailsEF_UF);
        $this->assertArrayHasKey("Filter", $pdfFilespecDetailsEF_UF);
        $this->assertArrayHasKey("Subtype", $pdfFilespecDetailsEF_UF);
        $this->assertArrayHasKey("Type", $pdfFilespecDetailsEF_UF);
        $this->assertArrayHasKey("Length", $pdfFilespecDetailsEF_UF);
        $this->assertEquals("FlateDecode", $pdfFilespecDetailsEF_UF["Filter"]);
        $this->assertEquals("text/plain", $pdfFilespecDetailsEF_UF["Subtype"]);
        $this->assertEquals("EmbeddedFile", $pdfFilespecDetailsEF_UF["Type"]);
        $this->assertEquals(195, $pdfFilespecDetailsEF_UF["Length"]);
    }
}
