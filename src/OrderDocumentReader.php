<?php

declare(strict_types=1);

/**
 * This file is a part of horstoeko/orderx.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\orderx;

use SimpleXMLElement;
use horstoeko\orderx\exception\OrderFileNotFoundException;
use horstoeko\orderx\exception\OrderCannotFindProfileString;
use horstoeko\orderx\exception\OrderUnknownProfileException;

/**
 * Class representing the document reader for incoming XML-Documents with
 * XML data in BASIC-, COMFORT- and EXTENDED profile
 *
 * @category Order-X
 * @package  Order-X
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/orderx
 */
class OrderDocumentReader extends OrderDocument
{
    /**
     * @var string
     */
    private $binarydatadirectory = "";

    /**
     * Set the directory where the attached binary data from
     * additional referenced documents are temporary stored
     *
     * @param string $binarydatadirectory
     * @return OrderDocumentReader
     */
    public function setBinaryDataDirectory(string $binarydatadirectory): OrderDocumentReader
    {
        $this->binarydatadirectory = "";

        if ($binarydatadirectory) {
            if (is_dir($binarydatadirectory) && is_writable($binarydatadirectory)) {
                $this->binarydatadirectory = $binarydatadirectory;
            }
        }

        return $this;
    }

    /**
     * Guess the profile type of a xml file
     *
     * @codeCoverageIgnore
     *
     * @param string $xmlfilename The filename to read invoice data from
     * @return OrderDocumentReader
     * @throws Exception
     */
    public static function readAndGuessFromFile(string $xmlfilename): OrderDocumentReader
    {
        if (!file_exists($xmlfilename)) {
            throw new OrderFileNotFoundException($xmlfilename);
        }

        return self::readAndGuessFromContent(file_get_contents($xmlfilename));
    }

    /**
     * Guess the profile type of the readden xml document
     *
     * @codeCoverageIgnore
     *
     * @param string $xmlcontent The XML content as a string to read the invoice data from
     * @return OrderDocumentReader
     * @throws Exception
     */
    public static function readAndGuessFromContent(string $xmlcontent): OrderDocumentReader
    {
        $xmldocument = new SimpleXMLElement($xmlcontent);
        $typeelement = $xmldocument->xpath('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocumentContext/ram:GuidelineSpecifiedDocumentContextParameter/ram:ID');

        if (!is_array($typeelement) || !isset($typeelement[0])) {
            throw new OrderCannotFindProfileString();
        }

        foreach (OrderProfiles::PROFILEDEF as $profile => $profiledef) {
            if ($typeelement[0] == $profiledef["contextparameter"]) {
                return (new self($profile))->readContent($xmlcontent);
            }
        }

        throw new OrderUnknownProfileException((string)$typeelement[0]);
    }

    /**
     * Read content of a zuferd/xrechnung xml from a string
     *
     * @codeCoverageIgnore
     *
     * @param string $xmlcontent The XML content as a string to read the invoice data from
     * @return OrderDocumentReader
     */
    private function readContent(string $xmlcontent): OrderDocumentReader
    {
        $this->invoiceObject = $this->serializer->deserialize($xmlcontent, 'horstoeko\orderx\entities\\' . $this->getProfileDefinition()["name"] . '\rsm\SCRDMCCBDACIOMessageStructure', 'xml');
        return $this;
    }
}
