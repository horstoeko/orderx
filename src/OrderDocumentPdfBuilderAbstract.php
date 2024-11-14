<?php

/**
 * This file is a part of horstoeko/orderx.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\orderx;

use DOMXpath;
use Throwable;
use DOMDocument;
use InvalidArgumentException;
use horstoeko\mimedb\MimeDb;
use horstoeko\orderx\codelists\OrderDocumentTypes;
use horstoeko\orderx\exception\OrderFileNotFoundException;
use horstoeko\orderx\exception\OrderUnknownMimetype;
use horstoeko\orderx\OrderPackageVersion;
use horstoeko\orderx\OrderPdfWriter;
use horstoeko\stringmanagement\FileUtils;
use setasign\Fpdi\PdfParser\StreamReader as PdfStreamReader;

/**
 * Class representing the base facillity adding XML data
 * to an existing PDF with conversion to PDF/A
 *
 * @category Order-X
 * @package  Order-X
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/orderx
 */
abstract class OrderDocumentPdfBuilderAbstract
{
    /**
     * Constants for Relationship types
     * 'Data', 'Alternative', 'Source', 'Supplement', 'Unspecified'
     */
    public const AF_RELATIONSHIP_DATA = "Data";
    public const AF_RELATIONSHIP_ALTERNATIVE = "Alternative";
    public const AF_RELATIONSHIP_SOURCE = "Source";
    public const AF_RELATIONSHIP_SUPPLEMENT = "Supplement";
    public const AF_RELATIONSHIP_UNSPECIFIED = "Unspecified";

    /**
     * Additional creator tool (e.g. the ERP software that called the PHP library)
     *
     * @var string
     */
    private $additionalCreatorTool = "";

    /**
     * The relationship type to use for the XML attachment. Detault is Data
     *
     * @var string
     */
    private $attachmentRelationshipType = 'Data';

    /**
     * Instance of the pdfwriter
     *
     * @var OrderPdfWriter
     */
    private $pdfWriter = null;

    /**
     * Contains the data of the original PDF document
     *
     * @var string
     */
    private $pdfData = "";

    /**
     * List of files which should be additionally attached to PDF
     *
     * @var array
     */
    private $additionalFilesToAttach = [];

    /**
     * Constructor
     *
     * @param string $pdfData
     * The full filename or a string containing the binary pdf data. This
     * is the original PDF (e.g. created by a ERP system)
     */
    public function __construct(string $pdfData)
    {
        $this->pdfData = $pdfData;
        $this->pdfWriter = new OrderPdfWriter();
    }

    /**
     * Generates the final document
     *
     * @return static
     */
    public function generateDocument()
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
     * @return static
     */
    public function saveDocument(string $toFilename)
    {
        $this->pdfWriter->Output($toFilename, 'F');

        return $this;
    }

    /**
     * Returns the PDF as an inline file
     *
     * @param  string $toFilename
     * @return string
     */
    public function saveDocumentInline(string $toFilename): string
    {
        return $this->pdfWriter->Output($toFilename, 'I');
    }

    /**
     * Returns the PDF as a string
     *
     * @param  string $toFilename
     * @return string
     */
    public function downloadString(string $toFilename): string
    {
        return $this->pdfWriter->Output($toFilename, 'S');
    }

    /**
     * Sets an additional creator tool (e.g. the ERP software that called the PHP library)
     *
     * @param  string $additionalCreatorTool
     * The name of the creator
     * @return static
     */
    public function setAdditionalCreatorTool(string $additionalCreatorTool)
    {
        $this->additionalCreatorTool = $additionalCreatorTool;

        return $this;
    }

    /**
     * Returns the creator tool name (the PHP library, and if given also the additional creator tool)
     *
     * @return string
     */
    public function getCreatorToolName(): string
    {
        $toolName = sprintf('Order-X PHP library %s by HorstOeko', OrderPackageVersion::getInstalledVersion());

        if ($this->additionalCreatorTool) {
            return $this->additionalCreatorTool . ' / ' . $toolName;
        }

        return $toolName;
    }

    /**
     * Set the type of relationship for the XML attachment. Allowed
     * types are 'Data', 'Alternative'
     *
     * @param  string $relationshipType
     * @return static
     */
    public function setAttachmentRelationshipType(string $relationshipType)
    {
        if (!in_array($relationshipType, [static::AF_RELATIONSHIP_DATA, static::AF_RELATIONSHIP_ALTERNATIVE, static::AF_RELATIONSHIP_SOURCE])) {
            $relationshipType = static::AF_RELATIONSHIP_DATA;
        }

        $this->attachmentRelationshipType = $relationshipType;

        return $this;
    }

    /**
     * Returns the relationship type for the XML attachment. This
     * can return 'Data', 'Alternative'
     *
     * @return string
     */
    public function getAttachmentRelationshipType(): string
    {
        return $this->attachmentRelationshipType;
    }

    /**
     * Set the type of relationship for the XML attachment to "Data"
     *
     * @return static
     */
    public function setAttachmentRelationshipTypeToData()
    {
        return $this->setAttachmentRelationshipType(static::AF_RELATIONSHIP_DATA);
    }

    /**
     * Set the type of relationship for the XML attachment to "Alternative"
     *
     * @return static
     */
    public function setAttachmentRelationshipTypeToAlternative()
    {
        return $this->setAttachmentRelationshipType(static::AF_RELATIONSHIP_ALTERNATIVE);
    }

    /**
     * Set the type of relationship for the XML attachment to "Source"
     *
     * @return static
     */
    public function setAttachmentRelationshipTypeToSource()
    {
        return $this->setAttachmentRelationshipType(static::AF_RELATIONSHIP_SOURCE);
    }

    /**
     * Attach an additional file to PDF. The file that is specified in $fullFilename
     * must exists
     *
     * @param  string $fullFilename
     * @param  string $displayName
     * @param  string $relationshipType
     * @return static
     * @throws OrderFileNotFoundException
     * @throws OrderUnknownMimetype
     */
    public function attachAdditionalFileByRealFile(string $fullFilename, string $displayName = "", string $relationshipType = "")
    {
        // Checks that the file really exists

        if (empty($fullFilename)) {
            throw new InvalidArgumentException("You must specify a filename for the content to attach");
        }

        if (!file_exists($fullFilename)) {
            throw new OrderFileNotFoundException($fullFilename);
        }

        // Load content

        $content = file_get_contents($fullFilename);

        // Add attachment

        $this->attachAdditionalFileByContent(
            $content,
            $fullFilename,
            $displayName,
            $relationshipType,
        );

        return $this;
    }

    /**
     * Attach an additional file to PDF by a content string
     *
     * @param  string $content
     * @param  string $filename
     * @param  string $displayName
     * @param  string $relationshipType
     * @return static
     */
    public function attachAdditionalFileByContent(string $content, string $filename, string $displayName = "", string $relationshipType = "")
    {
        // Check content. The content must not be empty

        if (empty($content)) {
            throw new InvalidArgumentException("You must specify a content to attach");
        }

        // Check filename. The filename must not be empty

        if (empty($filename)) {
            throw new InvalidArgumentException("You must specify a filename for the content to attach");
        }

        // Mimetype for the file must exist

        $mimeType = (new MimeDb())->findFirstMimeTypeByExtension(FileUtils::getFileExtension($filename));

        if (is_null($mimeType)) {
            throw new OrderUnknownMimetype();
        }

        // Sanatize relationship type

        if (empty($relationshipType)) {
            $relationshipType = static::AF_RELATIONSHIP_SUPPLEMENT;
        }

        if (!in_array($relationshipType, [static::AF_RELATIONSHIP_DATA, static::AF_RELATIONSHIP_ALTERNATIVE, static::AF_RELATIONSHIP_SOURCE, static::AF_RELATIONSHIP_SUPPLEMENT, static::AF_RELATIONSHIP_UNSPECIFIED])) {
            $relationshipType = static::AF_RELATIONSHIP_SUPPLEMENT;
        }

        // Sanatize displayname

        if (empty($displayName)) {
            $displayName = FileUtils::getFilenameWithExtension($filename);
        }

        // Add to attachment list

        $this->additionalFilesToAttach[] = [
            PdfStreamReader::createByString($content),
            FileUtils::getFilenameWithExtension($filename),
            $displayName,
            $relationshipType,
            str_replace('/', '#2F', $mimeType)
        ];

        return $this;
    }

    /**
     * Get the content of XML to attach
     *
     * @return string
     */
    abstract protected function getXmlContent(): string;

    /**
     * Get the filename of the XML to attach
     *
     * @return string
     */
    abstract protected function getXmlAttachmentFilename(): string;

    /**
     * Get the XMP name for the XML to attach
     *
     * @return string
     */
    abstract protected function getXmlAttachmentXmpName(): string;

    /**
     * Internal function which sets up the PDF
     *
     * @return void
     */
    private function startCreatePdf(): void
    {
        // Get PDF data

        $pdfDataRef = null;

        if ($this->isFile($this->pdfData)) {
            $pdfDataRef = $this->pdfData;
        } elseif (is_string($this->pdfData)) {
            $pdfDataRef = PdfStreamReader::createByString($this->pdfData);
        }

        // Get XML data from Builder

        $documentBuilderXmlDataRef = PdfStreamReader::createByString($this->getXmlContent());

        // Start

        $this->pdfWriter->attach(
            $documentBuilderXmlDataRef,
            $this->getXmlAttachmentFilename(),
            'Order-X XML File',
            $this->getAttachmentRelationshipType(),
            'text#2Fxml'
        );

        // Add additional attachments

        foreach ($this->additionalFilesToAttach as $fileToAttach) {
            $this->pdfWriter->attach(
                $fileToAttach[0],
                $fileToAttach[1],
                $fileToAttach[2],
                $fileToAttach[3],
                $fileToAttach[4],
            );
        }

        // Set flag to always show the attachment pane

        $this->pdfWriter->openAttachmentPane();

        // Copy pages from the original PDF

        $pageCount = $this->pdfWriter->setSourceFile($pdfDataRef);

        for ($pageNumber = 1; $pageNumber <= $pageCount; ++$pageNumber) {
            $pageContent = $this->pdfWriter->importPage($pageNumber, '/MediaBox', true, true);
            $this->pdfWriter->AddPage();
            $this->pdfWriter->useTemplate($pageContent, 0, 0, null, null, true);
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

        $xmp = simplexml_load_file(OrderSettings::getFullXmpMetaDataFilename());

        $descNodes = $xmp->xpath('rdf:Description');
        $descNode = $descNodes[0];

        $descNode->children('fx_1_', true)->{'ConformanceLevel'} = strtoupper($this->getXmlAttachmentXmpName());

        $descDcNodes = $descNode->children('dc', true);
        $descDcNodes->title->children('rdf', true)->Alt->li = $pdfMetadataInfos['title'];
        $descDcNodes->creator->children('rdf', true)->Seq->li = $pdfMetadataInfos['author'];
        $descDcNodes->description->children('rdf', true)->Alt->li = $pdfMetadataInfos['subject'];

        $descPdfNodes = $descNode->children('pdf', true);
        $descPdfNodes->{'Producer'} = 'FPDF';

        $descXmpNodes = $descNode->children('xmp', true);
        $descXmpNodes->{'CreatorTool'} = $this->getCreatorToolName();
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
        $title = sprintf('%s, %s %s', $orderInformations['seller'], $orderInformations['docTypeName'], $orderInformations['orderId']);
        $subject = sprintf('Order-X %s %s dated %s issued by %s', $orderInformations['docTypeName'], $orderInformations['orderId'], $dateString, $orderInformations['seller']);

        $pdfMetadata = array(
            'author' => $orderInformations['seller'],
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
        $domDocument = new DOMDocument();
        $domDocument->loadXML($this->getXmlContent());

        $xpath = new DOMXPath($domDocument);

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
            'seller' => $sellerName,
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
    private function isFile($pdfData): bool
    {
        try {
            return @is_file($pdfData);
        } catch (Throwable $ex) {
            return false;
        }
    }
}
