<?php

/**
 * This file is a part of horstoeko/orderx.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\orderx;

use horstoeko\orderx\exception\OrderFileNotFoundException;
use horstoeko\orderx\exception\OrderNoValidAttachmentFoundInPdfException;
use Smalot\PdfParser\Parser as PdfParser;

/**
 * Class representing the document reader for incoming PDF/A-Documents with
 * XML data in BASIC-, COMFORT- and EXTENDED profile
 *
 * @category Order-X
 * @package  Order-X
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/orderx
 */
class OrderDocumentPdfReader
{
    /**
     * List of filenames which are possible in PDF
     */
    const ATTACHMENT_FILEAMES = ['order-x.xml'];

    /**
     * Load a PDF file (Order-X)
     *
     * @param  string $pdfFilename
     * Contains a full-qualified filename which must exist and must be readable
     * @return OrderDocumentReader|null
     * @throws OrderFileNotFoundException
     * @throws OrderNoValidAttachmentFoundInPdfException
     */
    public static function readAndGuessFromFile(string $pdfFilename): ?OrderDocumentReader
    {
        if (!file_exists($pdfFilename)) {
            throw new OrderFileNotFoundException($pdfFilename);
        }
        if (!is_readable($pdfFilename)) {
            throw new OrderFileNotFoundException($pdfFilename);
        }

        $pdfParser = new PdfParser();
        $pdfParsed = $pdfParser->parseFile($pdfFilename);
        $filespecs = $pdfParsed->getObjectsByType('Filespec');

        $attachmentFound = false;
        $attachmentIndex = 0;
        $embeddedFileIndex = 0;
        $orderDocument = null;

        foreach ($filespecs as $filespec) {
            $filespecDetails = $filespec->getDetails();
            if (in_array($filespecDetails['F'], static::ATTACHMENT_FILEAMES)) {
                $attachmentFound = true;
                break;
            }
            $attachmentIndex++;
        }

        if (true == $attachmentFound) {
            /**
             * @var array<\Smalot\PdfParser\PDFObject>
             */
            $embeddedFiles = $pdfParsed->getObjectsByType('EmbeddedFile');
            foreach ($embeddedFiles as $embeddedFile) {
                if ($attachmentIndex == $embeddedFileIndex) {
                    $orderDocument = OrderDocumentReader::readAndGuessFromContent($embeddedFile->getContent());
                    break;
                }
                $embeddedFileIndex++;
            }
        }

        if (is_null($orderDocument)) {
            throw new OrderNoValidAttachmentFoundInPdfException();
        }

        return $orderDocument;
    }
}
