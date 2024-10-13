<?php

namespace horstoeko\orderx\tests\testcases;

use horstoeko\orderx\OrderSettings;
use horstoeko\orderx\tests\TestCase;
use horstoeko\stringmanagement\FileUtils;
use horstoeko\stringmanagement\PathUtils;
use horstoeko\orderx\OrderDocumentPdfBuilder;
use setasign\Fpdi\PdfParser\PdfParserException;
use horstoeko\orderx\codelists\OrderDocumentTypes;
use horstoeko\orderx\exception\OrderFileNotFoundException;
use horstoeko\orderx\tests\traits\HandlesCreateTestDocument;

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
        $pdfBuilder->downloadString($destinationPdfFilename);

        $this->assertIsString($destinationPdfFilename);
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
        $pdfBuilder->downloadString($destinationPdfFilename);

        $this->assertIsString($destinationPdfFilename);
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
        $pdfBuilder->downloadString($destinationPdfFilename);
    }
}
