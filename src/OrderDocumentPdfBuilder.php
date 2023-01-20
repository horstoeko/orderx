<?php

/**
 * This file is a part of horstoeko/orderx.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\orderx;

use horstoeko\orderx\codelists\OrderDocumentTypes;
use horstoeko\orderx\OrderDocumentBuilder;
use horstoeko\orderx\OrderPackageVersion;
use horstoeko\orderx\OrderPdfWriter;
use horstoeko\stringmanagement\PathUtils;
use setasign\Fpdi\PdfParser\StreamReader as PdfStreamReader;

/**
 * Class representing the facillity adding XML data from OrderDocumentBuilder
 * to an existing PDF with conversion to PDF/A
 *
 * @category Order-X
 * @package  Order-X
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/orderx
 */
class OrderDocumentPdfBuilder
{
    /**
     * Internal reference to the xml builder instance
     *
     * @var OrderDocumentBuilder
     */
    private $documentBuiler = null;

    /**
     * Instance of the pdfwriter
     *
     * @var OrderPdfWriter
     */
    private $pdfWriter = null;

    /**
     * Contains the data of the original PDF documjent
     *
     * @var string
     */
    private $pdfData = "";

    /**
     * Constructor
     *
     * @param OrderDocumentBuilder $documentBuiler
     * The instance of the document builder. Needed to get the XML data
     * @param string               $pdfData
     * The full filename or a string containing the binary pdf data. This
     * is the original PDF (e.g. created by a ERP system)
     */
    public function __construct(OrderDocumentBuilder $documentBuiler, string $pdfData)
    {
        $this->documentBuiler = $documentBuiler;
        $this->pdfData = $pdfData;

        $this->pdfWriter = new OrderPdfWriter();
    }

    /**
     * Generates the final document
     *
     * @return OrderDocumentPdfBuilder
     */
    public function generateDocument(): OrderDocumentPdfBuilder
    {
        $this->startCreatePdf();

        return $this;
    }

    /**
     * Saves the document generated with generateDocument to a file
     *
     * @param  string $toFilename
     * The full qualified filename to which the generated PDF (with attachment)
     * is stored
     * @return OrderDocumentPdfBuilder
     */
    public function saveDocument(string $toFilename): OrderDocumentPdfBuilder
    {
        $this->pdfWriter->Output($toFilename, 'F');

        return $this;
    }

    /**
     * Internal function which sets up the PDF
     *
     * @return void
     */
    private function startCreatePdf(): void
    {
        // Get PDF data

        $pdfDataRef = null;

        if ($this->pdfDataIsFile($this->pdfData)) {
            $pdfDataRef = $this->pdfData;
        } elseif (is_string($this->pdfData)) {
            $pdfDataRef = PdfStreamReader::createByString($this->pdfData);
        }

        // Get XML data from Builder

        $documentBuilderXmlDataRef = PdfStreamReader::createByString($this->documentBuiler->getContentAsDomDocument()->saveXML());

        // Start

        $this->pdfWriter->attach(
            $documentBuilderXmlDataRef,
            $this->documentBuiler->getProfileDefinition()['attachmentfilename'],
            'Order-X XML File',
            'Data',
            'text#2Fxml'
        );

        $this->pdfWriter->openAttachmentPane();

        // Copy pages from the original PDF

        $pageCount = $this->pdfWriter->setSourceFile($pdfDataRef);

        for ($pageNumber = 1; $pageNumber <= $pageCount; ++$pageNumber) {
            $pageContent = $this->pdfWriter->importPage($pageNumber, '/MediaBox');
            $this->pdfWriter->AddPage();
            $this->pdfWriter->useTemplate($pageContent);
        }

        // Set PDF version 1.7 according to PDF/A-3 ISO 32000-1

        $this->pdfWriter->setPdfVersion('1.7', true);

        // Update meta data (e.g. such as author, producer, title)

        $this->updatePdfMetadata();
    }

    /**
     * Update PDF metadata to according to Order-X XML data.
     *
     * @return void
     */
    private function updatePdfMetadata(): void
    {
        $pdfMetadataInfos = $this->preparePdfMetadata();
        $this->pdfWriter->setPdfMetadataInfos($pdfMetadataInfos);

        $xmp = simplexml_load_file(PathUtils::combinePathWithFile(OrderSettings::getAssetDirectory(), 'orderx_extension_schema.xmp'));

        $descNodes = $xmp->xpath('rdf:Description');
        $descNode = $descNodes[0];

        $descNode->children('fx_1_', true)->{'ConformanceLevel'} = strtoupper($this->documentBuiler->getProfileDefinition()["xmpname"]);

        $descDcNodes = $descNode->children('dc', true);
        $descDcNodes->title->children('rdf', true)->Alt->li = $pdfMetadataInfos['title'];
        $descDcNodes->creator->children('rdf', true)->Seq->li = $pdfMetadataInfos['author'];
        $descDcNodes->description->children('rdf', true)->Alt->li = $pdfMetadataInfos['subject'];

        $descPdfNodes = $descNode->children('pdf', true);
        $descPdfNodes->{'Producer'} = 'FPDF';

        $descXmpNodes = $descNode->children('xmp', true);
        $descXmpNodes->{'CreatorTool'} = sprintf('Order-X PHP library %s by HorstOeko', OrderPackageVersion::getInstalledVersion());
        $descXmpNodes->{'CreateDate'} = $pdfMetadataInfos['createdDate'];
        $descXmpNodes->{'ModifyDate'} = $pdfMetadataInfos['modifiedDate'];

        $this->pdfWriter->addMetadataDescriptionNode($descNode->asXML());
    }

    /**
     * Prepare PDF Metadata informations from Order-X XML.
     *
     * @return array
     */
    private function preparePdfMetadata(): array
    {
        $orderInformations = $this->extractOrderInformations();

        $dateString = date('Y-m-d', strtotime($orderInformations['date']));
        $title = sprintf('%s, %s %s', $orderInformations['sellerName'], $orderInformations['docTypeName'], $orderInformations['orderId']);
        $subject = sprintf('Order-X %s %s dated %s issued by %s', $orderInformations['docTypeName'], $orderInformations['orderId'], $dateString, $orderInformations['sellerName']);

        $pdfMetadata = array(
            'author' => $orderInformations['sellerName'],
            'keywords' => sprintf('%s, Order-X', $orderInformations['docTypeName']),
            'title' => $title,
            'subject' => $subject,
            'createdDate' => $orderInformations['date'],
            'modifiedDate' => date('Y-m-d\TH:i:s') . '+00:00',
        );

        return $pdfMetadata;
    }

    /**
     * Extract major invoice information from Order-X XML.
     *
     * @return array
     */
    private function extractOrderInformations(): array
    {
        $xpath = $this->documentBuiler->getContentAsDomXPath();

        $dateXpath = $xpath->query('//rsm:ExchangedDocument/ram:IssueDateTime/udt:DateTimeString');
        $date = $dateXpath->item(0)->nodeValue;
        $dateReformatted = date('Y-m-d\TH:i:s', strtotime($date)) . '+00:00';

        $orderIdXpath = $xpath->query('//rsm:ExchangedDocument/ram:ID');
        $orderId = $orderIdXpath->item(0)->nodeValue;

        $sellerXpath = $xpath->query('//ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:Name');
        $sellerName = $sellerXpath->item(0)->nodeValue;

        $docTypeXpath = $xpath->query('//rsm:ExchangedDocument/ram:TypeCode');
        $docTypeCode = $docTypeXpath->item(0)->nodeValue;

        switch ($docTypeCode) {
        case OrderDocumentTypes::ORDER:
            $docTypeName = 'Order';
            break;
        case OrderDocumentTypes::ORDER_CHANGE:
            $docTypeName = 'Order Change';
            break;
        case OrderDocumentTypes::ORDER_RESPONSE:
            $docTypeName = 'Order Response';
            break;
        default:
            $docTypeName = 'Order';
            break;
        }

        $orderInformation = array(
            'orderId' => $orderId,
            'docTypeName' => $docTypeName,
            'sellerName' => $sellerName,
            'date' => $dateReformatted,
        );

        return $orderInformation;
    }

    /**
     * Returns true if the submittet parameter $pdfData is a valid file.
     * Otherwise it will return false
     *
     * @param  string $pdfData
     * @return boolean
     */
    private function pdfDataIsFile($pdfData): bool
    {
        try {
            return @is_file($pdfData);
        } catch (\TypeError $ex) {
            return false;
        }
    }
}
