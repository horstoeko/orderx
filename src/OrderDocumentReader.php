<?php

/**
 * This file is a part of horstoeko/orderx.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\orderx;

use Closure;
use DateTime;
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

    /**
     * Undocumented function
     *
     * @param string|null $documentNo
     * @param string|null $documentTypeCode
     * @param DateTime|null $documentDate
     * @param string|null $documentCurrency
     * @param string|null $documentName
     * @param string|null $documentLanguageId
     * @param DateTime|null $documentEffectiveSpecifiedPeriod
     * @param string|null $documentPurposeCode
     * @param string|null $documentRequestedResponseTypeCode
     * @return OrderDocumentBuilder
     */
    public function getDocumentInformation(?string &$documentNo, ?string &$documentTypeCode, ?DateTime &$documentDate, ?string &$documentCurrency, ?string &$documentName, ?string &$documentLanguageId, ?DateTime &$documentEffectiveSpecifiedPeriod, ?string &$documentPurposeCode, ?string &$documentRequestedResponseTypeCode): OrderDocumentReader
    {
        $documentNo = $this->getInvoiceValueByPath("getExchangedDocument.getID", "");
        $documentTypeCode = $this->getInvoiceValueByPath("getExchangedDocument.getTypeCode", "");
        $documentDate = $this->objectHelper->toDateTime(
            $this->getInvoiceValueByPath("getExchangedDocument.getIssueDateTime.getDateTimeString", ""),
            $this->getInvoiceValueByPath("getExchangedDocument.getIssueDateTime.getDateTimeString.getFormat", "")
        );
        $documentCurrency = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getOrderCurrencyCode", "");
        $documentName = $this->getInvoiceValueByPath("getExchangedDocument.getName", "");
        $documentLanguageIds = $this->getInvoiceValueByPath("getExchangedDocument.getLanguageID", []);
        $documentLanguageId = (isset($documentLanguageIds[0]) ? $this->objectHelper->tryCallByPathAndReturn($documentLanguageIds[0], "value") : "");
        $documentEffectiveSpecifiedPeriod = $this->objectHelper->toDateTime(
            $this->getInvoiceValueByPath("getExchangedDocument.getEffectiveSpecifiedPeriod.getDateTimeString", ""),
            $this->getInvoiceValueByPath("getExchangedDocument.getEffectiveSpecifiedPeriod.getDateTimeString.getFormat", "")
        );
        $documentPurposeCode = $this->getInvoiceValueByPath("getExchangedDocument.getPurposeCode", "");
        $documentRequestedResponseTypeCode = $this->getInvoiceValueByPath("getExchangedDocument.getRequestedResponseTypeCode", "");

        return $this;
    }

    /**
     * Function to return a value from $invoiceObject by path
     *
     * @codeCoverageIgnore
     *
     * @param string $methods
     * @param mixed $defaultValue
     * @return mixed
     */
    private function getInvoiceValueByPath(string $methods, $defaultValue)
    {
        return $this->getInvoiceValueByPathFrom($this->invoiceObject, $methods, $defaultValue);
    }

    /**
     * Function to return a value from $from by path
     *
     * @codeCoverageIgnore
     *
     * @param object|null $from
     * @param string $methods
     * @param mixed $defaultValue
     * @return mixed
     */
    private function getInvoiceValueByPathFrom(?object $from, string $methods, $defaultValue)
    {
        return $this->objectHelper->tryCallByPathAndReturn($from, $methods) ?? $defaultValue;
    }

    /**
     * Convert to array
     *
     * @codeCoverageIgnore
     *
     * @param mixed $value
     * @param array $methods
     * @return array
     */
    private function convertToArray($value, array $methods)
    {
        $result = [];
        $isFlat = count($methods) == 1;
        $value = $this->objectHelper->ensureArray($value);

        foreach ($value as $valueItem) {
            $resultItem = [];

            foreach ($methods as $methodKey => $method) {
                if (is_array($method)) {
                    $defaultValue = $method[1];
                    $method = $method[0];
                } else {
                    $defaultValue = null;
                }

                if ($method instanceof Closure) {
                    $itemValue = $method($valueItem);
                } else {
                    $itemValue = $this->objectHelper->tryCallByPathAndReturn($valueItem, $method) ?? $defaultValue;
                }

                if ($isFlat === true) {
                    $result[] = $itemValue;
                } else {
                    $resultItem[$methodKey] = $itemValue;
                }
            }

            if ($isFlat !== true) {
                $result[] = $resultItem;
            }
        }

        return $result;
    }

    /**
     * Convert to associative array
     *
     * @codeCoverageIgnore
     *
     * @param mixed $value
     * @param string $methodKey
     * @param string $methodValue
     * @return array
     */
    private function convertToAssociativeArray($value, string $methodKey, string $methodValue)
    {
        $result = [];
        $value = $this->objectHelper->ensureArray($value);

        foreach ($value as $valueItem) {
            $theValueForKey = $this->objectHelper->tryCallByPathAndReturn($valueItem, $methodKey);
            $theValueForValue = $this->objectHelper->tryCallByPathAndReturn($valueItem, $methodValue);

            if (!OrderObjectHelper::isNullOrEmpty($theValueForKey) && !OrderObjectHelper::isNullOrEmpty($theValueForValue)) {
                $result[$theValueForKey] = $theValueForValue;
            }
        }

        return $result;
    }
}
