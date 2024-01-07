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
use horstoeko\orderx\exception\OrderFileNotFoundException;
use horstoeko\orderx\OrderProfileResolver;
use horstoeko\stringmanagement\FileUtils;
use horstoeko\stringmanagement\PathUtils;
use horstoeko\stringmanagement\StringUtils;
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
     * @var integer
     */
    private $documentBuyerContactPointer = 0;

    /**
     * @var integer
     */
    private $documentBuyerRequisitionerContactPointer = 0;

    /**
     * @var integer
     */
    private $documentShipToContactPointer = 0;

    /**
     * @var integer
     */
    private $documentShipFromContactPointer = 0;

    /**
     * @var integer
     */
    private $documentAddRefDocPointer = 0;

    /**
     * @var integer
     */
    private $documentUltimateCustomerOrderRefDocPointer = 0;

    /**
     * @var integer
     */
    private $documentRequestedDeliverySupplyChainEventPointer = 0;

    /**
     * @var integer
     */
    private $documentRequestedDespatchSupplyChainEventPointer = 0;

    /**
     * @var integer
     */
    private $documentInvoiceeContactPointer = 0;

    /**
     * @var integer
     */
    private $documentPaymentMeansPointer = 0;

    /**
     * @var integer
     */
    private $documentAllowanceChargePointer = 0;

    /**
     * @var integer
     */
    private $documentPaymentTermsPointer = 0;

    /**
     * @var integer
     */
    private $documentTaxPointer = 0;

    /**
     * Internal pointer for the position
     *
     * @var integer
     */
    private $positionPointer = 0;

    /**
     * Internal pointer for the position note
     *
     * @var integer
     */
    private $positionNotePointer = 0;

    /**
     * Internal pointer for the position's gross price allowances/charges
     *
     * @var integer
     */
    private $positionGrossPriceAllowanceChargePointer = 0;

    /**
     * Internal pointer for the position taxes
     *
     * @var integer
     */
    private $positionTaxPointer = 0;

    /**
     * Internal pointer for the position's allowances/charges
     *
     * @var integer
     */
    private $positionAllowanceChargePointer = 0;

    /**
     * Internal pointer for the position's additional product referenced document
     *
     * @var integer
     */
    private $positionProductReferencedDocumentPointer = 0;

    /**
     * @var integer
     */
    private $positionProductCharacteristicPointer = 0;

    /**
     * @var integer
     */
    private $positionProductClassificationPointer = 0;

    /**
     * @var integer
     */
    private $positionProductInstancePointer = 0;

    /**
     * @var integer
     */
    private $positionAddRefDocPointer = 0;

    /**
     * @var integer
     */
    private $positionUltimateCustomerOrderRefDocPointer = 0;

    /**
     * @var integer
     */
    private $positionRequestedDeliverySupplyChainEventPointer = 0;

    /**
     * @var integer
     */
    private $positionRequestedDespatchSupplyChainEventPointer = 0;

    /**
     * Set the directory where the attached binary data from
     * additional referenced documents are temporary stored
     *
     * @param  string $binarydatadirectory
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
     * @param  string $xmlfilename The filename to read invoice data from
     * @return OrderDocumentReader
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
     * @param  string $xmlcontent The XML content as a string to read the invoice data from
     * @return OrderDocumentReader
     */
    public static function readAndGuessFromContent(string $xmlcontent): OrderDocumentReader
    {
        $profileId = OrderProfileResolver::resolveProfileId($xmlcontent);

        return (new static($profileId))->readContent($xmlcontent);
    }

    /**
     * Read content of a orderx xml from a string
     *
     * @param  string $xmlcontent The XML content as a string to read the invoice data from
     * @return OrderDocumentReader
     */
    private function readContent(string $xmlcontent): OrderDocumentReader
    {
        $this->orderObject = $this->serializer->deserialize($xmlcontent, 'horstoeko\orderx\entities\\' . $this->getProfileDefinition()["name"] . '\rsm\SCRDMCCBDACIOMessageStructure', 'xml');
        return $this;
    }

    /**
     * Get main information about this document
     *
     * @param  string|null   $documentNo
     * An identifier of a referenced purchase order, issued by the Buyer.
     * @param  string|null   $documentTypeCode
     * A code specifying the functional type of the Order
     * Commercial orders and credit notes are defined according the entries in UNTDID 1001
     * Other entries of UNTDID 1001  with specific orders may be used if applicable.
     *  - 220 for Order
     *  - 230 for Order Change
     *  - 231 for Order Response
     * @param  DateTime|null $documentDate
     * The date when the document was issued by the buyer
     * @param  string|null   $documentCurrency
     * The code for the order currency
     * @param  string|null   $documentName
     * The document type (free text)
     * @param  string|null   $documentLanguageId
     * A unique identifier for a language used in this exchanged document
     * @param  DateTime|null $documentEffectiveSpecifiedPeriod
     * The specified period within which this exchanged document is effective
     * @param  string|null   $documentPurposeCode
     * The purpose, expressed as text, of this exchanged document
     * -  7 : Duplicate
     * -  9 : Original
     * - 35 : Retransmission
     * @param  string|null   $documentRequestedResponseTypeCode
     * Response requested for this exchanged document. Value = AC to request an Order_Response
     * Value = AC to request an Order_Response
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
     * @param  boolean|null $copyindicator
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
     * @param  boolean|null $testdocumentindicator
     * Returns true if this document is only for test purposes
     * @return OrderDocumentReader
     */
    public function getIsTestDocument(?bool &$testdocumentindicator): OrderDocumentReader
    {
        $testdocumentindicator = $this->getInvoiceValueByPath("getExchangedDocumentContext.getTestIndicator.getIndicator", false);
        return $this;
    }

    /**
     * Set the intérnal note pointer to the first document note
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
     * Set the intérnal note pointer to the next document note.
     * It will return false if there isn't one more document note
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
     * @param  array|null  $content
     * Note content
     * @param  string|null $subjectCode
     * Subject code. To be chosen from the entries in UNTDID 4451
     * @param  string|null $contentCode
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
     * Get the document money summation
     *
     * @param  float      $lineTotalAmount      Sum of the net amounts of all prder items
     *                                          Sum of all order line net amounts in the
     *                                          order.
     * @param  float|null $grandTotalAmount     Total order amount including sales tax
     *                                          The total amount of the order with
     *                                          VAT.
     * @param  float|null $chargeTotalAmount    Sum of the surcharges at document level
     *                                          Sum of all charges on document level in
     *                                          the order.
     * @param  float|null $allowanceTotalAmount Sum of the discounts at document level
     *                                          Sum of all allowances on document level in the order.
     * @param  float|null $taxBasisTotalAmount  Total order amount excluding sales tax
     *                                          The total amount of the order without
     *                                          VAT.
     * @param  float|null $taxTotalAmount       Total amount of the order tax, Total tax amount in the booking currency
     *                                          The total VAT amount for the order.
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
            $taxTotalAmount = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getSpecifiedTradeSettlementHeaderMonetarySummation.getTaxTotalAmount.value", 0);
        }

        $lineTotalAmount = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getSpecifiedTradeSettlementHeaderMonetarySummation.getLineTotalAmount.value", 0);
        $chargeTotalAmount = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getSpecifiedTradeSettlementHeaderMonetarySummation.getChargeTotalAmount.value", 0);
        $allowanceTotalAmount = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getSpecifiedTradeSettlementHeaderMonetarySummation.getAllowanceTotalAmount.value", 0);

        return $this;
    }

    /**
     * Get the identifier defined by the Buyer (e.g. contact ID, department, office id, project code).
     *
     * @param  string|null $buyerreference
     * An identifier assigned by the Buyer used for internal routing purposes
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
     * @param  string      $name
     * The full formal name by which the party is registered in the national registry of
     * legal entities or as a Taxable person or otherwise trades as a person or persons.
     * @param  array|null  $id
     * An identification of the Party. The identification scheme identifier of the Party identifier.
     * @param  string|null $description
     * Additional legal information relevant for the Paety.
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
     * Get global identifiers of the seller.
     *
     * @param  array|null $globalID
     * Array of the sellers global ids indexed by the identification scheme. The identification scheme results
     * from the list published by the ISO/IEC 6523 Maintenance Agency.
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
     * @param  array|null $taxreg
     * Array of sales tax identification numbers of the seller indexed by Scheme identifier (e.g.: __FC__ for _Tax number of the seller_ and __VA__
     * for _Sales tax identification number of the seller_)
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
     * @param  string|null $lineone
     * The main line in the sellers address. This is usually the street name and house number or
     * the post office box
     * @param  string|null $linetwo
     * Line 2 of the seller's address. This is an additional address line in an address that can be
     * used to provide additional details in addition to the main line
     * @param  string|null $linethree
     * Line 3 of the seller's address. This is an additional address line in an address that can be
     * used to provide additional details in addition to the main line
     * @param  string|null $postcode
     * The identifier for an addressable group of properties according to the relevant postal service.
     * @param  string|null $city
     * Usual name of the city or municipality in which the seller's address is located
     * @param  string|null $country
     * Code used to identify the country. If no tax agent is specified, this is the country in which the sales tax
     * is due. The lists of approved countries are maintained by the EN ISO 3166-1 Maintenance Agency “Codes for the
     * representation of names of countries and their subdivisions”
     * If no tax representative is specified, this is the country where VAT is liable. The lists of valid countries
     * are registered with the EN ISO 3166-1 Maintenance agency, “Codes for the representation of names of countries
     * and their subdivisions”.
     * @param  string|null $subdivision
     * The subdivision of a country.
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
     * @param  string|null $legalorgid
     * An identifier issued by an official registrar that identifies the
     * seller as a legal entity or legal person. If no identification scheme ($legalorgtype) is provided,
     * it should be known to the buyer and seller
     * @param  string|null $legalorgtype
     * The identification scheme identifier of the Seller legal registration identifier.
     * If used, the identification scheme identifier shall be chosen from the entries of the list published
     * by the ISO/IEC 6523 maintenance agency.
     * @param  string|null $legalorgname
     * A name by which the seller is known, if different from the seller's name (also known as
     * the company name). Note: This may be used if different from the seller's name.
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
     * You may use this together with OrderDocumentReader::getDocumentSellerContact
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
     * You may use this together with OrderDocumentReader::getDocumentSellerContact
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
     * @param  string|null $contactpersonname
     * Contact point for a legal entity, such as a personal name of the contact person
     * @param  string|null $contactdepartmentname
     * Contact point for a legal entity, such as a name of the department or office
     * @param  string|null $contactphoneno
     * A phone number for the contact point.
     * @param  string|null $contactfaxno
     * A fax number for the contact point.
     * @param  string|null $contactemailadd
     * An e-mail address for the contact point.
     * @param  string|null $contacttypecode
     * The code specifying the type of trade contact. To be chosen from the entries in UNTDID 3139
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
     * @param  string|null $uriType
     * Identifies the electronic address to which the application level response to the order may be delivered.
     * @param  string|null $uriId
     * The identification scheme identifier of the electronic address. The scheme identifier shall be chosen
     * from a list to be maintained by the Connecting Europe Facility.
     * @return OrderDocumentReader
     */
    public function getDocumentSellerElectronicAddress(?string &$uriType, ?string &$uriId): OrderDocumentReader
    {
        $uriType = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getSellerTradeParty.getURIUniversalCommunication.getURIID.getschemeID", "");
        $uriId = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getSellerTradeParty.getURIUniversalCommunication.getURIID", "");

        return $this;
    }

    /**
     * Get information about the Buyer.
     *
     * @param  string      $name
     * The full formal name by which the party is registered in the national registry of
     * legal entities or as a Taxable person or otherwise trades as a person or persons.
     * @param  array|null  $id
     * An identification of the Party. The identification scheme identifier of the Party identifier.
     * @param  string|null $description
     * Additional legal information relevant for the Paety.
     * @return OrderDocumentReader
     */
    public function getDocumentBuyer(?string &$name, ?array &$id, ?string &$description): OrderDocumentReader
    {
        $name = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerTradeParty.getName", "");
        $id = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerTradeParty.getID", []);
        $description = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerTradeParty.getDescription", "");

        $id = $this->convertToArray($id, ["id" => "value"]);

        return $this;
    }

    /**
     * Get global identifier of the buyer.
     *
     * @param  array|null $globalID
     * Array of the sellers global ids indexed by the identification scheme. The identification scheme results
     * from the list published by the ISO/IEC 6523 Maintenance Agency.
     * @return OrderDocumentReader
     */
    public function getDocumentBuyerGlobalId(?array &$globalID): OrderDocumentReader
    {
        $globalID = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerTradeParty.getGlobalID", []);
        $globalID = $this->convertToAssociativeArray($globalID, "getSchemeID", "value");

        return $this;
    }

    /**
     * Get detailed information on the buyer's tax information.
     *
     * @param  array|null $taxreg
     * Array of sales tax identification numbers of the seller indexed by Scheme identifier (e.g.: __FC__ for _Tax number of the seller_ and __VA__
     * for _Sales tax identification number of the seller_)
     * @return OrderDocumentReader
     */
    public function getDocumentBuyerTaxRegistration(?array &$taxreg): OrderDocumentReader
    {
        $taxreg = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerTradeParty.getSpecifiedTaxRegistration", []);
        $taxreg = $this->convertToAssociativeArray($taxreg, "getID.getSchemeID", "getID.value");

        return $this;
    }

    /**
     * Get the address of buyer trade party
     *
     * @param  string|null $lineone
     * The main line in the buyers address. This is usually the street name and house number or
     * the post office box
     * @param  string|null $linetwo
     * Line 2 of the buyers address. This is an additional address line in an address that can be
     * used to provide additional details in addition to the main line
     * @param  string|null $linethree
     * Line 3 of the buyers address. This is an additional address line in an address that can be
     * used to provide additional details in addition to the main line
     * @param  string|null $postcode
     * The identifier for an addressable group of properties according to the relevant postal service.
     * @param  string|null $city
     * Usual name of the city or municipality in which the buyers address is located
     * @param  string|null $country
     * Code used to identify the country. If no tax agent is specified, this is the country in which the sales tax
     * is due. The lists of approved countries are maintained by the EN ISO 3166-1 Maintenance Agency “Codes for the
     * representation of names of countries and their subdivisions”
     * @param  string|null $subdivision
     * The subdivision of a country.
     * @return OrderDocumentReader
     */
    public function getDocumentBuyerAddress(?string &$lineone, ?string &$linetwo, ?string &$linethree, ?string &$postcode, ?string &$city, ?string &$country, ?string &$subdivision): OrderDocumentReader
    {
        $lineone = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerTradeParty.getPostalTradeAddress.getLineOne", "");
        $linetwo = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerTradeParty.getPostalTradeAddress.getLineTwo", "");
        $linethree = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerTradeParty.getPostalTradeAddress.getLineThree", "");
        $postcode = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerTradeParty.getPostalTradeAddress.getPostcodeCode.value", "");
        $city = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerTradeParty.getPostalTradeAddress.getCityName", "");
        $country = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerTradeParty.getPostalTradeAddress.getCountryID", "");
        $subdivision = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerTradeParty.getPostalTradeAddress.getCountrySubDivisionName", "");

        return $this;
    }

    /**
     * Get the legal organisation of buyer trade party
     *
     * @param  string|null $legalorgid
     * An identifier issued by an official registrar that identifies the
     * buyer as a legal entity or legal person. If no identification scheme ($legalorgtype) is provided,
     * it should be known to the buyer and buyer
     * @param  string|null $legalorgtype
     * The identifier for the identification scheme of the legal
     * registration of the buyer. If the identification scheme is used, it must be selected from
     * ISO/IEC 6523 list
     * @param  string|null $legalorgname
     * A name by which the buyer is known, if different from the buyers name
     * (also known as the company name)
     * @return OrderDocumentReader
     */
    public function getDocumentBuyerLegalOrganisation(?string &$legalorgid, ?string &$legalorgtype, ?string &$legalorgname): OrderDocumentReader
    {
        $legalorgid = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerTradeParty.getSpecifiedLegalOrganization.getID.value", "");
        $legalorgtype = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerTradeParty.getSpecifiedLegalOrganization.getID.getSchemeID", "");
        $legalorgname = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerTradeParty.getSpecifiedLegalOrganization.getTradingBusinessName", "");

        return $this;
    }

    /**
     * Seek to the first buyer contact of the document.
     * Returns true if a first buyer contact is available, otherwise false
     * You may use this together with OrderDocumentReader::getDocumentBuyerContact
     *
     * @return boolean
     */
    public function firstDocumentBuyerContact(): bool
    {
        $this->documentBuyerContactPointer = 0;
        $contacts = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerTradeParty.getDefinedTradeContact", []));
        return isset($contacts[$this->documentBuyerContactPointer]);
    }

    /**
     * Seek to the next available first Buyer contact of the document.
     * Returns true if another Buyer contact is available, otherwise false
     * You may use this together with OrderDocumentReader::getDocumentBuyerContact
     *
     * @return boolean
     */
    public function nextDocumentBuyerContact(): bool
    {
        $this->documentBuyerContactPointer++;
        $contacts = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerTradeParty.getDefinedTradeContact", []));
        return isset($contacts[$this->documentBuyerContactPointer]);
    }

    /**
     * Get detailed information on the buyer's contact person
     *
     * @param  string|null $contactpersonname
     * Contact point for a legal entity, such as a personal name of the contact person
     * @param  string|null $contactdepartmentname
     * Contact point for a legal entity, such as a name of the department or office
     * @param  string|null $contactphoneno
     * A phone number for the contact point.
     * @param  string|null $contactfaxno
     * A fax number for the contact point.
     * @param  string|null $contactemailadd
     * An e-mail address for the contact point.
     * @param  string|null $contacttypecode
     * The code specifying the type of trade contact. To be chosen from the entries in UNTDID 3139
     * @return OrderDocumentReader
     */
    public function getDocumentBuyerContact(?string &$contactpersonname, ?string &$contactdepartmentname, ?string &$contactphoneno, ?string &$contactfaxno, ?string &$contactemailadd, ?string &$contacttypecode): OrderDocumentReader
    {
        $contacts = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerTradeParty.getDefinedTradeContact", []));
        $contact = $this->objectHelper->getArrayIndex($contacts, $this->documentBuyerContactPointer);
        $contactpersonname = $this->getInvoiceValueByPathFrom($contact, "getPersonName", "");
        $contactdepartmentname = $this->getInvoiceValueByPathFrom($contact, "getDepartmentName", "");
        $contactphoneno = $this->getInvoiceValueByPathFrom($contact, "getTelephoneUniversalCommunication.getCompleteNumber", "");
        $contactfaxno = $this->getInvoiceValueByPathFrom($contact, "getFaxUniversalCommunication.getCompleteNumber", "");
        $contactemailadd = $this->getInvoiceValueByPathFrom($contact, "getEmailURIUniversalCommunication.getURIID", "");
        $contacttypecode = $this->getInvoiceValueByPathFrom($contact, "getTypeCode", "");

        return $this;
    }

    /**
     * Set the universal communication info for the buyer
     *
     * @param  string|null $uriType
     * Identifies the electronic address to which the application level response to the order may be delivered.
     * @param  string|null $uriId
     * The identification scheme identifier of the electronic address. The scheme identifier shall be chosen
     * from a list to be maintained by the Connecting Europe Facility.
     * @return OrderDocumentReader
     */
    public function getDocumentBuyerElectronicAddress(?string &$uriType, ?string &$uriId): OrderDocumentReader
    {
        $uriType = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerTradeParty.getURIUniversalCommunication.getURIID.getschemeID", "");
        $uriId = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerTradeParty.getURIUniversalCommunication.getURIID", "");

        return $this;
    }

    /**
     * Detailed information about the party who raises the Order originally on behalf of the Buyer
     *
     * @param  string      $name
     * The full formal name by which the party is registered in the national registry of
     * legal entities or as a Taxable person or otherwise trades as a person or persons.
     * @param  array|null  $id
     * An identification of the Party. The identification scheme identifier of the Party identifier.
     * @param  string|null $description
     * Additional legal information relevant for the Paety.
     * @return OrderDocumentReader
     */
    public function getDocumentBuyerRequisitioner(?string &$name, ?array &$id, ?string &$description): OrderDocumentReader
    {
        $name = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerRequisitionerTradeParty.getName", "");
        $id = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerRequisitionerTradeParty.getID", []);
        $description = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerRequisitionerTradeParty.getDescription", "");

        $id = $this->convertToArray($id, ["id" => "value"]);

        return $this;
    }

    /**
     * Get global id's for the party who raises the Order originally on behalf of the buyer requisitioner
     *
     * @param  array|null $globalID
     * Array of the sellers global ids indexed by the identification scheme. The identification scheme results
     * from the list published by the ISO/IEC 6523 Maintenance Agency.
     * @return OrderDocumentReader
     */
    public function getDocumentBuyerRequisitionerGlobalId(?array &$globalID): OrderDocumentReader
    {
        $globalID = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerRequisitionerTradeParty.getGlobalID", []);
        $globalID = $this->convertToAssociativeArray($globalID, "getSchemeID", "value");

        return $this;
    }

    /**
     * Get detailed information on the buyer requisitioner's tax information.
     *
     * @param  array|null $taxreg
     * Array of sales tax identification numbers of the seller indexed by Scheme identifier (e.g.: __FC__ for _Tax number of the seller_ and __VA__
     * for _Sales tax identification number of the seller_)
     * @return OrderDocumentReader
     */
    public function getDocumentBuyerRequisitionerTaxRegistration(?array &$taxreg): OrderDocumentReader
    {
        $taxreg = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerRequisitionerTradeParty.getSpecifiedTaxRegistration", []);
        $taxreg = $this->convertToAssociativeArray($taxreg, "getID.getSchemeID", "getID.value");

        return $this;
    }

    /**
     * Get the address of buyer requisitioner's trade party
     *
     * @param  string|null $lineone
     * The main line in the buyer requisitioners address. This is usually the street name and house number or
     * the post office box
     * @param  string|null $linetwo
     * Line 2 of the buyer requisitioners address. This is an additional address line in an address that can be
     * used to provide additional details in addition to the main line
     * @param  string|null $linethree
     * Line 3 of the buyer requisitioners address. This is an additional address line in an address that can be
     * used to provide additional details in addition to the main line
     * @param  string|null $postcode
     * The identifier for an addressable group of properties according to the relevant postal service.
     * @param  string|null $city
     * Usual name of the city or municipality in which the buyer requisitioners address is located
     * @param  string|null $country
     * Code used to identify the country. If no tax agent is specified, this is the country in which the sales tax
     * is due. The lists of approved countries are maintained by the EN ISO 3166-1 Maintenance Agency “Codes for the
     * representation of names of countries and their subdivisions”
     * @param  string|null $subdivision
     * The subdivision of a country.
     * @return OrderDocumentReader
     */
    public function getDocumentBuyerRequisitionerAddress(?string &$lineone, ?string &$linetwo, ?string &$linethree, ?string &$postcode, ?string &$city, ?string &$country, ?string &$subdivision): OrderDocumentReader
    {
        $lineone = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerRequisitionerTradeParty.getPostalTradeAddress.getLineOne", "");
        $linetwo = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerRequisitionerTradeParty.getPostalTradeAddress.getLineTwo", "");
        $linethree = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerRequisitionerTradeParty.getPostalTradeAddress.getLineThree", "");
        $postcode = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerRequisitionerTradeParty.getPostalTradeAddress.getPostcodeCode.value", "");
        $city = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerRequisitionerTradeParty.getPostalTradeAddress.getCityName", "");
        $country = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerRequisitionerTradeParty.getPostalTradeAddress.getCountryID", "");
        $subdivision = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerRequisitionerTradeParty.getPostalTradeAddress.getCountrySubDivisionName", "");

        return $this;
    }

    /**
     * Get the legal organisation of buyer requisitioner trade party
     *
     * @param  string|null $legalorgid
     * An identifier issued by an official registrar that identifies the
     * buyer requisitioner as a legal entity or legal person.
     * @param  string|null $legalorgtype
     * The identifier for the identification scheme of the legal
     * registration of the buyer requisitioner. If the identification scheme is used, it must be selected from
     * ISO/IEC 6523 list
     * @param  string|null $legalorgname
     * A name by which the buyer requisitioner is known, if different from the buyer requisitioners name
     * (also known as the company name)
     * @return OrderDocumentReader
     */
    public function getDocumentBuyerRequisitionerLegalOrganisation(?string &$legalorgid, ?string &$legalorgtype, ?string &$legalorgname): OrderDocumentReader
    {
        $legalorgid = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerRequisitionerTradeParty.getSpecifiedLegalOrganization.getID.value", "");
        $legalorgtype = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerRequisitionerTradeParty.getSpecifiedLegalOrganization.getID.getSchemeID", "");
        $legalorgname = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerRequisitionerTradeParty.getSpecifiedLegalOrganization.getTradingBusinessName", "");

        return $this;
    }

    /**
     * Seek to the first buyer requisitioner's contact of the document.
     * Returns true if a first buyer contact is available, otherwise false
     * You may use this together with OrderDocumentReader::getDocumentBuyerContact
     *
     * @return boolean
     */
    public function firstDocumentBuyerRequisitionerContact(): bool
    {
        $this->documentBuyerRequisitionerContactPointer = 0;
        $contacts = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerRequisitionerTradeParty.getDefinedTradeContact", []));
        return isset($contacts[$this->documentBuyerRequisitionerContactPointer]);
    }

    /**
     * Seek to the next available first Buyer requisitioner's contact of the document.
     * Returns true if another Buyer contact is available, otherwise false
     * You may use this together with OrderDocumentReader::getDocumentBuyerContact
     *
     * @return boolean
     */
    public function nextDocumentBuyerRequisitionerContact(): bool
    {
        $this->documentBuyerRequisitionerContactPointer++;
        $contacts = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerRequisitionerTradeParty.getDefinedTradeContact", []));
        return isset($contacts[$this->documentBuyerRequisitionerContactPointer]);
    }

    /**
     * Get detailed information on the buyer requisitioner's contact person
     *
     * @param  string|null $contactpersonname
     * Contact point for a legal entity, such as a personal name of the contact person
     * @param  string|null $contactdepartmentname
     * Contact point for a legal entity, such as a name of the department or office
     * @param  string|null $contactphoneno
     * A phone number for the contact point.
     * @param  string|null $contactfaxno
     * A fax number for the contact point.
     * @param  string|null $contactemailadd
     * An e-mail address for the contact point.
     * @param  string|null $contacttypecode
     * The code specifying the type of trade contact. To be chosen from the entries in UNTDID 3139
     * @return OrderDocumentReader
     */
    public function getDocumentBuyerRequisitionerContact(?string &$contactpersonname, ?string &$contactdepartmentname, ?string &$contactphoneno, ?string &$contactfaxno, ?string &$contactemailadd, ?string &$contacttypecode): OrderDocumentReader
    {
        $contacts = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerRequisitionerTradeParty.getDefinedTradeContact", []));
        $contact = $this->objectHelper->getArrayIndex($contacts, $this->documentBuyerRequisitionerContactPointer);
        $contactpersonname = $this->getInvoiceValueByPathFrom($contact, "getPersonName", "");
        $contactdepartmentname = $this->getInvoiceValueByPathFrom($contact, "getDepartmentName", "");
        $contactphoneno = $this->getInvoiceValueByPathFrom($contact, "getTelephoneUniversalCommunication.getCompleteNumber", "");
        $contactfaxno = $this->getInvoiceValueByPathFrom($contact, "getFaxUniversalCommunication.getCompleteNumber", "");
        $contactemailadd = $this->getInvoiceValueByPathFrom($contact, "getEmailURIUniversalCommunication.getURIID", "");
        $contacttypecode = $this->getInvoiceValueByPathFrom($contact, "getTypeCode", "");

        return $this;
    }

    /**
     * Get the universal communication info for the buyer requisitioner
     *
     * @param  string|null $uriType
     * Identifies the electronic address to which the application level response to the order may be delivered.
     * @param  string|null $uriId
     * The identification scheme identifier of the electronic address. The scheme identifier shall be chosen
     * from a list to be maintained by the Connecting Europe Facility.
     * @return OrderDocumentReader
     */
    public function getDocumentBuyerRequisitionerElectronicAddress(?string &$uriType, ?string &$uriId): OrderDocumentReader
    {
        $uriType = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerRequisitionerTradeParty.getURIUniversalCommunication.getURIID.getschemeID", "");
        $uriId = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerRequisitionerTradeParty.getURIUniversalCommunication.getURIID", "");

        return $this;
    }

    /**
     * Get information on the delivery conditions
     *
     * @param  string|null $code
     * The code specifying the type of delivery for these trade delivery terms. To be chosen from the entries
     * in UNTDID 4053 + INCOTERMS List
     * - 1 : Delivery arranged by the supplier (Indicates that the supplier will arrange delivery of the goods).
     * - 2 : Delivery arranged by logistic service provider (Code indicating that the logistic service provider has arranged the delivery of goods).
     * - CFR : Cost and Freight (insert named port of destination)
     * - CIF : Cost, Insurance and Freight (insert named port of destination)
     * - CIP : Carriage and Insurance Paid to (insert named place of destination)
     * - CPT : Carriage Paid To (insert named place of destination)
     * - DAP : Delivered At Place (insert named place of destination)
     * - DAT : Delivered At Terminal (insert named terminal at port or place of destination)
     * - DDP : Delivered Duty Paid (insert named place of destination)
     * - EXW : Ex Works (insert named place of delivery)
     * - FAS : Free Alongside Ship (insert named port of shipment)
     * - FCA : Free Carrier (insert named place of delivery)
     * - FOB : Free On Board (insert named port of shipment)
     * @param  string|null $description
     * A textual description of these trade delivery terms
     * @param  string|null $functionCode
     * A code specifying a function of these trade delivery terms (Pick up,or delivered) To be chosen from the entries
     * in UNTDID 4055
     * @param  string|null $relevantTradeLocationId
     * The unique identifier of a country location used or referenced in trade.
     * @param  string|null $relevantTradeLocationName
     * The name, expressed as text, of this location used or referenced in trade.
     * @return OrderDocumentReader
     */
    public function getDocumentDeliveryTerms(?string &$code, ?string &$description, ?string &$functionCode, ?string &$relevantTradeLocationId, ?string &$relevantTradeLocationName): OrderDocumentReader
    {
        $code = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getApplicableTradeDeliveryTerms.getDeliveryTypeCode", "");
        $description = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getApplicableTradeDeliveryTerms.getDescription", "");
        $functionCode = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getApplicableTradeDeliveryTerms.getFunctionCode", "");
        $relevantTradeLocationId = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getApplicableTradeDeliveryTerms.getRelevantTradeLocation.getID", "");
        $relevantTradeLocationName = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getApplicableTradeDeliveryTerms.getRelevantTradeLocation.getName", "");

        return $this;
    }

    /**
     * Get details of the associated order confirmation
     *
     * @param  string        $sellerOrderRefId
     * An identifier of a referenced Sales order, issued by the Seller
     * @param  DateTime|null $sellerOrderRefDate
     * The formatted date or date time for the issuance of this referenced Sales Order.
     * @return OrderDocumentReader
     */
    public function getDocumentSellerOrderReferencedDocument(?string &$sellerOrderRefId, ?DateTime &$sellerOrderRefDate): OrderDocumentReader
    {
        $sellerOrderRefId = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getSellerOrderReferencedDocument.getIssuerAssignedID.value", "");
        $sellerOrderRefDate = $this->objectHelper->toDateTime(
            $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getSellerOrderReferencedDocument.getFormattedIssueDateTime.getDateTimeString.value", ""),
            $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getSellerOrderReferencedDocument.getFormattedIssueDateTime.getDateTimeString.getFormat", "")
        );

        return $this;
    }

    /**
     * Get details of the related buyer order
     *
     * @param  string        $buyerOrderRefId
     * An identifier of a referenced purchase order, issued by the Buyer.
     * @param  DateTime|null $buyerOrderRefDate
     * The formatted date or date time for the issuance of this referenced Buyer Order.
     * @return OrderDocumentReader
     */
    public function getDocumentBuyerOrderReferencedDocument(?string &$buyerOrderRefId, ?DateTime &$buyerOrderRefDate): OrderDocumentReader
    {
        $buyerOrderRefId = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerOrderReferencedDocument.getIssuerAssignedID.value", "");
        $buyerOrderRefDate = $this->objectHelper->toDateTime(
            $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerOrderReferencedDocument.getFormattedIssueDateTime.getDateTimeString.value", ""),
            $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBuyerOrderReferencedDocument.getFormattedIssueDateTime.getDateTimeString.getFormat", "")
        );

        return $this;
    }

    /**
     * Get details of the related quotation
     *
     * @param  string        $quotationRefId
     * An Identifier of a Quotation, issued by the Seller.
     * @param  DateTime|null $quotationRefDate
     * Date of order
     * @return OrderDocumentReader
     */
    public function getDocumentQuotationReferencedDocument(?string &$quotationRefId, ?DateTime &$quotationRefDate): OrderDocumentReader
    {
        $quotationRefId = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getQuotationReferencedDocument.getIssuerAssignedID.value", "");
        $quotationRefDate = $this->objectHelper->toDateTime(
            $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getQuotationReferencedDocument.getFormattedIssueDateTime.getDateTimeString.value", ""),
            $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getQuotationReferencedDocument.getFormattedIssueDateTime.getDateTimeString.getFormat", "")
        );

        return $this;
    }

    /**
     * Get details of the associated contract. The contract identifier should be unique in the context
     * of the specific trading relationship and for a defined time period.
     *
     * @param  string        $contractRefId
     * The identification of a contract.
     * @param  DateTime|null $contractRefDate
     * The formatted date or date time for the issuance of this referenced Contract.
     * @return OrderDocumentReader
     */
    public function getDocumentContractReferencedDocument(?string &$contractRefId, ?DateTime &$contractRefDate): OrderDocumentReader
    {
        $contractRefId = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getContractReferencedDocument.getIssuerAssignedID.value", "");
        $contractRefDate = $this->objectHelper->toDateTime(
            $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getContractReferencedDocument.getFormattedIssueDateTime.getDateTimeString.value", ""),
            $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getContractReferencedDocument.getFormattedIssueDateTime.getDateTimeString.getFormat", "")
        );

        return $this;
    }

    /**
     * Get details of a Requisition Document, issued by the Buyer or the Buyer Requisitioner
     *
     * @param  string        $requisitionRefId
     * The identification of a Requisition Document, issued by the Buyer or the Buyer Requisitioner.
     * @param  DateTime|null $requisitionRefDate
     * The formatted date or date time for the issuance of this referenced Requisition.
     * @return OrderDocumentReader
     */
    public function getDocumentRequisitionReferencedDocument(?string &$requisitionRefId, ?DateTime &$requisitionRefDate): OrderDocumentReader
    {
        $requisitionRefId = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getRequisitionReferencedDocument.getIssuerAssignedID.value", "");
        $requisitionRefDate = $this->objectHelper->toDateTime(
            $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getRequisitionReferencedDocument.getFormattedIssueDateTime.getDateTimeString.value", ""),
            $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getRequisitionReferencedDocument.getFormattedIssueDateTime.getDateTimeString.getFormat", "")
        );

        return $this;
    }

    /**
     * Set the intérnal additional ref. documents pointer to the first position. This method will return false if there is no
     * additional ref. document. This method should be used together with __OrderDocumentReader::getDocumentAdditionalReferencedDocument__
     *
     * @return boolean
     */
    public function firstDocumentAdditionalReferencedDocument(): bool
    {
        $this->documentAddRefDocPointer = 0;
        $additionalRefDoc = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getAdditionalReferencedDocument", []));
        return isset($additionalRefDoc[$this->documentAddRefDocPointer]);
    }

    /**
     * Set the intérnal additional ref. documents pointer to the next position. This method will return false if there is no more
     * additional ref. document. This method should be used together with __OrderDocumentReader::getDocumentAdditionalReferencedDocument__
     *
     * @return boolean
     */
    public function nextDocumentAdditionalReferencedDocument(): bool
    {
        $this->documentAddRefDocPointer++;
        $additionalRefDoc = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getAdditionalReferencedDocument", []));
        return isset($additionalRefDoc[$this->documentAddRefDocPointer]);
    }

    /**
     * Add an information about additional supporting documents substantiating the claims made in the order.
     * The additional supporting documents can be used for both referencing a document number which is expected to be
     * known by the receiver, an external document (referenced by a URL) or as an embedded document (such as a time
     * report in pdf). The option to link to an external document will be needed, for example in the case of large
     * attachments and/or when sensitive information, e.g. person-related services, has to be separated from the order itself.
     *
     * This method should be used together witd __OrderDocumentReader::firstDocumentAdditionalReferencedDocument__ and
     * __OrderDocumentReader::nextDocumentAdditionalReferencedDocument__
     *
     * @param  string|null   $additionalRefTypeCode
     * Type of referenced document (See codelist UNTDID 1001)
     *  - Code 916 "reference paper" is used to reference the identification of the document on which the invoice is based
     *  - Code 50 "Price / sales catalog response" is used to reference the tender or the lot
     *  - Code 130 "invoice data sheet" is used to reference an identifier for an object specified by the seller.
     * @param  string|null   $additionalRefId
     * The identifier of the tender or lot to which the invoice relates, or an identifier specified by the seller for
     * an object on which the invoice is based, or an identifier of the document on which the invoice is based.
     * @param  string|null   $additionalRefURIID
     * The Uniform Resource Locator (URL) at which the external document is available. A means of finding the resource
     * including the primary access method intended for it, e.g. http: // or ftp: //. The location of the external document
     * must be used if the buyer needs additional information to support the amounts billed. External documents are not part
     * of the invoice. Access to external documents can involve certain risks.
     * @param  string|null   $additionalRefName
     * A description of the document, e.g. Hourly billing, usage or consumption report, etc.
     * @param  string|null   $additionalRefRefTypeCode
     * The identifier for the identification scheme of the identifier of the item invoiced. If it is not clear to the
     * recipient which scheme is used for the identifier, an identifier of the scheme should be used, which must be selected
     * from UNTDID 1153 in accordance with the code list entries.
     * @param  DateTime|null $additionalRefDate
     * The formatted date or date time for the issuance of this referenced Additional Document.
     * @return OrderDocumentReader
     */
    public function getDocumentAdditionalReferencedDocument(?string &$additionalRefTypeCode, ?string &$additionalRefId, ?string &$additionalRefURIID, ?string &$additionalRefName, ?string &$additionalRefRefTypeCode, ?DateTime &$additionalRefDate): OrderDocumentReader
    {
        $additionalRefDocs = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getAdditionalReferencedDocument", []));
        $additionalRefDoc = $this->objectHelper->getArrayIndex($additionalRefDocs, $this->documentAddRefDocPointer);

        $additionalRefTypeCode = $this->getInvoiceValueByPathFrom($additionalRefDoc, 'getTypeCode', '');
        $additionalRefId = $this->getInvoiceValueByPathFrom($additionalRefDoc, 'getIssuerAssignedID', '');
        $additionalRefURIID = $this->getInvoiceValueByPathFrom($additionalRefDoc, 'getURIID', '');
        $additionalRefName = $this->getInvoiceValueByPathFrom($additionalRefDoc, 'getName', '');
        $additionalRefRefTypeCode = $this->getInvoiceValueByPathFrom($additionalRefDoc, 'getReferenceTypeCode', '');
        $additionalRefDate = $this->objectHelper->toDateTime(
            $this->getInvoiceValueByPathFrom($additionalRefDoc, 'getFormattedIssueDateTime.getDateTimeString', ''),
            $this->getInvoiceValueByPathFrom($additionalRefDoc, 'getFormattedIssueDateTime.getDateTimeString.getFormat', '')
        );

        return $this;
    }

    /**
     * Get the binary data from the current additional document. You have to
     * specify $binarydatadirectory-Property using the __setBinaryDataDirectory__ method.
     *
     * This method should be used together witd __OrderDocumentReader::firstDocumentAdditionalReferencedDocument__ and
     * __OrderDocumentReader::nextDocumentAdditionalReferencedDocument__
     *
     * @param  string|null $binarydatafilename
     * The fuill-qualified filename where the data where stored. If no binary data are
     * available, this value will be empty
     * @return OrderDocumentReader
     */
    public function getDocumentAdditionalReferencedDocumentBinaryData(?string &$binarydatafilename): OrderDocumentReader
    {
        $additionalDocuments = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getAdditionalReferencedDocument", []));
        $additionalDocument = $this->objectHelper->getArrayIndex($additionalDocuments, $this->documentAddRefDocPointer);

        $binarydatafilename = $this->getInvoiceValueByPathFrom($additionalDocument, "getAttachmentBinaryObject.getFilename", "");
        $binarydata = $this->getInvoiceValueByPathFrom($additionalDocument, "getAttachmentBinaryObject.value", "");

        if (StringUtils::stringIsNullOrEmpty($binarydatafilename) === false
            && StringUtils::stringIsNullOrEmpty($binarydata) === false
            && StringUtils::stringIsNullOrEmpty($this->binarydatadirectory) === false
        ) {
            $binarydatafilename = PathUtils::combinePathWithFile($this->binarydatadirectory, $binarydatafilename);
            FileUtils::base64ToFile($binarydata, $binarydatafilename);
        } else {
            $binarydatafilename = "";
        }

        return $this;
    }

    /**
     * Get details of a blanket order referenced document
     *
     * @param  string        $blanketOrderRefId
     * The identification of a Blanket Order, issued by the Buyer or the Buyer Requisitioner.
     * @param  DateTime|null $blanketOrderRefDate
     * The formatted date or date time for the issuance of this referenced Blanket Order.
     * @return OrderDocumentReader
     */
    public function getDocumentBlanketOrderReferencedDocument(?string &$blanketOrderRefId, ?DateTime &$blanketOrderRefDate): OrderDocumentReader
    {
        $blanketOrderRefId = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBlanketOrderReferencedDocument.getIssuerAssignedID.value", "");
        $blanketOrderRefDate = $this->objectHelper->toDateTime(
            $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBlanketOrderReferencedDocument.getFormattedIssueDateTime.getDateTimeString.value", ""),
            $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getBlanketOrderReferencedDocument.getFormattedIssueDateTime.getDateTimeString.getFormat", "")
        );

        return $this;
    }

    /**
     * Get details of a the Previous Order Change Document, issued by the Buyer or the Buyer Requisitioner.
     *
     * @param  string        $prevOrderChangeRefId
     * The identification of a the Previous Order Change Document, issued by the Buyer or the Buyer Requisitioner.
     * @param  DateTime|null $prevOrderChangeRefDate
     * The formatted date or date time for the issuance of this referenced Previous Order Change.
     * @return OrderDocumentReader
     */
    public function getDocumentPreviousOrderChangeReferencedDocument(?string &$prevOrderChangeRefId, ?DateTime &$prevOrderChangeRefDate): OrderDocumentReader
    {
        $prevOrderChangeRefId = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getPreviousOrderChangeReferencedDocument.getIssuerAssignedID.value", "");
        $prevOrderChangeRefDate = $this->objectHelper->toDateTime(
            $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getPreviousOrderChangeReferencedDocument.getFormattedIssueDateTime.getDateTimeString.value", ""),
            $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getPreviousOrderChangeReferencedDocument.getFormattedIssueDateTime.getDateTimeString.getFormat", "")
        );

        return $this;
    }

    /**
     * Set details of a the Previous Order Response Document, issued by the Seller.
     *
     * @param  string        $prevOrderResponseRefId
     * The identification of a the Previous Order Response Document, issued by the Seller.
     * @param  DateTime|null $prevOrderResponseRefDate
     * The formatted date or date time for the issuance of this referenced Previous Order Response.
     * @return OrderDocumentReader
     */
    public function getDocumentPreviousOrderResponseReferencedDocument(?string &$prevOrderResponseRefId, ?DateTime &$prevOrderResponseRefDate): OrderDocumentReader
    {
        $prevOrderResponseRefId = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getPreviousOrderResponseReferencedDocument.getIssuerAssignedID.value", "");
        $prevOrderResponseRefDate = $this->objectHelper->toDateTime(
            $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getPreviousOrderResponseReferencedDocument.getFormattedIssueDateTime.getDateTimeString.value", ""),
            $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getPreviousOrderResponseReferencedDocument.getFormattedIssueDateTime.getDateTimeString.getFormat", "")
        );

        return $this;
    }

    /**
     * Set the procuring project specified for this header trade agreement.
     *
     * @param  string $procuringProjectId
     * The unique identifier of this procuring project.
     * @param  string $procuringProjectName
     * The name of this procuring project.
     * @return OrderDocumentReader
     */
    public function getDocumentProcuringProject(?string &$procuringProjectId, ?string &$procuringProjectName): OrderDocumentReader
    {
        $procuringProjectId = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getSpecifiedProcuringProject.getID", "");
        $procuringProjectName = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getSpecifiedProcuringProject.getName", "");

        return $this;
    }

    /**
     * Seek to the first ultimate customer order referenced document (on document-level)
     * If an applicable document exists, this function will return true, otherwise false
     *
     * @return boolean
     */
    public function firstDocumentUltimateCustomerOrderReferencedDocument(): bool
    {
        $this->documentUltimateCustomerOrderRefDocPointer = 0;
        $ultimateCustomerOrderRefDocs = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getUltimateCustomerOrderReferencedDocument", []));
        return isset($ultimateCustomerOrderRefDocs[$this->documentUltimateCustomerOrderRefDocPointer]);
    }

    /**
     * Seek to the next ultimate customer order referenced document (on document-level)
     * If another applicable document exists, this function will return true, otherwise false
     *
     * @return boolean
     */
    public function nextDocumentUltimateCustomerOrderReferencedDocument(): bool
    {
        $this->documentUltimateCustomerOrderRefDocPointer++;
        $ultimateCustomerOrderRefDocs = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getUltimateCustomerOrderReferencedDocument", []));
        return isset($ultimateCustomerOrderRefDocs[$this->documentUltimateCustomerOrderRefDocPointer]);
    }

    /**
     * Set the ultimate customer order referenced document (on document level)
     *
     * @param  string|null   $ultimateCustomerOrderRefId
     * Ultimate Customer Order Referenced Doc ID applied to this line
     * @param  DateTime|null $ultimateCustomerOrderRefDate
     * The formatted date or date time for the issuance of this Ultimate Customer Order Referenced Doc.
     * @return OrderDocumentReader
     */
    public function getDocumentUltimateCustomerOrderReferencedDocument(?string &$ultimateCustomerOrderRefId, ?DateTime &$ultimateCustomerOrderRefDate): OrderDocumentReader
    {
        $ultimateCustomerOrderRefDocs = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeAgreement.getUltimateCustomerOrderReferencedDocument", []));
        $ultimateCustomerOrderRefDoc = $this->objectHelper->getArrayIndex($ultimateCustomerOrderRefDocs, $this->documentUltimateCustomerOrderRefDocPointer);

        $ultimateCustomerOrderRefId = $this->getInvoiceValueByPathFrom($ultimateCustomerOrderRefDoc, 'getIssuerAssignedID', '');
        $ultimateCustomerOrderRefDate = $this->objectHelper->toDateTime(
            $this->getInvoiceValueByPathFrom($ultimateCustomerOrderRefDoc, 'getFormattedIssueDateTime.getDateTimeString', ''),
            $this->getInvoiceValueByPathFrom($ultimateCustomerOrderRefDoc, 'getFormattedIssueDateTime.getDateTimeString.getFormat', '')
        );

        return $this;
    }

    /**
     * Set information about the Ship-To-Party
     * The Ship-To-Party provides information about where and when the goods and services ordered are delivered.
     *
     * @param  string      $name
     * The name of the party to which the goods and services are delivered.
     * @param  array|null  $id
     * An identification of the Party.
     * If no scheme is specified, it should be known by Buyer and Seller, e.g. a previously exchanged Buyer or Seller assigned identifier.
     * If used, the identification scheme shall be chosen from the entries of the list published by the ISO/IEC 6523 maintenance agency.
     * @param  string|null $description
     * Additional legal information relevant for the Paety.
     * @return OrderDocumentReader
     */
    public function getDocumentShipTo(?string &$name, ?array &$id, ?string &$description): OrderDocumentReader
    {
        $name = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipToTradeParty.getName", "");
        $id = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipToTradeParty.getID", []);
        $description = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipToTradeParty.getDescription", "");

        $id = $this->convertToArray($id, ["id" => "value"]);

        return $this;
    }

    /**
     * Get global identifiers for the Ship-to Trade Party
     *
     * @param  array|null $globalID
     * Array of the sellers global ids indexed by the identification scheme. The identification scheme results
     * from the list published by the ISO/IEC 6523 Maintenance Agency.
     * @return OrderDocumentReader
     */
    public function getDocumentShipToGlobalId(?array &$globalID): OrderDocumentReader
    {
        $globalID = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipToTradeParty.getGlobalID", []);
        $globalID = $this->convertToAssociativeArray($globalID, "getSchemeID", "value");

        return $this;
    }

    /**
     * Get Tax registration to Ship-To Trade party
     *
     * @param  array|null $taxreg
     * Array of sales tax identification numbers of the seller indexed by Scheme identifier (e.g.: __FC__ for _Tax number of the seller_ and __VA__
     * for _Sales tax identification number of the seller_)
     * @return OrderDocumentReader
     */
    public function getDocumentShipToTaxRegistration(?array &$taxreg): OrderDocumentReader
    {
        $taxreg = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipToTradeParty.getSpecifiedTaxRegistration", []);
        $taxreg = $this->convertToAssociativeArray($taxreg, "getID.getSchemeID", "getID.value");

        return $this;
    }

    /**
     * Get the postal address of the Ship-To party
     *
     * @param  string|null $lineone
     * The main line in the party's address. This is usually the street name and house number or
     * the post office box
     * @param  string|null $linetwo
     * Line 2 of the party's address. This is an additional address line in an address that can be
     * used to provide additional details in addition to the main line
     * @param  string|null $linethree
     * Line 3 of the party's address. This is an additional address line in an address that can be
     * used to provide additional details in addition to the main line
     * @param  string|null $postcode
     * The identifier for an addressable group of properties according to the relevant postal service.
     * @param  string|null $city
     * Usual name of the city or municipality in which the party's address is located
     * @param  string|null $country
     * Code used to identify the country. If no tax agent is specified, this is the country in which the sales tax
     * is due. The lists of approved countries are maintained by the EN ISO 3166-1 Maintenance Agency “Codes for the
     * representation of names of countries and their subdivisions”
     * @param  string|null $subdivision
     * The subdivision of a country.
     * @return OrderDocumentReader
     */
    public function getDocumentShipToAddress(?string &$lineone, ?string &$linetwo, ?string &$linethree, ?string &$postcode, ?string &$city, ?string &$country, ?string &$subdivision): OrderDocumentReader
    {
        $lineone = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipToTradeParty.getPostalTradeAddress.getLineOne", "");
        $linetwo = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipToTradeParty.getPostalTradeAddress.getLineTwo", "");
        $linethree = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipToTradeParty.getPostalTradeAddress.getLineThree", "");
        $postcode = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipToTradeParty.getPostalTradeAddress.getPostcodeCode.value", "");
        $city = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipToTradeParty.getPostalTradeAddress.getCityName", "");
        $country = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipToTradeParty.getPostalTradeAddress.getCountryID", "");
        $subdivision = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipToTradeParty.getPostalTradeAddress.getCountrySubDivisionName", "");

        return $this;
    }

    /**
     * Set legal organisation of the Ship-To party
     *
     * @param  string|null $legalorgid
     * An identifier issued by an official registrar that identifies the
     * party as a legal entity or legal person. If no identification scheme ($legalorgtype) is provided,
     * it should be known to the buyer or seller party
     * @param  string|null $legalorgtype The identifier for the identification scheme of the legal
     *                                   registration of the party. In particular, the following scheme codes are used: 0021 : SWIFT, 0088 : EAN,
     *                                   0060 : DUNS, 0177 : ODETTE
     * @param  string|null $legalorgname A name by which the party is known, if different from the party's name
     *                                   (also known as the company name)
     * @return OrderDocumentReader
     */
    public function getDocumentShipToLegalOrganisation(?string &$legalorgid, ?string &$legalorgtype, ?string &$legalorgname): OrderDocumentReader
    {
        $legalorgid = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipToTradeParty.getSpecifiedLegalOrganization.getID.value", "");
        $legalorgtype = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipToTradeParty.getSpecifiedLegalOrganization.getID.getSchemeID", "");
        $legalorgname = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipToTradeParty.getSpecifiedLegalOrganization.getTradingBusinessName", "");

        return $this;
    }

    /**
     * Seek to the first ship-to contact.
     * Returns true if a first ship-to contact is available, otherwise false
     * You should use this together with OrderDocumentReader::getDocumentShipToContact
     *
     * @return boolean
     */
    public function firstDocumentShipToContact(): bool
    {
        $this->documentShipToContactPointer = 0;
        $contacts = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipToTradeParty.getDefinedTradeContact", []));
        return isset($contacts[$this->documentShipToContactPointer]);
    }

    /**
     * Seek to the next available ship-to contact.
     * Returns true if another ship-to contact is available, otherwise false
     * You should use this together with OrderDocumentReader::getDocumentShipToContact
     *
     * @return boolean
     */
    public function nextDocumentShipToContact(): bool
    {
        $this->documentShipToContactPointer++;
        $contacts = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipToTradeParty.getDefinedTradeContact", []));
        return isset($contacts[$this->documentShipToContactPointer]);
    }

    /**
     * Get contact of the Ship-To party
     *
     * @param  string|null $contactpersonname
     * Contact point for a legal entity, such as a personal name of the contact person
     * @param  string|null $contactdepartmentname
     * Contact point for a legal entity, such as a name of the department or office
     * @param  string|null $contactphoneno
     * A phone number for the contact point.
     * @param  string|null $contactfaxno
     * A fax number for the contact point.
     * @param  string|null $contactemailadd
     * An e-mail address for the contact point.
     * @param  string|null $contacttypecode
     * The code specifying the type of trade contact. To be chosen from the entries in UNTDID 3139
     * @return OrderDocumentReader
     */
    public function getDocumentShipToContact(?string &$contactpersonname, ?string &$contactdepartmentname, ?string &$contactphoneno, ?string &$contactfaxno, ?string &$contactemailadd, ?string &$contacttypecode): OrderDocumentReader
    {
        $contacts = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipToTradeParty.getDefinedTradeContact", []));
        $contact = $this->objectHelper->getArrayIndex($contacts, $this->documentShipToContactPointer);
        $contactpersonname = $this->getInvoiceValueByPathFrom($contact, "getPersonName", "");
        $contactdepartmentname = $this->getInvoiceValueByPathFrom($contact, "getDepartmentName", "");
        $contactphoneno = $this->getInvoiceValueByPathFrom($contact, "getTelephoneUniversalCommunication.getCompleteNumber", "");
        $contactfaxno = $this->getInvoiceValueByPathFrom($contact, "getFaxUniversalCommunication.getCompleteNumber", "");
        $contactemailadd = $this->getInvoiceValueByPathFrom($contact, "getEmailURIUniversalCommunication.getURIID", "");
        $contacttypecode = $this->getInvoiceValueByPathFrom($contact, "getTypeCode", "");

        return $this;
    }

    /**
     * Set the universal communication info for the Ship-To Trade Party
     *
     * @param  string|null $uriType
     * Identifies the electronic address to which the application level response to the order may be delivered.
     * @param  string|null $uriId
     * The identification scheme identifier of the electronic address. The scheme identifier shall be chosen
     * from a list to be maintained by the Connecting Europe Facility.
     * @return OrderDocumentReader
     */
    public function getDocumentShipToElectronicAddress(?string &$uriType, ?string &$uriId): OrderDocumentReader
    {
        $uriType = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipToTradeParty.getURIUniversalCommunication.getURIID.getschemeID", "");
        $uriId = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipToTradeParty.getURIUniversalCommunication.getURIID", "");

        return $this;
    }

    /**
     * Get information about the party from which the goods and services are delivered or picked up
     *
     * @param  string      $name
     * The full formal name by which the party is registered in the national registry of
     * legal entities or as a Taxable person or otherwise trades as a person or persons.
     * @param  array|null  $id
     * An identification of the Party. The identification scheme identifier of the Party identifier.
     * @param  string|null $description
     * Additional legal information relevant for the Paety.
     * @return OrderDocumentReader
     */
    public function getDocumentShipFrom(?string &$name, ?array &$id, ?string &$description): OrderDocumentReader
    {
        $name = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipFromTradeParty.getName", "");
        $id = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipFromTradeParty.getID", []);
        $description = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipFromTradeParty.getDescription", "");

        $id = $this->convertToArray($id, ["id" => "value"]);

        return $this;
    }

    /**
     * Get global identifier of the party from which the goods and services are delivered or picked up
     *
     * @param  array|null $globalID
     * Array of the sellers global ids indexed by the identification scheme. The identification scheme results
     * from the list published by the ISO/IEC 6523 Maintenance Agency.
     * @return OrderDocumentReader
     */
    public function getDocumentShipFromGlobalId(?array &$globalID): OrderDocumentReader
    {
        $globalID = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipFromTradeParty.getGlobalID", []);
        $globalID = $this->convertToAssociativeArray($globalID, "getSchemeID", "value");

        return $this;
    }

    /**
     * Get Tax registration of the party from which the goods and services are delivered or picked up
     *
     * @param  array|null $taxreg
     * Array of sales tax identification numbers of the seller indexed by Scheme identifier (e.g.: __FC__ for _Tax number of the seller_ and __VA__
     * for _Sales tax identification number of the seller_)
     * @return OrderDocumentReader
     */
    public function getDocumentShipFromTaxRegistration(?array &$taxreg): OrderDocumentReader
    {
        $taxreg = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipFromTradeParty.getSpecifiedTaxRegistration", []);
        $taxreg = $this->convertToAssociativeArray($taxreg, "getID.getSchemeID", "getID.value");

        return $this;
    }

    /**
     * Get the postal address of the party from which the goods and services are delivered or picked up
     *
     * @param  string|null $lineone
     * The main line in the party's address. This is usually the street name and house number or
     * the post office box
     * @param  string|null $linetwo
     * Line 2 of the party's address. This is an additional address line in an address that can be
     * used to provide additional details in addition to the main line
     * @param  string|null $linethree
     * Line 3 of the party's address. This is an additional address line in an address that can be
     * used to provide additional details in addition to the main line
     * @param  string|null $postcode
     * The identifier for an addressable group of properties according to the relevant postal service.
     * @param  string|null $city
     * Usual name of the city or municipality in which the party's address is located
     * @param  string|null $country
     * Code used to identify the country. If no tax agent is specified, this is the country in which the sales tax
     * is due. The lists of approved countries are maintained by the EN ISO 3166-1 Maintenance Agency “Codes for the
     * representation of names of countries and their subdivisions”
     * @param  string|null $subdivision
     * The subdivision of a country.
     * @return OrderDocumentReader
     */
    public function getDocumentShipFromAddress(?string &$lineone, ?string &$linetwo, ?string &$linethree, ?string &$postcode, ?string &$city, ?string &$country, ?string &$subdivision): OrderDocumentReader
    {
        $lineone = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipFromTradeParty.getPostalTradeAddress.getLineOne", "");
        $linetwo = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipFromTradeParty.getPostalTradeAddress.getLineTwo", "");
        $linethree = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipFromTradeParty.getPostalTradeAddress.getLineThree", "");
        $postcode = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipFromTradeParty.getPostalTradeAddress.getPostcodeCode.value", "");
        $city = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipFromTradeParty.getPostalTradeAddress.getCityName", "");
        $country = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipFromTradeParty.getPostalTradeAddress.getCountryID", "");
        $subdivision = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipFromTradeParty.getPostalTradeAddress.getCountrySubDivisionName", "");

        return $this;
    }

    /**
     * Set legal organisation of the party from which the goods and services are delivered or picked up
     *
     * @param  string|null $legalorgid
     * An identifier issued by an official registrar that identifies the
     * party as a legal entity or legal person. If no identification scheme ($legalorgtype) is provided,
     * it should be known to the buyer or seller party
     * @param  string|null $legalorgtype The identifier for the identification scheme of the legal
     *                                   registration of the party. In particular, the following scheme codes are used: 0021 : SWIFT, 0088 : EAN,
     *                                   0060 : DUNS, 0177 : ODETTE
     * @param  string|null $legalorgname A name by which the party is known, if different from the party's name
     *                                   (also known as the company name)
     * @return OrderDocumentReader
     */
    public function getDocumentShipFromLegalOrganisation(?string &$legalorgid, ?string &$legalorgtype, ?string &$legalorgname): OrderDocumentReader
    {
        $legalorgid = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipFromTradeParty.getSpecifiedLegalOrganization.getID.value", "");
        $legalorgtype = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipFromTradeParty.getSpecifiedLegalOrganization.getID.getSchemeID", "");
        $legalorgname = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipFromTradeParty.getSpecifiedLegalOrganization.getTradingBusinessName", "");

        return $this;
    }

    /**
     * Seek to the first ship-from contact of the document.
     * Returns true if a ship-to contact is available, otherwise false
     * You may use this together with OrderDocumentReader::getDocumentShipFromContact
     *
     * @return boolean
     */
    public function firstDocumentShipFromContact(): bool
    {
        $this->documentShipFromContactPointer = 0;
        $contacts = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipFromTradeParty.getDefinedTradeContact", []));
        return isset($contacts[$this->documentShipFromContactPointer]);
    }

    /**
     * Seek to the next available first ship-to contact of the document.
     * Returns true if another ship-to contact is available, otherwise false
     * You may use this together with OrderDocumentReader::getDocumentShipFromContact
     *
     * @return boolean
     */
    public function nextDocumentShipFromContact(): bool
    {
        $this->documentShipFromContactPointer++;
        $contacts = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipFromTradeParty.getDefinedTradeContact", []));
        return isset($contacts[$this->documentShipFromContactPointer]);
    }

    /**
     * Get contact of the party from which the goods and services are delivered or picked up
     *
     * @param  string|null $contactpersonname
     * Contact point for a legal entity, such as a personal name of the contact person
     * @param  string|null $contactdepartmentname
     * Contact point for a legal entity, such as a name of the department or office
     * @param  string|null $contactphoneno
     * A phone number for the contact point.
     * @param  string|null $contactfaxno
     * A fax number for the contact point.
     * @param  string|null $contactemailadd
     * An e-mail address for the contact point.
     * @param  string|null $contacttypecode
     * The code specifying the type of trade contact. To be chosen from the entries in UNTDID 3139
     * @return OrderDocumentReader
     */
    public function getDocumentShipFromContact(?string &$contactpersonname, ?string &$contactdepartmentname, ?string &$contactphoneno, ?string &$contactfaxno, ?string &$contactemailadd, ?string &$contacttypecode): OrderDocumentReader
    {
        $contacts = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipFromTradeParty.getDefinedTradeContact", []));
        $contact = $this->objectHelper->getArrayIndex($contacts, $this->documentShipFromContactPointer);
        $contactpersonname = $this->getInvoiceValueByPathFrom($contact, "getPersonName", "");
        $contactdepartmentname = $this->getInvoiceValueByPathFrom($contact, "getDepartmentName", "");
        $contactphoneno = $this->getInvoiceValueByPathFrom($contact, "getTelephoneUniversalCommunication.getCompleteNumber", "");
        $contactfaxno = $this->getInvoiceValueByPathFrom($contact, "getFaxUniversalCommunication.getCompleteNumber", "");
        $contactemailadd = $this->getInvoiceValueByPathFrom($contact, "getEmailURIUniversalCommunication.getURIID", "");
        $contacttypecode = $this->getInvoiceValueByPathFrom($contact, "getTypeCode", "");

        return $this;
    }

    /**
     * Get the universal communication info for the party from which the goods and services are delivered or picked up
     *
     * @param  string|null $uriType
     * Identifies the electronic address to which the application level response to the order may be delivered.
     * @param  string|null $uriId
     * The identification scheme identifier of the electronic address. The scheme identifier shall be chosen
     * from a list to be maintained by the Connecting Europe Facility.
     * @return OrderDocumentReader
     */
    public function getDocumentShipFromElectronicAddress(?string &$uriType, ?string &$uriId): OrderDocumentReader
    {
        $uriType = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipFromTradeParty.getURIUniversalCommunication.getURIID.getschemeID", "");
        $uriId = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getShipFromTradeParty.getURIUniversalCommunication.getURIID", "");

        return $this;
    }

    /**
     * Seek to the first document requested delivery supply chain event of the document.
     * Returns true if a first event is available, otherwise false
     * You may use this together with OrderDocumentReader::getDocumentRequestedDeliverySupplyChainEvent
     *
     * @return boolean
     */
    public function firstDocumentRequestedDeliverySupplyChainEvent(): bool
    {
        $this->documentRequestedDeliverySupplyChainEventPointer = 0;
        $events = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getRequestedDeliverySupplyChainEvent", []));
        return isset($events[$this->documentRequestedDeliverySupplyChainEventPointer]);
    }

    /**
     * Seek to the next document requested delivery supply chain event of the document.
     * Returns true if a event is available, otherwise false
     * You may use this together with OrderDocumentReader::getDocumentRequestedDeliverySupplyChainEvent
     *
     * @return boolean
     */
    public function nextDocumentRequestedDeliverySupplyChainEvent(): bool
    {
        $this->documentRequestedDeliverySupplyChainEventPointer++;
        $events = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getRequestedDeliverySupplyChainEvent", []));
        return isset($events[$this->documentRequestedDeliverySupplyChainEventPointer]);
    }

    /**
     * Get the requested date or period on which delivery is requested
     * This method should be used together with __OrderDocumentReader::firstDocumentRequestedDeliverySupplyChainEvent__
     * and __OrderDocumentReader::nextDocumentRequestedDeliverySupplyChainEvent__
     *
     * @param  DateTime      $occurrenceDateTime
     * A Requested Date on which Delivery is requested
     * @param  DateTime|null $startDateTime
     * The Start Date of he Requested Period on which Delivery is requested
     * @param  DateTime|null $endDateTime
     * The End Date of he Requested Period on which Delivery is requested
     * @return OrderDocumentReader
     */
    public function getDocumentRequestedDeliverySupplyChainEvent(?DateTime &$occurrenceDateTime, ?DateTime &$startDateTime, ?DateTime &$endDateTime): OrderDocumentReader
    {
        $events = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getRequestedDeliverySupplyChainEvent", []));
        $event = $this->objectHelper->getArrayIndex($events, $this->documentRequestedDeliverySupplyChainEventPointer);

        $occurrenceDateTime = $this->objectHelper->toDateTime(
            $this->getInvoiceValueByPathFrom($event, "getOccurrenceDateTime.getDateTimeString", ""),
            $this->getInvoiceValueByPathFrom($event, "getOccurrenceDateTime.getDateTimeString.getFormat", "")
        );
        $startDateTime = $this->objectHelper->toDateTime(
            $this->getInvoiceValueByPathFrom($event, "getOccurrenceSpecifiedPeriod.getStartDateTime.getDateTimeString", ""),
            $this->getInvoiceValueByPathFrom($event, "getOccurrenceSpecifiedPeriod.getStartDateTime.getDateTimeString.getFormat", "")
        );
        $endDateTime = $this->objectHelper->toDateTime(
            $this->getInvoiceValueByPathFrom($event, "getOccurrenceSpecifiedPeriod.getEndDateTime.getDateTimeString", ""),
            $this->getInvoiceValueByPathFrom($event, "getOccurrenceSpecifiedPeriod.getEndDateTime.getDateTimeString.getFormat", "")
        );

        return $this;
    }

    /**
     * Seek to the first document requested despatch supply chain event of the document.
     * Returns true if a first event is available, otherwise false
     * You may use this together with OrderDocumentReader::getRequestedDespatchSupplyChainEvent
     * (This is the date or period on which delivery is requested)
     *
     * @return boolean
     */
    public function firstDocumentRequestedDespatchSupplyChainEvent(): bool
    {
        $this->documentRequestedDespatchSupplyChainEventPointer = 0;
        $events = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getRequestedDespatchSupplyChainEvent", []));
        return isset($events[$this->documentRequestedDespatchSupplyChainEventPointer]);
    }

    /**
     * Seek to the next document requested despatch supply chain event of the document.
     * Returns true if a event is available, otherwise false
     * You may use this together with OrderDocumentReader::getRequestedDespatchSupplyChainEvent
     * (This is the date or period on which delivery is requested)
     *
     * @return boolean
     */
    public function nextDocumentRequestedDespatchSupplyChainEvent(): bool
    {
        $this->documentRequestedDespatchSupplyChainEventPointer++;
        $events = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getRequestedDespatchSupplyChainEvent", []));
        return isset($events[$this->documentRequestedDespatchSupplyChainEventPointer]);
    }

    /**
     * Get the requested date or period on which delivery is requested
     * This method should be used together with __OrderDocumentReader::firstDocumentRequestedDespatchSupplyChainEvent__
     * and __OrderDocumentReader::nextDocumentRequestedDespatchSupplyChainEvent__
     *
     * @param  DateTime      $occurrenceDateTime
     * A Requested Date on which Pick up is requested
     * @param  DateTime|null $startDateTime
     * The Start Date of he Requested Period on which Pick up is requested
     * @param  DateTime|null $endDateTime
     * The End Date of he Requested Period on which Pick up is requested
     * @return OrderDocumentReader
     */
    public function getDocumentRequestedDespatchSupplyChainEvent(?DateTime &$occurrenceDateTime, ?DateTime &$startDateTime, ?DateTime &$endDateTime): OrderDocumentReader
    {
        $events = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeDelivery.getRequestedDespatchSupplyChainEvent", []));
        $event = $this->objectHelper->getArrayIndex($events, $this->documentRequestedDeliverySupplyChainEventPointer);

        $occurrenceDateTime = $this->objectHelper->toDateTime(
            $this->getInvoiceValueByPathFrom($event, "getOccurrenceDateTime.getDateTimeString", ""),
            $this->getInvoiceValueByPathFrom($event, "getOccurrenceDateTime.getDateTimeString.getFormat", "")
        );
        $startDateTime = $this->objectHelper->toDateTime(
            $this->getInvoiceValueByPathFrom($event, "getOccurrenceSpecifiedPeriod.getStartDateTime.getDateTimeString", ""),
            $this->getInvoiceValueByPathFrom($event, "getOccurrenceSpecifiedPeriod.getStartDateTime.getDateTimeString.getFormat", "")
        );
        $endDateTime = $this->objectHelper->toDateTime(
            $this->getInvoiceValueByPathFrom($event, "getOccurrenceSpecifiedPeriod.getEndDateTime.getDateTimeString", ""),
            $this->getInvoiceValueByPathFrom($event, "getOccurrenceSpecifiedPeriod.getEndDateTime.getDateTimeString.getFormat", "")
        );

        return $this;
    }

    /**
     * Get detailed information on the Party to which the invoice must be sent
     *
     * @param  string      $name
     * The full formal name by which the party is registered in the national registry of
     * legal entities or as a Taxable person or otherwise trades as a person or persons.
     * @param  array|null  $id
     * An identification of the Party. The identification scheme identifier of the Party identifier.
     * @param  string|null $description
     * Additional legal information relevant for the Paety.
     * @return OrderDocumentReader
     */
    public function getDocumentInvoicee(?string &$name, ?array &$id, ?string &$description): OrderDocumentReader
    {
        $name = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getInvoiceeTradeParty.getName", "");
        $id = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getInvoiceeTradeParty.getID", []);
        $description = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getInvoiceeTradeParty.getDescription", "");

        $id = $this->convertToArray($id, ["id" => "value"]);

        return $this;
    }

    /**
     * Get global identifier of the Party to which the invoice must be sent.
     *
     * @param  array|null $globalID
     * Array of the sellers global ids indexed by the identification scheme. The identification scheme results
     * from the list published by the ISO/IEC 6523 Maintenance Agency.
     * @return OrderDocumentReader
     */
    public function getDocumentInvoiceeGlobalId(?array &$globalID): OrderDocumentReader
    {
        $globalID = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getInvoiceeTradeParty.getGlobalID", []);
        $globalID = $this->convertToAssociativeArray($globalID, "getSchemeID", "value");

        return $this;
    }

    /**
     * Get Tax registration to the Party to which the invoice must be sent
     *
     * @param  array|null $taxreg
     * Array of sales tax identification numbers of the seller indexed by Scheme identifier (e.g.: __FC__ for _Tax number of the seller_ and __VA__
     * for _Sales tax identification number of the seller_)
     * @return OrderDocumentReader
     */
    public function getDocumentInvoiceeTaxRegistration(?array &$taxreg): OrderDocumentReader
    {
        $taxreg = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getInvoiceeTradeParty.getSpecifiedTaxRegistration", []);
        $taxreg = $this->convertToAssociativeArray($taxreg, "getID.getSchemeID", "getID.value");

        return $this;
    }

    /**
     * Get the address of the Party to which the invoice must be sent
     *
     * @param  string|null $lineone
     * The main line in the invoicee's address. This is usually the street name and house number or
     * the post office box
     * @param  string|null $linetwo
     * Line 2 of the invoicee's address. This is an additional address line in an address that can be
     * used to provide additional details in addition to the main line
     * @param  string|null $linethree
     * Line 3 of the invoicee' address. This is an additional address line in an address that can be
     * used to provide additional details in addition to the main line
     * @param  string|null $postcode
     * The identifier for an addressable group of properties according to the relevant postal service.
     * @param  string|null $city
     * Usual name of the city or municipality in which the invoicee' address is located
     * @param  string|null $country
     * Code used to identify the country. If no tax agent is specified, this is the country in which the sales tax
     * is due. The lists of approved countries are maintained by the EN ISO 3166-1 Maintenance Agency “Codes for the
     * representation of names of countries and their subdivisions”
     * @param  string|null $subdivision
     * The subdivision of a country.
     * @return OrderDocumentReader
     */
    public function getDocumentInvoiceeAddress(?string &$lineone, ?string &$linetwo, ?string &$linethree, ?string &$postcode, ?string &$city, ?string &$country, ?string &$subdivision): OrderDocumentReader
    {
        $lineone = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getInvoiceeTradeParty.getPostalTradeAddress.getLineOne", "");
        $linetwo = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getInvoiceeTradeParty.getPostalTradeAddress.getLineTwo", "");
        $linethree = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getInvoiceeTradeParty.getPostalTradeAddress.getLineThree", "");
        $postcode = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getInvoiceeTradeParty.getPostalTradeAddress.getPostcodeCode.value", "");
        $city = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getInvoiceeTradeParty.getPostalTradeAddress.getCityName", "");
        $country = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getInvoiceeTradeParty.getPostalTradeAddress.getCountryID", "");
        $subdivision = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getInvoiceeTradeParty.getPostalTradeAddress.getCountrySubDivisionName", "");

        return $this;
    }

    /**
     * Get the legal organisation of the Party to which the invoice must be sent
     *
     * @param  string|null $legalorgid
     * An identifier issued by an official registrar that identifies the
     * invoice as a legal entity or legal person. If no identification scheme ($legalorgtype) is provided,
     * it should be known to the buyer and invoice
     * @param  string|null $legalorgtype
     * The identifier for the identification scheme of the legal
     * registration of the invoice. If the identification scheme is used, it must be selected from
     * ISO/IEC 6523 list
     * @param  string|null $legalorgname
     * A name by which the invoice is known, if different from the invoice's name
     * (also known as the company name)
     * @return OrderDocumentReader
     */
    public function getDocumentInvoiceeLegalOrganisation(?string &$legalorgid, ?string &$legalorgtype, ?string &$legalorgname): OrderDocumentReader
    {
        $legalorgid = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getInvoiceeTradeParty.getSpecifiedLegalOrganization.getID.value", "");
        $legalorgtype = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getInvoiceeTradeParty.getSpecifiedLegalOrganization.getID.getSchemeID", "");
        $legalorgname = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getInvoiceeTradeParty.getSpecifiedLegalOrganization.getTradingBusinessName", "");

        return $this;
    }

    /**
     * Seek to the first contact of the Party to which the invoice must be sent.
     * Returns true if a first invoice contact is available, otherwise false
     * You may use this together with OrderDocumentReader::getDocumentinvoiceeContact
     *
     * @return boolean
     */
    public function firstDocumentInvoiceeContact(): bool
    {
        $this->documentInvoiceeContactPointer = 0;
        $contacts = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getInvoiceeTradeParty.getDefinedTradeContact", []));
        return isset($contacts[$this->documentInvoiceeContactPointer]);
    }

    /**
     * Seek to the next available contact of the Party to which the invoice must be sent.
     * Returns true if another invoicee contact is available, otherwise false
     * You may use this together with OrderDocumentReader::getDocumentInvoiceeContact
     *
     * @return boolean
     */
    public function nextDocumentInvoiceeContact(): bool
    {
        $this->documentInvoiceeContactPointer++;
        $contacts = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getInvoiceeTradeParty.getDefinedTradeContact", []));
        return isset($contacts[$this->documentInvoiceeContactPointer]);
    }

    /**
     * Get detailed information on the Party to which the invoice must be sent
     *
     * @param  string|null $contactpersonname
     * Contact point for a legal entity, such as a personal name of the contact person
     * @param  string|null $contactdepartmentname
     * Contact point for a legal entity, such as a name of the department or office
     * @param  string|null $contactphoneno
     * A phone number for the contact point.
     * @param  string|null $contactfaxno
     * A fax number for the contact point.
     * @param  string|null $contactemailadd
     * An e-mail address for the contact point.
     * @param  string|null $contacttypecode
     * The code specifying the type of trade contact. To be chosen from the entries in UNTDID 3139
     * @return OrderDocumentReader
     */
    public function getDocumentInvoiceeContact(?string &$contactpersonname, ?string &$contactdepartmentname, ?string &$contactphoneno, ?string &$contactfaxno, ?string &$contactemailadd, ?string &$contacttypecode): OrderDocumentReader
    {
        $contacts = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getInvoiceeTradeParty.getDefinedTradeContact", []));
        $contact = $this->objectHelper->getArrayIndex($contacts, $this->documentInvoiceeContactPointer);
        $contactpersonname = $this->getInvoiceValueByPathFrom($contact, "getPersonName", "");
        $contactdepartmentname = $this->getInvoiceValueByPathFrom($contact, "getDepartmentName", "");
        $contactphoneno = $this->getInvoiceValueByPathFrom($contact, "getTelephoneUniversalCommunication.getCompleteNumber", "");
        $contactfaxno = $this->getInvoiceValueByPathFrom($contact, "getFaxUniversalCommunication.getCompleteNumber", "");
        $contactemailadd = $this->getInvoiceValueByPathFrom($contact, "getEmailURIUniversalCommunication.getURIID", "");
        $contacttypecode = $this->getInvoiceValueByPathFrom($contact, "getTypeCode", "");

        return $this;
    }

    /**
     * Set the universal communication info for the Party to which the invoice must be sent
     *
     * @param  string|null $uriType
     * Identifies the electronic address to which the application level response to the order may be delivered.
     * @param  string|null $uriId
     * The identification scheme identifier of the electronic address. The scheme identifier shall be chosen
     * from a list to be maintained by the Connecting Europe Facility.
     * @return OrderDocumentReader
     */
    public function getDocumentInvoiceeElectronicAddress(?string &$uriType, ?string &$uriId): OrderDocumentReader
    {
        $uriType = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getInvoiceeTradeParty.getURIUniversalCommunication.getURIID.getschemeID", "");
        $uriId = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getInvoiceeTradeParty.getURIUniversalCommunication.getURIID", "");

        return $this;
    }

    /**
     * Seek to the first payment means of the document.
     * Returns true if a first payment mean is available, otherwise false
     * You may use this together with OrderDocumentReader::getDocumentPaymentMeans
     *
     * @return boolean
     */
    public function firstDocumentPaymentMeans(): bool
    {
        $this->documentPaymentMeansPointer = 0;
        $paymentMeans = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getSpecifiedTradeSettlementPaymentMeans", []));
        return isset($paymentMeans[$this->documentPaymentMeansPointer]);
    }

    /**
     * Seek to the next payment means of the document
     * Returns true if another payment mean is available, otherwise false
     * You may use this together with OrderDocumentReader::getDocumentPaymentMeans
     *
     * @return boolean
     */
    public function nextDocumentPaymentMeans(): bool
    {
        $this->documentPaymentMeansPointer++;
        $paymentMeans = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getSpecifiedTradeSettlementPaymentMeans", []));
        return isset($paymentMeans[$this->documentPaymentMeansPointer]);
    }

    /**
     * Get detailed information on the payment method
     *
     * @param  string      $paymentMeansCode
     * The means, expressed as code, for how a payment is expected to be or has been settled.
     * Entries from the UNTDID 4461 code list  shall be used. Distinction should be made between
     * SEPA and non-SEPA payments, and between credit payments, direct debits, card payments and
     * other instruments.
     * @param  string|null $paymentMeansInformation
     * Such as cash, credit transfer, direct debit, credit card, etc.
     * @return OrderDocumentReader
     */
    public function getDocumentPaymentMeans(?string &$paymentMeansCode, ?string &$paymentMeansInformation): OrderDocumentReader
    {
        $paymentMeans = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getSpecifiedTradeSettlementPaymentMeans", []));
        $paymentMean = $this->objectHelper->getArrayIndex($paymentMeans, $this->documentPaymentMeansPointer);

        $paymentMeansCode = $this->getInvoiceValueByPathFrom($paymentMean, "getTypeCode", "");
        $paymentMeansInformation = implode("\r\n", $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($paymentMean, "getInformation", "")));

        return $this;
    }

    /**
     * Seek to the first documents payment terms position
     * Returns true if the first position is available, otherwise false
     * You may use this together with OrderDocumentReader::getDocumentPaymentTerm
     *
     * @return boolean
     */
    public function firstDocumentPaymentTerms(): bool
    {
        $this->documentPaymentTermsPointer = 0;
        $paymentTerms = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getSpecifiedTradePaymentTerms", []));
        return isset($paymentTerms[$this->documentPaymentTermsPointer]);
    }

    /**
     * Seek to the next documents payment terms position
     * Returns true if a other position is available, otherwise false
     * You may use this together with OrderDocumentReader::getDocumentPaymentTerm
     *
     * @return boolean
     */
    public function nextDocumentPaymentTerms(): bool
    {
        $this->documentPaymentTermsPointer++;
        $paymentTerms = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getSpecifiedTradePaymentTerms", []));
        return isset($paymentTerms[$this->documentPaymentTermsPointer]);
    }

    /**
     * Get currently seeked payment term
     * This controlled by firstDocumentPaymentTerms and nextDocumentPaymentTerms methods
     *
     * @param  string|null $paymentTermsDescription
     * A text description of the payment terms that apply to the payment amount due (including a
     * description of possible penalties). Note: This element can contain multiple lines and
     * multiple conditions.
     * @return OrderDocumentReader
     */
    public function getDocumentPaymentTerm(?string &$paymentTermsDescription): OrderDocumentReader
    {
        $paymentTerms = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getSpecifiedTradePaymentTerms", []));
        $paymentTerm = $this->objectHelper->getArrayIndex($paymentTerms, $this->documentPaymentTermsPointer);

        $paymentTermsDescription = $paymentTerm;

        return $this;
    }

    /**
     * Seek to the first document tax
     * Returns true if a first tax (at document level) is available, otherwise false
     * You may use this together with OrderDocumentReader::getDocumentTax
     *
     * @return boolean
     */
    public function firstDocumentTax(): bool
    {
        $this->documentTaxPointer = 0;
        $taxes = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getApplicableTradeTax", []));
        return isset($taxes[$this->documentTaxPointer]);
    }

    /**
     * Seek to the next document tax
     * Returns true if another tax (at document level) is available, otherwise false
     * You may use this together with OrderDocumentReader::getDocumentTax
     *
     * @return boolean
     */
    public function nextDocumentTax(): bool
    {
        $this->documentTaxPointer++;
        $taxes = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getApplicableTradeTax", []));
        return isset($taxes[$this->documentTaxPointer]);
    }

    /**
     * Get current VAT breakdown (at document level)
     *
     * @param  string|null $categoryCode
     * Coded description of a sales tax category
     *
     * The following entries from UNTDID 5305 are used (details in brackets):
     *  - Standard rate (sales tax is due according to the normal procedure)
     *  - Goods to be taxed according to the zero rate (sales tax is charged with a percentage of zero)
     *  - Tax exempt (USt./IGIC/IPSI)
     *  - Reversal of the tax liability (the rules for reversing the tax liability at USt./IGIC/IPSI apply)
     *  - VAT exempt for intra-community deliveries of goods (USt./IGIC/IPSI not levied due to rules on intra-community deliveries)
     *  - Free export item, tax not levied (VAT / IGIC/IPSI not levied due to export outside the EU)
     *  - Services outside the tax scope (sales are not subject to VAT / IGIC/IPSI)
     *  - Canary Islands general indirect tax (IGIC tax applies)
     *  - IPSI (tax for Ceuta / Melilla) applies.
     *
     * The codes for the VAT category are as follows:
     *  - S = sales tax is due at the normal rate
     *  - Z = goods to be taxed according to the zero rate
     *  - E = tax exempt
     *  - AE = reversal of tax liability
     *  - K = VAT is not shown for intra-community deliveries
     *  - G = tax not levied due to export outside the EU
     *  - O = Outside the tax scope
     *  - L = IGIC (Canary Islands)
     *  - M = IPSI (Ceuta / Melilla)
     * @param  string|null $typeCode
     * Coded description of a sales tax category. Note: Fixed value = "VAT"
     * @param  float|null  $basisAmount
     * Tax base amount, Each sales tax breakdown must show a category-specific tax base amount.
     * @param  float|null  $calculatedAmount
     * The total amount to be paid for the relevant VAT category. Note: Calculated by multiplying
     * the amount to be taxed according to the sales tax category by the sales tax rate applicable
     * for the sales tax category concerned
     * @param  float|null  $rateApplicablePercent
     * The sales tax rate, expressed as the percentage applicable to the sales tax category in
     * question. Note: The code of the sales tax category and the category-specific sales tax rate
     * must correspond to one another. The value to be given is the percentage. For example, the
     * value 20 is given for 20% (and not 0.2)
     * @param  string|null $exemptionReason
     * Reason for tax exemption (free text)
     * @param  string|null $exemptionReasonCode
     * Reason given in code form for the exemption of the amount from VAT. Note: Code list issued
     * and maintained by the Connecting Europe Facility.
     * @param  float|null  $lineTotalBasisAmount
     * Tax rate goods amount
     * @param  float|null  $allowanceChargeBasisAmount
     * Total amount of surcharges and deductions of the tax rate at document level
     * @param  string|null $dueDateTypeCode
     * The code for the date on which sales tax becomes relevant for the seller and the buyer.
     * The code must distinguish between the following entries from UNTDID 2005:
     *  - date of issue of the invoice document
     *  - actual delivery date
     *  - Date of payment.
     *
     * The VAT Collection Date Code is used when the VAT Collection Date is not known for VAT purposes
     * when the invoice is issued.
     *
     * The semantic values cited in the standard, which are represented by the values 3, 35, 432 in
     * UNTDID2005, are mapped to the following values of UNTDID2475, which is the relevant code list
     * supported by CII 16B:
     *  - 5: date of issue of the invoice
     *  - 29: Delivery date, current status
     *  - 72: Paid to date
     *
     * In Germany, the date of delivery and service is decisive.
     * @return OrderDocumentReader
     */
    public function getDocumentTax(?string &$categoryCode, ?string &$typeCode, ?float &$basisAmount, ?float &$calculatedAmount, ?float &$rateApplicablePercent, ?string &$exemptionReason, ?string &$exemptionReasonCode, ?float &$lineTotalBasisAmount, ?float &$allowanceChargeBasisAmount, ?string &$dueDateTypeCode): OrderDocumentReader
    {
        $taxes = $this->objectHelper->ensureArray($this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getApplicableTradeTax", []));
        $tax = $this->objectHelper->getArrayIndex($taxes, $this->documentTaxPointer);

        $categoryCode = $this->getInvoiceValueByPathFrom($tax, "getCategoryCode", "");
        $typeCode = $this->getInvoiceValueByPathFrom($tax, "getTypeCode", "");
        $basisAmount = $this->getInvoiceValueByPathFrom($tax, "getBasisAmount.value", 0.0);
        $calculatedAmount = $this->getInvoiceValueByPathFrom($tax, "getCalculatedAmount.value", 0.0);
        $rateApplicablePercent = $this->getInvoiceValueByPathFrom($tax, "getRateApplicablePercent.value", 0.0);
        $exemptionReason = $this->getInvoiceValueByPathFrom($tax, "getExemptionReason", "");
        $exemptionReasonCode = $this->getInvoiceValueByPathFrom($tax, "getExemptionReasonCode", "");
        $lineTotalBasisAmount = $this->getInvoiceValueByPathFrom($tax, "getLineTotalBasisAmount.value", 0.0);
        $allowanceChargeBasisAmount = $this->getInvoiceValueByPathFrom($tax, "getAllowanceChargeBasisAmount.value", 0.0);
        $dueDateTypeCode = $this->getInvoiceValueByPathFrom($tax, "getDueDateTypeCode", "");

        return $this;
    }

    /**
     * Seek to the first documents allowance charge. Returns true if the first allowance/charge is available, otherwise false
     * You may use this together with OrderDocumentReader::getDocumentAllowanceCharge
     *
     * @return boolean
     */
    public function firstDocumentAllowanceCharge(): bool
    {
        $this->documentAllowanceChargePointer = 0;
        $allowanceCharge = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getSpecifiedTradeAllowanceCharge", []);
        return isset($allowanceCharge[$this->documentAllowanceChargePointer]);
    }

    /**
     * Seek to the next documents allowance charge. Returns true if another allowance/charge is available, otherwise false
     * You may use this together with OrderDocumentReader::getDocumentAllowanceCharge
     *
     * @return boolean
     */
    public function nextDocumentAllowanceCharge(): bool
    {
        $this->documentAllowanceChargePointer++;
        $allowanceCharge = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getSpecifiedTradeAllowanceCharge", []);
        return isset($allowanceCharge[$this->documentAllowanceChargePointer]);
    }

    /**
     * Get information about the currently seeked surcharges and charges applicable to the
     * bill as a whole, Deductions, such as for withheld taxes may also be specified in this group
     *
     * @param  float|null   $actualAmount
     * Amount of the surcharge or discount at document level
     * @param  boolean|null $isCharge
     * Switch that indicates whether the following data refer to an allowance or a discount, true means that
     * this an charge
     * @param  string|null  $taxCategoryCode
     * A coded indication of which sales tax category applies to the surcharge or deduction at document level
     *
     * The following entries from UNTDID 5305 are used (details in brackets):
     *  - Standard rate (sales tax is due according to the normal procedure)
     *  - Goods to be taxed according to the zero rate (sales tax is charged with a percentage of zero)
     *  - Tax exempt (USt./IGIC/IPSI)
     *  - Reversal of the tax liability (the rules for reversing the tax liability at USt./IGIC/IPSI apply)
     *  - VAT exempt for intra-community deliveries of goods (USt./IGIC/IPSI not levied due to rules on intra-community deliveries)
     *  - Free export item, tax not levied (VAT / IGIC/IPSI not levied due to export outside the EU)
     *  - Services outside the tax scope (sales are not subject to VAT / IGIC/IPSI)
     *  - Canary Islands general indirect tax (IGIC tax applies)
     *  - IPSI (tax for Ceuta / Melilla) applies.
     *
     * The codes for the VAT category are as follows:
     *  - S = sales tax is due at the normal rate
     *  - Z = goods to be taxed according to the zero rate
     *  - E = tax exempt
     *  - AE = reversal of tax liability
     *  - K = VAT is not shown for intra-community deliveries
     *  - G = tax not levied due to export outside the EU
     *  - O = Outside the tax scope
     *  - L = IGIC (Canary Islands)
     *  - M = IPSI (Ceuta/Melilla)
     * @param  string|null  $taxTypeCode
     * Code for the VAT category of the surcharge or charge at document level. Note: Fixed value = "VAT"
     * @param  float|null   $rateApplicablePercent
     * VAT rate for the surcharge or discount on document level. Note: The code of the sales tax category
     * and the category-specific sales tax rate must correspond to one another. The value to be given is
     * the percentage. For example, the value 20 is given for 20% (and not 0.2)
     * @param  float|null   $sequence
     * Calculation order
     * @param  float|null   $calculationPercent
     * Percentage surcharge or discount at document level
     * @param  float|null   $basisAmount
     * The base amount that may be used in conjunction with the percentage of the surcharge or discount
     * at document level to calculate the amount of the discount at document level
     * @param  float|null   $basisQuantity
     * Basismenge des Rabatts
     * @param  string|null  $basisQuantityUnitCode
     * Einheit der Preisbasismenge
     *  - Codeliste: Rec. N°20 Vollständige Liste, In Recommendation N°20 Intro 2.a ist beschrieben, dass
     *    beide Listen kombiniert anzuwenden sind.
     *  - Codeliste: Rec. N°21 Vollständige Liste, In Recommendation N°20 Intro 2.a ist beschrieben, dass
     *    beide Listen kombiniert anzuwenden sind.
     * @param  string|null  $reasonCode
     * The reason given as a code for the surcharge or discount at document level. Note: Use entries from
     * the UNTDID 5189 code list. The code of the reason for the surcharge or discount at document level
     * and the reason for the surcharge or discount at document level must correspond to each other
     *
     * Code list: UNTDID 7161 Complete list, code list: UNTDID 5189 Restricted
     * Include PEPPOL subset:
     *  - 41 - Bonus for works ahead of schedule
     *  - 42 - Other bonus
     *  - 60 - Manufacturer’s consumer discount
     *  - 62 - Due to military status
     *  - 63 - Due to work accident
     *  - 64 - Special agreement
     *  - 65 - Production error discount
     *  - 66 - New outlet discount
     *  - 67 - Sample discount
     *  - 68 - End-of-range discount
     *  - 70 - Incoterm discount
     *  - 71 - Point of sales threshold allowance
     *  - 88 - Material surcharge/deduction
     *  - 95 - Discount
     *  - 100 - Special rebate
     *  - 102 - Fixed long term
     *  - 103 - Temporary
     *  - 104 - Standard
     *  - 105 - Yearly turnover
     * @param  string|null  $reason
     * The reason given in text form for the surcharge or discount at document level
     * @return OrderDocumentReader
     */
    public function getDocumentAllowanceCharge(?float &$actualAmount, ?bool &$isCharge, ?string &$taxCategoryCode, ?string &$taxTypeCode, ?float &$rateApplicablePercent, ?float &$sequence, ?float &$calculationPercent, ?float &$basisAmount, ?float &$basisQuantity, ?string &$basisQuantityUnitCode, ?string &$reasonCode, ?string &$reason): OrderDocumentReader
    {
        $allowanceCharges = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getSpecifiedTradeAllowanceCharge", []);
        $allowanceCharge = $this->objectHelper->getArrayIndex($allowanceCharges, $this->documentAllowanceChargePointer);

        $actualAmount = $this->getInvoiceValueByPathFrom($allowanceCharge, "getActualAmount.value", 0.0);
        $isCharge = $this->getInvoiceValueByPathFrom($allowanceCharge, "getChargeIndicator.getIndicator", false);
        $taxCategoryCode = $this->getInvoiceValueByPathFrom($allowanceCharge, "getCategoryTradeTax.getCategoryCode", "");
        $taxTypeCode = $this->getInvoiceValueByPathFrom($allowanceCharge, "getCategoryTradeTax.getTypeCode", "");
        $rateApplicablePercent = $this->getInvoiceValueByPathFrom($allowanceCharge, "getCategoryTradeTax.getRateApplicablePercent.value", 0.0);
        $sequence = $this->getInvoiceValueByPathFrom($allowanceCharge, "getSequenceNumeric.value", 0);
        $calculationPercent = $this->getInvoiceValueByPathFrom($allowanceCharge, "getCalculationPercent.value", 0.0);
        $basisAmount = $this->getInvoiceValueByPathFrom($allowanceCharge, "getBasisAmount.value", 0.0);
        $basisQuantity = $this->getInvoiceValueByPathFrom($allowanceCharge, "getBasisQuantity.value", 0.0);
        $basisQuantityUnitCode = $this->getInvoiceValueByPathFrom($allowanceCharge, "getBasisQuantity.getUnitCode", "");
        $reasonCode = $this->getInvoiceValueByPathFrom($allowanceCharge, "getReasonCode", "");
        $reason = $this->getInvoiceValueByPathFrom($allowanceCharge, "getReason", "");

        return $this;
    }

    /**
     * Get an AccountingAccount
     *
     * @param  string|null $id
     * A textual value that specifies where to book the relevant data into the Buyer's financial accounts.
     * @param  string|null $typeCode
     * The code specifying the type of trade accounting account, such as general (main), secondary, cost accounting or budget account.
     * @return OrderDocumentReader
     */
    public function getDocumentReceivableSpecifiedTradeAccountingAccount(?string &$id, ?string &$typeCode): OrderDocumentReader
    {
        $id = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getReceivableSpecifiedTradeAccountingAccount.getID", "");
        $typeCode = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getApplicableHeaderTradeSettlement.getReceivableSpecifiedTradeAccountingAccount.getTypeCode", "");

        return $this;
    }

    /**
     * Seek to the first document position
     * Returns true if the first position is available, otherwise false
     * You may use it together with getDocumentPositionGenerals
     *
     * @return boolean
     */
    public function firstDocumentPosition(): bool
    {
        $this->positionPointer = 0;

        $this->positionNotePointer = 0;
        $this->positionGrossPriceAllowanceChargePointer = 0;
        $this->positionTaxPointer = 0;
        $this->positionAllowanceChargePointer = 0;
        $this->positionAddRefDocPointer = 0;
        $this->positionUltimateCustomerOrderRefDocPointer = 0;
        $this->positionProductCharacteristicPointer = 0;
        $this->positionProductClassificationPointer = 0;
        $this->positionProductInstancePointer = 0;
        $this->positionProductReferencedDocumentPointer = 0;
        $this->positionRequestedDeliverySupplyChainEventPointer = 0;
        $this->positionRequestedDespatchSupplyChainEventPointer = 0;

        $tradeLineItem = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);

        return isset($tradeLineItem[$this->positionPointer]);
    }

    /**
     * Seek to the next document position
     * Returns true if another position is available, otherwise false
     * You may use it together with getDocumentPositionGenerals
     *
     * @return boolean
     */
    public function nextDocumentPosition(): bool
    {
        $this->positionPointer++;

        $this->positionNotePointer = 0;
        $this->positionGrossPriceAllowanceChargePointer = 0;
        $this->positionTaxPointer = 0;
        $this->positionAllowanceChargePointer = 0;
        $this->positionAddRefDocPointer = 0;
        $this->positionUltimateCustomerOrderRefDocPointer = 0;
        $this->positionProductCharacteristicPointer = 0;
        $this->positionProductClassificationPointer = 0;
        $this->positionProductInstancePointer = 0;
        $this->positionProductReferencedDocumentPointer = 0;
        $this->positionRequestedDeliverySupplyChainEventPointer = 0;
        $this->positionRequestedDespatchSupplyChainEventPointer = 0;

        $tradeLineItem = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);

        return isset($tradeLineItem[$this->positionPointer]);
    }

    /**
     * Get general information of the current position
     *
     * @param  string|null $lineid
     * A unique identifier for the relevant item within the invoice (item number)
     * @param  string|null $lineStatusCode
     * Indicates whether the invoice item contains prices that must be taken into account when
     * calculating the invoice amount, or whether it only contains information.
     * The following code should be used: TYPE_LINE
     * @return OrderDocumentReader
     */
    public function getDocumentPositionGenerals(?string &$lineid, ?string &$lineStatusCode): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $lineid = $this->getInvoiceValueByPathFrom($tradeLineItem, "getAssociatedDocumentLineDocument.getLineID.value", "");
        $lineStatusCode = $this->getInvoiceValueByPathFrom($tradeLineItem, "getAssociatedDocumentLineDocument.getLineStatusCode.value", "");

        return $this;
    }

    /**
     * Seek to the first document position note
     * Returns true if the first note (at line level) is available, otherwise false
     * You may use it together with getDocumentPositionNote
     *
     * @return boolean
     */
    public function firstDocumentPositionNote(): bool
    {
        $this->positionNotePointer = 0;

        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $tradeLineItemNote = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getAssociatedDocumentLineDocument.getIncludedNote", []));

        return isset($tradeLineItemNote[$this->positionNotePointer]);
    }

    /**
     * Seek to the next document position note
     * Returns true if there is one more note (at line level) available, otherwise false
     * You may use it together with getDocumentPositionNote
     *
     * @return boolean
     */
    public function nextDocumentPositionNote(): bool
    {
        $this->positionNotePointer++;

        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $tradeLineItemNote = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getAssociatedDocumentLineDocument.getIncludedNote", []));

        return isset($tradeLineItemNote[$this->positionNotePointer]);
    }

    /**
     * Get detailed information on the free text on the position
     *
     * @param  string|null $content
     * A free text that contains unstructured information that is relevant to the invoice item
     * @param  string|null $contentCode
     * Text modules agreed bilaterally, which are transmitted here as code.
     * @param  string|null $subjectCode
     * Free text for the position (code for the type)
     * __Codelist:__ UNTDID 4451
     * @return OrderDocumentReader
     */
    public function getDocumentPositionNote(?string &$content, ?string &$contentCode, ?string &$subjectCode): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);
        $tradeLineItemNotes = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getAssociatedDocumentLineDocument.getIncludedNote", []));
        $tradeLineItemNote = $this->objectHelper->getArrayIndex($tradeLineItemNotes, $this->positionNotePointer);

        $content = implode("\r\n", $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItemNote, "getContent", "")));
        $contentCode = $this->getInvoiceValueByPathFrom($tradeLineItemNote, "getContentCode", "");
        $subjectCode = $this->getInvoiceValueByPathFrom($tradeLineItemNote, "getSubjectCode", "");

        return $this;
    }

    /**
     * Get product details to the last retrieved position (line) in the document
     *
     * @param  string|null $name
     * A name of the item (item name)
     * @param  string|null $description
     * A textual description of a use of this item.
     * @param  string|null $sellerAssignedID
     * An identifier assigned to the item by the seller
     * @param  string|null $buyerAssignedID
     * An identifier, assigned by the Buyer, for the item.
     * @param  array|null  $globalID
     * Array of the sellers global ids indexed by the identification scheme. The identification scheme results
     * from the list published by the ISO/IEC 6523 Maintenance Agency.
     * @param  string|null $batchId
     * A batch identifier for this item.
     * @param  string|null $brandName
     * The brand name, expressed as text, for this item.
     * @return OrderDocumentReader
     */
    public function getDocumentPositionProductDetails(?string &$name, ?string &$description, ?string &$sellerAssignedID, ?string &$buyerAssignedID, ?array &$globalID, ?string &$batchId, ?string &$brandName): OrderDocumentReader
    {
        $tradeLineItem = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $tradeLineItem[$this->positionPointer];

        $name = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedTradeProduct.getName", "");
        $description = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedTradeProduct.getDescription", "");
        $sellerAssignedID = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedTradeProduct.getSellerAssignedID", "");
        $buyerAssignedID = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedTradeProduct.getBuyerAssignedID", "");
        $globalID = $this->convertToAssociativeArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedTradeProduct.getGlobalID", []), "getSchemeID", "value");

        $batchId = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedTradeProduct.getBatchID", "");
        $brandName = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedTradeProduct.getBrandName", "");

        return $this;
    }

    /**
     * Seek to the first document position product characteristic
     * Returns true if the first product characteristic is available, otherwise false
     * You may use it together with getDocumentPositionProductCharacteristic
     *
     * @return boolean
     */
    public function firstDocumentPositionProductCharacteristic(): bool
    {
        $this->positionProductCharacteristicPointer = 0;

        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $tradeLineItemProductCharacteristic = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedTradeProduct.getApplicableProductCharacteristic", []));

        return isset($tradeLineItemProductCharacteristic[$this->positionProductCharacteristicPointer]);
    }

    /**
     * Seek to the next document position product characteristic
     * Returns true if the next position product characteristic is available, otherwise false
     * You may use it together with getDocumentPositionProductCharacteristic
     *
     * @return boolean
     */
    public function nextDocumentPositionProductCharacteristic(): bool
    {
        $this->positionProductCharacteristicPointer++;

        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $tradeLineItemProductCharacteristic = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedTradeProduct.getApplicableProductCharacteristic", []));

        return isset($tradeLineItemProductCharacteristic[$this->positionProductCharacteristicPointer]);
    }

    /**
     * Set (single) extra characteristics to the formerly added product.
     * Contains information about the characteristics of the goods and services invoiced
     *
     * @param  string      $description
     * The name of the attribute or property of the product such as "Colour"
     * @param  array       $values
     * The values of the attribute or property of the product such as "Red"
     * @param  string|null $typecode
     * Type of product property (code). The codes must be taken from the
     * UNTDID 6313 codelist. Available only in the Extended-Profile
     * @param  float|null  $measureValue
     * A measure of a value for this product characteristic.
     * @param  string|null $measureUnitCode
     * A unit for the measure value for this product characteristic. To be chosen from the entries in UNTDID xxx
     * @return OrderDocumentReader
     */
    public function getDocumentPositionProductCharacteristic(?string &$description, ?array &$values, ?string &$typecode, ?float &$measureValue, ?string &$measureUnitCode): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $tradeLineItemProductCharacteristics = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedTradeProduct.getApplicableProductCharacteristic", []));
        $tradeLineItemProductCharacteristic = $this->objectHelper->getArrayIndex($tradeLineItemProductCharacteristics, $this->positionProductCharacteristicPointer);

        $description = implode("\r\n", $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItemProductCharacteristic, "getDescription", [])));
        $values = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItemProductCharacteristic, "getValue", []));
        $typecode = $this->getInvoiceValueByPathFrom($tradeLineItemProductCharacteristic, "getTypeCode", "");
        $measureValue = ""; //TODO: ValueMeassure
        $measureUnitCode = ""; //TODO: ValueMeassure Unit

        return $this;
    }

    /**
     * Seek to the first document position product classification
     * Returns true if the first product classification is available, otherwise false
     * You may use it together with getDocumentPositionProductClassification
     *
     * @return boolean
     */
    public function firstDocumentPositionProductClassification(): bool
    {
        $this->positionProductClassificationPointer = 0;

        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $tradeLineItemProductClassification = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedTradeProduct.getDesignatedProductClassification", []));

        return isset($tradeLineItemProductClassification[$this->positionProductClassificationPointer]);
    }

    /**
     * Seek to the next document position product classification
     * Returns true if the next product classification is available, otherwise false
     * You may use it together with getDocumentPositionProductClassification
     *
     * @return boolean
     */
    public function nextDocumentPositionProductClassification(): bool
    {
        $this->positionProductClassificationPointer++;

        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $tradeLineItemProductClassification = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedTradeProduct.getDesignatedProductClassification", []));

        return isset($tradeLineItemProductClassification[$this->positionProductClassificationPointer]);
    }

    /**
     * Get detailed information on product classification
     *
     * @param  string      $classCode
     * A code for classifying the item by its type or nature.
     * Classification codes are used to allow grouping of similar items for a various purposes e.g.
     * public procurement (CPV), e-Commerce (UNSPSC) etc. The identification scheme shall be chosen
     * from the entries in UNTDID 7143
     * @param  string|null $className
     * A class name, expressed as text, for this product classification
     * @param  string|null $listID
     * The identification scheme identifier of Item classification identifier
     * Identification scheme must be chosen among the values available in UNTDID 7143
     * @param  string|null $listVersionID
     * Scheme version identifier
     * @return OrderDocumentReader
     */
    public function getDocumentPositionProductClassification(?string &$classCode, ?string &$className, ?string &$listID, ?string &$listVersionID): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $tradeLineItemProductClassifications = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedTradeProduct.getDesignatedProductClassification", []));
        $tradeLineItemProductClassification = $this->objectHelper->getArrayIndex($tradeLineItemProductClassifications, $this->positionProductClassificationPointer);

        $classCode = $this->getInvoiceValueByPathFrom($tradeLineItemProductClassification, "getClassCode", "");
        $className = $this->getInvoiceValueByPathFrom($tradeLineItemProductClassification, "getClassName", "");
        $listID = $this->getInvoiceValueByPathFrom($tradeLineItemProductClassification, "getClassCode.getListID", "");
        $listVersionID = $this->getInvoiceValueByPathFrom($tradeLineItemProductClassification, "getClassCode.getListVersionID", "");

        return $this;
    }

    /**
     * Seek to the first document position product instance
     * Returns true if the first product instance is available, otherwise false
     * You may use it together with getDocumentPositionProductInstance
     *
     * @return boolean
     */
    public function firstDocumentPositionProductInstance(): bool
    {
        $this->positionProductInstancePointer = 0;

        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $tradeLineItemProductInstance = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedTradeProduct.getIndividualTradeProductInstance", []));

        return isset($tradeLineItemProductInstance[$this->positionProductInstancePointer]);
    }

    /**
     * Seek to the next document position product Instance
     * Returns true if the next product Instance is available, otherwise false
     * You may use it together with getDocumentPositionProductInstance
     *
     * @return boolean
     */
    public function nextDocumentPositionProductInstance(): bool
    {
        $this->positionProductInstancePointer++;

        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $tradeLineItemProductInstance = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedTradeProduct.getIndividualTradeProductInstance", []));

        return isset($tradeLineItemProductInstance[$this->positionProductInstancePointer]);
    }

    /**
     * Get the unique batch identifier for this trade product instance and
     * the unique supplier assigned serial identifier for this trade product instance.
     *
     * @param  string      $batchID
     * The unique batch identifier for this trade product instance
     * @param  string|null $serialId
     * The unique supplier assigned serial identifier for this trade product instance.
     * @return OrderDocumentReader
     */
    public function getDocumentPositionProductInstance(?string &$batchID, ?string &$serialId): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $tradeLineItemProductInstances = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedTradeProduct.getIndividualTradeProductInstance", []));
        $tradeLineItemProductInstance = $this->objectHelper->getArrayIndex($tradeLineItemProductInstances, $this->positionProductInstancePointer);

        $batchID = $this->getInvoiceValueByPathFrom($tradeLineItemProductInstance, "getBatchID", "");
        $serialId = $this->getInvoiceValueByPathFrom($tradeLineItemProductInstance, "getSerialID", "");

        return $this;
    }

    /**
     * Get the supply chain packaging information
     *
     * @param  string|null $typeCode
     * The code specifying the type of supply chain packaging.
     * To be chosen from the entries in UNTDID 7065
     * @param  float|null  $width
     * The measure of the width component of this spatial dimension.
     * @param  string|null $widthUnitCode
     * Unit Code of the measure of the width component of this spatial dimension.
     * @param  float|null  $length
     * The measure of the length component of this spatial dimension.
     * @param  string|null $lengthUnitCode
     * Unit Code of the measure of the Length component of this spatial dimension.
     * @param  float|null  $height
     * The measure of the height component of this spatial dimension.
     * @param  string|null $heightUnitCode
     * Unit Code of the measure of the Height component of this spatial dimension.
     * @return OrderDocumentReader
     */
    public function getDocumentPositionSupplyChainPackaging(?string &$typeCode, ?float &$width, ?string &$widthUnitCode, ?float &$length, ?string &$lengthUnitCode, ?float &$height, ?string &$heightUnitCode): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $typeCode = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedTradeProduct.getApplicableSupplyChainPackaging.getTypeCode", "");
        $width = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedTradeProduct.getApplicableSupplyChainPackaging.getLinearSpatialDimension.getWidthMeasure.value", 0);
        $widthUnitCode = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedTradeProduct.getApplicableSupplyChainPackaging.getLinearSpatialDimension.getWidthMeasure.getUnitCode", "");
        $length = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedTradeProduct.getApplicableSupplyChainPackaging.getLinearSpatialDimension.getLengthMeasure.value", 0);
        $lengthUnitCode = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedTradeProduct.getApplicableSupplyChainPackaging.getLinearSpatialDimension.getLengthMeasure.getUnitCode", "");
        $height = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedTradeProduct.getApplicableSupplyChainPackaging.getLinearSpatialDimension.getHeightMeasure.value", 0);
        $heightUnitCode = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedTradeProduct.getApplicableSupplyChainPackaging.getLinearSpatialDimension.getHeightMeasure.getUnitCode", "");

        return $this;
    }

    /**
     * Get information on the product origin
     *
     * @param  string $country
     * The code identifying the country from which the item originates.
     * The lists of valid countries are registered with the EN ISO 3166-1 Maintenance agency, “Codes for the
     * representation of names of countries and their subdivisions”.
     * @return OrderDocumentReader
     */
    public function getDocumentPositionProductOriginTradeCountry(?string &$country): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $country = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedTradeProduct.getOriginTradeCountry.getID", "");

        return $this;
    }

    /**
     * Seek to the first document position product referenced document
     * Returns true if the first product referenced document is available, otherwise false
     * You may use it together with getDocumentPositionProductReferencedDocument
     *
     * @return boolean
     */
    public function firstDocumentPositionProductReferencedDocument(): bool
    {
        $this->positionProductReferencedDocumentPointer = 0;

        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $tradeLineItemProdRefDoc = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedTradeProduct.getAdditionalReferenceReferencedDocument", []));

        return isset($tradeLineItemProdRefDoc[$this->positionProductReferencedDocumentPointer]);
    }

    /**
     * Seek to the next document position product referenced document
     * Returns true if the next product referenced document is available, otherwise false
     * You may use it together with getDocumentPositionProductReferencedDocument
     *
     * @return boolean
     */
    public function nextDocumentPositionProductReferencedDocument(): bool
    {
        $this->positionProductReferencedDocumentPointer++;

        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $tradeLineItemProdRefDoc = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedTradeProduct.getAdditionalReferenceReferencedDocument", []));

        return isset($tradeLineItemProdRefDoc[$this->positionProductReferencedDocumentPointer]);
    }

    /**
     * Get an additional product reference document at position level
     *
     * @param  string|null   $issuerassignedid
     * The unique issuer assigned identifier for this referenced document.
     * @param  string|null   $typecode
     * The code specifying the type of referenced document.
     * To be chosen from the entries in UNTDID 1001
     * @param  string|null   $uriid
     * The unique Uniform Resource Identifier (URI) for this referenced document.
     * @param  string|null   $lineid
     * @param  string|null   $name
     * A name, expressed as text, for this referenced document.
     * @param  string|null   $reftypecode
     * @param  DateTime|null $issueddate
     * @return OrderDocumentReader
     */
    public function getDocumentPositionProductReferencedDocument(?string &$issuerassignedid, ?string &$typecode, ?string &$uriid, ?string &$lineid, ?string &$name, ?string &$reftypecode, ?DateTime &$issueddate): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $tradeLineItemProdRefDocs = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedTradeProduct.getAdditionalReferenceReferencedDocument", []));
        $tradeLineItemProdRefDoc = $this->objectHelper->getArrayIndex($tradeLineItemProdRefDocs, $this->positionProductReferencedDocumentPointer);

        $issuerassignedid = $this->getInvoiceValueByPathFrom($tradeLineItemProdRefDoc, "getIssuerAssignedID", "");
        $typecode = $this->getInvoiceValueByPathFrom($tradeLineItemProdRefDoc, "getTypeCode", "");
        $uriid = $this->getInvoiceValueByPathFrom($tradeLineItemProdRefDoc, "getURIID", "");
        $lineid = $this->getInvoiceValueByPathFrom($tradeLineItemProdRefDoc, "getLineID.value", "");
        $name = $this->getInvoiceValueByPathFrom($tradeLineItemProdRefDoc, "getName", "");
        $reftypecode = $this->getInvoiceValueByPathFrom($tradeLineItemProdRefDoc, "getReferenceTypeCode", "");
        $issueddate = $this->objectHelper->toDateTime(
            $this->getInvoiceValueByPathFrom($tradeLineItemProdRefDoc, 'getFormattedIssueDateTime.getDateTimeString', ''),
            $this->getInvoiceValueByPathFrom($tradeLineItemProdRefDoc, 'getFormattedIssueDateTime.getDateTimeString.getFormat', '')
        );

        return $this;
    }

    /**
     * Get the binary data of an additional product reference document at position level
     *
     * @param  string|null $binarydatafilename
     * The fuill-qualified filename where the data where stored. If no binary data are
     * available, this value will be empty
     * @return OrderDocumentReader
     */
    public function getDocumentPositionProductReferencedDocumentBinaryData(?string &$binarydatafilename): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $tradeLineItemProdRefDocs = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedTradeProduct.getAdditionalReferenceReferencedDocument", []));
        $tradeLineItemProdRefDoc = $this->objectHelper->getArrayIndex($tradeLineItemProdRefDocs, $this->positionProductReferencedDocumentPointer);

        $binarydatafilename = $this->getInvoiceValueByPathFrom($tradeLineItemProdRefDoc, "getAttachmentBinaryObject.getFilename", "");
        $binarydata = $this->getInvoiceValueByPathFrom($tradeLineItemProdRefDoc, "getAttachmentBinaryObject.value", "");

        if (StringUtils::stringIsNullOrEmpty($binarydatafilename) === false
            && StringUtils::stringIsNullOrEmpty($binarydata) === false
            && StringUtils::stringIsNullOrEmpty($this->binarydatadirectory) === false
        ) {
            $binarydatafilename = PathUtils::combinePathWithFile($this->binarydatadirectory, $binarydatafilename);
            FileUtils::base64ToFile($binarydata, $binarydatafilename);
        } else {
            $binarydatafilename = "";
        }

        return $this;
    }

    /**
     * Seek to the first documents position additional referenced document
     * Returns true if the first position is available, otherwise false
     * You may use it together with getDocumentPositionAdditionalReferencedDocument
     *
     * @return boolean
     */
    public function firstDocumentPositionAdditionalReferencedDocument(): bool
    {
        $this->positionAddRefDocPointer = 0;

        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $addRefDocs = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getAdditionalReferencedDocument", []));

        return isset($addRefDocs[$this->positionAddRefDocPointer]);
    }

    /**
     * Seek to the next documents position additional referenced document
     * Returns true if the first position is available, otherwise false
     * You may use it together with getDocumentPositionAdditionalReferencedDocument
     *
     * @return boolean
     */
    public function nextDocumentPositionAdditionalReferencedDocument(): bool
    {
        $this->positionAddRefDocPointer++;

        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $addRefDocs = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getAdditionalReferencedDocument", []));

        return isset($addRefDocs[$this->positionAddRefDocPointer]);
    }

    /**
     * Get an additional Document reference on a position
     *
     * @param  string|null   $issuerassignedid
     * The identifier of the tender or lot to which the invoice relates, or an identifier specified by the seller for
     * an object on which the invoice is based, or an identifier of the document on which the invoice is based.
     * @param  string|null   $typecode
     * Type of referenced document (See codelist UNTDID 1001)
     *  - Code 916 "reference paper" is used to reference the identification of the document on which the invoice is based
     *  - Code 50 "Price / sales catalog response" is used to reference the tender or the lot
     *  - Code 130 "invoice data sheet" is used to reference an identifier for an object specified by the seller.
     * @param  string|null   $uriid
     * The Uniform Resource Locator (URL) at which the external document is available. A means of finding the resource
     * including the primary access method intended for it, e.g. http: // or ftp: //. The location of the external document
     * must be used if the buyer needs additional information to support the amounts billed. External documents are not part
     * of the invoice. Access to external documents can involve certain risks.
     * @param  string|null   $lineid
     * The referenced position identifier in the additional document
     * @param  string|null   $name
     * A description of the document, e.g. Hourly billing, usage or consumption report, etc.
     * @param  string|null   $reftypecode
     * The identifier for the identification scheme of the identifier of the item invoiced. If it is not clear to the
     * recipient which scheme is used for the identifier, an identifier of the scheme should be used, which must be selected
     * from UNTDID 1153 in accordance with the code list entries.
     * @param  DateTime|null $issueddate
     * The formatted date or date time for the issuance of this referenced Additional Document.
     * @return OrderDocumentReader
     */
    public function getDocumentPositionAdditionalReferencedDocument(?string &$issuerassignedid, ?string &$typecode, ?string &$uriid, ?string &$lineid, ?string &$name, ?string &$reftypecode, ?DateTime &$issueddate): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $additionalDocuments = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getAdditionalReferencedDocument", []));
        $additionalDocument = $this->objectHelper->getArrayIndex($additionalDocuments, $this->positionAddRefDocPointer);

        $issuerassignedid = $this->getInvoiceValueByPathFrom($additionalDocument, "getIssuerAssignedID", "");
        $typecode = $this->getInvoiceValueByPathFrom($additionalDocument, "getTypeCode", "");
        $uriid = $this->getInvoiceValueByPathFrom($additionalDocument, "getURIID", "");
        $lineid = $this->getInvoiceValueByPathFrom($additionalDocument, "getLineID.value", "");
        $name = $this->getInvoiceValueByPathFrom($additionalDocument, "getName", "");
        $reftypecode = $this->getInvoiceValueByPathFrom($additionalDocument, "getReferenceTypeCode", "");
        $issueddate = $this->objectHelper->toDateTime(
            $this->getInvoiceValueByPathFrom($additionalDocument, 'getFormattedIssueDateTime.getDateTimeString', ''),
            $this->getInvoiceValueByPathFrom($additionalDocument, 'getFormattedIssueDateTime.getDateTimeString.getFormat', '')
        );

        return $this;
    }

    /**
     * Get the binary data of an additional Document reference on a position
     *
     * @param  string|null $binarydatafilename
     * The fuill-qualified filename where the data where stored. If no binary data are
     * available, this value will be empty
     * @return OrderDocumentReader
     */
    public function getDocumentPositionAdditionalReferencedDocumentBinaryData(?string &$binarydatafilename): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $additionalDocuments = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getAdditionalReferencedDocument", []));
        $additionalDocument = $this->objectHelper->getArrayIndex($additionalDocuments, $this->positionAddRefDocPointer);

        $binarydatafilename = $this->getInvoiceValueByPathFrom($additionalDocument, "getAttachmentBinaryObject.getFilename", "");
        $binarydata = $this->getInvoiceValueByPathFrom($additionalDocument, "getAttachmentBinaryObject.value", "");

        if (StringUtils::stringIsNullOrEmpty($binarydatafilename) === false
            && StringUtils::stringIsNullOrEmpty($binarydata) === false
            && StringUtils::stringIsNullOrEmpty($this->binarydatadirectory) === false
        ) {
            $binarydatafilename = PathUtils::combinePathWithFile($this->binarydatadirectory, $binarydatafilename);
            FileUtils::base64ToFile($binarydata, $binarydatafilename);
        } else {
            $binarydatafilename = "";
        }

        return $this;
    }

    /**
     * Get details of the related buyer order position
     *
     * @param  string $buyerOrderRefLineId
     * An identifier for a position within an order placed by the buyer. Note: Reference is made to the order
     * reference at the document level.
     * @return OrderDocumentReader
     */
    public function getDocumentPositionBuyerOrderReferencedDocument(?string &$buyerOrderRefLineId): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $buyerOrderRefLineId = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getBuyerOrderReferencedDocument.getLineID.value", []);

        return $this;
    }

    /**
     * Get details of the related quotation position
     *
     * @param  string|null   $quotationRefId
     * The quotation document referenced in this line trade agreement
     * @param  string|null   $quotationRefLineId
     * The unique identifier of a line in this Quotation referenced document
     * @param  DateTime|null $quotationRefDate
     * The formatted date or date time for the issuance of this referenced Quotation.
     * @return OrderDocumentReader
     */
    public function getDocumentPositionQuotationReferencedDocument(?string &$quotationRefId, ?string &$quotationRefLineId, ?DateTime &$quotationRefDate): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $quotationRefId = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getQuotationReferencedDocument.getIssuerAssignedID", []);
        $quotationRefLineId = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getQuotationReferencedDocument.getLineID.value", []);
        $quotationRefDate = $this->objectHelper->toDateTime(
            $this->getInvoiceValueByPathFrom($tradeLineItem, 'getSpecifiedLineTradeAgreement.getQuotationReferencedDocument.getFormattedIssueDateTime.getDateTimeString', ''),
            $this->getInvoiceValueByPathFrom($tradeLineItem, 'getSpecifiedLineTradeAgreement.getQuotationReferencedDocument.getFormattedIssueDateTime.getDateTimeString.getFormat', '')
        );

        return $this;
    }

    /**
     * Get the unit price excluding sales tax before deduction of the discount on the item price.
     *
     * @param  float|null  $chargeAmount
     * The unit price excluding sales tax before deduction of the discount on the item price.
     * Note: If the price is shown according to the net calculation, the price must also be shown
     * according to the gross calculation.
     * @param  float|null  $basisQuantity
     * The number of item units for which the price applies (price base quantity)
     * @param  string|null $basisQuantityUnitCode
     * The unit code of the number of item units for which the price applies (price base quantity)
     * @return OrderDocumentReader
     */
    public function getDocumentPositionGrossPrice(?float &$chargeAmount, ?float &$basisQuantity, ?string &$basisQuantityUnitCode): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $chargeAmount = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getGrossPriceProductTradePrice.getChargeAmount.value", 0.0);
        $basisQuantity = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getGrossPriceProductTradePrice.getBasisQuantity.value", 0.0);
        $basisQuantityUnitCode = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getGrossPriceProductTradePrice.getBasisQuantity.getUnitCode", "");

        return $this;
    }

    /**
     * Seek to the first documents position gross price allowance/charge position
     * Returns true if the first gross price allowance/charge position is available, otherwise false
     * You may use it together with getDocumentPositionGrossPriceAllowanceCharge
     *
     * @return boolean
     */
    public function firstDocumentPositionGrossPriceAllowanceCharge(): bool
    {
        $this->positionGrossPriceAllowanceChargePointer = 0;

        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $allowanceCharges = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getGrossPriceProductTradePrice.getAppliedTradeAllowanceCharge", []));

        return isset($allowanceCharges[$this->positionGrossPriceAllowanceChargePointer]);
    }

    /**
     * Seek to the next documents position gross price allowance/charge position
     * Returns true if a other gross price allowance/charge position is available, otherwise false
     * You may use it together with getDocumentPositionGrossPriceAllowanceCharge
     *
     * @return boolean
     */
    public function nextDocumentPositionGrossPriceAllowanceCharge(): bool
    {
        $this->positionGrossPriceAllowanceChargePointer++;

        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $allowanceCharges = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getGrossPriceProductTradePrice.getAppliedTradeAllowanceCharge", []));

        return isset($allowanceCharges[$this->positionGrossPriceAllowanceChargePointer]);
    }

    /**
     * Get detailed information on surcharges and discounts
     *
     * @param  float|null   $actualAmount
     * Discount on the item price. The total discount subtracted from the gross price to calculate the
     * net price. Note: Only applies if the discount is given per unit and is not included in the gross price.
     * @param  boolean|null $isCharge
     * Switch for surcharge/discount, if true then its an charge
     * @param  float|null   $calculationPercent
     * Discount/surcharge in percent. Up to level EN16931, only the final result of the discount (ActualAmount)
     * is transferred
     * @param  float|null   $basisAmount
     * Base amount of the discount/surcharge
     * @param  string|null  $reason
     * Reason for surcharge/discount (free text)
     * @param  string|null  $taxTypeCode
     * @param  string|null  $taxCategoryCode
     * @param  float|null   $rateApplicablePercent
     * @param  float|null   $sequence
     * @param  float|null   $basisQuantity
     * @param  string|null  $basisQuantityUnitCode
     * @param  string|null  $reasonCode
     * @return OrderDocumentReader
     */
    public function getDocumentPositionGrossPriceAllowanceCharge(?float &$actualAmount, ?bool &$isCharge, ?float &$calculationPercent, ?float &$basisAmount, ?string &$reason, ?string &$taxTypeCode, ?string &$taxCategoryCode, ?float &$rateApplicablePercent, ?float &$sequence, ?float &$basisQuantity, ?string &$basisQuantityUnitCode, ?string &$reasonCode): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $allowanceCharges = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getGrossPriceProductTradePrice.getAppliedTradeAllowanceCharge", []));
        $allowanceCharge = $this->objectHelper->getArrayIndex($allowanceCharges, $this->positionGrossPriceAllowanceChargePointer);

        $actualAmount = $this->getInvoiceValueByPathFrom($allowanceCharge, "getActualAmount.value", 0.0);
        $isCharge = $this->getInvoiceValueByPathFrom($allowanceCharge, "getChargeIndicator.getIndicator", false);
        $calculationPercent = $this->getInvoiceValueByPathFrom($allowanceCharge, "getCalculationPercent.value", 0.0);
        $basisAmount = $this->getInvoiceValueByPathFrom($allowanceCharge, "getBasisAmount.value", 0.0);
        $reason = $this->getInvoiceValueByPathFrom($allowanceCharge, "getReason", "");
        $taxTypeCode = $this->getInvoiceValueByPathFrom($allowanceCharge, "getCategoryTradeTax.getTypeCode", "");
        $taxCategoryCode = $this->getInvoiceValueByPathFrom($allowanceCharge, "getCategoryTradeTax.getCategoryCode", "");
        $rateApplicablePercent = $this->getInvoiceValueByPathFrom($allowanceCharge, "getCategoryTradeTax.getRateApplicablePercent.value", 0.0);
        $sequence = $this->getInvoiceValueByPathFrom($allowanceCharge, "getSequenceNumeric.value", 0.0);
        $basisQuantity = $this->getInvoiceValueByPathFrom($allowanceCharge, "getBasisQuantity.value", 0.0);
        $basisQuantityUnitCode = $this->getInvoiceValueByPathFrom($allowanceCharge, "getBasisQuantity.getUnitCode", "");
        $reasonCode = $this->getInvoiceValueByPathFrom($allowanceCharge, "getReasonCode", "");

        return $this;
    }

    /**
     * Get detailed information on the net price of the item
     *
     * @param  float       $chargeAmount
     * The price of an item, exclusive of VAT, after subtracting item price discount.
     * The Item net price has to be equal with the Item gross price less the Item price discount.
     * @param  float|null  $basisQuantity
     * The number of item units to which the price applies.
     * @param  string|null $basisQuantityUnitCode
     * The unit of measure that applies to the Item price base quantity.
     * The Item price base quantity unit of measure shall be the same as the requested quantity unit of measure.
     * @return OrderDocumentReader
     */
    public function getDocumentPositionNetPrice(?float &$chargeAmount, ?float &$basisQuantity, ?string &$basisQuantityUnitCode): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $chargeAmount = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getNetPriceProductTradePrice.getChargeAmount.value", 0.0);
        $basisQuantity = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getNetPriceProductTradePrice.getBasisQuantity.value", 0.0);
        $basisQuantityUnitCode = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getNetPriceProductTradePrice.getBasisQuantity.getUnitCode", "");

        return $this;
    }

    /**
     * Tax included for B2C on position level
     *
     * @param  string      $categoryCode
     * Coded description of a sales tax category
     * The code specifying the category to which this trade related tax, levy or duty applies, such as codes
     * for "Exempt from Tax", "Standard Rate", "Free Export Item - Tax Not Charged". Reference United Nations Code List (UNCL) 5305.
     * The following entries of UNTDID 5305  are used (further clarification between brackets):
     *  - Standard rate (Liable for  TAX in a standard way)
     *  - Zero rated goods (Liable for TAX with a percentage rate of zero)
     *  - Exempt from tax
     *  - VAT Reverse Charge (Reverse charge VAT/IGIC/IPSI rules apply)
     *  - VAT exempt for intra community supply of goods (VAT/IGIC/IPSI not levied due to Intra-community supply rules)
     *  - Free export item, tax not charged
     *  - Services outside scope of tax (Sale is not subject to TAX)
     *  - Canary Islands General Indirect Tax (Liable for IGIC tax)
     *  - Liable for IPSI (Ceuta/Melilla tax)
     * @param  string      $typeCode
     * The code specifying the type of trade related tax, levy or duty, such as a code for a Value Added Tax (VAT).
     * Reference United Nations Code List (UNCL) 5153
     * Value = VAT for VAT, ENV for Environmental, EXC for excise duty
     * @param  float|null  $rateApplicablePercent
     * The VAT rate, represented as percentage that applies to the ordered item.
     * @param  float|null  $calculatedAmount
     * A monetary value resulting from the calculation of this trade related tax, levy or duty.
     * @param  string|null $exemptionReason
     * Reason for tax exemption (free text)
     * @param  string|null $exemptionReasonCode
     * Reason given in code form for the exemption of the amount from VAT. Note: Code list issued
     * and maintained by the Connecting Europe Facility.
     * @return OrderDocumentReader
     */
    public function getDocumentPositionNetPriceTax(?string &$categoryCode, ?string &$typeCode, ?float &$rateApplicablePercent, ?float &$calculatedAmount, ?string &$exemptionReason, ?string &$exemptionReasonCode): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $categoryCode = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getNetPriceProductTradePrice.getIncludedTradeTax.getCategoryCode.value", "");
        $typeCode = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getNetPriceProductTradePrice.getIncludedTradeTax.getTypeCode.value", "");
        $rateApplicablePercent = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getNetPriceProductTradePrice.getIncludedTradeTax.getRateApplicablePercent.value", 0.0);
        $calculatedAmount = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getNetPriceProductTradePrice.getIncludedTradeTax.getCalculatedAmount.value", 0.0);
        $exemptionReason = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getNetPriceProductTradePrice.getIncludedTradeTax.getExemptionReason.value", "");
        $exemptionReasonCode = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getNetPriceProductTradePrice.getIncludedTradeTax.getExemptionReasonCode.value", "");

        return $this;
    }

    /**
     * Get the Referenced Catalog ID applied to this line
     *
     * @param  string|null   $catalogueRefId
     * Referenced Catalog ID applied to this line
     * @param  string|null   $catalogueRefLineId
     * Referenced Catalog LineID applied to this line
     * @param  DateTime|null $catalogueRefDate
     * The formatted date or date time for the issuance of this referenced Catalog.
     * @return OrderDocumentReader
     */
    public function getDocumentPositionCatalogueReferencedDocument(?string &$catalogueRefId, ?string &$catalogueRefLineId, ?DateTime &$catalogueRefDate): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $catalogueRefId = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getCatalogueReferencedDocument.getIssuerAssignedID.value", "");
        $catalogueRefLineId = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getCatalogueReferencedDocument.getLineID.value", "");
        $catalogueRefDate = $this->objectHelper->toDateTime(
            $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getCatalogueReferencedDocument.getFormattedIssueDateTime.getDateTimeString", ""),
            $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getCatalogueReferencedDocument.getFormattedIssueDateTime.getDateTimeString.getFormat", "")
        );

        return $this;
    }

    /**
     * Get details of a blanket order referenced document on position-level
     *
     * @param  string $blanketOrderRefLineId
     * The unique identifier of a line in the Blanket Order referenced document
     * @return OrderDocumentReader
     */
    public function getDocumentPositionBlanketOrderReferencedDocument(?string &$blanketOrderRefLineId): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $blanketOrderRefLineId = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getBlanketOrderReferencedDocument.getLineID.value", "");

        return $this;
    }

    /**
     * Seek to the first ultimate customer order referenced document (on line-level)
     * If an applicable document exists, this function will return true, otherwise false
     * You should use this together with OrderDocumentReader::getDocumentPositionUltimateCustomerOrderReferencedDocument
     *
     * @return boolean
     */
    public function firstDocumentPositionUltimateCustomerOrderReferencedDocument(): bool
    {
        $this->positionUltimateCustomerOrderRefDocPointer = 0;

        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $ultimateCustomerOrderRefDocs = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getUltimateCustomerOrderReferencedDocument", []));

        return isset($ultimateCustomerOrderRefDocs[$this->positionUltimateCustomerOrderRefDocPointer]);
    }

    /**
     * Seek to the next ultimate customer order referenced document (on line-level)
     * If another applicable document exists, this function will return true, otherwise false
     * You should use this together with OrderDocumentReader::getDocumentPositionUltimateCustomerOrderReferencedDocument
     *
     * @return boolean
     */
    public function nextDocumentPositionUltimateCustomerOrderReferencedDocument(): bool
    {
        $this->positionUltimateCustomerOrderRefDocPointer++;

        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $ultimateCustomerOrderRefDocs = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getUltimateCustomerOrderReferencedDocument", []));

        return isset($ultimateCustomerOrderRefDocs[$this->positionUltimateCustomerOrderRefDocPointer]);
    }

    /**
     * Get the ultimate customer order referenced document (on line level)
     * Use this together with OrderDocumentReader::firstDocumentPositionUltimateCustomerOrderReferencedDocument
     * and OrderDocumentReader::nextDocumentPositionUltimateCustomerOrderReferencedDocument
     *
     * @param  string|null   $ultimateCustomerOrderRefId
     * Ultimate Customer Order Referenced Doc ID applied to this line
     * @param  string|null   $ultimateCustomerOrderRefLineId
     * Ultimate Customer Order Referenced Doc LineID applied to this line
     * @param  DateTime|null $ultimateCustomerOrderRefDate
     * The formatted date or date time for the issuance of this Ultimate Customer Order Referenced Doc.
     * @return OrderDocumentReader
     */
    public function getDocumentPositionUltimateCustomerOrderReferencedDocument(?string &$ultimateCustomerOrderRefId, ?string &$ultimateCustomerOrderRefLineId, ?DateTime &$ultimateCustomerOrderRefDate): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $ultimateCustomerOrderRefDocs = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeAgreement.getUltimateCustomerOrderReferencedDocument", []));
        $ultimateCustomerOrderRefDoc = $this->objectHelper->getArrayIndex($ultimateCustomerOrderRefDocs, $this->documentUltimateCustomerOrderRefDocPointer);

        $ultimateCustomerOrderRefId = $this->getInvoiceValueByPathFrom($ultimateCustomerOrderRefDoc, 'getIssuerAssignedID', '');
        $ultimateCustomerOrderRefLineId = $this->getInvoiceValueByPathFrom($ultimateCustomerOrderRefDoc, 'getLineID.value', '');
        $ultimateCustomerOrderRefDate = $this->objectHelper->toDateTime(
            $this->getInvoiceValueByPathFrom($ultimateCustomerOrderRefDoc, 'getFormattedIssueDateTime.getDateTimeString', ''),
            $this->getInvoiceValueByPathFrom($ultimateCustomerOrderRefDoc, 'getFormattedIssueDateTime.getDateTimeString.getFormat', '')
        );

        return $this;
    }

    /**
     * Get the indication, at line level, of whether or not this trade delivery can be partially delivered.
     *
     * @param  boolean $partialDelivery
     * If TRUE partial delivery is allowed
     * @return OrderDocumentReader
     */
    public function getDocumentPositionPartialDelivery(?bool &$partialDelivery): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $partialDelivery = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeDelivery.getPartialDeliveryAllowedIndicator.getIndicator", false);

        return $this;
    }

    /**
     * Get the quantity, at line level, requested for this trade delivery.
     *
     * @param  float  $requestedQuantity
     * The quantity, at line level, requested for this trade delivery.
     * @param  string $requestedQuantityUnitCode
     * Unit Code for the requested quantity.
     * @return OrderDocumentReader
     */
    public function getDocumentPositionDeliverReqQuantity(?float &$requestedQuantity, ?string &$requestedQuantityUnitCode): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $requestedQuantity = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeDelivery.getRequestedQuantity.value", 0.0);
        $requestedQuantityUnitCode = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeDelivery.getRequestedQuantity.getUnitCode", "");

        return $this;
    }

    /**
     * Get the number of packages, at line level, in this trade delivery.
     *
     * @param  float  $packageQuantity
     * The number of packages, at line level, in this trade delivery.
     * @param  string $packageQuantityUnitCode
     * Unit Code for the package quantity.
     * @return OrderDocumentReader
     */
    public function getDocumentPositionDeliverPackageQuantity(?float &$packageQuantity, ?string &$packageQuantityUnitCode): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $packageQuantity = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeDelivery.getPackageQuantity.value", 0.0);
        $packageQuantityUnitCode = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeDelivery.getPackageQuantity.getUnitCode", "");

        return $this;
    }

    /**
     * Get the number of packages, at line level, in this trade delivery.
     *
     * @param  float  $perPackageQuantity
     * The number of packages, at line level, in this trade delivery.
     * @param  string $perPackageQuantityUnitCode
     * Unit Code for the package quantity.
     * @return OrderDocumentReader
     */
    public function getDocumentPositionDeliverPerPackageQuantity(?float &$perPackageQuantity, ?string &$perPackageQuantityUnitCode): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $perPackageQuantity = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeDelivery.getPerPackageUnitQuantity.value", 0.0);
        $perPackageQuantityUnitCode = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeDelivery.getPerPackageUnitQuantity.getUnitCode", "");

        return $this;
    }

    /**
     * Get the quantity, at line level, agreed for this trade delivery.
     *
     * @param  float  $agreedQuantity
     * The quantity, at line level, agreed for this trade delivery.
     * @param  string $agreedQuantityUnitCode
     * Unit Code for the package quantity.
     * @return OrderDocumentReader
     */
    public function getDocumentPositionDeliverAgreedQuantity(?float &$agreedQuantity, ?string &$agreedQuantityUnitCode): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $agreedQuantity = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeDelivery.getAgreedQuantity.value", 0.0);
        $agreedQuantityUnitCode = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeDelivery.getAgreedQuantity.getUnitCode", "");

        return $this;
    }

    /**
     * Seek to the first document position requested delivery supply chain event
     * Returns true if the first requested delivery supply chain event is available, otherwise false
     * You may use it together with getDocumentPositionRequestedDeliverySupplyChainEvent
     * (The Requested Date or Period on which Delivery is requested)
     *
     * @return boolean
     */
    public function firstDocumentPositionRequestedDeliverySupplyChainEvent(): bool
    {
        $this->positionRequestedDeliverySupplyChainEventPointer = 0;

        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $tradeLineRequestedDeliverySupplyChainEvent = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeDelivery.getRequestedDeliverySupplyChainEvent", []));

        return isset($tradeLineRequestedDeliverySupplyChainEvent[$this->positionRequestedDeliverySupplyChainEventPointer]);
    }

    /**
     * Seek to the next document position requested delivery supply chain event
     * Returns true if another requested delivery supply chain event is available, otherwise false
     * You may use it together with getDocumentPositionRequestedDeliverySupplyChainEvent
     * (The Requested Date or Period on which Delivery is requested)
     *
     * @return boolean
     */
    public function nextDocumentPositionRequestedDeliverySupplyChainEvent(): bool
    {
        $this->positionRequestedDeliverySupplyChainEventPointer++;

        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $tradeLineRequestedDeliverySupplyChainEvent = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeDelivery.getRequestedDeliverySupplyChainEvent", []));

        return isset($tradeLineRequestedDeliverySupplyChainEvent[$this->positionRequestedDeliverySupplyChainEventPointer]);
    }

    /**
     * Get the requested date or period on which delivery is requested (on position level)
     *
     * @param  DateTime|null $occurrenceDateTime
     * A Requested Date on which Delivery is requested
     * @param  DateTime|null $startDateTime
     * The Start Date of he Requested Period on which Delivery is requested
     * @param  DateTime|null $endDateTime
     * The End Date of he Requested Period on which Delivery is requested
     * @return OrderDocumentReader
     */
    public function getDocumentPositionRequestedDeliverySupplyChainEvent(?DateTime &$occurrenceDateTime, ?DateTime &$startDateTime, ?DateTime &$endDateTime): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $tradeLineRequestedDeliverySupplyChainEvents = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeDelivery.getRequestedDeliverySupplyChainEvent", []));
        $tradeLineRequestedDeliverySupplyChainEvent = $this->objectHelper->getArrayIndex($tradeLineRequestedDeliverySupplyChainEvents, $this->positionRequestedDeliverySupplyChainEventPointer);

        $occurrenceDateTime = $this->objectHelper->toDateTime(
            $this->getInvoiceValueByPathFrom($tradeLineRequestedDeliverySupplyChainEvent, "getOccurrenceDateTime.getDateTimeString", ""),
            $this->getInvoiceValueByPathFrom($tradeLineRequestedDeliverySupplyChainEvent, "getOccurrenceDateTime.getDateTimeString.getFormat", "")
        );
        $startDateTime = $this->objectHelper->toDateTime(
            $this->getInvoiceValueByPathFrom($tradeLineRequestedDeliverySupplyChainEvent, "getOccurrenceSpecifiedPeriod.getStartDateTime.getDateTimeString", ""),
            $this->getInvoiceValueByPathFrom($tradeLineRequestedDeliverySupplyChainEvent, "getOccurrenceSpecifiedPeriod.getStartDateTime.getDateTimeString.getFormat", "")
        );
        $endDateTime = $this->objectHelper->toDateTime(
            $this->getInvoiceValueByPathFrom($tradeLineRequestedDeliverySupplyChainEvent, "getOccurrenceSpecifiedPeriod.getEndDateTime.getDateTimeString", ""),
            $this->getInvoiceValueByPathFrom($tradeLineRequestedDeliverySupplyChainEvent, "getOccurrenceSpecifiedPeriod.getEndDateTime.getDateTimeString.getFormat", "")
        );

        return $this;
    }

    /**
     * Seek to the first document position requested despatch supply chain event
     * Returns true if the first requested despatch supply chain event is available, otherwise false
     * You may use it together with getDocumentPositionRequestedDespatchSupplyChainEvent
     * This is the Requested date or period on which pick up is requested (on position level)
     *
     * @return boolean
     */
    public function firstDocumentPositionRequestedDespatchSupplyChainEvent(): bool
    {
        $this->positionRequestedDespatchSupplyChainEventPointer = 0;

        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $tradeLineRequestedDespatchSupplyChainEvent = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeDelivery.getRequestedDespatchSupplyChainEvent", []));

        return isset($tradeLineRequestedDespatchSupplyChainEvent[$this->positionRequestedDespatchSupplyChainEventPointer]);
    }

    /**
     * Seek to the next document position requested despatch supply chain event
     * Returns true if another requested despatch supply chain event is available, otherwise false
     * You may use it together with getDocumentPositionRequestedDespatchSupplyChainEvent
     * This is the Requested date or period on which pick up is requested (on position level)
     *
     * @return boolean
     */
    public function nextDocumentPositionRequestedDespatchSupplyChainEvent(): bool
    {
        $this->positionRequestedDespatchSupplyChainEventPointer++;

        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $tradeLineRequestedDespatchSupplyChainEvent = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeDelivery.getRequestedDespatchSupplyChainEvent", []));

        return isset($tradeLineRequestedDespatchSupplyChainEvent[$this->positionRequestedDespatchSupplyChainEventPointer]);
    }

    /**
     * Get the requested date or period on which delivery is requested (on position level)
     *
     * @param  DateTime|null $occurrenceDateTime
     * A Requested Date on which Pick up is requested
     * @param  DateTime|null $startDateTime
     * The Start Date of he Requested Period on which Pick up is requested
     * @param  DateTime|null $endDateTime
     * The End Date of he Requested Period on which Pick up is requested
     * @return OrderDocumentReader
     */
    public function getDocumentPositionRequestedDespatchSupplyChainEvent(?DateTime &$occurrenceDateTime, ?DateTime &$startDateTime, ?DateTime &$endDateTime): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $tradeLineRequestedDespatchSupplyChainEvents = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeDelivery.getRequestedDespatchSupplyChainEvent", []));
        $tradeLineRequestedDespatchSupplyChainEvent = $this->objectHelper->getArrayIndex($tradeLineRequestedDespatchSupplyChainEvents, $this->positionRequestedDespatchSupplyChainEventPointer);

        $occurrenceDateTime = $this->objectHelper->toDateTime(
            $this->getInvoiceValueByPathFrom($tradeLineRequestedDespatchSupplyChainEvent, "getOccurrenceDateTime.getDateTimeString", ""),
            $this->getInvoiceValueByPathFrom($tradeLineRequestedDespatchSupplyChainEvent, "getOccurrenceDateTime.getDateTimeString.getFormat", "")
        );
        $startDateTime = $this->objectHelper->toDateTime(
            $this->getInvoiceValueByPathFrom($tradeLineRequestedDespatchSupplyChainEvent, "getOccurrenceSpecifiedPeriod.getStartDateTime.getDateTimeString", ""),
            $this->getInvoiceValueByPathFrom($tradeLineRequestedDespatchSupplyChainEvent, "getOccurrenceSpecifiedPeriod.getStartDateTime.getDateTimeString.getFormat", "")
        );
        $endDateTime = $this->objectHelper->toDateTime(
            $this->getInvoiceValueByPathFrom($tradeLineRequestedDespatchSupplyChainEvent, "getOccurrenceSpecifiedPeriod.getEndDateTime.getDateTimeString", ""),
            $this->getInvoiceValueByPathFrom($tradeLineRequestedDespatchSupplyChainEvent, "getOccurrenceSpecifiedPeriod.getEndDateTime.getDateTimeString.getFormat", "")
        );

        return $this;
    }

    /**
     * Seek to the first document position tax
     * Returns true if the first tax position is available, otherwise false
     * You may use it together with OrderDocumentReader::getDocumentPositionTax
     *
     * @return boolean
     */
    public function firstDocumentPositionTax(): bool
    {
        $this->positionTaxPointer = 0;

        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $taxes = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeSettlement.getApplicableTradeTax", []));

        return isset($taxes[$this->positionTaxPointer]);
    }

    /**
     * Seek to the next document position tax
     * Returns true if another tax position is available, otherwise false
     * You may use it together with OrderDocumentReader::getDocumentPositionTax
     *
     * @return boolean
     */
    public function nextDocumentPositionTax(): bool
    {
        $this->positionTaxPointer++;

        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $taxes = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeSettlement.getApplicableTradeTax", []));

        return isset($taxes[$this->positionTaxPointer]);
    }

    /**
     * Get information about the sales tax that applies to the goods and services invoiced
     * in the relevant invoice line
     *
     * @param  string      $categoryCode
     * Coded description of a sales tax category
     * The code specifying the category to which this trade related tax, levy or duty applies, such as codes
     * for "Exempt from Tax", "Standard Rate", "Free Export Item - Tax Not Charged". Reference United Nations Code List (UNCL) 5305.
     * The following entries of UNTDID 5305  are used (further clarification between brackets):
     *  - Standard rate (Liable for  TAX in a standard way)
     *  - Zero rated goods (Liable for TAX with a percentage rate of zero)
     *  - Exempt from tax
     *  - VAT Reverse Charge (Reverse charge VAT/IGIC/IPSI rules apply)
     *  - VAT exempt for intra community supply of goods (VAT/IGIC/IPSI not levied due to Intra-community supply rules)
     *  - Free export item, tax not charged
     *  - Services outside scope of tax (Sale is not subject to TAX)
     *  - Canary Islands General Indirect Tax (Liable for IGIC tax)
     *  - Liable for IPSI (Ceuta/Melilla tax)
     * @param  string      $typeCode
     * The code specifying the type of trade related tax, levy or duty, such as a code for a Value Added Tax (VAT).
     * Reference United Nations Code List (UNCL) 5153
     * Value = VAT for VAT, ENV for Environmental, EXC for excise duty
     * @param  float       $rateApplicablePercent
     * The VAT rate, represented as percentage that applies to the ordered item.
     * @param  float|null  $calculatedAmount
     * A monetary value resulting from the calculation of this trade related tax, levy or duty.
     * @param  string|null $exemptionReason
     * Reason for tax exemption (free text)
     * @param  string|null $exemptionReasonCode
     * Reason given in code form for the exemption of the amount from VAT. Note: Code list issued
     * and maintained by the Connecting Europe Facility.
     * @return OrderDocumentReader
     */
    public function getDocumentPositionTax(?string &$categoryCode, ?string &$typeCode, ?float &$rateApplicablePercent, ?float &$calculatedAmount, ?string &$exemptionReason, ?string &$exemptionReasonCode): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $taxes = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeSettlement.getApplicableTradeTax", []));
        $tax = $this->objectHelper->getArrayIndex($taxes, $this->positionTaxPointer);

        $categoryCode = $this->getInvoiceValueByPathFrom($tax, "getCategoryCode", "");
        $typeCode = $this->getInvoiceValueByPathFrom($tax, "getTypeCode", "");
        $rateApplicablePercent = $this->getInvoiceValueByPathFrom($tax, "getRateApplicablePercent.value", 0.0);
        $calculatedAmount = $this->getInvoiceValueByPathFrom($tax, "getCalculatedAmount.value", 0.0);
        $exemptionReason = $this->getInvoiceValueByPathFrom($tax, "getExemptionReason", "");
        $exemptionReasonCode = $this->getInvoiceValueByPathFrom($tax, "getExemptionReasonCode", "");

        return $this;
    }

    /**
     * Seek to the first allowance charge (on position level)
     * Returns true if the first position is available, otherwise false
     * You may use it together with OrderDocumentReader::getDocumentPositionAllowanceCharge
     *
     * @return boolean
     */
    public function firstDocumentPositionAllowanceCharge(): bool
    {
        $this->positionAllowanceChargePointer = 0;

        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $allowanceCharges = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeSettlement.getSpecifiedTradeAllowanceCharge", []));

        return isset($allowanceCharges[$this->positionAllowanceChargePointer]);
    }

    /**
     * Seek to the next allowance charge (on position level)
     * Returns true if another position is available, otherwise false
     * You may use it together with OrderDocumentReader::getDocumentPositionAllowanceCharge
     *
     * @return boolean
     */
    public function nextDocumentPositionAllowanceCharge(): bool
    {
        $this->positionAllowanceChargePointer++;

        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $allowanceCharges = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeSettlement.getSpecifiedTradeAllowanceCharge", []));

        return isset($allowanceCharges[$this->positionAllowanceChargePointer]);
    }

    /**
     * Get surcharges and discounts on position level
     *
     * @param  float       $actualAmount
     * The amount of an allowance, without VAT.
     * @param  boolean     $isCharge
     * Indicator indicating whether the following data is for a charge or an allowance.
     * @param  float|null  $calculationPercent
     * The percentage that may be used, in conjunction with the order line allowance base amount,
     * to calculate the order line allowance amount.
     * @param  float|null  $basisAmount
     * The base amount that may be used, in conjunction with the order line allowance percentage,
     * to calculate the order line allowance amount.
     * @param  string|null $reasonCode
     * The reason for the order line allowance, expressed as a code.
     * Use entries of the UNTDID 5189 code list. The order line level allowance reason code and the order
     * line level allowance reason shall indicate the same allowance reason.
     * @param  string|null $reason
     * The reason for the order line allowance, expressed as text.
     * @return OrderDocumentReader
     */
    public function getDocumentPositionAllowanceCharge(?float &$actualAmount, ?bool &$isCharge, ?float &$calculationPercent, ?float &$basisAmount, ?string &$reasonCode, ?string &$reason): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $allowanceCharges = $this->objectHelper->ensureArray($this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeSettlement.getSpecifiedTradeAllowanceCharge", []));
        $allowanceCharge = $this->objectHelper->getArrayIndex($allowanceCharges, $this->positionAllowanceChargePointer);

        $isCharge = $this->getInvoiceValueByPathFrom($allowanceCharge, "getChargeIndicator.getIndicator", false);
        $calculationPercent = $this->getInvoiceValueByPathFrom($allowanceCharge, "getCalculationPercent.value", 0.0);
        $basisAmount = $this->getInvoiceValueByPathFrom($allowanceCharge, "getBasisAmount.value", 0.0);
        $actualAmount = $this->getInvoiceValueByPathFrom($allowanceCharge, "getActualAmount.value", 0.0);
        $reasonCode = $this->getInvoiceValueByPathFrom($allowanceCharge, "getReasonCode", "");
        $reason = $this->getInvoiceValueByPathFrom($allowanceCharge, "getReason", "");

        return $this;
    }

    /**
     * Get detailed information on item totals
     *
     * @param  float      $lineTotalAmount
     * The total amount of the order line.
     * The amount is “net” without VAT, i.e. inclusive of line level allowances and charges as well as other relevant taxes.
     * @param  float|null $totalAllowanceChargeAmount
     * A monetary value of a total allowance and charge reported in this trade settlement line monetary summation.
     * The amount is “net” without VAT, i.e. inclusive of line level allowances and charges as well as other relevant taxes.
     * @return OrderDocumentReader
     */
    public function getDocumentPositionLineSummation(?float &$lineTotalAmount, ?float &$totalAllowanceChargeAmount): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $lineTotalAmount = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeSettlement.getSpecifiedTradeSettlementLineMonetarySummation.getLineTotalAmount.value", 0.0);
        $totalAllowanceChargeAmount = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeSettlement.getSpecifiedTradeSettlementLineMonetarySummation.getTotalAllowanceChargeAmount.value", 0.0);

        return $this;
    }

    /**
     * Set an AccountingAccount on position level
     *
     * @param  string      $id
     * The unique identifier for this trade accounting account.
     * @param  string|null $typeCode
     * The code specifying the type of trade accounting account, such as
     * general (main), secondary, cost accounting or budget account.
     * @return OrderDocumentReader
     */
    public function getDocumentPositionReceivableTradeAccountingAccount(?string &$id, ?string &$typeCode): OrderDocumentReader
    {
        $tradeLineItems = $this->getInvoiceValueByPath("getSupplyChainTradeTransaction.getIncludedSupplyChainTradeLineItem", []);
        $tradeLineItem = $this->objectHelper->getArrayIndex($tradeLineItems, $this->positionPointer);

        $id = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeSettlement.getReceivableSpecifiedTradeAccountingAccount.getID", "");
        $typeCode = $this->getInvoiceValueByPathFrom($tradeLineItem, "getSpecifiedLineTradeSettlement.getReceivableSpecifiedTradeAccountingAccount.getTypeCode", "");

        return $this;
    }

    /**
     * Function to return a value from $orderobject by path
     *
     * @codeCoverageIgnore
     *
     * @param  string $methods
     * @param  mixed  $defaultValue
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
     * @param  object|null $from
     * @param  string      $methods
     * @param  mixed       $defaultValue
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
     * @param  mixed $value
     * @param  array $methods
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
     * @param  mixed  $value
     * @param  string $methodKey
     * @param  string $methodValue
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
