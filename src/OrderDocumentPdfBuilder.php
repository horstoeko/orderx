<?php

/**
 * This file is a part of horstoeko/orderx.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\orderx;

use horstoeko\orderx\OrderDocumentBuilder;

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
class OrderDocumentPdfBuilder extends OrderDocumentPdfBuilderAbstract
{
    /**
     * Internal reference to the xml builder instance
     *
     * @var OrderDocumentBuilder
     */
    private $documentBuiler = null;

    /**
     * Cached XML data
     *
     * @var string
     */
    private $xmlDataCache = "";

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

        parent::__construct($pdfData);
    }

    /**
     * @inheritDoc
     */
    protected function getXmlContent(): string
    {
        if ($this->xmlDataCache) {
            return $this->xmlDataCache;
        }

        $this->xmlDataCache = $this->documentBuiler->getContentAsDomDocument()->saveXML();

        return $this->xmlDataCache;
    }

    /**
     * @inheritDoc
     */
    protected function getXmlAttachmentFilename(): string
    {
        return $this->documentBuiler->getProfileDefinition()['attachmentfilename'];
    }

    /**
     * @inheritDoc
     */
    protected function getXmlAttachmentXmpName(): string
    {
        return $this->documentBuiler->getProfileDefinition()["xmpname"];
    }
}
