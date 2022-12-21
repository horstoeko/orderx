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
use OutOfRangeException;

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
     * @var integer
     */
    private $documentNotePointer = 0;

    /**
     * @var integer
     */
    private $documentSellerContactPointer = 0;

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
     * @param string $xmlcontent The XML content as a string to read the invoice data from
     * @return OrderDocumentReader
     */
    private function readContent(string $xmlcontent): OrderDocumentReader
    {
        $this->orderObject = $this->serializer->deserialize($xmlcontent, 'horstoeko\orderx\entities\\' . $this->getProfileDefinition()["name"] . '\rsm\SCRDMCCBDACIOMessageStructure', 'xml');
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
     * @return OrderDocumentReader
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
     * Read copy indicator
     *
     * @param boolean|null $copyindicator
     * Returns true if this document is a copy from the original document
     * @return OrderDocumentReader
     */
    public function getIsDocumentCopy(?bool &$copyindicator): OrderDocumentReader
    {
        $copyindicator = $this->getInvoiceValueByPath("getExchangedDocument.getCopyIndicator.getIndicator", false);
        return $this;
    }

    /**
     * Read a test document indicator
     *
     * @param boolean|null $testdocumentindicator
     * Returns true if this document is only for test purposes
     * @return OrderDocumentReader
     */
    public function getIsTestDocument(?bool &$testdocumentindicator): OrderDocumentReader
    {
        $testdocumentindicator = $this->getInvoiceValueByPath("getExchangedDocumentContext.getTestIndicator.getIndicator", false);
        return $this;
    }

    /**
     * Set the intérnal note pointer to the first position
     * It will return false if there is no note on the first position
     *
     * @return boolean
     */
    public function firstDocumentNote(): bool
    {
        $this->documentNotePointer = 0;
        $notes = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getExchangedDocument.getIncludedNote", []));
        return isset($notes[$this->documentNotePointer]);
    }

    /**
     * Set the intérnal note pointer to the next position.
     * It will return false if there is no next note
     *
     * @return boolean
     */
    public function nextDocumentNote(): bool
    {
        $this->documentNotePointer++;
        $notes = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getExchangedDocument.getIncludedNote", []));
        return isset($notes[$this->documentNotePointer]);
    }

    /**
     * Retrieve the document note. You have to check first if there is a note using
     * the firstDocumentNote/nextDocumentNote methods
     *
     * @param array|null $content
     * Note content
     * @param string|null $subjectCode
     * Subject code. To be chosen from the entries in UNTDID 4451
     * @param string|null $contentCode
     * Content code. To be chosen from the entries in UNTDID xxx
     * @return OrderDocumentReader
     * @throws OutOfRangeException
     */
    public function getDocumentNote(?array &$content, ?string &$subjectCode, ?string &$contentCode): OrderDocumentReader
    {
        $notes = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getExchangedDocument.getIncludedNote", []));
        $note = $this->objectHelper->getArrayIndex($notes, $this->documentNotePointer);
        $content = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($note, "getContent", ""));
        $subjectCode = $this->getInvoiceValueByPathFrom($note, "getSubjectCode", "");
        $contentCode = $this->getInvoiceValueByPathFrom($note, "getContentCode", "");
        return $this;
    }

    /**
     * Get the document's money summation
     *
     * @param float $lineTotalAmount Sum of the net amounts of all prder items
     * Sum of all order line net amounts in the order.
     * @param float|null $grandTotalAmount Total order amount including sales tax
     * The total amount of the order with VAT.
     * @param float|null $chargeTotalAmount Sum of the surcharges at document level
     * Sum of all charges on document level in the order.
     * @param float|null $allowanceTotalAmount Sum of the discounts at document level
     * Sum of all allowances on document level in the order.
     * @param float|null $taxBasisTotalAmount Total order amount excluding sales tax
     * The total amount of the order without VAT.
     * @param float|null $taxTotalAmount Total amount of the order tax, Total tax amount in the booking currency
     * The total VAT amount for the order.
     * @return OrderDocumentReader
     */
    public function getDocumentSummation(?float &$lineTotalAmount, ?float &$grandTotalAmount, ?float &$chargeTotalAmount, ?float &$allowanceTotalAmount, ?float &$taxBasisTotalAmount, ?float &$taxTotalAmount): OrderDocumentReader
    {
        $orderCurrencyCode = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getOrderCurrencyCode", "");

        $grandTotalAmountElement = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getSpecifiedTradeSettlementHeaderMonetarySummation.getGrandTotalAmount", 0);
        if (is_array($grandTotalAmountElement)) {
            foreach ($grandTotalAmountElement as $grandTotalAmountElementItem) {
                $grandTotalAmountCurrencyCode = $this->objectHelper->tryCallAndReturn($grandTotalAmountElementItem, "getCurrencyID") ?? "";
                if ($grandTotalAmountCurrencyCode == $orderCurrencyCode || $grandTotalAmountCurrencyCode == "") {
                    $grandTotalAmount = $this->objectHelper->tryCallAndReturn($grandTotalAmountElementItem, "value") ?? 0;
                    break;
                }
            }
        } else {
            $grandTotalAmount = $this->objectHelper->tryCallAndReturn($grandTotalAmountElement, "value") ?? 0;
        }

        $taxBasisTotalAmountElement = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getSpecifiedTradeSettlementHeaderMonetarySummation.getTaxBasisTotalAmount", 0);
        if (is_array($taxBasisTotalAmountElement)) {
            foreach ($taxBasisTotalAmountElement as $taxBasisTotalAmountElementItem) {
                $taxBasisTotalAmountCurrencyCode = $this->objectHelper->tryCallAndReturn($taxBasisTotalAmountElementItem, "getCurrencyID") ?? "";
                if ($taxBasisTotalAmountCurrencyCode == $orderCurrencyCode || $taxBasisTotalAmountCurrencyCode == "") {
                    $taxBasisTotalAmount = $this->objectHelper->tryCallAndReturn($taxBasisTotalAmountElementItem, "value") ?? 0;
                    break;
                }
            }
        } else {
            $taxBasisTotalAmount = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getSpecifiedTradeSettlementHeaderMonetarySummation.getTaxBasisTotalAmount.value", 0);
        }

        $taxTotalAmountElement = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getSpecifiedTradeSettlementHeaderMonetarySummation.getTaxTotalAmount", 0);
        if (is_array($taxTotalAmountElement)) {
            foreach ($taxTotalAmountElement as $taxTotalAmountElementItem) {
                $taxTotalAmountCurrencyCode = $this->objectHelper->tryCallAndReturn($taxTotalAmountElementItem, "getCurrencyID") ?? "";
                if ($taxTotalAmountCurrencyCode == $orderCurrencyCode || $taxTotalAmountCurrencyCode == "") {
                    $taxTotalAmount = $this->objectHelper->tryCallAndReturn($taxTotalAmountElementItem, "value") ?? 0;
                    break;
                }
            }
        } else {
            $taxTotalAmount = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getSpecifiedTradeSettlementHeaderMonetarySummation.getTaxBasisTotalAmount.value", 0);
        }

        $lineTotalAmount = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getSpecifiedTradeSettlementHeaderMonetarySummation.getLineTotalAmount.value", 0);
        $chargeTotalAmount = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getSpecifiedTradeSettlementHeaderMonetarySummation.getChargeTotalAmount.value", 0);
        $allowanceTotalAmount = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getSpecifiedTradeSettlementHeaderMonetarySummation.getAllowanceTotalAmount.value", 0);

        return $this;
    }

    /**
     * Get the identifier assigned by the buyer and used for internal routing.
     *
     * __Note__: The reference is specified by the buyer (e.g. contact details, department, office ID, project code),
     * but stated by the seller on the invoice.
     *
     * @param string|null $buyerreference
     * An identifier assigned by the buyer and used for internal routing
     * @return OrderDocumentReader
     */
    public function getDocumentBuyerReference(?string &$buyerreference): OrderDocumentReader
    {
        $buyerreference = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerReference", "");
        return $this;
    }

    /**
     * Get detailed information about the seller (=service provider)
     *
     * @param string|null $name
     * The full formal name under which the seller is registered in the
     * National Register of Legal Entities, Taxable Person or otherwise acting as person(s)
     * @param array|null $id
     * An identifier of the seller. In many systems, seller identification
     * is key information. Multiple seller IDs can be assigned or specified. They can be differentiated
     * by using different identification schemes. If no scheme is given, it should be known to the buyer
     * and seller, e.g. a previously exchanged, buyer-assigned identifier of the seller
     * @param string|null $description
     * Further legal information that is relevant for the seller
     * @return OrderDocumentReader
     */
    public function getDocumentSeller(?string &$name, ?array &$id, ?string &$description): OrderDocumentReader
    {
        $name = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getSellerTradeParty.getName", "");
        $id = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getSellerTradeParty.getID", []);
        $description = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getSellerTradeParty.getDescription", "");

        $id = $this->convertToArray($id, ["id" => "value"]);

        return $this;
    }

    /**
     * Get global identifier of the seller.
     *
     * __Notes__
     *
     * - The Seller's ID identification scheme is a unique identifier
     *   assigned to a seller by a global registration organization
     *
     * @param array|null $globalID
     * Array of the sellers global ids indexed by the identification scheme. The identification scheme results
     * from the list published by the ISO/IEC 6523 Maintenance Agency. In particular, the following scheme
     * codes are used: 0021 : SWIFT, 0088 : EAN, 0060 : DUNS, 0177 : ODETTE
     * @return OrderDocumentReader
     */
    public function getDocumentSellerGlobalId(?array &$globalID): OrderDocumentReader
    {
        $globalID = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getSellerTradeParty.getGlobalID", []);
        $globalID = $this->convertToAssociativeArray($globalID, "getSchemeID", "value");

        return $this;
    }

    /**
     * Get detailed information on the seller's tax information.
     *
     * __Notes__
     *  - The local identification (defined by the seller's address) of the seller for tax purposes or a reference that
     *    enables the seller to indicate his reporting status for tax purposes. This information may have an impact on how the buyer
     *    pays the bill (such as regarding social security contributions). So e.g. in some countries, if the seller is not reported
     *    for tax, the buyer will withhold the tax amount and pay it on behalf of the seller
     *
     * @param array|null $taxreg
     * Array of sales tax identification numbers of the seller indexed by __FC__ for _Tax number of the seller_ and __VA__
     * for _Sales tax identification number of the seller_
     * @return OrderDocumentReader
     */
    public function getDocumentSellerTaxRegistration(?array &$taxreg): OrderDocumentReader
    {
        $taxreg = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getSellerTradeParty.getSpecifiedTaxRegistration", []);
        $taxreg = $this->convertToAssociativeArray($taxreg, "getID.getSchemeID", "getID.value");

        return $this;
    }

    /**
     * Get the address of seller trade party
     *
     * @param string|null $lineone
     * The main line in the sellers address. This is usually the street name and house number or
     * the post office box
     * @param string|null $linetwo
     * Line 2 of the seller's address. This is an additional address line in an address that can be
     * used to provide additional details in addition to the main line
     * @param string|null $linethree
     * Line 3 of the seller's address. This is an additional address line in an address that can be
     * used to provide additional details in addition to the main line
     * @param string|null $postcode
     * Identifier for a group of properties, such as a zip code
     * @param string|null $city
     * Usual name of the city or municipality in which the seller's address is located
     * @param string|null $country
     * Code used to identify the country. If no tax agent is specified, this is the country in which the sales tax
     * is due. The lists of approved countries are maintained by the EN ISO 3166-1 Maintenance Agency “Codes for the
     * representation of names of countries and their subdivisions”
     * @param array|null $subdivision
     * The sellers state
     * @return OrderDocumentReader
     */
    public function getDocumentSellerAddress(?string &$lineone, ?string &$linetwo, ?string &$linethree, ?string &$postcode, ?string &$city, ?string &$country, ?string &$subdivision): OrderDocumentReader
    {
        $lineone = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getSellerTradeParty.getPostalTradeAddress.getLineOne", "");
        $linetwo = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getSellerTradeParty.getPostalTradeAddress.getLineTwo", "");
        $linethree = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getSellerTradeParty.getPostalTradeAddress.getLineThree", "");
        $postcode = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getSellerTradeParty.getPostalTradeAddress.getPostcodeCode.value", "");
        $city = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getSellerTradeParty.getPostalTradeAddress.getCityName", "");
        $country = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getSellerTradeParty.getPostalTradeAddress.getCountryID", "");
        $subdivision = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getSellerTradeParty.getPostalTradeAddress.getCountrySubDivisionName", "");

        return $this;
    }

    /**
     * Get the legal organisation of seller trade party
     *
     * @param string|null $legalorgid
     * An identifier issued by an official registrar that identifies the
     * seller as a legal entity or legal person. If no identification scheme ($legalorgtype) is provided,
     * it should be known to the buyer and seller
     * @param string|null $legalorgtype
     * The identifier for the identification scheme of the legal
     * registration of the seller. If the identification scheme is used, it must be selected from
     * ISO/IEC 6523 list
     * @param string|null $legalorgname
     * A name by which the seller is known, if different from the seller's name
     * (also known as the company name)
     * @return OrderDocumentReader
     */
    public function getDocumentSellerLegalOrganisation(?string &$legalorgid, ?string &$legalorgtype, ?string &$legalorgname): OrderDocumentReader
    {
        $legalorgid = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getSellerTradeParty.getSpecifiedLegalOrganization.getID.value", "");
        $legalorgtype = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getSellerTradeParty.getSpecifiedLegalOrganization.getID.getSchemeID", "");
        $legalorgname = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getSellerTradeParty.getSpecifiedLegalOrganization.getTradingBusinessName", "");

        return $this;
    }

    /**
     * Seek to the first seller contact of the document.
     * Returns true if a first seller contact is available, otherwise false
     * You may use this together with ZugferdDocumentReader::getDocumentSellerContact
     *
     * @return boolean
     */
    public function firstDocumentSellerContact(): bool
    {
        $this->documentSellerContactPointer = 0;
        $contacts = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getSellerTradeParty.getDefinedTradeContact", []));
        return isset($contacts[$this->documentSellerContactPointer]);
    }

    /**
     * Seek to the next available first seller contact of the document.
     * Returns true if another seller contact is available, otherwise false
     * You may use this together with ZugferdDocumentReader::getDocumentSellerContact
     *
     * @return boolean
     */
    public function nextDocumentSellerContact(): bool
    {
        $this->documentSellerContactPointer++;
        $contacts = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getSellerTradeParty.getDefinedTradeContact", []));
        return isset($contacts[$this->documentSellerContactPointer]);
    }

    /**
     * Get detailed information on the seller's contact person
     *
     * @param string|null $contactpersonname
     * Contact point for a legal entity,
     * such as a personal name of the contact person
     * @param string|null $contactdepartmentname
     * Contact point for a legal entity, such as a name of the department or office
     * @param string|null $contactphoneno
     * Detailed information on the seller's phone number
     * @param string|null $contactfaxno
     * Detailed information on the seller's fax number
     * @param string|null $contactemailadd
     * Detailed information on the seller's email address
     * @param string|null $contacttypecode
     * @return OrderDocumentReader
     */
    public function getDocumentSellerContact(?string &$contactpersonname, ?string &$contactdepartmentname, ?string &$contactphoneno, ?string &$contactfaxno, ?string &$contactemailadd, ?string &$contacttypecode): OrderDocumentReader
    {
        $contacts = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getSellerTradeParty.getDefinedTradeContact", []));
        $contact = $this->objectHelper->getArrayIndex($contacts, $this->documentSellerContactPointer);
        $contactpersonname = $this->getInvoiceValueByPathFrom($contact, "getPersonName", "");
        $contactdepartmentname = $this->getInvoiceValueByPathFrom($contact, "getDepartmentName", "");
        $contactphoneno = $this->getInvoiceValueByPathFrom($contact, "getTelephoneUniversalCommunication.getCompleteNumber", "");
        $contactfaxno = $this->getInvoiceValueByPathFrom($contact, "getFaxUniversalCommunication.getCompleteNumber", "");
        $contactemailadd = $this->getInvoiceValueByPathFrom($contact, "getEmailURIUniversalCommunication.getURIID", "");
        $contacttypecode = $this->getInvoiceValueByPathFrom($contact, "getTypeCode", "");

        return $this;
    }

    /**
     * Set the universal communication info for the seller
     *
     * @param string|null $uriType
     * @param string|null $uriId
     * @return OrderDocumentReader
     */
    public function getDocumentSellerElectronicAddress(?string &$uriType = null, ?string &$uriId = null): OrderDocumentReader
    {
        $uriType = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getSellerTradeParty.getURIUniversalCommunication.getURIID.getschemeID", "");
        $uriId = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getSellerTradeParty.getURIUniversalCommunication.getURIID", "");

        return $this;
    }

    /**
     * Function to return a value from $orderobject by path
     *
     * @codeCoverageIgnore
     *
     * @param string $methods
     * @param mixed $defaultValue
     * @return mixed
     */
    private function getInvoiceValueByPath(string $methods, $defaultValue)
    {
        return $this->getInvoiceValueByPathFrom($this->orderObject, $methods, $defaultValue);
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
