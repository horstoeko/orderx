<?php

/**
 * This file is a part of horstoeko/orderx.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\orderx;

use DateTime;
use DOMDocument;
use DOMXPath;

/**
 * Class representing the document builder for outgoing documents
 *
 * @category Order-X
 * @package  Order-X
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/orderx
 */
class OrderDocumentBuilder extends OrderDocument
{
    /**
     * HeaderTradeAgreement
     *
     * @var object|null
     */
    protected $headerTradeAgreement = null;

    /**
     * HeaderTradeDelivery
     *
     * @var object|null
     */
    protected $headerTradeDelivery = null;

    /**
     * HeaderTradeSettlement
     *
     * @var object|null
     */
    protected $headerTradeSettlement = null;

    /**
     * SupplyChainTradeTransactionType
     *
     * @var object|null
     */
    protected $headerSupplyChainTradeTransaction = null;

    /**
     * Last added payment terms
     *
     * @var object|null
     */
    protected $currentPaymentTerms = null;

    /**
     * Last added position (line) to the docuemnt
     *
     * @var object|null
     */
    protected $currentPosition = null;

    /**
     * Constructor
     *
     * @codeCoverageIgnore
     * @param int $profile
     */
    public function __construct(int $profile)
    {
        parent::__construct($profile);

        $this->initNewDocument();
    }

    /**
     * Creates a new OrderDocumentBuilder with profile $profile
     *
     * @codeCoverageIgnore
     *
     * @param integer $profile
     * @return OrderDocumentBuilder
     */
    public static function createNew(int $profile): OrderDocumentBuilder
    {
        return (new self($profile));
    }

    /**
     * Initialized a new document with profile settings
     *
     * @return OrderDocumentBuilder
     */
    public function initNewDocument(): OrderDocumentBuilder
    {
        $this->orderObject = $this->objectHelper->getOrderX();
        $this->headerTradeAgreement = $this->orderObject->getSupplyChainTradeTransaction()->getApplicableHeaderTradeAgreement();
        $this->headerTradeDelivery = $this->orderObject->getSupplyChainTradeTransaction()->getApplicableHeaderTradeDelivery();
        $this->headerTradeSettlement = $this->orderObject->getSupplyChainTradeTransaction()->getApplicableHeaderTradeSettlement();
        $this->headerSupplyChainTradeTransaction = $this->orderObject->getSupplyChainTradeTransaction();

        return $this;
    }

    /**
     * This method can be overridden in derived class
     * It is called before a XML is written
     *
     * @return void
     */
    protected function onBeforeGetContent()
    {
        // Do nothing
    }

    /**
     * Write the content of a Oder object to a string
     *
     * @return string
     */
    public function getContent(): string
    {
        $this->onBeforeGetContent();

        return $this->serializer->serialize($this->orderObject, 'xml');
    }

    /**
     * Write the content of a Oder object to a DOMDocument instance
     *
     * @return DOMDocument
     */
    public function getContentAsDomDocument(): DOMDocument
    {
        $domDocument = new DOMDocument();
        $domDocument->loadXML($this->getContent());

        return $domDocument;
    }

    /**
     * Write the content of a Oder object to a DOMXpath instance
     *
     * @return DOMXpath
     */
    public function getContentAsDomXPath(): DOMXpath
    {
        $domXPath = new DOMXPath($this->getContentAsDomDocument());

        return $domXPath;
    }

    /**
     * Write the content of a Order object to a file
     *
     * @param string $xmlfilename
     * @return OrderDocument
     */
    public function writeFile(string $xmlfilename): OrderDocument
    {
        file_put_contents($xmlfilename, $this->getContent());
        return $this;
    }

    /**
     * Set main information about this document
     *
     * @param string $documentNo
     * An identifier of a referenced purchase order, issued by the Buyer.
     * @param string $documentTypeCode
     * A code specifying the functional type of the Order
     * Commercial orders and credit notes are defined according the entries in UNTDID 1001
     * Other entries of UNTDID 1001  with specific orders may be used if applicable.
     *  - 220 for Order
     *  - 230 for Order Change
     *  - 231 for Order Response
     * @param DateTime $documentDate
     * The date when the document was issued by the buyer
     * @param string $documentCurrency
     * The code for the order currency
     * @param string|null $documentName
     * The document type (free text)
     * @param string|null $documentLanguageId
     * A unique identifier for a language used in this exchanged document
     * @param DateTime|null $documentEffectiveSpecifiedPeriod
     * The specified period within which this exchanged document is effective
     * @param string|null $documentPurposeCode
     * The purpose, expressed as text, of this exchanged document
     * -  7 : Duplicate
     * -  9 : Original
     * - 35 : Retransmission
     * @param string|null $documentRequestedResponseTypeCode
     * A code specifying a type of response requested for this exchanged document
     * Value = AC to request an Order_Response
     * @return OrderDocumentBuilder
     */
    public function setDocumentInformation(string $documentNo, string $documentTypeCode, DateTime $documentDate, string $documentCurrency, ?string $documentName = null, ?string $documentLanguageId = null, ?DateTime $documentEffectiveSpecifiedPeriod = null, ?string $documentPurposeCode = null, ?string $documentRequestedResponseTypeCode = null): OrderDocumentBuilder
    {
        $this->objectHelper->tryCall($this, "setDocumentNo", $documentNo);
        $this->objectHelper->tryCall($this, "setDocumentTypeCode", $documentTypeCode);
        $this->objectHelper->tryCall($this, "setDocumentDate", $documentDate);
        $this->objectHelper->tryCall($this, "setDocumentCurrency", $documentCurrency);
        $this->objectHelper->tryCall($this, "setDocumentName", $documentName);
        $this->objectHelper->tryCall($this, "setDocumentLanguageId", $documentLanguageId);
        $this->objectHelper->tryCall($this, "setDocumentPurposeCode", $documentPurposeCode);
        $this->objectHelper->tryCall($this, "setDocumentRequestedResponseTypeCode", $documentRequestedResponseTypeCode);
        $this->objectHelper->tryCall2($this, "setDocumentEffectiveSpecifiedPeriod", $documentEffectiveSpecifiedPeriod, $documentEffectiveSpecifiedPeriod);

        return $this;
    }

    /**
     * Short-Hand function for setting the document no.
     *
     * @param string $documentNo
     * @return OrderDocumentBuilder
     */
    public function setDocumentNo(string $documentNo): OrderDocumentBuilder
    {
        $this->objectHelper->tryCall($this->orderObject->getExchangedDocument(), "setID", $this->objectHelper->getIdType($documentNo));
        return $this;
    }

    /**
     * Short-Hand function for setting the document's name
     *
     * @param string $documentName
     * @return OrderDocumentBuilder
     */
    public function setDocumentName(string $documentName): OrderDocumentBuilder
    {
        $this->objectHelper->tryCall($this->orderObject->getExchangedDocument(), "setName", $this->objectHelper->getTextType($documentName));
        return $this;
    }

    /**
     * Short-Hand function for setting the document type
     *
     * @param string $documentTypeCode
     * @return OrderDocumentBuilder
     */
    public function setDocumentTypeCode(string $documentTypeCode): OrderDocumentBuilder
    {
        $this->objectHelper->tryCall($this->orderObject->getExchangedDocument(), "setTypeCode", $this->objectHelper->getCodeType($documentTypeCode));
        return $this;
    }

    /**
     * Short-Hand function for setting the document date
     *
     * @param DateTime $documentDate
     * @return OrderDocumentBuilder
     */
    public function setDocumentDate(DateTime $documentDate): OrderDocumentBuilder
    {
        $this->objectHelper->tryCall($this->orderObject->getExchangedDocument(), "setIssueDateTime", $this->objectHelper->getDateTimeType($documentDate));
        return $this;
    }

    /**
     * Short-Hand function for setting the document's currency
     *
     * @param string $documentCurrency
     * @return OrderDocumentBuilder
     */
    public function setDocumentCurrency(string $documentCurrency): OrderDocumentBuilder
    {
        $this->objectHelper->tryCall($this->headerTradeSettlement, "setOrderCurrencyCode", $this->objectHelper->getIdType($documentCurrency));
        return $this;
    }

    /**
     * Short-Hand function for setting the document's language
     *
     * @param string $documentLanguageId
     * @return OrderDocumentBuilder
     */
    public function setDocumentLanguageId(string $documentLanguageId): OrderDocumentBuilder
    {
        $this->objectHelper->tryCallIfMethodExists(
            $this->orderObject->getExchangedDocument(),
            "addToLanguageID",
            "setLanguageID",
            [$this->objectHelper->getIdType($documentLanguageId)],
            $this->objectHelper->getIdType($documentLanguageId)
        );
        return $this;
    }

    /**
     * Short-Hand function for setting the specified period within which this
     * exchanged document is effective
     *
     * @param DateTime $effectiveSpecifiedPeriodFrom
     * Effective Document Period Start Date (Order)
     * @param DateTime $effectiveSpecifiedPeriodTo
     * Effective Document Period End Date (Order)
     * @return OrderDocumentBuilder
     */
    public function setDocumentEffectiveSpecifiedPeriod(DateTime $effectiveSpecifiedPeriodFrom, DateTime $effectiveSpecifiedPeriodTo): OrderDocumentBuilder
    {
        $this->objectHelper->tryCall($this->orderObject->getExchangedDocument(), "setEffectiveSpecifiedPeriod", $this->objectHelper->getSpecifiedPeriodType($effectiveSpecifiedPeriodFrom, $effectiveSpecifiedPeriodTo));
        return $this;
    }

    /**
     * Short-Hand function for setting the doocument's purpose code
     *
     * @param string $documentPurposeCode
     * The purpose, expressed as text, of this exchanged document.
     * -  7 : Duplicate
     * -  9 : Original
     * - 35 : Retransmission
     * @return OrderDocumentBuilder
     */
    public function setDocumentPurposeCode(string $documentPurposeCode): OrderDocumentBuilder
    {
        $this->objectHelper->tryCall($this->orderObject->getExchangedDocument(), "setPurposeCode", $this->objectHelper->getCodeType($documentPurposeCode));
        return $this;
    }

    /**
     * Short-Hand function for setting a code specifying a type of response
     * requested for this exchanged document.
     *
     * @param string $documentRequestedResponseTypeCode
     * Value = AC to request an Order_Response
     * @return OrderDocumentBuilder
     */
    public function setDocumentRequestedResponseTypeCode(string $documentRequestedResponseTypeCode): OrderDocumentBuilder
    {
        $this->objectHelper->tryCall($this->orderObject->getExchangedDocument(), "setRequestedResponseTypeCode", $this->objectHelper->getCodeType($documentRequestedResponseTypeCode));
        return $this;
    }

    /**
     * Set the documents business process specified document ontext parameter
     *
     * @param string $businessProcessSpecifiedDocumentContextParameter
     * Identifies the business process context in which the transaction appears,
     * to enable the Buyer to process the Order in an appropriate way.
     *
     * @return OrderDocumentBuilder
     */
    public function setDocumentBusinessProcessSpecifiedDocumentContextParameter(string $businessProcessSpecifiedDocumentContextParameter): OrderDocumentBuilder
    {
        $this->objectHelper->tryCall($this->orderObject->getExchangedDocumentContext(), "setBusinessProcessSpecifiedDocumentContextParameter", $this->objectHelper->getDocumentContextParameterType($businessProcessSpecifiedDocumentContextParameter));
        return $this;
    }

    /**
     * Mark document as a copy from the original one
     *
     * @param boolean|null $isDocumentCopy
     * Is document a copy. If this parameter is not submitted the true is suggested
     * @return OrderDocumentBuilder
     */
    public function setIsDocumentCopy(?bool $isDocumentCopy = null): OrderDocumentBuilder
    {
        $this->objectHelper->tryCall($this->orderObject->getExchangedDocument(), "setCopyIndicator", $this->objectHelper->getIndicatorType($isDocumentCopy ?? true));
        return $this;
    }

    /**
     * Mark document as a test document
     *
     * @param boolean|null $isTestDocument
     * Is document a test. If this parameter is not submitted the true is suggested
     * @return OrderDocumentBuilder
     */
    public function setIsTestDocument(?bool $isTestDocument = null): OrderDocumentBuilder
    {
        $this->objectHelper->tryCall($this->orderObject->getExchangedDocumentContext(), "setTestIndicator", $this->objectHelper->getIndicatorType($isTestDocument ?? true));
        return $this;
    }

    /**
     * Add a note to the docuzment
     *
     * @param string $content Free text on the order
     * A textual note that gives unstructured information that is relevant to the order as a whole.
     * @param string|null $subjectCode Code to qualify the free text for the order
     * The subject of the textual note in BT-22. To be chosen from the entries in UNTDID 4451
     * @param string|null $contentCode Code to qualify the free text for the order
     * To be chosen from the entries in UNTDID xxx
     * @return OrderDocumentBuilder
     */
    public function addDocumentNote(string $content, ?string $subjectCode = null, ?string $contentCode = null): OrderDocumentBuilder
    {
        $note = $this->objectHelper->getNoteType($content, $contentCode, $subjectCode);
        $this->objectHelper->tryCall($this->orderObject->getExchangedDocument(), "addToIncludedNote", $note);
        return $this;
    }

    /**
     * Set the document money summation
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
     * @return OrderDocumentBuilder
     */
    public function setDocumentSummation(float $lineTotalAmount, ?float $grandTotalAmount = null, ?float $chargeTotalAmount = null, ?float $allowanceTotalAmount = null, ?float $taxBasisTotalAmount = null, ?float $taxTotalAmount = null): OrderDocumentBuilder
    {
        $summation = $this->objectHelper->getTradeSettlementHeaderMonetarySummationType($grandTotalAmount, $lineTotalAmount, $chargeTotalAmount, $allowanceTotalAmount, $taxBasisTotalAmount, $taxTotalAmount);
        $this->objectHelper->tryCall($this->headerTradeSettlement, "setSpecifiedTradeSettlementHeaderMonetarySummation", $summation);
        $taxTotalAmount = $this->objectHelper->tryCallAndReturn($summation, "getTaxTotalAmount");
        $orderCurrencyCode = $this->objectHelper->tryCallByPathAndReturn($this->headerTradeSettlement, "getOrderCurrencyCode.value");
        if (isset($this->objectHelper->ensureArray($taxTotalAmount)[0])) {
            $this->objectHelper->tryCall($this->objectHelper->ensureArray($taxTotalAmount)[0], 'setCurrencyID', $orderCurrencyCode);
        }
        return $this;
    }

    /**
     * Set the identifier defined by the Buyer (e.g. contact ID, department, office id, project code).
     *
     * @param string $buyerreference
     * An identifier assigned by the Buyer used for internal routing purposes
     * @return OrderDocumentBuilder
     */
    public function setDocumentBuyerReference(string $buyerreference): OrderDocumentBuilder
    {
        $reference = $this->objectHelper->getTextType($buyerreference);
        $this->objectHelper->tryCall($this->headerTradeAgreement, "setBuyerReference", $reference);
        return $this;
    }

    /**
     * Detailed information about the seller (=service provider)
     *
     * @param string $name
     * The full formal name by which the party is registered in the national registry of
     * legal entities or as a Taxable person or otherwise trades as a person or persons.
     * @param string|null $id
     * An identification of the Party. The identification scheme identifier of the Party identifier.
     * @param string|null $description
     * Additional legal information relevant for the Paety.
     * @return OrderDocumentBuilder
     */
    public function setDocumentSeller(string $name, ?string $id = null, ?string $description = null): OrderDocumentBuilder
    {
        $sellerTradeParty = $this->objectHelper->getTradeParty($name, $id, $description);
        $this->objectHelper->tryCall($this->headerTradeAgreement, "setSellerTradeParty", $sellerTradeParty);
        return $this;
    }

    /**
     * Add a global id for the seller
     *
     * __Notes__
     *
     * - The Seller's ID identification scheme is a unique identifier
     *   assigned to a seller by a global registration organization
     *
     * @param string $globalID
     * GloablID, if global identifier exists and can be stated in $globalIDType, ID else
     * @param string $globalIDType
     * If used, the identification scheme identifier shall be chosen from the entries of the list published
     * by the ISO/IEC 6523 maintenance agency
     * @return OrderDocumentBuilder
     */
    public function addDocumentSellerGlobalId(string $globalID, string $globalIDType): OrderDocumentBuilder
    {
        $sellerTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeAgreement, "getSellerTradeParty");
        $this->objectHelper->tryCall($sellerTradeParty, "addToGlobalID", $this->objectHelper->getIdType($globalID, $globalIDType));
        return $this;
    }

    /**
     * Set the seller's tax information
     *
     * @param string $taxregtype
     * Scheme identifier for supplier VAT identifier
     * @param string $taxregid
     * The Seller's VAT identifier (also known as Seller VAT identification number). VAT number prefixed by a country code.
     * A VAT registered Supplier shall include his VAT ID, except when he uses a tax representative.
     * @return OrderDocumentBuilder
     */
    public function setDocumentSellerTaxRegistration(string $taxregtype, string $taxregid): OrderDocumentBuilder
    {
        $sellerTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeAgreement, "getSellerTradeParty");
        $taxreg = $this->objectHelper->getTaxRegistrationType($taxregtype, $taxregid);
        $this->objectHelper->tryCallIfMethodExists($sellerTradeParty, "addToSpecifiedTaxRegistration", "setSpecifiedTaxRegistration", [$taxreg], $taxreg);
        return $this;
    }

    /**
     * Add additional tax information for the seller
     *
     * @param string $taxregtype
     * Scheme identifier for supplier VAT identifier
     * @param string $taxregid
     * The Seller's VAT identifier (also known as Seller VAT identification number). VAT number prefixed by a country code.
     * A VAT registered Supplier shall include his VAT ID, except when he uses a tax representative.
     * @return OrderDocumentBuilder
     */
    public function addDocumentSellerTaxRegistration(string $taxregtype, string $taxregid): OrderDocumentBuilder
    {
        $sellerTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeAgreement, "getSellerTradeParty");
        $taxreg = $this->objectHelper->getTaxRegistrationType($taxregtype, $taxregid);
        $this->objectHelper->tryCall($sellerTradeParty, "addToSpecifiedTaxRegistration", $taxreg);
        return $this;
    }

    /**
     * Sets detailed information on the business address of the seller
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
     * The identifier for an addressable group of properties according to the relevant postal service.
     * @param string|null $city
     * Usual name of the city or municipality in which the seller's address is located
     * @param string|null $country
     * Code used to identify the country. If no tax agent is specified, this is the country in which the sales tax
     * is due. The lists of approved countries are maintained by the EN ISO 3166-1 Maintenance Agency “Codes for the
     * representation of names of countries and their subdivisions”
     * If no tax representative is specified, this is the country where VAT is liable. The lists of valid countries
     * are registered with the EN ISO 3166-1 Maintenance agency, “Codes for the representation of names of countries
     * and their subdivisions”.
     * @param string|null $subdivision
     * The subdivision of a country.
     * @return OrderDocumentBuilder
     */
    public function setDocumentSellerAddress(?string $lineone = null, ?string $linetwo = null, ?string $linethree = null, ?string $postcode = null, ?string $city = null, ?string $country = null, ?string $subdivision = null): OrderDocumentBuilder
    {
        $sellerTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeAgreement, "getSellerTradeParty");
        $address = $this->objectHelper->getTradeAddress($lineone, $linetwo, $linethree, $postcode, $city, $country, $subdivision);
        $this->objectHelper->tryCall($sellerTradeParty, "setPostalTradeAddress", $address);
        return $this;
    }

    /**
     * Set Organization details
     *
     * @param string|null $legalorgid
     * An identifier issued by an official registrar that identifies the
     * seller as a legal entity or legal person. If no identification scheme ($legalorgtype) is provided,
     * it should be known to the buyer and seller
     * @param string|null $legalorgtype
     * The identification scheme identifier of the Seller legal registration identifier.
     * If used, the identification scheme identifier shall be chosen from the entries of the list published
     * by the ISO/IEC 6523 maintenance agency.
     * @param string|null $legalorgname
     * A name by which the seller is known, if different from the seller's name (also known as
     * the company name). Note: This may be used if different from the seller's name.
     * @return OrderDocumentBuilder
     */
    public function setDocumentSellerLegalOrganisation(?string $legalorgid = null, ?string $legalorgtype = null, ?string $legalorgname = null): OrderDocumentBuilder
    {
        $sellerTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeAgreement, "getSellerTradeParty");
        $legalorg = $this->objectHelper->getLegalOrganization($legalorgid, $legalorgtype, $legalorgname);
        $this->objectHelper->tryCall($sellerTradeParty, "setSpecifiedLegalOrganization", $legalorg);
        return $this;
    }

    /**
     * Set detailed information on the seller's contact person
     *
     * @param string|null $contactpersonname
     * Contact point for a legal entity, such as a personal name of the contact person
     * @param string|null $contactdepartmentname
     * Contact point for a legal entity, such as a name of the department or office
     * @param string|null $contactphoneno
     * A phone number for the contact point.
     * @param string|null $contactfaxno
     * A fax number for the contact point.
     * @param string|null $contactemailadd
     * An e-mail address for the contact point.
     * @param string|null $contacttypecode
     * The code specifying the type of trade contact. To be chosen from the entries in UNTDID 3139
     * @return OrderDocumentBuilder
     */
    public function setDocumentSellerContact(?string $contactpersonname = null, ?string $contactdepartmentname = null, ?string $contactphoneno = null, ?string $contactfaxno = null, ?string $contactemailadd = null, ?string $contacttypecode = null): OrderDocumentBuilder
    {
        $sellerTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeAgreement, "getSellerTradeParty");
        $contact = $this->objectHelper->getTradeContact($contactpersonname, $contactdepartmentname, $contactphoneno, $contactfaxno, $contactemailadd, $contacttypecode);
        $this->objectHelper->tryCallIfMethodExists($sellerTradeParty, "addToDefinedTradeContact", "setDefinedTradeContact", [$contact], $contact);
        return $this;
    }

    /**
     * Add additional detailed information on the seller's contact person.
     *
     * @param string|null $contactpersonname
     * Contact point for a legal entity, such as a personal name of the contact person
     * @param string|null $contactdepartmentname
     * Contact point for a legal entity, such as a name of the department or office
     * @param string|null $contactphoneno
     * A phone number for the contact point.
     * @param string|null $contactfaxno
     * A fax number for the contact point.
     * @param string|null $contactemailadd
     * An e-mail address for the contact point.
     * @param string|null $contacttypecode
     * The code specifying the type of trade contact. To be chosen from the entries in UNTDID 3139
     * @return OrderDocumentBuilder
     */
    public function addDocumentSellerContact(?string $contactpersonname = null, ?string $contactdepartmentname = null, ?string $contactphoneno = null, ?string $contactfaxno = null, ?string $contactemailadd = null, ?string $contacttypecode = null): OrderDocumentBuilder
    {
        $sellerTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeAgreement, "getSellerTradeParty");
        $contact = $this->objectHelper->getTradeContact($contactpersonname, $contactdepartmentname, $contactphoneno, $contactfaxno, $contactemailadd, $contacttypecode);
        $this->objectHelper->tryCall($sellerTradeParty, "addToDefinedTradeContact", $contact);
        return $this;
    }

    /**
     * Set the universal communication info for the seller
     *
     * @param string|null $uriType
     * Identifies the electronic address to which the application level response to the order may be delivered.
     * @param string|null $uriId
     * The identification scheme identifier of the electronic address. The scheme identifier shall be chosen
     * from a list to be maintained by the Connecting Europe Facility.
     * @return OrderDocumentBuilder
     */
    public function setDocumentSellerElectronicAddress(?string $uriType = null, ?string $uriId = null): OrderDocumentBuilder
    {
        $sellerTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeAgreement, "getSellerTradeParty");
        $universalCommunication = $this->objectHelper->getUniversalCommunicationType(null, $uriId, $uriType);
        $this->objectHelper->tryCall($sellerTradeParty, "setURIUniversalCommunication", $universalCommunication);
        return $this;
    }

    /**
     * Set information about the Buyer.
     *
     * @param string $name
     * The full formal name by which the party is registered in the national registry of
     * legal entities or as a Taxable person or otherwise trades as a person or persons.
     * @param string|null $id
     * An identification of the Party. The identification scheme identifier of the Party identifier.
     * @param string|null $description
     * Additional legal information relevant for the Paety.
     * @return OrderDocumentBuilder
     */
    public function setDocumentBuyer(string $name, ?string $id = null, ?string $description = null): OrderDocumentBuilder
    {
        $buyerTradeParty = $this->objectHelper->getTradeParty($name, $id, $description);
        $this->objectHelper->tryCall($this->headerTradeAgreement, "setBuyerTradeParty", $buyerTradeParty);
        return $this;
    }

    /**
     * Add a global id for the buyer
     *
     * @param string $globalID
     * The buyers's identifier identification scheme is an identifier uniquely assigned to a buyer by a
     * global registration organization.
     * @param string $globalIDType
     * If the identifier is used for the identification scheme, it must be selected from the entries in
     * the list published by the ISO / IEC 6523 Maintenance Agency.
     * @return OrderDocumentBuilder
     */
    public function addDocumentBuyerGlobalId(string $globalID, string $globalIDType): OrderDocumentBuilder
    {
        $buyerTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeAgreement, "getBuyerTradeParty");
        $this->objectHelper->tryCall($buyerTradeParty, "addToGlobalID", $this->objectHelper->getIdType($globalID, $globalIDType));
        return $this;
    }

    /**
     * Set detailed information on the buyers's tax information
     *
     * @param string $taxregtype
     * Scheme identifier for supplier VAT identifier
     * @param string $taxregid
     * The Seller's VAT identifier (also known as Seller VAT identification number). VAT number prefixed by a country code.
     * A VAT registered Supplier shall include his VAT ID, except when he uses a tax representative.
     * @return OrderDocumentBuilder
     */
    public function setDocumentBuyerTaxRegistration(string $taxregtype, string $taxregid): OrderDocumentBuilder
    {
        $buyerTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeAgreement, "getBuyerTradeParty");
        $taxreg = $this->objectHelper->getTaxRegistrationType($taxregtype, $taxregid);
        $this->objectHelper->tryCallIfMethodExists($buyerTradeParty, "addToSpecifiedTaxRegistration", "setSpecifiedTaxRegistration", [$taxreg], $taxreg);
        return $this;
    }

    /**
     * Add detailed information on the buyers's tax information
     *
     * @param string $taxregtype
     * Scheme identifier for supplier VAT identifier
     * @param string $taxregid
     * The Seller's VAT identifier (also known as Seller VAT identification number). VAT number prefixed by a country code.
     * A VAT registered Supplier shall include his VAT ID, except when he uses a tax representative.
     * @return OrderDocumentBuilder
     */
    public function addDocumentBuyerTaxRegistration(string $taxregtype, string $taxregid): OrderDocumentBuilder
    {
        $buyerTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeAgreement, "getBuyerTradeParty");
        $taxreg = $this->objectHelper->getTaxRegistrationType($taxregtype, $taxregid);
        $this->objectHelper->tryCall($buyerTradeParty, "addToSpecifiedTaxRegistration", $taxreg);
        return $this;
    }

    /**
     * Sets detailed information on the business address of the buyer
     *
     * @param string|null $lineone
     * The main line in the buyers address. This is usually the street name and house number or
     * the post office box
     * @param string|null $linetwo
     * Line 2 of the buyers address. This is an additional address line in an address that can be
     * used to provide additional details in addition to the main line
     * @param string|null $linethree
     * Line 3 of the buyers address. This is an additional address line in an address that can be
     * used to provide additional details in addition to the main line
     * @param string|null $postcode
     * The identifier for an addressable group of properties according to the relevant postal service.
     * @param string|null $city
     * Usual name of the city or municipality in which the buyers address is located
     * @param string|null $country
     * Code used to identify the country. If no tax agent is specified, this is the country in which the sales tax
     * is due. The lists of approved countries are maintained by the EN ISO 3166-1 Maintenance Agency “Codes for the
     * representation of names of countries and their subdivisions”
     * @param string|null $subdivision
     * The subdivision of a country.
     * @return OrderDocumentBuilder
     */
    public function setDocumentBuyerAddress(?string $lineone = null, ?string $linetwo = null, ?string $linethree = null, ?string $postcode = null, ?string $city = null, ?string $country = null, ?string $subdivision = null): OrderDocumentBuilder
    {
        $buyerTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeAgreement, "getBuyerTradeParty");
        $address = $this->objectHelper->getTradeAddress($lineone, $linetwo, $linethree, $postcode, $city, $country, $subdivision);
        $this->objectHelper->tryCall($buyerTradeParty, "setPostalTradeAddress", $address);
        return $this;
    }

    /**
     * Set legal organisation of the buyer party
     *
     * @param string|null $legalorgid
     * An identifier issued by an official registrar that identifies the
     * buyer as a legal entity or legal person. If no identification scheme ($legalorgtype) is provided,
     * it should be known to the buyer and buyer
     * @param string|null $legalorgtype
     * The identifier for the identification scheme of the legal
     * registration of the buyer. If the identification scheme is used, it must be selected from
     * ISO/IEC 6523 list
     * @param string|null $legalorgname
     * A name by which the buyer is known, if different from the buyers name
     * (also known as the company name)
     * @return OrderDocumentBuilder
     */
    public function setDocumentBuyerLegalOrganisation(?string $legalorgid = null, ?string $legalorgtype = null, ?string $legalorgname = null): OrderDocumentBuilder
    {
        $buyerTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeAgreement, "getBuyerTradeParty");
        $legalorg = $this->objectHelper->getLegalOrganization($legalorgid, $legalorgtype, $legalorgname);
        $this->objectHelper->tryCall($buyerTradeParty, "setSpecifiedLegalOrganization", $legalorg);
        return $this;
    }

    /**
     * Set contact of the buyer party
     *
     * @param string|null $contactpersonname
     * Contact point for a legal entity, such as a personal name of the contact person
     * @param string|null $contactdepartmentname
     * Contact point for a legal entity, such as a name of the department or office
     * @param string|null $contactphoneno
     * A phone number for the contact point.
     * @param string|null $contactfaxno
     * A fax number for the contact point.
     * @param string|null $contactemailadd
     * An e-mail address for the contact point.
     * @param string|null $contacttypecode
     * The code specifying the type of trade contact. To be chosen from the entries in UNTDID 3139
     * @return OrderDocumentBuilder
     */
    public function setDocumentBuyerContact(?string $contactpersonname = null, ?string $contactdepartmentname = null, ?string $contactphoneno = null, ?string $contactfaxno = null, ?string $contactemailadd = null, ?string $contacttypecode = null): OrderDocumentBuilder
    {
        $buyerTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeAgreement, "getBuyerTradeParty");
        $contact = $this->objectHelper->getTradeContact($contactpersonname, $contactdepartmentname, $contactphoneno, $contactfaxno, $contactemailadd, $contacttypecode);
        $this->objectHelper->tryCallIfMethodExists($buyerTradeParty, "addToDefinedTradeContact", "setDefinedTradeContact", [$contact], $contact);
        return $this;
    }

    /**
     * Add additional contact of the buyer party. This only supported in the
     *
     * @param string|null $contactpersonname
     * Contact point for a legal entity, such as a personal name of the contact person
     * @param string|null $contactdepartmentname
     * Contact point for a legal entity, such as a name of the department or office
     * @param string|null $contactphoneno
     * A phone number for the contact point.
     * @param string|null $contactfaxno
     * A fax number for the contact point.
     * @param string|null $contactemailadd
     * An e-mail address for the contact point.
     * @param string|null $contacttypecode
     * The code specifying the type of trade contact. To be chosen from the entries in UNTDID 3139
     * @return OrderDocumentBuilder
     */
    public function addDocumentBuyerContact(?string $contactpersonname = null, ?string $contactdepartmentname = null, ?string $contactphoneno = null, ?string $contactfaxno = null, ?string $contactemailadd = null, ?string $contacttypecode = null): OrderDocumentBuilder
    {
        $buyerTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeAgreement, "getBuyerTradeParty");
        $contact = $this->objectHelper->getTradeContact($contactpersonname, $contactdepartmentname, $contactphoneno, $contactfaxno, $contactemailadd, $contacttypecode);
        $this->objectHelper->tryCall($buyerTradeParty, "addToDefinedTradeContact", $contact);
        return $this;
    }

    /**
     * Set the universal communication info for the Buyer
     *
     * @param string|null $uriType
     * Identifies the electronic address to which the application level response to the order may be delivered.
     * @param string|null $uriId
     * The identification scheme identifier of the electronic address. The scheme identifier shall be chosen
     * from a list to be maintained by the Connecting Europe Facility.
     * @return OrderDocumentBuilder
     */
    public function setDocumentBuyerElectronicAddress(?string $uriType = null, ?string $uriId = null): OrderDocumentBuilder
    {
        $buyerTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeAgreement, "getBuyerTradeParty");
        $universalCommunication = $this->objectHelper->getUniversalCommunicationType(null, $uriId, $uriType);
        $this->objectHelper->tryCall($buyerTradeParty, "setURIUniversalCommunication", $universalCommunication);
        return $this;
    }

    /**
     * Detailed information about the party who raises the Order originally on behalf of the Buyer
     *
     * @param string $name
     * The full formal name by which the party is registered in the national registry of
     * legal entities or as a Taxable person or otherwise trades as a person or persons.
     * @param string|null $id
     * An identification of the Party. The identification scheme identifier of the Party identifier.
     * @param string|null $description
     * Additional legal information relevant for the Paety.
     * @return OrderDocumentBuilder
     */
    public function setDocumentBuyerRequisitioner(string $name, ?string $id = null, ?string $description = null): OrderDocumentBuilder
    {
        $buyerRequisitionerTradeParty = $this->objectHelper->getTradeParty($name, $id, $description);
        $this->objectHelper->tryCall($this->headerTradeAgreement, "setBuyerRequisitionerTradeParty", $buyerRequisitionerTradeParty);
        return $this;
    }

    /**
     * Add a global id for the party who raises the Order originally on behalf of the buyer requisitioner
     *
     * @param string $globalID
     * The buyer requisitioner's identifier identification scheme is an identifier uniquely assigned to a buyer requisitioner by a
     * global registration organization.
     * @param string $globalIDType
     * If the identifier is used for the identification scheme, it must be selected from the entries in
     * the list published by the ISO / IEC 6523 Maintenance Agency.
     * @return OrderDocumentBuilder
     */
    public function addDocumentBuyerRequisitionerGlobalId(string $globalID, string $globalIDType): OrderDocumentBuilder
    {
        $buyerRequisitionerTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeAgreement, "getBuyerRequisitionerTradeParty");
        $this->objectHelper->tryCall($buyerRequisitionerTradeParty, "addToGlobalID", $this->objectHelper->getIdType($globalID, $globalIDType));
        return $this;
    }

    /**
     * Set tax registration information of the party who raises the Order originally on behalf of the Buyer
     *
     * @param string $taxregtype
     * Scheme identifier for supplier VAT identifier
     * @param string $taxregid
     * The Seller's VAT identifier (also known as Seller VAT identification number). VAT number prefixed by a country code.
     * A VAT registered Supplier shall include his VAT ID, except when he uses a tax representative.
     * @return OrderDocumentBuilder
     */
    public function setDocumentBuyerRequisitionerTaxRegistration(string $taxregtype, string $taxregid): OrderDocumentBuilder
    {
        $buyerRequisitionerTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeAgreement, "getBuyerRequisitionerTradeParty");
        $taxreg = $this->objectHelper->getTaxRegistrationType($taxregtype, $taxregid);
        $this->objectHelper->tryCallIfMethodExists($buyerRequisitionerTradeParty, "addToSpecifiedTaxRegistration", "setSpecifiedTaxRegistration", [$taxreg], $taxreg);
        return $this;
    }

    /**
     * Add tax registration information of the party who raises the Order originally on behalf of the Buyer
     *
     * @param string $taxregtype
     * Scheme identifier for supplier VAT identifier
     * @param string $taxregid
     * The Seller's VAT identifier (also known as Seller VAT identification number). VAT number prefixed by a country code.
     * A VAT registered Supplier shall include his VAT ID, except when he uses a tax representative.
     * @return OrderDocumentBuilder
     */
    public function addDocumentBuyerRequisitionerTaxRegistration(string $taxregtype, string $taxregid): OrderDocumentBuilder
    {
        $buyerRequisitionerTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeAgreement, "getBuyerRequisitionerTradeParty");
        $taxreg = $this->objectHelper->getTaxRegistrationType($taxregtype, $taxregid);
        $this->objectHelper->tryCall($buyerRequisitionerTradeParty, "addToSpecifiedTaxRegistration", $taxreg);
        return $this;
    }

    /**
     * Sets detailed information of the party who raises the Order originally on behalf of the Buyer
     *
     * @param string|null $lineone
     * The main line in the buyer requisitioners address. This is usually the street name and house number or
     * the post office box
     * @param string|null $linetwo
     * Line 2 of the buyer requisitioners address. This is an additional address line in an address that can be
     * used to provide additional details in addition to the main line
     * @param string|null $linethree
     * Line 3 of the buyer requisitioners address. This is an additional address line in an address that can be
     * used to provide additional details in addition to the main line
     * @param string|null $postcode
     * The identifier for an addressable group of properties according to the relevant postal service.
     * @param string|null $city
     * Usual name of the city or municipality in which the buyer requisitioners address is located
     * @param string|null $country
     * Code used to identify the country. If no tax agent is specified, this is the country in which the sales tax
     * is due. The lists of approved countries are maintained by the EN ISO 3166-1 Maintenance Agency “Codes for the
     * representation of names of countries and their subdivisions”
     * @param string|null $subdivision
     * The subdivision of a country.
     * @return OrderDocumentBuilder
     */
    public function setDocumentBuyerRequisitionerAddress(?string $lineone = null, ?string $linetwo = null, ?string $linethree = null, ?string $postcode = null, ?string $city = null, ?string $country = null, ?string $subdivision = null): OrderDocumentBuilder
    {
        $buyerRequisitionerTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeAgreement, "getBuyerRequisitionerTradeParty");
        $address = $this->objectHelper->getTradeAddress($lineone, $linetwo, $linethree, $postcode, $city, $country, $subdivision);
        $this->objectHelper->tryCall($buyerRequisitionerTradeParty, "setPostalTradeAddress", $address);
        return $this;
    }

    /**
     * Set legal organisation of the party who raises the Order originally on behalf of the Buyer
     *
     * @param string|null $legalorgid
     * An identifier issued by an official registrar that identifies the
     * buyer requisitioner as a legal entity or legal person.
     * @param string|null $legalorgtype
     * The identifier for the identification scheme of the legal
     * registration of the buyer requisitioner. If the identification scheme is used, it must be selected from
     * ISO/IEC 6523 list
     * @param string|null $legalorgname
     * A name by which the buyer requisitioner is known, if different from the buyer requisitioners name
     * (also known as the company name)
     * @return OrderDocumentBuilder
     */
    public function setDocumentBuyerRequisitionerLegalOrganisation(?string $legalorgid = null, ?string $legalorgtype = null, ?string $legalorgname = null): OrderDocumentBuilder
    {
        $buyerRequisitionerTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeAgreement, "getBuyerRequisitionerTradeParty");
        $legalorg = $this->objectHelper->getLegalOrganization($legalorgid, $legalorgtype, $legalorgname);
        $this->objectHelper->tryCall($buyerRequisitionerTradeParty, "setSpecifiedLegalOrganization", $legalorg);
        return $this;
    }

    /**
     * Set contact of the party who raises the Order originally on behalf of the Buyer
     *
     * @param string|null $contactpersonname
     * Contact point for a legal entity, such as a personal name of the contact person
     * @param string|null $contactdepartmentname
     * Contact point for a legal entity, such as a name of the department or office
     * @param string|null $contactphoneno
     * A phone number for the contact point.
     * @param string|null $contactfaxno
     * A fax number for the contact point.
     * @param string|null $contactemailadd
     * An e-mail address for the contact point.
     * @param string|null $contacttypecode
     * The code specifying the type of trade contact. To be chosen from the entries in UNTDID 3139
     * @return OrderDocumentBuilder
     */
    public function setDocumentBuyerRequisitionerContact(?string $contactpersonname = null, ?string $contactdepartmentname = null, ?string $contactphoneno = null, ?string $contactfaxno = null, ?string $contactemailadd = null, ?string $contacttypecode = null): OrderDocumentBuilder
    {
        $buyerRequisitionerTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeAgreement, "getBuyerRequisitionerTradeParty");
        $contact = $this->objectHelper->getTradeContact($contactpersonname, $contactdepartmentname, $contactphoneno, $contactfaxno, $contactemailadd, $contacttypecode);
        $this->objectHelper->tryCallIfMethodExists($buyerRequisitionerTradeParty, "addToDefinedTradeContact", "setDefinedTradeContact", [$contact], $contact);
        return $this;
    }

    /**
     * Add additional contact of the party who raises the Order originally on behalf of the Buyer
     *
     * @param string|null $contactpersonname
     * Contact point for a legal entity, such as a personal name of the contact person
     * @param string|null $contactdepartmentname
     * Contact point for a legal entity, such as a name of the department or office
     * @param string|null $contactphoneno
     * A phone number for the contact point.
     * @param string|null $contactfaxno
     * A fax number for the contact point.
     * @param string|null $contactemailadd
     * An e-mail address for the contact point.
     * @param string|null $contacttypecode
     * The code specifying the type of trade contact. To be chosen from the entries in UNTDID 3139
     * @return OrderDocumentBuilder
     */
    public function addDocumentBuyerRequisitionerContact(?string $contactpersonname = null, ?string $contactdepartmentname = null, ?string $contactphoneno = null, ?string $contactfaxno = null, ?string $contactemailadd = null, ?string $contacttypecode = null): OrderDocumentBuilder
    {
        $buyerRequisitionerTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeAgreement, "getBuyerRequisitionerTradeParty");
        $contact = $this->objectHelper->getTradeContact($contactpersonname, $contactdepartmentname, $contactphoneno, $contactfaxno, $contactemailadd, $contacttypecode);
        $this->objectHelper->tryCall($buyerRequisitionerTradeParty, "addToDefinedTradeContact", $contact);
        return $this;
    }

    /**
     * Set the universal communication info for the buyer requisitioner
     *
     * @param string|null $uriType
     * Identifies the electronic address to which the application level response to the order may be delivered.
     * @param string|null $uriId
     * The identification scheme identifier of the electronic address. The scheme identifier shall be chosen
     * from a list to be maintained by the Connecting Europe Facility.
     * @return OrderDocumentBuilder
     */
    public function setDocumentBuyerRequisitionerElectronicAddress(?string $uriType = null, ?string $uriId = null): OrderDocumentBuilder
    {
        $buyerRequisitionerTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeAgreement, "getBuyerRequisitionerTradeParty");
        $universalCommunication = $this->objectHelper->getUniversalCommunicationType(null, $uriId, $uriType);
        $this->objectHelper->tryCall($buyerRequisitionerTradeParty, "setURIUniversalCommunication", $universalCommunication);
        return $this;
    }

    /**
     * Set information on the delivery conditions
     *
     * @param string|null $deliveryTypeCode
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
     * @param string|null $description
     * A textual description of these trade delivery terms
     * @param string|null $functionCode
     * A code specifying a function of these trade delivery terms (Pick up,or delivered) To be chosen from the entries
     * in UNTDID 4055
     * @param string|null $relevantTradeLocationId
     * The unique identifier of a country location used or referenced in trade.
     * @param string|null $relevantTradeLocationName
     * The name, expressed as text, of this location used or referenced in trade.
     * @return OrderDocumentBuilder
     */
    public function setDocumentDeliveryTerms(?string $deliveryTypeCode = null, ?string $description = null, ?string $functionCode = null, ?string $relevantTradeLocationId = null, ?string $relevantTradeLocationName = null): OrderDocumentBuilder
    {
        $deliveryterms = $this->objectHelper->getTradeDeliveryTermsType($deliveryTypeCode, $description, $functionCode, $relevantTradeLocationId, $relevantTradeLocationName);
        $this->objectHelper->tryCall($this->headerTradeAgreement, "setApplicableTradeDeliveryTerms", $deliveryterms);
        return $this;
    }

    /**
     * Set details of the associated order confirmation
     *
     * @param string $sellerOrderRefId
     * An identifier of a referenced Sales order, issued by the Seller
     * @param DateTime|null $sellerOrderRefDate
     * The formatted date or date time for the issuance of this referenced Sales Order.
     * @return OrderDocumentBuilder
     */
    public function setDocumentSellerOrderReferencedDocument(string $sellerOrderRefId, ?DateTime $sellerOrderRefDate = null): OrderDocumentBuilder
    {
        $sellerOrderRefDoc = $this->objectHelper->getReferencedDocumentType($sellerOrderRefId, null, null, null, null, null, $sellerOrderRefDate, null);
        $this->objectHelper->tryCall($this->headerTradeAgreement, "setSellerOrderReferencedDocument", $sellerOrderRefDoc);
        return $this;
    }

    /**
     * Set details of the related buyer order
     *
     * @param string $buyerOrderRefId
     * An identifier of a referenced purchase order, issued by the Buyer.
     * @param DateTime|null $buyerOrderRefDate
     * The formatted date or date time for the issuance of this referenced Buyer Order.
     * @return OrderDocumentBuilder
     */
    public function setDocumentBuyerOrderReferencedDocument(string $buyerOrderRefId, ?DateTime $buyerOrderRefDate = null): OrderDocumentBuilder
    {
        $buyerOrderRefDoc = $this->objectHelper->getReferencedDocumentType($buyerOrderRefId, null, null, null, null, null, $buyerOrderRefDate, null);
        $this->objectHelper->tryCall($this->headerTradeAgreement, "setBuyerOrderReferencedDocument", $buyerOrderRefDoc);
        return $this;
    }

    /**
     * Set details of the related quotation
     *
     * @param string $quotationRefId
     * An Identifier of a Quotation, issued by the Seller.
     * @param DateTime|null $quotationRefDate
     * Date of order
     * @return OrderDocumentBuilder
     */
    public function setDocumentQuotationReferencedDocument(string $quotationRefId, ?DateTime $quotationRefDate = null): OrderDocumentBuilder
    {
        $quotationRefDoc = $this->objectHelper->getReferencedDocumentType($quotationRefId, null, null, null, null, null, $quotationRefDate, null);
        $this->objectHelper->tryCall($this->headerTradeAgreement, "setQuotationReferencedDocument", $quotationRefDoc);
        return $this;
    }

    /**
     * Set details of the associated contract. The contract identifier should be unique in the context
     * of the specific trading relationship and for a defined time period.
     *
     * @param string $contractRefId
     * The identification of a contract.
     * @param DateTime|null $contractRefDate
     * The formatted date or date time for the issuance of this referenced Contract.
     * @return OrderDocumentBuilder
     */
    public function setDocumentContractReferencedDocument(string $contractRefId, ?DateTime $contractRefDate = null): OrderDocumentBuilder
    {
        $contractRefDoc = $this->objectHelper->getReferencedDocumentType($contractRefId, null, null, null, null, null, $contractRefDate, null);
        $this->objectHelper->tryCallIfMethodExists($this->headerTradeAgreement, "addToContractReferencedDocument", "setContractReferencedDocument", [$contractRefDoc], $contractRefDoc);
        return $this;
    }

    /**
     * Add new details of the associated contract
     *
     * @param string $contractRefId
     * The contract reference should be assigned once in the context of the specific trade relationship and for a
     * defined period of time (contract number)
     * @param DateTime|null $contractRefDate
     * The formatted date or date time for the issuance of this referenced Contract.
     * @return OrderDocumentBuilder
     */
    public function addDocumentContractReferencedDocument(string $contractRefId, ?DateTime $contractRefDate = null): OrderDocumentBuilder
    {
        $contractRefDoc = $this->objectHelper->getReferencedDocumentType($contractRefId, null, null, null, null, null, $contractRefDate, null);
        $this->objectHelper->tryCall($this->headerTradeAgreement, "addToContractReferencedDocument", $contractRefDoc);
        return $this;
    }

    /**
     * Set details of a Requisition Document, issued by the Buyer or the Buyer Requisitioner
     *
     * @param string $requisitionRefId
     * The identification of a Requisition Document, issued by the Buyer or the Buyer Requisitioner.
     * @param DateTime|null $requisitionRefDate
     * The formatted date or date time for the issuance of this referenced Requisition.
     * @return OrderDocumentBuilder
     */
    public function setDocumentRequisitionReferencedDocument(string $requisitionRefId, ?DateTime $requisitionRefDate = null): OrderDocumentBuilder
    {
        $requisitionRefDoc = $this->objectHelper->getReferencedDocumentType($requisitionRefId, null, null, null, null, null, $requisitionRefDate, null);
        $this->objectHelper->tryCallIfMethodExists($this->headerTradeAgreement, "addToRequisitionReferencedDocument", "setRequisitionReferencedDocument", [$requisitionRefDoc], $requisitionRefDoc);
        return $this;
    }

    /**
     * Add an addititonal Requisition Document, issued by the Buyer or the Buyer Requisitioner
     *
     * @param string $requisitionRefId
     * The identification of a Requisition Document, issued by the Buyer or the Buyer Requisitioner.
     * @param DateTime|null $requisitionRefDate
     * The formatted date or date time for the issuance of this referenced Requisition.
     * @return OrderDocumentBuilder
     */
    public function addDocumentRequisitionReferencedDocument(string $requisitionRefId, ?DateTime $requisitionRefDate = null): OrderDocumentBuilder
    {
        $requisitionRefDoc = $this->objectHelper->getReferencedDocumentType($requisitionRefId, null, null, null, null, null, $requisitionRefDate, null);
        $this->objectHelper->tryCall($this->headerTradeAgreement, "addToRequisitionReferencedDocument", $requisitionRefDoc);
        return $this;
    }

    /**
     * Add an information about additional supporting documents substantiating the claims made in the order.
     * The additional supporting documents can be used for both referencing a document number which is expected to be
     * known by the receiver, an external document (referenced by a URL) or as an embedded document (such as a time
     * report in pdf). The option to link to an external document will be needed, for example in the case of large
     * attachments and/or when sensitive information, e.g. person-related services, has to be separated from the order itself.
     *
     * @param string $additionalRefTypeCode
     * Type of referenced document (See codelist UNTDID 1001)
     *  - Code 916 "reference paper" is used to reference the identification of the document on which the invoice is based
     *  - Code 50 "Price / sales catalog response" is used to reference the tender or the lot
     *  - Code 130 "invoice data sheet" is used to reference an identifier for an object specified by the seller.
     * @param string|null $additionalRefId
     * The identifier of the tender or lot to which the invoice relates, or an identifier specified by the seller for
     * an object on which the invoice is based, or an identifier of the document on which the invoice is based.
     * @param string|null $additionalRefURIID
     * The Uniform Resource Locator (URL) at which the external document is available. A means of finding the resource
     * including the primary access method intended for it, e.g. http: // or ftp: //. The location of the external document
     * must be used if the buyer needs additional information to support the amounts billed. External documents are not part
     * of the invoice. Access to external documents can involve certain risks.
     * @param string|array|null $additionalRefName
     * A description of the document, e.g. Hourly billing, usage or consumption report, etc.
     * @param string|null $additionalRefRefTypeCode
     * The identifier for the identification scheme of the identifier of the item invoiced. If it is not clear to the
     * recipient which scheme is used for the identifier, an identifier of the scheme should be used, which must be selected
     * from UNTDID 1153 in accordance with the code list entries.
     * @param DateTime|null $additionalRefDate
     * The formatted date or date time for the issuance of this referenced Additional Document.
     * @param string|null $binarydatafilename
     * Contains a file name of an attachment document embedded as a binary object.
     * Allowed mime codes:
     * - application/pdf
     * - image/png
     * - image/jpeg
     * - text/csv
     * - application/vnd.openxmlformats
     * - officedocument.spreadsheetml.sheet
     * - application/vnd.oasis.opendocument. Spreadsheet
     * @return OrderDocumentBuilder
     */
    public function addDocumentAdditionalReferencedDocument(string $additionalRefTypeCode, ?string $additionalRefId, ?string $additionalRefURIID = null, $additionalRefName = null, ?string $additionalRefRefTypeCode = null, ?DateTime $additionalRefDate = null, ?string $binarydatafilename = null): OrderDocumentBuilder
    {
        $additionalRefDoc = $this->objectHelper->getReferencedDocumentType($additionalRefId, $additionalRefURIID, null, $additionalRefTypeCode, $additionalRefName, $additionalRefRefTypeCode, $additionalRefDate, $binarydatafilename);
        $this->objectHelper->tryCall($this->headerTradeAgreement, "addToAdditionalReferencedDocument", $additionalRefDoc);
        return $this;
    }

    /**
     * Set details of a blanket order referenced document
     *
     * @param string $blanketOrderRefId
     * The identification of a Blanket Order, issued by the Buyer or the Buyer Requisitioner.
     * @param DateTime|null $blanketOrderRefDate
     * The formatted date or date time for the issuance of this referenced Blanket Order.
     * @return OrderDocumentBuilder
     */
    public function setDocumentBlanketOrderReferencedDocument(string $blanketOrderRefId, ?DateTime $blanketOrderRefDate = null): OrderDocumentBuilder
    {
        $blanketOrderRefDoc = $this->objectHelper->getReferencedDocumentType($blanketOrderRefId, null, null, null, null, null, $blanketOrderRefDate, null);
        $this->objectHelper->tryCallIfMethodExists($this->headerTradeAgreement, "addToBlanketOrderReferencedDocument", "setBlanketOrderReferencedDocument", [$blanketOrderRefDoc], $blanketOrderRefDoc);
        return $this;
    }

    /**
     * Add new details of a blanket order referenced document
     *
     * @param string $blanketOrderRefId
     * The identification of a Blanket Order, issued by the Buyer or the Buyer Requisitioner.
     * @param DateTime|null $blanketOrderRefDate
     * The formatted date or date time for the issuance of this referenced Blanket Order.
     * @return OrderDocumentBuilder
     */
    public function addDocumentBlanketOrderReferencedDocument(string $blanketOrderRefId, ?DateTime $blanketOrderRefDate = null): OrderDocumentBuilder
    {
        $blanketOrderRefDoc = $this->objectHelper->getReferencedDocumentType($blanketOrderRefId, null, null, null, null, null, $blanketOrderRefDate, null);
        $this->objectHelper->tryCall($this->headerTradeAgreement, "addToBlanketOrderReferencedDocument", $blanketOrderRefDoc);
        return $this;
    }

    /**
     * Set details of a the Previous Order Change Document, issued by the Buyer or the Buyer Requisitioner.
     *
     * @param string $prevOrderChangeRefId
     * The identification of a the Previous Order Change Document, issued by the Buyer or the Buyer Requisitioner.
     * @param DateTime|null $prevOrderChangeRefDate
     * The formatted date or date time for the issuance of this referenced Previous Order Change.
     * @return OrderDocumentBuilder
     */
    public function setDocumentPreviousOrderChangeReferencedDocument(string $prevOrderChangeRefId, ?DateTime $prevOrderChangeRefDate = null): OrderDocumentBuilder
    {
        $prevOrderChangeRefDoc = $this->objectHelper->getReferencedDocumentType($prevOrderChangeRefId, null, null, null, null, null, $prevOrderChangeRefDate, null);
        $this->objectHelper->tryCallIfMethodExists($this->headerTradeAgreement, "addToPreviousOrderChangeReferencedDocument", "setPreviousOrderChangeReferencedDocument", [$prevOrderChangeRefDoc], $prevOrderChangeRefDoc);
        return $this;
    }

    /**
     * Add new details of a the Previous Order Change Document, issued by the Buyer or the Buyer Requisitioner.
     *
     * @param string $prevOrderChangeRefId
     * The identification of a the Previous Order Change Document, issued by the Buyer or the Buyer Requisitioner.
     * @param DateTime|null $prevOrderChangeRefDate
     * The formatted date or date time for the issuance of this referenced Previous Order Change.
     * @return OrderDocumentBuilder
     */
    public function addDocumentPreviousOrderChangeReferencedDocument(string $prevOrderChangeRefId, ?DateTime $prevOrderChangeRefDate = null): OrderDocumentBuilder
    {
        $prevOrderChangeRefDoc = $this->objectHelper->getReferencedDocumentType($prevOrderChangeRefId, null, null, null, null, null, $prevOrderChangeRefDate, null);
        $this->objectHelper->tryCall($this->headerTradeAgreement, "addToPreviousOrderChangeReferencedDocument", $prevOrderChangeRefDoc);
        return $this;
    }

    /**
     * Set details of a the Previous Order Response Document, issued by the Seller.
     *
     * @param string $prevOrderResponseRefId
     * The identification of a the Previous Order Response Document, issued by the Seller.
     * @param DateTime|null $prevOrderResponseRefDate
     * The formatted date or date time for the issuance of this referenced Previous Order Response.
     * @return OrderDocumentBuilder
     */
    public function setDocumentPreviousOrderResponseReferencedDocument(string $prevOrderResponseRefId, ?DateTime $prevOrderResponseRefDate = null): OrderDocumentBuilder
    {
        $prevOrderResponseRefDoc = $this->objectHelper->getReferencedDocumentType($prevOrderResponseRefId, null, null, null, null, null, $prevOrderResponseRefDate, null);
        $this->objectHelper->tryCallIfMethodExists($this->headerTradeAgreement, "addToPreviousOrderResponseReferencedDocument", "setPreviousOrderResponseReferencedDocument", [$prevOrderResponseRefDoc], $prevOrderResponseRefDoc);
        return $this;
    }

    /**
     * Add new details of a the Previous Order Response Document, issued by the Seller.
     *
     * @param string $prevOrderResponseRefId
     * The identification of a the Previous Order Response Document, issued by the Seller.
     * @param DateTime|null $prevOrderResponseRefDate
     * The formatted date or date time for the issuance of this referenced Previous Order Response.
     * @return OrderDocumentBuilder
     */
    public function addDocumentPreviousOrderResponseReferencedDocument(string $prevOrderResponseRefId, ?DateTime $prevOrderResponseRefDate = null): OrderDocumentBuilder
    {
        $prevOrderResponseRefDoc = $this->objectHelper->getReferencedDocumentType($prevOrderResponseRefId, null, null, null, null, null, $prevOrderResponseRefDate, null);
        $this->objectHelper->tryCall($this->headerTradeAgreement, "addToPreviousOrderResponseReferencedDocument", $prevOrderResponseRefDoc);
        return $this;
    }

    /**
     * Set the procuring project specified for this header trade agreement.
     *
     * @param string $procuringProjectId
     * The unique identifier of this procuring project.
     * @param string $procuringProjectName
     * The name of this procuring project.
     * @return OrderDocumentBuilder
     */
    public function setDocumentProcuringProject(string $procuringProjectId, string $procuringProjectName): OrderDocumentBuilder
    {
        $procuringProject = $this->objectHelper->getProcuringProjectType($procuringProjectId, $procuringProjectName);
        $this->objectHelper->tryCall($this->headerTradeAgreement, "setSpecifiedProcuringProject", $procuringProject);
        return $this;
    }

    /**
     * Set information about the Ship-To-Party
     * The Ship-To-Party provides information about where and when the goods and services ordered are delivered.
     *
     * @param string $name
     * The name of the party to which the goods and services are delivered.
     * @param string|null $id
     * An identification of the Party.
     * If no scheme is specified, it should be known by Buyer and Seller, e.g. a previously exchanged Buyer or Seller assigned identifier.
     * If used, the identification scheme shall be chosen from the entries of the list published by the ISO/IEC 6523 maintenance agency.
     * @param string|null $description
     * Additional legal information relevant for the Paety.
     * @return OrderDocumentBuilder
     */
    public function setDocumentShipTo(string $name, ?string $id = null, ?string $description = null): OrderDocumentBuilder
    {
        $shipToTradeParty = $this->objectHelper->getTradeParty($name, $id, $description);
        $this->objectHelper->tryCall($this->headerTradeDelivery, "setShipToTradeParty", $shipToTradeParty);
        return $this;
    }

    /**
     * Add a global id for the Ship-to Trade Party
     *
     * @param string $globalID
     * GloablID, if global identifier exists and can be stated in @schemeID
     * @param string $globalIDType
     * Scheme identifier of the identifier of the party
     * If used, the identification scheme identifier shall be chosen from the entries of the list published
     * by the ISO/IEC 6523 maintenance agency.
     * @return OrderDocumentBuilder
     */
    public function addDocumentShipToGlobalId(string $globalID, string $globalIDType): OrderDocumentBuilder
    {
        $shipToTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeDelivery, "getShipToTradeParty");
        $this->objectHelper->tryCall($shipToTradeParty, "addToGlobalID", $this->objectHelper->getIdType($globalID, $globalIDType));
        return $this;
    }

    /**
     * Set Tax registration to Ship-To Trade party
     *
     * @param string $taxregtype
     * Scheme identifier for supplier VAT identifier
     * @param string $taxregid
     * The Seller's VAT identifier (also known as Seller VAT identification number). VAT number prefixed by a country code.
     * A VAT registered Supplier shall include his VAT ID, except when he uses a tax representative.
     * @return OrderDocumentBuilder
     */
    public function setDocumentShipToTaxRegistration(string $taxregtype, string $taxregid): OrderDocumentBuilder
    {
        $shipToTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeDelivery, "getShipToTradeParty");
        $taxreg = $this->objectHelper->getTaxRegistrationType($taxregtype, $taxregid);
        $this->objectHelper->tryCallIfMethodExists($shipToTradeParty, "addToSpecifiedTaxRegistration", "setSpecifiedTaxRegistration", [$taxreg], $taxreg);
        return $this;
    }

    /**
     * Add Tax registration to Ship-To Trade party
     *
     * @param string $taxregtype
     * Scheme identifier for Ship-To VAT Identifier
     *  - Value = "VA" for VAT ID
     *  - Value = "FC" for local Tax ID
     * @param string $taxregid
     * The Ship-To's VAT identifier (also known as Buyer VAT identification number).
     * VAT number prefixed by a country code based on EN ISO 3166-1 "Codes for the representation of names of
     * countries and their subdivisions"
     * @return OrderDocumentBuilder
     */
    public function addDocumentShipToTaxRegistration(string $taxregtype, string $taxregid): OrderDocumentBuilder
    {
        $shipToTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeDelivery, "getShipToTradeParty");
        $taxreg = $this->objectHelper->getTaxRegistrationType($taxregtype, $taxregid);
        $this->objectHelper->tryCall($shipToTradeParty, "addToSpecifiedTaxRegistration", $taxreg);
        return $this;
    }

    /**
     * Set the postal address of the Ship-To party
     *
     * @param string|null $lineone
     * The main line in the party's address. This is usually the street name and house number or
     * the post office box
     * @param string|null $linetwo
     * Line 2 of the party's address. This is an additional address line in an address that can be
     * used to provide additional details in addition to the main line
     * @param string|null $linethree
     * Line 3 of the party's address. This is an additional address line in an address that can be
     * used to provide additional details in addition to the main line
     * @param string|null $postcode
     * The identifier for an addressable group of properties according to the relevant postal service.
     * @param string|null $city
     * Usual name of the city or municipality in which the party's address is located
     * @param string|null $country
     * Code used to identify the country. If no tax agent is specified, this is the country in which the sales tax
     * is due. The lists of approved countries are maintained by the EN ISO 3166-1 Maintenance Agency “Codes for the
     * representation of names of countries and their subdivisions”
     * @param string|null $subdivision
     * The subdivision of a country.
     * @return OrderDocumentBuilder
     */
    public function setDocumentShipToAddress(?string $lineone = null, ?string $linetwo = null, ?string $linethree = null, ?string $postcode = null, ?string $city = null, ?string $country = null, ?string $subdivision = null): OrderDocumentBuilder
    {
        $shipToTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeDelivery, "getShipToTradeParty");
        $address = $this->objectHelper->getTradeAddress($lineone, $linetwo, $linethree, $postcode, $city, $country, $subdivision);
        $this->objectHelper->tryCall($shipToTradeParty, "setPostalTradeAddress", $address);
        return $this;
    }

    /**
     * Set legal organisation of the Ship-To party
     *
     * @param string|null $legalorgid
     * An identifier issued by an official registrar that identifies the
     * party as a legal entity or legal person. If no identification scheme ($legalorgtype) is provided,
     * it should be known to the buyer or seller party
     * @param string|null $legalorgtype The identifier for the identification scheme of the legal
     * registration of the party. In particular, the following scheme codes are used: 0021 : SWIFT, 0088 : EAN,
     * 0060 : DUNS, 0177 : ODETTE
     * @param string|null $legalorgname A name by which the party is known, if different from the party's name
     * (also known as the company name)
     * @return OrderDocumentBuilder
     */
    public function setDocumentShipToLegalOrganisation(?string $legalorgid = null, ?string $legalorgtype = null, ?string $legalorgname = null): OrderDocumentBuilder
    {
        $shipToTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeDelivery, "getShipToTradeParty");
        $legalorg = $this->objectHelper->getLegalOrganization($legalorgid, $legalorgtype, $legalorgname);
        $this->objectHelper->tryCall($shipToTradeParty, "setSpecifiedLegalOrganization", $legalorg);
        return $this;
    }

    /**
     * Set contact of the Ship-To party
     *
     * @param string|null $contactpersonname
     * Contact point for a legal entity, such as a personal name of the contact person
     * @param string|null $contactdepartmentname
     * Contact point for a legal entity, such as a name of the department or office
     * @param string|null $contactphoneno
     * A phone number for the contact point.
     * @param string|null $contactfaxno
     * A fax number for the contact point.
     * @param string|null $contactemailadd
     * An e-mail address for the contact point.
     * @param string|null $contacttypecode
     * The code specifying the type of trade contact. To be chosen from the entries in UNTDID 3139
     * @return OrderDocumentBuilder
     */
    public function setDocumentShipToContact(?string $contactpersonname = null, ?string $contactdepartmentname = null, ?string $contactphoneno = null, ?string $contactfaxno = null, ?string $contactemailadd = null, ?string $contacttypecode = null): OrderDocumentBuilder
    {
        $shipToTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeDelivery, "getShipToTradeParty");
        $contact = $this->objectHelper->getTradeContact($contactpersonname, $contactdepartmentname, $contactphoneno, $contactfaxno, $contactemailadd, $contacttypecode);
        $this->objectHelper->tryCallIfMethodExists($shipToTradeParty, "addToDefinedTradeContact", "setDefinedTradeContact", [$contact], $contact);
        return $this;
    }

    /**
     * Add a contact to the Ship-To party.
     *
     * @param string|null $contactpersonname
     * Contact point for a legal entity, such as a personal name of the contact person
     * @param string|null $contactdepartmentname
     * Contact point for a legal entity, such as a name of the department or office
     * @param string|null $contactphoneno
     * A phone number for the contact point.
     * @param string|null $contactfaxno
     * A fax number for the contact point.
     * @param string|null $contactemailadd
     * An e-mail address for the contact point.
     * @param string|null $contactTypeCpde
     * The code specifying the type of trade contact. To be chosen from the entries in UNTDID 3139
     * @return OrderDocumentBuilder
     */
    public function addDocumentShipToContact(?string $contactpersonname = null, ?string $contactdepartmentname = null, ?string $contactphoneno = null, ?string $contactfaxno = null, ?string $contactemailadd = null, ?string $contactTypeCpde = null): OrderDocumentBuilder
    {
        $shipToTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeDelivery, "getShipToTradeParty");
        $contact = $this->objectHelper->getTradeContact($contactpersonname, $contactdepartmentname, $contactphoneno, $contactfaxno, $contactemailadd, $contactTypeCpde);
        $this->objectHelper->tryCall($shipToTradeParty, "addToDefinedTradeContact", $contact);
        return $this;
    }

    /**
     * Get the universal communication info for the Ship-To Trade Party
     *
     * @param string|null $uriType
     * Identifies the electronic address to which the application level response to the order may be delivered.
     * @param string|null $uriId
     * The identification scheme identifier of the electronic address. The scheme identifier shall be chosen
     * from a list to be maintained by the Connecting Europe Facility.
     * @return OrderDocumentBuilder
     */
    public function setDocumentShipToElectronicAddress(?string $uriType = null, ?string $uriId = null): OrderDocumentBuilder
    {
        $shipToTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeDelivery, "getShipToTradeParty");
        $universalCommunication = $this->objectHelper->getUniversalCommunicationType(null, $uriId, $uriType);
        $this->objectHelper->tryCall($shipToTradeParty, "setURIUniversalCommunication", $universalCommunication);
        return $this;
    }

    /**
     * Set information about the party from which the goods and services are delivered or picked up
     *
     * @param string $name
     * The full formal name by which the party is registered in the national registry of
     * legal entities or as a Taxable person or otherwise trades as a person or persons.
     * @param string|null $id
     * An identification of the Party. The identification scheme identifier of the Party identifier.
     * @param string|null $description
     * Additional legal information relevant for the Paety.
     * @return OrderDocumentBuilder
     */
    public function setDocumentShipFrom(string $name, ?string $id = null, ?string $description = null): OrderDocumentBuilder
    {
        $shipToTradeParty = $this->objectHelper->getTradeParty($name, $id, $description);
        $this->objectHelper->tryCall($this->headerTradeDelivery, "setShipFromTradeParty", $shipToTradeParty);
        return $this;
    }

    /**
     * Add a global id to the party from which the goods and services are delivered or picked up
     *
     * @param string $globalID
     * Global identifier of the goods recipient
     * @param string $globalIDType
     * Type of global identification number, must be selected from the entries in
     * the list published by the ISO / IEC 6523 Maintenance Agency.
     * @return OrderDocumentBuilder
     */
    public function addDocumentShipFromGlobalId(string $globalID, string $globalIDType): OrderDocumentBuilder
    {
        $shipFromTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeDelivery, "getShipFromTradeParty");
        $this->objectHelper->tryCall($shipFromTradeParty, "addToGlobalID", $this->objectHelper->getIdType($globalID, $globalIDType));
        return $this;
    }

    /**
     * Set Tax registration of the party from which the goods and services are delivered or picked up
     *
     * @param string $taxregtype
     * Scheme identifier for supplier VAT identifier
     * @param string $taxregid
     * The Seller's VAT identifier (also known as Seller VAT identification number). VAT number prefixed by a country code.
     * A VAT registered Supplier shall include his VAT ID, except when he uses a tax representative.
     * @return OrderDocumentBuilder
     */
    public function setDocumentShipFromTaxRegistration(string $taxregtype, string $taxregid): OrderDocumentBuilder
    {
        $shipFromTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeDelivery, "getShipFromTradeParty");
        $taxreg = $this->objectHelper->getTaxRegistrationType($taxregtype, $taxregid);
        $this->objectHelper->tryCallIfMethodExists($shipFromTradeParty, "addToSpecifiedTaxRegistration", "setSpecifiedTaxRegistration", [$taxreg], $taxreg);
        return $this;
    }

    /**
     * Add an additional Tax registration to the party from which the goods and services are delivered or picked up
     *
     * @param string $taxregtype
     * Scheme identifier for supplier VAT identifier
     * @param string $taxregid
     * The Seller's VAT identifier (also known as Seller VAT identification number). VAT number prefixed by a country code.
     * A VAT registered Supplier shall include his VAT ID, except when he uses a tax representative.
     * @return OrderDocumentBuilder
     */
    public function addDocumentShipFromTaxRegistration(string $taxregtype, string $taxregid): OrderDocumentBuilder
    {
        $shipFromTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeDelivery, "getShipFromTradeParty");
        $taxreg = $this->objectHelper->getTaxRegistrationType($taxregtype, $taxregid);
        $this->objectHelper->tryCall($shipFromTradeParty, "addToSpecifiedTaxRegistration", $taxreg);
        return $this;
    }

    /**
     * Sets the postal address of the party from which the goods and services are delivered or picked up
     *
     * @param string|null $lineone
     * The main line in the party's address. This is usually the street name and house number or
     * the post office box
     * @param string|null $linetwo
     * Line 2 of the party's address. This is an additional address line in an address that can be
     * used to provide additional details in addition to the main line
     * @param string|null $linethree
     * Line 3 of the party's address. This is an additional address line in an address that can be
     * used to provide additional details in addition to the main line
     * @param string|null $postcode
     * The identifier for an addressable group of properties according to the relevant postal service.
     * @param string|null $city
     * Usual name of the city or municipality in which the party's address is located
     * @param string|null $country
     * Code used to identify the country. If no tax agent is specified, this is the country in which the sales tax
     * is due. The lists of approved countries are maintained by the EN ISO 3166-1 Maintenance Agency “Codes for the
     * representation of names of countries and their subdivisions”
     * @param string|null $subdivision
     * The subdivision of a country.
     * @return OrderDocumentBuilder
     */
    public function setDocumentShipFromAddress(?string $lineone = null, ?string $linetwo = null, ?string $linethree = null, ?string $postcode = null, ?string $city = null, ?string $country = null, ?string $subdivision = null): OrderDocumentBuilder
    {
        $shipFromTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeDelivery, "getShipFromTradeParty");
        $address = $this->objectHelper->getTradeAddress($lineone, $linetwo, $linethree, $postcode, $city, $country, $subdivision);
        $this->objectHelper->tryCall($shipFromTradeParty, "setPostalTradeAddress", $address);
        return $this;
    }

    /**
     * Set legal organisation of the party from which the goods and services are delivered or picked up
     *
     * @param string|null $legalorgid
     * An identifier issued by an official registrar that identifies the
     * party as a legal entity or legal person. If no identification scheme ($legalorgtype) is provided,
     * it should be known to the buyer or seller party
     * @param string|null $legalorgtype
     * The identifier for the identification scheme of the legal registration of the party. In particular,
     * the following scheme codes are used: 0021 : SWIFT, 0088 : EAN, 0060 : DUNS, 0177 : ODETTE
     * @param string|null $legalorgname
     * A name by which the party is known, if different from the party's name (also known as the company name)
     * @return OrderDocumentBuilder
     */
    public function setDocumentShipFromLegalOrganisation(?string $legalorgid = null, ?string $legalorgtype = null, ?string $legalorgname = null): OrderDocumentBuilder
    {
        $shipFromTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeDelivery, "getShipFromTradeParty");
        $legalorg = $this->objectHelper->getLegalOrganization($legalorgid, $legalorgtype, $legalorgname);
        $this->objectHelper->tryCall($shipFromTradeParty, "setSpecifiedLegalOrganization", $legalorg);
        return $this;
    }

    /**
     * Set contact of the party from which the goods and services are delivered or picked up
     *
     * @param string|null $contactpersonname
     * Contact point for a legal entity, such as a personal name of the contact person
     * @param string|null $contactdepartmentname
     * Contact point for a legal entity, such as a name of the department or office
     * @param string|null $contactphoneno
     * A phone number for the contact point.
     * @param string|null $contactfaxno
     * A fax number for the contact point.
     * @param string|null $contactemailadd
     * An e-mail address for the contact point.
     * @param string|null $contacttypecode
     * The code specifying the type of trade contact. To be chosen from the entries in UNTDID 3139
     * @return OrderDocumentBuilder
     */
    public function setDocumentShipFromContact(?string $contactpersonname = null, ?string $contactdepartmentname = null, ?string $contactphoneno = null, ?string $contactfaxno = null, ?string $contactemailadd = null, ?string $contacttypecode = null): OrderDocumentBuilder
    {
        $shipFromTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeDelivery, "getShipFromTradeParty");
        $contact = $this->objectHelper->getTradeContact($contactpersonname, $contactdepartmentname, $contactphoneno, $contactfaxno, $contactemailadd, $contacttypecode);
        $this->objectHelper->tryCallIfMethodExists($shipFromTradeParty, "addToDefinedTradeContact", "setDefinedTradeContact", [$contact], $contact);
        return $this;
    }

    /**
     * Add an additional contact to the party from which the goods and services are delivered or picked up
     *
     * @param string|null $contactpersonname
     * Contact point for a legal entity, such as a personal name of the contact person
     * @param string|null $contactdepartmentname
     * Contact point for a legal entity, such as a name of the department or office
     * @param string|null $contactphoneno
     * A phone number for the contact point.
     * @param string|null $contactfaxno
     * A fax number for the contact point.
     * @param string|null $contactemailadd
     * An e-mail address for the contact point.
     * @param string|null $contacttypecode
     * The code specifying the type of trade contact. To be chosen from the entries in UNTDID 3139
     * @return OrderDocumentBuilder
     */
    public function addDocumentShipFromContact(?string $contactpersonname = null, ?string $contactdepartmentname = null, ?string $contactphoneno = null, ?string $contactfaxno = null, ?string $contactemailadd = null, ?string $contacttypecode = null): OrderDocumentBuilder
    {
        $shipFromTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeDelivery, "getShipFromTradeParty");
        $contact = $this->objectHelper->getTradeContact($contactpersonname, $contactdepartmentname, $contactphoneno, $contactfaxno, $contactemailadd, $contacttypecode);
        $this->objectHelper->tryCall($shipFromTradeParty, "addToDefinedTradeContact", $contact);
        return $this;
    }

    /**
     * Set the universal communication info for the party from which the goods and services are delivered or picked up
     *
     * @param string|null $uriType
     * Identifies the electronic address to which the application level response to the order may be delivered.
     * @param string|null $uriId
     * The identification scheme identifier of the electronic address. The scheme identifier shall be chosen
     * from a list to be maintained by the Connecting Europe Facility.
     * @return OrderDocumentBuilder
     */
    public function setDocumentShipFromElectronicAddress(?string $uriType = null, ?string $uriId = null): OrderDocumentBuilder
    {
        $shipFromTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeDelivery, "getShipFromTradeParty");
        $universalCommunication = $this->objectHelper->getUniversalCommunicationType(null, $uriId, $uriType);
        $this->objectHelper->tryCall($shipFromTradeParty, "setURIUniversalCommunication", $universalCommunication);
        return $this;
    }

    /**
     * Set the requested date or period on which delivery is requested
     *
     * @param DateTime $occurrenceDateTime
     * A Requested Date on which Delivery is requested
     * @param DateTime|null $startDateTime
     * The Start Date of he Requested Period on which Delivery is requested
     * @param DateTime|null $endDateTime
     * The End Date of he Requested Period on which Delivery is requested
     * @return OrderDocumentBuilder
     */
    public function setDocumentRequestedDeliverySupplyChainEvent(?DateTime $occurrenceDateTime = null, ?DateTime $startDateTime = null, ?DateTime $endDateTime = null): OrderDocumentBuilder
    {
        $supplychainevent = $this->objectHelper->getDeliverySupplyChainEvent($occurrenceDateTime, $startDateTime, $endDateTime);
        $this->objectHelper->tryCallIfMethodExists($this->headerTradeDelivery, "addToRequestedDeliverySupplyChainEvent", "setRequestedDeliverySupplyChainEvent", [$supplychainevent], $supplychainevent);
        return $this;
    }

    /**
     * Add an additional requested date or period on which delivery is requested
     *
     * @param DateTime $occurrenceDateTime
     * A Requested Date on which Delivery is requested
     * @param DateTime|null $startDateTime
     * The Start Date of he Requested Period on which Delivery is requested
     * @param DateTime|null $endDateTime
     * The End Date of he Requested Period on which Delivery is requested
     * @return OrderDocumentBuilder
     */
    public function addDocumentRequestedDeliverySupplyChainEvent(?DateTime $occurrenceDateTime = null, ?DateTime $startDateTime = null, ?DateTime $endDateTime = null): OrderDocumentBuilder
    {
        $supplychainevent = $this->objectHelper->getDeliverySupplyChainEvent($occurrenceDateTime, $startDateTime, $endDateTime);
        $this->objectHelper->tryCall($this->headerTradeDelivery, "addToRequestedDeliverySupplyChainEvent", $supplychainevent);
        return $this;
    }

    /**
     * Set detailed information on the Party to which the invoice must be sent
     *
     * @param string $name
     * The full formal name by which the party is registered in the national registry of
     * legal entities or as a Taxable person or otherwise trades as a person or persons.
     * @param string|null $id
     * An identification of the Party. The identification scheme identifier of the Party identifier.
     * @param string|null $description
     * Additional legal information relevant for the Paety.
     * @return OrderDocumentBuilder
     */
    public function setDocumentInvoicee(string $name, ?string $id = null, ?string $description = null): OrderDocumentBuilder
    {
        $invoiceeTradeParty = $this->objectHelper->getTradeParty($name, $id, $description);
        $this->objectHelper->tryCall($this->headerTradeSettlement, "setInvoiceeTradeParty", $invoiceeTradeParty);
        return $this;
    }

    /**
     * Add a global id for the Party to which the invoice must be sent
     *
     * @param string $globalID
     * Global identification number
     * @param string $globalIDType
     * Type of global identification number, must be selected from the entries in
     * the list published by the ISO / IEC 6523 Maintenance Agency.
     * @return OrderDocumentBuilder
     */
    public function addDocumentInvoiceeGlobalId(string $globalID, string $globalIDType): OrderDocumentBuilder
    {
        $invoiceeTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeSettlement, "getInvoiceeTradeParty");
        $this->objectHelper->tryCall($invoiceeTradeParty, "addToGlobalID", $this->objectHelper->getIdType($globalID, $globalIDType));
        return $this;
    }

    /**
     * Set Tax registration to the Party to which the invoice must be sent
     *
     * @param string $taxregtype
     * Scheme identifier for supplier VAT identifier
     * @param string $taxregid
     * The Seller's VAT identifier (also known as Seller VAT identification number). VAT number prefixed by a country code.
     * A VAT registered Supplier shall include his VAT ID, except when he uses a tax representative.
     * @return OrderDocumentBuilder
     */
    public function setDocumentInvoiceeTaxRegistration(string $taxregtype, string $taxregid): OrderDocumentBuilder
    {
        $invoiceeTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeSettlement, "getInvoiceeTradeParty");
        $taxreg = $this->objectHelper->getTaxRegistrationType($taxregtype, $taxregid);
        $this->objectHelper->tryCallIfMethodExists($invoiceeTradeParty, "addToSpecifiedTaxRegistration", "setSpecifiedTaxRegistration", [$taxreg], $taxreg);
        return $this;
    }

    /**
     * Add an additional Tax registration to the Party to which the invoice must be sent
     *
     * @param string $taxregtype
     * Scheme identifier for supplier VAT identifier
     * @param string $taxregid
     * The Seller's VAT identifier (also known as Seller VAT identification number). VAT number prefixed by a country code.
     * A VAT registered Supplier shall include his VAT ID, except when he uses a tax representative.
     * @return OrderDocumentBuilder
     */
    public function addDocumentInvoiceeTaxRegistration(string $taxregtype, string $taxregid): OrderDocumentBuilder
    {
        $invoiceeTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeSettlement, "getInvoiceeTradeParty");
        $taxreg = $this->objectHelper->getTaxRegistrationType($taxregtype, $taxregid);
        $this->objectHelper->tryCall($invoiceeTradeParty, "addToSpecifiedTaxRegistration", $taxreg);
        return $this;
    }

    /**
     * Sets the postal address of the Party to which the invoice must be sent
     *
     * @param string|null $lineone
     * The main line in the party's address. This is usually the street name and house number or
     * the post office box
     * @param string|null $linetwo
     * Line 2 of the party's address. This is an additional address line in an address that can be
     * used to provide additional details in addition to the main line
     * @param string|null $linethree
     * Line 3 of the party's address. This is an additional address line in an address that can be
     * used to provide additional details in addition to the main line
     * @param string|null $postcode
     * The identifier for an addressable group of properties according to the relevant postal service.
     * @param string|null $city
     * Usual name of the city or municipality in which the party's address is located
     * @param string|null $country
     * Code used to identify the country. If no tax agent is specified, this is the country in which the sales tax
     * is due. The lists of approved countries are maintained by the EN ISO 3166-1 Maintenance Agency “Codes for the
     * representation of names of countries and their subdivisions”
     * @param string|null $subdivision
     * The subdivision of a country.
     * @return OrderDocumentBuilder
     */
    public function setDocumentInvoiceeAddress(?string $lineone = null, ?string $linetwo = null, ?string $linethree = null, ?string $postcode = null, ?string $city = null, ?string $country = null, ?string $subdivision = null): OrderDocumentBuilder
    {
        $invoiceeTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeSettlement, "getInvoiceeTradeParty");
        $address = $this->objectHelper->getTradeAddress($lineone, $linetwo, $linethree, $postcode, $city, $country, $subdivision);
        $this->objectHelper->tryCall($invoiceeTradeParty, "setPostalTradeAddress", $address);
        return $this;
    }

    /**
     * Set legal organisation of the Party to which the invoice must be sent
     *
     * @param string|null $legalorgid
     * An identifier issued by an official registrar that identifies the
     * party as a legal entity or legal person. If no identification scheme ($legalorgtype) is provided,
     * it should be known to the buyer or seller party
     * @param string|null $legalorgtype
     * The identifier for the identification scheme of the legal registration of the party. In particular,
     * the following scheme codes are used: 0021 : SWIFT, 0088 : EAN, 0060 : DUNS, 0177 : ODETTE
     * @param string|null $legalorgname
     * A name by which the party is known, if different from the party's name (also known as the company name)
     * @return OrderDocumentBuilder
     */
    public function setDocumentInvoiceeLegalOrganisation(?string $legalorgid = null, ?string $legalorgtype = null, ?string $legalorgname = null): OrderDocumentBuilder
    {
        $invoiceeTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeSettlement, "getInvoiceeTradeParty");
        $legalorg = $this->objectHelper->getLegalOrganization($legalorgid, $legalorgtype, $legalorgname);
        $this->objectHelper->tryCall($invoiceeTradeParty, "setSpecifiedLegalOrganization", $legalorg);
        return $this;
    }

    /**
     * Set contact of the Party to which the invoice must be sent
     *
     * @param string|null $contactpersonname
     * Contact point for a legal entity, such as a personal name of the contact person
     * @param string|null $contactdepartmentname
     * Contact point for a legal entity, such as a name of the department or office
     * @param string|null $contactphoneno
     * A phone number for the contact point.
     * @param string|null $contactfaxno
     * A fax number for the contact point.
     * @param string|null $contactemailadd
     * An e-mail address for the contact point.
     * @param string|null $contacttypecode
     * The code specifying the type of trade contact. To be chosen from the entries in UNTDID 3139
     * @return OrderDocumentBuilder
     */
    public function setDocumentInvoiceeContact(?string $contactpersonname = null, ?string $contactdepartmentname = null, ?string $contactphoneno = null, ?string $contactfaxno = null, ?string $contactemailadd = null, ?string $contacttypecode = null): OrderDocumentBuilder
    {
        $invoiceeTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeSettlement, "getInvoiceeTradeParty");
        $contact = $this->objectHelper->getTradeContact($contactpersonname, $contactdepartmentname, $contactphoneno, $contactfaxno, $contactemailadd, $contacttypecode);
        $this->objectHelper->tryCallIfMethodExists($invoiceeTradeParty, "addToDefinedTradeContact", "setDefinedTradeContact", [$contact], $contact);
        return $this;
    }

    /**
     * Add an additional contact to the Party to which the invoice must be sent
     *
     * @param string|null $contactpersonname
     * Contact point for a legal entity, such as a personal name of the contact person
     * @param string|null $contactdepartmentname
     * Contact point for a legal entity, such as a name of the department or office
     * @param string|null $contactphoneno
     * A phone number for the contact point.
     * @param string|null $contactfaxno
     * A fax number for the contact point.
     * @param string|null $contactemailadd
     * An e-mail address for the contact point.
     * @param string|null $contacttypecode
     * The code specifying the type of trade contact. To be chosen from the entries in UNTDID 3139
     */
    public function addDocumentInvoiceeContact(?string $contactpersonname = null, ?string $contactdepartmentname = null, ?string $contactphoneno = null, ?string $contactfaxno = null, ?string $contactemailadd = null, ?string $contacttypecode = null): OrderDocumentBuilder
    {
        $invoiceeTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeSettlement, "getInvoiceeTradeParty");
        $contact = $this->objectHelper->getTradeContact($contactpersonname, $contactdepartmentname, $contactphoneno, $contactfaxno, $contactemailadd, $contacttypecode);
        $this->objectHelper->tryCall($invoiceeTradeParty, "addToDefinedTradeContact", $contact);
        return $this;
    }

    /**
     * Set the universal communication info for the Party to which the invoice must be sent
     *
     * @param string|null $uriType
     * Identifies the electronic address to which the application level response to the order may be delivered.
     * @param string|null $uriId
     * The identification scheme identifier of the electronic address. The scheme identifier shall be chosen
     * from a list to be maintained by the Connecting Europe Facility.
     * @return OrderDocumentBuilder
     */
    public function setDocumentInvoiceeElectronicAddress(?string $uriType = null, ?string $uriId = null): OrderDocumentBuilder
    {
        $invoiceeTradeParty = $this->objectHelper->tryCallAndReturn($this->headerTradeSettlement, "getInvoiceeTradeParty");
        $universalCommunication = $this->objectHelper->getUniversalCommunicationType(null, $uriId, $uriType);
        $this->objectHelper->tryCall($invoiceeTradeParty, "setURIUniversalCommunication", $universalCommunication);
        return $this;
    }

    /**
     * Set detailed information on the payment method
     *
     * @param string $paymentMeansCode
     * The means, expressed as code, for how a payment is expected to be or has been settled.
     * Entries from the UNTDID 4461 code list  shall be used. Distinction should be made between
     * SEPA and non-SEPA payments, and between credit payments, direct debits, card payments and
     * other instruments.
     * @param string|null $paymentMeansInformation
     * Such as cash, credit transfer, direct debit, credit card, etc.
     * @return OrderDocumentBuilder
     */
    public function setDocumentPaymentMean(string $paymentMeansCode, ?string $paymentMeansInformation = null): OrderDocumentBuilder
    {
        $paymentMeans = $this->objectHelper->getTradeSettlementPaymentMeansType($paymentMeansCode, $paymentMeansInformation);
        $this->objectHelper->tryCallIfMethodExists($this->headerTradeSettlement, "addToSpecifiedTradeSettlementPaymentMeans", "setSpecifiedTradeSettlementPaymentMeans", [$paymentMeans], $paymentMeans);
        return $this;
    }

    /**
     * Add additional information on the payment method
     *
     * @param string $paymentMeansCode
     * The means, expressed as code, for how a payment is expected to be or has been settled.
     * Entries from the UNTDID 4461 code list  shall be used. Distinction should be made between
     * SEPA and non-SEPA payments, and between credit payments, direct debits, card payments and
     * other instruments.
     * @param string|null $paymentMeansInformation
     * Such as cash, credit transfer, direct debit, credit card, etc.
     * @return OrderDocumentBuilder
     */
    public function addDocumentPaymentMean(string $paymentMeansCode, ?string $paymentMeansInformation = null): OrderDocumentBuilder
    {
        $paymentMeans = $this->objectHelper->getTradeSettlementPaymentMeansType($paymentMeansCode, $paymentMeansInformation);
        $this->objectHelper->tryCall($this->headerTradeSettlement, "addToSpecifiedTradeSettlementPaymentMeans", $paymentMeans);
        return $this;
    }

    /**
     * Add a payment term
     *
     * @param string $paymentTermsDescription
     * A text description of the payment terms that apply to the payment amount due (including a
     * description of possible penalties). Note: This element can contain multiple lines and
     * multiple conditions.
     * @return OrderDocumentBuilder
     */
    public function setDocumentPaymentTerm(string $paymentTermsDescription): OrderDocumentBuilder
    {
        if ($this->profileId == OrderProfiles::PROFILE_EXTENDED) {
            $paymentTerms = $paymentTermsDescription;
        } else {
            $paymentTerms = $this->objectHelper->getTradePaymentTermsType($paymentTermsDescription);
        }
        $this->objectHelper->tryCallIfMethodExists($this->headerTradeSettlement, "addToSpecifiedTradePaymentTerms", "setSpecifiedTradePaymentTerms", [$paymentTerms], $paymentTerms);
        $this->currentPaymentTerms = $paymentTerms;
        return $this;
    }

    /**
     * Add an additional payment term
     *
     * @param string $paymentTermsDescription
     * A text description of the payment terms that apply to the payment amount due (including a
     * description of possible penalties). Note: This element can contain multiple lines and
     * multiple conditions.
     * @return OrderDocumentBuilder
     */
    public function addDocumentPaymentTerm(string $paymentTermsDescription): OrderDocumentBuilder
    {
        if ($this->profileId == OrderProfiles::PROFILE_EXTENDED) {
            $paymentTerms = $paymentTermsDescription;
        } else {
            $paymentTerms = $this->objectHelper->getTradePaymentTermsType($paymentTermsDescription);
        }
        $this->objectHelper->tryCall($this->headerTradeSettlement, "addToSpecifiedTradePaymentTerms", $paymentTerms);
        $this->currentPaymentTerms = $paymentTerms;
        return $this;
    }

    /**
     * Add a VAT breakdown (at document level)
     *
     * @param string $categoryCode
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
     * @param string $typeCode
     * Coded description of a sales tax category. Note: Fixed value = "VAT"
     * @param float $basisAmount
     * Tax base amount, Each sales tax breakdown must show a category-specific tax base amount.
     * @param float $calculatedAmount
     * The total amount to be paid for the relevant VAT category. Note: Calculated by multiplying
     * the amount to be taxed according to the sales tax category by the sales tax rate applicable
     * for the sales tax category concerned
     * @param float|null $rateApplicablePercent
     * The sales tax rate, expressed as the percentage applicable to the sales tax category in
     * question. Note: The code of the sales tax category and the category-specific sales tax rate
     * must correspond to one another. The value to be given is the percentage. For example, the
     * value 20 is given for 20% (and not 0.2)
     * @param string|null $exemptionReason
     * Reason for tax exemption (free text)
     * @param string|null $exemptionReasonCode
     * Reason given in code form for the exemption of the amount from VAT. Note: Code list issued
     * and maintained by the Connecting Europe Facility.
     * @param float|null $lineTotalBasisAmount
     * Tax rate goods amount
     * @param float|null $allowanceChargeBasisAmount
     * Total amount of surcharges and deductions of the tax rate at document level
     * @param string|null $dueDateTypeCode
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
     * @return OrderDocumentBuilder
     */
    public function addDocumentTax(string $categoryCode, string $typeCode, float $basisAmount, float $calculatedAmount, ?float $rateApplicablePercent = null, ?string $exemptionReason = null, ?string $exemptionReasonCode = null, ?float $lineTotalBasisAmount = null, ?float $allowanceChargeBasisAmount = null, ?string $dueDateTypeCode = null): OrderDocumentBuilder
    {
        $tax = $this->objectHelper->getTradeTaxType($categoryCode, $typeCode, $basisAmount, $calculatedAmount, $rateApplicablePercent, $exemptionReason, $exemptionReasonCode, $lineTotalBasisAmount, $allowanceChargeBasisAmount, $dueDateTypeCode);
        $this->objectHelper->tryCall($this->headerTradeSettlement, "addToApplicableTradeTax", $tax);
        return $this;
    }

    /**
     * Add a VAT breakdown (at document level) in a more simple way
     *
     * @param string $categoryCode
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
     * @param string $typeCode
     * Coded description of a sales tax category. Note: Fixed value = "VAT"
     * @param float $basisAmount
     * Tax base amount, Each sales tax breakdown must show a category-specific tax base amount.
     * @param float $calculatedAmount
     * The total amount to be paid for the relevant VAT category. Note: Calculated by multiplying
     * the amount to be taxed according to the sales tax category by the sales tax rate applicable
     * for the sales tax category concerned
     * @param float|null $rateApplicablePercent
     * The sales tax rate, expressed as the percentage applicable to the sales tax category in
     * question. Note: The code of the sales tax category and the category-specific sales tax rate
     * must correspond to one another. The value to be given is the percentage. For example, the
     * value 20 is given for 20% (and not 0.2)
     * @return OrderDocumentBuilder
     */
    public function addDocumentTaxSimple(string $categoryCode, string $typeCode, float $basisAmount, float $calculatedAmount, ?float $rateApplicablePercent = null): OrderDocumentBuilder
    {
        return $this->addDocumentTax($categoryCode, $typeCode, $basisAmount, $calculatedAmount, $rateApplicablePercent);
    }

    /**
     * Set information about surcharges and charges applicable to the bill as a whole, Deductions,
     * such as for withheld taxes may also be specified in this group
     *
     * @param float $actualAmount
     * Amount of the surcharge or discount at document level
     * @param boolean $isCharge
     * Switch that indicates whether the following data refer to an surcharge or a discount, true means that
     * this an charge
     * @param string|null $taxCategoryCode
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
     * @param string|null $taxTypeCode
     * Code for the VAT category of the surcharge or charge at document level. Note: Fixed value = "VAT"
     * @param float|null $rateApplicablePercent
     * VAT rate for the surcharge or discount on document level. Note: The code of the sales tax category
     * and the category-specific sales tax rate must correspond to one another. The value to be given is
     * the percentage. For example, the value 20 is given for 20% (and not 0.2)
     * @param float|null $sequence
     * Calculation order
     * @param float|null $calculationPercent
     * Percentage surcharge or discount at document level
     * @param float|null $basisAmount
     * The base amount that may be used in conjunction with the percentage of the surcharge or discount
     * at document level to calculate the amount of the discount at document level
     * @param float|null $basisQuantity
     * Basismenge des Rabatts
     * @param string|null $basisQuantityUnitCode
     * Einheit der Preisbasismenge
     *  - Codeliste: Rec. N°20 Vollständige Liste, In Recommendation N°20 Intro 2.a ist beschrieben, dass
     *    beide Listen kombiniert anzuwenden sind.
     *  - Codeliste: Rec. N°21 Vollständige Liste, In Recommendation N°20 Intro 2.a ist beschrieben, dass
     *    beide Listen kombiniert anzuwenden sind.
     * @param string|null $reasonCode
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
     * @param string|null $reason
     * The reason given in text form for the surcharge or discount at document level
     * @return OrderDocumentBuilder
     */
    public function setDocumentAllowanceCharge(float $actualAmount, bool $isCharge, ?string $taxCategoryCode = null, ?string $taxTypeCode = null, ?float $rateApplicablePercent = null, ?float $sequence = null, ?float $calculationPercent = null, ?float $basisAmount = null, ?float $basisQuantity = null, ?string $basisQuantityUnitCode = null, ?string $reasonCode = null, ?string $reason = null): OrderDocumentBuilder
    {
        $allowanceCharge = $this->objectHelper->getTradeAllowanceChargeType($actualAmount, $isCharge, $taxTypeCode, $taxCategoryCode, $rateApplicablePercent, $sequence, $calculationPercent, $basisAmount, $basisQuantity, $basisQuantityUnitCode, $reasonCode, $reason);
        $this->objectHelper->tryCallIfMethodExists($this->headerTradeSettlement, "addToSpecifiedTradeAllowanceCharge", "setSpecifiedTradeAllowanceCharge", [$allowanceCharge], $allowanceCharge);
        return $this;
    }

    /**
     * Add additional information about surcharges and charges applicable to the bill as a whole, Deductions,
     * such as for withheld taxes may also be specified in this group
     *
     * @param float $actualAmount
     * @param boolean $isCharge
     * @param string|null $taxCategoryCode
     * @param string|null $taxTypeCode
     * @param float|null $rateApplicablePercent
     * @param float|null $sequence
     * @param float|null $calculationPercent
     * @param float|null $basisAmount
     * @param float|null $basisQuantity
     * @param string|null $basisQuantityUnitCode
     * @param string|null $reasonCode
     * @param string|null $reason
     * @return OrderDocumentBuilder
     */
    public function addDocumentAllowanceCharge(float $actualAmount, bool $isCharge, ?string $taxCategoryCode = null, ?string $taxTypeCode = null, ?float $rateApplicablePercent = null, ?float $sequence = null, ?float $calculationPercent = null, ?float $basisAmount = null, ?float $basisQuantity = null, ?string $basisQuantityUnitCode = null, ?string $reasonCode = null, ?string $reason = null): OrderDocumentBuilder
    {
        $allowanceCharge = $this->objectHelper->getTradeAllowanceChargeType($actualAmount, $isCharge, $taxTypeCode, $taxCategoryCode, $rateApplicablePercent, $sequence, $calculationPercent, $basisAmount, $basisQuantity, $basisQuantityUnitCode, $reasonCode, $reason);
        $this->objectHelper->tryCall($this->headerTradeSettlement, "addToSpecifiedTradeAllowanceCharge", $allowanceCharge);
        return $this;
    }

    /**
     * Set an AccountingAccount
     *
     * @param string $id
     * A textual value that specifies where to book the relevant data into the Buyer's financial accounts.
     * @param string|null $typeCode
     * The code specifying the type of trade accounting account, such as general (main), secondary, cost accounting or budget account.
     * @return OrderDocumentBuilder
     */
    public function setDocumentReceivableSpecifiedTradeAccountingAccount(string $id, ?string $typeCode = null): OrderDocumentBuilder
    {
        $account = $this->objectHelper->getTradeAccountingAccountType($id, $typeCode);
        $this->objectHelper->tryCall($this->headerTradeSettlement, "setReceivableSpecifiedTradeAccountingAccount", $account);
        return $this;
    }

    /**
     * Adds a new position (line) to document
     *
     * @param string $lineid
     * A unique identifier for the relevant item within the invoice (item number)
     * @param string|null $lineStatusCode
     * The code specifying the status of this document line
     * To be chosen from the entries in UNTDID 1229, in particular:
     *  -  1 : Order line ADDED
     *  -  3 : Order line CHANGED
     *  -  5 : Order line ACCEPTED WITHOUT AMENDMENT
     *  -  6 : Order line ACCEPTED WITH AMENDMENT
     *  -  7 : Order line NOT ACCEPTED
     *  - 42 : Order line ALREADY DELIVERED
     * @return OrderDocumentBuilder
     */
    public function addNewPosition(string $lineid, ?string $lineStatusCode = null): OrderDocumentBuilder
    {
        $position = $this->objectHelper->getSupplyChainTradeLineItemType($lineid, $lineStatusCode);
        $this->objectHelper->tryCall($this->headerSupplyChainTradeTransaction, "addToIncludedSupplyChainTradeLineItem", $position);
        $this->currentPosition = $position;
        return $this;
    }

    /**
     * Remove the latest position
     *
     * @return OrderDocumentBuilder
     */
    public function removeLatestPosition(): OrderDocumentBuilder
    {
        $positions = $this->objectHelper->tryCallAndReturn($this->headerSupplyChainTradeTransaction, "getIncludedSupplyChainTradeLineItem");
        $noOfPositions = count($positions);

        if ($noOfPositions == 0) {
            return $this;
        }

        $this->objectHelper->tryCall($this->headerSupplyChainTradeTransaction, "unsetIncludedSupplyChainTradeLineItem", $noOfPositions - 1);

        $positions = $this->objectHelper->tryCallAndReturn($this->headerSupplyChainTradeTransaction, "getIncludedSupplyChainTradeLineItem");
        $noOfPositions = count($positions);

        if ($noOfPositions > 0) {
            $this->currentPosition = $positions[$noOfPositions - 1];
        } else {
            $this->currentPosition = null;
        }

        return $this;
    }

    /**
     * Add detailed information on the free text on the position
     *
     * @param string|null $content
     * A free text that contains unstructured information that is relevant to the invoice item
     * @param string|null $contentCode
     * Text modules agreed bilaterally, which are transmitted here as code.
     * @param string|null $subjectCode
     * Free text for the position (code for the type)
     * __Codelist:__ UNTDID 4451
     * @return OrderDocumentBuilder
     */
    public function setDocumentPositionNote(?string $content = null, ?string $contentCode = null, ?string $subjectCode = null): OrderDocumentBuilder
    {
        $linedoc = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getAssociatedDocumentLineDocument");
        $note = $this->objectHelper->getNoteType($content, $contentCode, $subjectCode);
        $this->objectHelper->tryCallAll($linedoc, ["addToIncludedNote", "setIncludedNote"], $note);
        return $this;
    }

    /**
     * Set product details to the last created position (line) in the document
     *
     * @param string|null $name
     * A name of the item (item name)
     * @param string|null $description
     * A textual description of a use of this item.
     * @param string|null $sellerAssignedID
     * An identifier assigned to the item by the seller
     * @param string|null $buyerAssignedID
     * An identifier, assigned by the Buyer, for the item.
     * @param string|null $globalIDType
     * Identifiant du schéma de l'identifiant standard de l'article
     * The identification scheme shall be identified from the entries of the list published by the ISO/IEC 6523 maintenance agency.
     * @param string|null $globalID
     * An item identifier based on a registered scheme.
     * @param string|null $batchId
     * A batch identifier for this item.
     * @param string|null $brandName
     * The brand name, expressed as text, for this item.
     * @return OrderDocumentBuilder
     */
    public function setDocumentPositionProductDetails(?string $name = null, ?string $description = null, ?string $sellerAssignedID = null, ?string $buyerAssignedID = null, ?string $globalIDType = null, ?string $globalID = null, ?string $batchId = null, ?string $brandName = null): OrderDocumentBuilder
    {
        $product = $this->objectHelper->getTradeProductType($name, $description, $sellerAssignedID, $buyerAssignedID, $globalIDType, $globalID, $batchId, $brandName);
        $this->objectHelper->tryCall($this->currentPosition, "setSpecifiedTradeProduct", $product);
        return $this;
    }

    /**
     * Set (single) extra characteristics to the formerly added product.
     * Contains information about the characteristics of the goods and services invoiced
     *
     * @param string $description
     * The name of the attribute or property of the product such as "Colour"
     * @param string $value
     * The value of the attribute or property of the product such as "Red"
     * @param string|null $typecode
     * Type of product property (code). The codes must be taken from the
     * UNTDID 6313 codelist. Available only in the Extended-Profile
     * @param float|null $measureValue
     * A measure of a value for this product characteristic.
     * @param string|null $measureUnitCode
     * A unit for the measure value for this product characteristic. To be chosen from the entries in UNTDID xxx
     * @return OrderDocumentBuilder
     */
    public function setDocumentPositionProductCharacteristic(string $description, string $value, ?string $typecode = null, ?float $measureValue = null, ?string $measureUnitCode = null): OrderDocumentBuilder
    {
        $product = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedTradeProduct");
        $productCharacteristic = $this->objectHelper->getProductCharacteristicType($typecode, $description, $value);
        $this->objectHelper->tryCallIfMethodExists($product, "addToApplicableProductCharacteristic", "setApplicableProductCharacteristic", [$productCharacteristic], $productCharacteristic);
        return $this;
    }

    /**
     * Add extra characteristics to the formerly added product.
     * Contains information about the characteristics of the goods and services invoiced
     *
     * @param string $description
     * The name of the attribute or property of the product such as "Colour"
     * @param string $value
     * The value of the attribute or property of the product such as "Red"
     * @param string|null $typecode
     * Type of product property (code). The codes must be taken from the
     * UNTDID 6313 codelist. Available only in the Extended-Profile
     * @param float|null $measureValue
     * A measure of a value for this product characteristic.
     * @param string|null $measureUnitCode
     * A unit for the measure value for this product characteristic. To be chosen from the entries in UNTDID xxx
     * @return OrderDocumentBuilder
     */
    public function addDocumentPositionProductCharacteristic(string $description, string $value, ?string $typecode = null, ?float $measureValue = null, ?string $measureUnitCode = null): OrderDocumentBuilder
    {
        $product = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedTradeProduct");
        $productCharacteristic = $this->objectHelper->getProductCharacteristicType($typecode, $description, $value);
        $this->objectHelper->tryCall($product, "addToApplicableProductCharacteristic", $productCharacteristic);
        return $this;
    }

    /**
     * Set detailed information on product classification
     *
     * @param string $classCode
     * A code for classifying the item by its type or nature.
     * Classification codes are used to allow grouping of similar items for a various purposes e.g.
     * public procurement (CPV), e-Commerce (UNSPSC) etc. The identification scheme shall be chosen
     * from the entries in UNTDID 7143
     * @param string|null $className
     * A class name, expressed as text, for this product classification
     * @param string|null $listID
     * The identification scheme identifier of Item classification identifier
     * Identification scheme must be chosen among the values available in UNTDID 7143
     * @param string|null $listVersionID
     * Scheme version identifier
     * @return OrderDocumentBuilder
     */
    public function setDocumentPositionProductClassification(string $classCode, ?string $className = null, ?string $listID = null, ?string $listVersionID = null): OrderDocumentBuilder
    {
        $product = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedTradeProduct");
        $productClassification = $this->objectHelper->getProductClassificationType($classCode, $className, $listID, $listVersionID);
        $this->objectHelper->tryCallIfMethodExists($product, "addToDesignatedProductClassification", "setDesignatedProductClassification", [$productClassification], $productClassification);
        return $this;
    }

    /**
     * Add one more detailed information on product classification
     *
     * @param string $classCode
     * A code for classifying the item by its type or nature.
     * Classification codes are used to allow grouping of similar items for a various purposes e.g.
     * public procurement (CPV), e-Commerce (UNSPSC) etc. The identification scheme shall be chosen
     * from the entries in UNTDID 7143
     * @param string|null $className
     * A class name, expressed as text, for this product classification
     * @param string|null $listID
     * The identification scheme identifier of Item classification identifier
     * Identification scheme must be chosen among the values available in UNTDID 7143
     * @param string|null $listVersionID
     * Scheme version identifier
     * @return OrderDocumentBuilder
     */
    public function addDocumentPositionProductClassification(string $classCode, ?string $className = null, ?string $listID = null, ?string $listVersionID = null): OrderDocumentBuilder
    {
        $product = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedTradeProduct");
        $productClassification = $this->objectHelper->getProductClassificationType($classCode, $className, $listID, $listVersionID);
        $this->objectHelper->tryCall($product, "addToDesignatedProductClassification", $productClassification);
        return $this;
    }

    /**
     * Set the unique batch identifier for this trade product instance and
     * the unique supplier assigned serial identifier for this trade product instance.
     *
     * @param string $batchID
     * The unique batch identifier for this trade product instance
     * @param string|null $serialId
     * The unique supplier assigned serial identifier for this trade product instance.
     * @return OrderDocumentBuilder
     */
    public function setDocumentPositionProductInstance(?string $batchID = null, ?string $serialId = null): OrderDocumentBuilder
    {
        $product = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedTradeProduct");
        $productInstance = $this->objectHelper->getTradeProductInstanceType($batchID, $serialId);
        $this->objectHelper->tryCallIfMethodExists($product, "addToIndividualTradeProductInstance", "setIndividualTradeProductInstance", [$productInstance], $productInstance);
        return $this;
    }

    /**
     * Add a new unique batch identifier for this trade product instance and
     * the unique supplier assigned serial identifier for this trade product instance.
     *
     * @param string $batchID
     * The unique batch identifier for this trade product instance
     * @param string|null $serialId
     * The unique supplier assigned serial identifier for this trade product instance.
     * @return OrderDocumentBuilder
     */
    public function addDocumentPositionProductInstance(?string $batchID = null, ?string $serialId = null): OrderDocumentBuilder
    {
        $product = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedTradeProduct");
        $productInstance = $this->objectHelper->getTradeProductInstanceType($batchID, $serialId);
        $this->objectHelper->tryCall($product, "addToIndividualTradeProductInstance", $productInstance);
        return $this;
    }

    /**
     * Specify the supply chain packaging
     *
     * @param string|null $typeCode
     * The code specifying the type of supply chain packaging.
     * To be chosen from the entries in UNTDID 7065
     * @param float|null $width
     * The measure of the width component of this spatial dimension.
     * @param string|null $widthUnitCode
     * Unit Code of the measure of the width component of this spatial dimension.
     * @param float|null $length
     * The measure of the length component of this spatial dimension.
     * @param string|null $lengthUnitCode
     * Unit Code of the measure of the Length component of this spatial dimension.
     * @param float|null $height
     * The measure of the height component of this spatial dimension.
     * @param string|null $heightUnitCode
     * Unit Code of the measure of the Height component of this spatial dimension.
     * @return OrderDocumentBuilder
     */
    public function setDocumentPositionSupplyChainPackaging(?string $typeCode = null, ?float $width = null, ?string $widthUnitCode = null, ?float $length = null, ?string $lengthUnitCode = null, ?float $height = null, ?string $heightUnitCode = null): OrderDocumentBuilder
    {
        $product = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedTradeProduct");
        $packaging = $this->objectHelper->getSupplyChainPackagingType($typeCode, $width, $widthUnitCode, $length, $lengthUnitCode, $height, $heightUnitCode);
        $this->objectHelper->tryCall($product, "setApplicableSupplyChainPackaging", $packaging);
        return $this;
    }

    /**
     * Set information on the product origin
     *
     * @param string $country
     * The code identifying the country from which the item originates.
     * The lists of valid countries are registered with the EN ISO 3166-1 Maintenance agency, “Codes for the
     * representation of names of countries and their subdivisions”.
     * @return OrderDocumentBuilder
     */
    public function setDocumentPositionProductOriginTradeCountry(string $country): OrderDocumentBuilder
    {
        $product = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedTradeProduct");
        $productTradeCounty = $this->objectHelper->getTradeCountryType($country);
        $this->objectHelper->tryCall($product, "setOriginTradeCountry", $productTradeCounty);
        return $this;
    }

    /**
     * Set an additional product reference document at position level
     *
     * @param string|null $issuerassignedid
     * The unique issuer assigned identifier for this referenced document.
     * @param string|null $typecode
     * The code specifying the type of referenced document.
     * To be chosen from the entries in UNTDID 1001
     * @param string|null $uriid
     * The unique Uniform Resource Identifier (URI) for this referenced document.
     * @param string|null $lineid
     * @param string|null $name
     * A name, expressed as text, for this referenced document.
     * @param string|null $reftypecode
     * @param DateTime|null $issueddate
     * @param string|null $binarydatafilename
     * @return OrderDocumentBuilder
     */
    public function setDocumentPositionProductReferencedDocument(?string $issuerassignedid = null, ?string $typecode = null, ?string $uriid = null, ?string $lineid = null, ?string $name = null, ?string $reftypecode = null, ?DateTime $issueddate = null, ?string $binarydatafilename = null): OrderDocumentBuilder
    {
        $product = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedTradeProduct");
        $tradeLineItemProdRefDoc = $this->objectHelper->getReferencedDocumentType($issuerassignedid, $uriid, $lineid, $typecode, $name, $reftypecode, $issueddate, $binarydatafilename);
        $this->objectHelper->tryCallIfMethodExists($product, "addToAdditionalReferenceReferencedDocument", "setAdditionalReferenceReferencedDocument", [$tradeLineItemProdRefDoc], $tradeLineItemProdRefDoc);
        return $this;
    }

    /**
     * Add an additional product reference document at position level
     *
     * @param string|null $issuerassignedid
     * The unique issuer assigned identifier for this referenced document.
     * @param string|null $typecode
     * The code specifying the type of referenced document.
     * @param string|null $uriid
     * The unique Uniform Resource Identifier (URI) for this referenced document.
     * @param string|null $lineid
     * @param string|null $name
     * A name, expressed as text, for this referenced document.
     * @param string|null $reftypecode
     * @param DateTime|null $issueddate
     * @param string|null $binarydatafilename
     * @return OrderDocumentBuilder
     */
    public function addDocumentPositionProductReferencedDocument(?string $issuerassignedid = null, ?string $typecode = null, ?string $uriid = null, ?string $lineid = null, ?string $name = null, ?string $reftypecode = null, ?DateTime $issueddate = null, ?string $binarydatafilename = null): OrderDocumentBuilder
    {
        $product = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedTradeProduct");
        $contractrefdoc = $this->objectHelper->getReferencedDocumentType($issuerassignedid, $uriid, $lineid, $typecode, $name, $reftypecode, $issueddate, $binarydatafilename);
        $this->objectHelper->tryCall($product, "addToAdditionalReferenceReferencedDocument", $contractrefdoc);
        return $this;
    }

    /**
     * Add an additional Document reference on a position
     *
     * @param string|null $issuerassignedid
     * The identifier of the tender or lot to which the invoice relates, or an identifier specified by the seller for
     * an object on which the invoice is based, or an identifier of the document on which the invoice is based.
     * @param string|null $typecode
     * Type of referenced document (See codelist UNTDID 1001)
     *  - Code 916 "reference paper" is used to reference the identification of the document on which the invoice is based
     *  - Code 50 "Price / sales catalog response" is used to reference the tender or the lot
     *  - Code 130 "invoice data sheet" is used to reference an identifier for an object specified by the seller.
     * @param string|null $uriid
     * The Uniform Resource Locator (URL) at which the external document is available. A means of finding the resource
     * including the primary access method intended for it, e.g. http: // or ftp: //. The location of the external document
     * must be used if the buyer needs additional information to support the amounts billed. External documents are not part
     * of the invoice. Access to external documents can involve certain risks.
     * @param string|null $lineid
     * The referenced position identifier in the additional document
     * @param string|null $name
     * A description of the document, e.g. Hourly billing, usage or consumption report, etc.
     * @param string|null $reftypecode
     * The identifier for the identification scheme of the identifier of the item invoiced. If it is not clear to the
     * recipient which scheme is used for the identifier, an identifier of the scheme should be used, which must be selected
     * from UNTDID 1153 in accordance with the code list entries.
     * @param DateTime|null $issueddate
     * Document date
     * @param string|null $binarydatafilename
     * Contains a file name of an attachment document embedded as a binary object
     * @return OrderDocumentBuilder
     */
    public function addDocumentPositionAdditionalReferencedDocument(?string $issuerassignedid = null, ?string $typecode = null, ?string $uriid = null, ?string $lineid = null, ?string $name = null, ?string $reftypecode = null, ?DateTime $issueddate = null, ?string $binarydatafilename = null): OrderDocumentBuilder
    {
        $additionalRefDoc = $this->objectHelper->getReferencedDocumentType($issuerassignedid, $uriid, $lineid, $typecode, $name, $reftypecode, $issueddate, $binarydatafilename);
        $positionAgreement = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedLineTradeAgreement");
        $this->objectHelper->tryCall($positionAgreement, "addToAdditionalReferencedDocument", $additionalRefDoc);
        return $this;
    }

    /**
     * Set details of the related buyer order position
     *
     * @param string $buyerOrderRefLineId
     * An identifier for a position within an order placed by the buyer. Note: Reference is made to the order
     * reference at the document level.
     * @return OrderDocumentBuilder
     */
    public function setDocumentPositionBuyerOrderReferencedDocument(string $buyerOrderRefLineId): OrderDocumentBuilder
    {
        $buyerOrderRefDoc = $this->objectHelper->getReferencedDocumentType(null, null, $buyerOrderRefLineId, null, null, null, null, null);
        $positionAgreement = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedLineTradeAgreement");
        $this->objectHelper->tryCall($positionAgreement, "setBuyerOrderReferencedDocument", $buyerOrderRefDoc);
        return $this;
    }

    /**
     * Set details of the related quotation position
     *
     * @param string|null $quotationRefId
     * The quotation document referenced in this line trade agreement
     * @param string|null $quotationRefLineId
     * The unique identifier of a line in this Quotation referenced document
     * @param DateTime|null $quotationRefDate
     * The formatted date or date time for the issuance of this referenced Quotation.
     * @return OrderDocumentBuilder
     */
    public function setDocumentPositionQuotationReferencedDocument(?string $quotationRefId = null, ?string $quotationRefLineId = null, ?DateTime $quotationRefDate = null): OrderDocumentBuilder
    {
        $quotationRefDoc = $this->objectHelper->getReferencedDocumentType($quotationRefId, null, $quotationRefLineId, null, null, null, $quotationRefDate, null);
        $positionAgreement = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedLineTradeAgreement");
        $this->objectHelper->tryCall($positionAgreement, "setQuotationReferencedDocument", $quotationRefDoc);
        return $this;
    }

    /**
     * Set the unit price excluding sales tax before deduction of the discount on the item price.
     *
     * @param float $chargeAmount
     * The unit price excluding sales tax before deduction of the discount on the item price.
     * Note: If the price is shown according to the net calculation, the price must also be shown
     * according to the gross calculation.
     * @param float|null $basisQuantity
     * The number of item units for which the price applies (price base quantity)
     * @param string|null $basisQuantityUnitCode
     * The unit code of the number of item units for which the price applies (price base quantity)
     * @return OrderDocumentBuilder
     */
    public function setDocumentPositionGrossPrice(float $chargeAmount, ?float $basisQuantity = null, ?string $basisQuantityUnitCode = null): OrderDocumentBuilder
    {
        $grossPrice = $this->objectHelper->getTradePriceType($chargeAmount, $basisQuantity, $basisQuantityUnitCode);
        $positionAgreement = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedLineTradeAgreement");
        $this->objectHelper->tryCall($positionAgreement, "setGrossPriceProductTradePrice", $grossPrice);
        return $this;
    }

    /**
     * Add detailed information on surcharges and discounts
     *
     * @param float $actualAmount
     * Discount on the item price. The total discount subtracted from the gross price to calculate the
     * net price. Note: Only applies if the discount is given per unit and is not included in the gross price.
     * @param boolean $isCharge
     * Switch for surcharge/discount, if true then its an charge
     * @param float|null $calculationPercent
     * Discount/surcharge in percent. Up to level EN16931, only the final result of the discount (ActualAmount)
     * is transferred
     * @param float|null $basisAmount
     * Base amount of the discount/surcharge
     * @param string|null $reason
     * Reason for surcharge/discount (free text)
     * @param string|null $taxTypeCode
     * @param string|null $taxCategoryCode
     * @param float|null $rateApplicablePercent
     * @param float|null $sequence
     * @param float|null $basisQuantity
     * @param string|null $basisQuantityUnitCode
     * @param string|null $reasonCode
     * @return OrderDocumentBuilder
     */
    public function addDocumentPositionGrossPriceAllowanceCharge(float $actualAmount, bool $isCharge, ?float $calculationPercent = null, ?float $basisAmount = null, ?string $reason = null, ?string $taxTypeCode = null, ?string $taxCategoryCode = null, ?float $rateApplicablePercent = null, ?float $sequence = null, ?float $basisQuantity = null, ?string $basisQuantityUnitCode = null, ?string $reasonCode = null): OrderDocumentBuilder
    {
        $positionAgreement = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedLineTradeAgreement");
        $grossPrice = $this->objectHelper->tryCallAndReturn($positionAgreement, "getGrossPriceProductTradePrice");
        $allowanceCharge = $this->objectHelper->getTradeAllowanceChargeType($actualAmount, $isCharge, $taxTypeCode, $taxCategoryCode, $rateApplicablePercent, $sequence, $calculationPercent, $basisAmount, $basisQuantity, $basisQuantityUnitCode, $reasonCode, $reason);
        $this->objectHelper->tryCallAll($grossPrice, ["addToAppliedTradeAllowanceCharge", "setAppliedTradeAllowanceCharge"], $allowanceCharge);
        return $this;
    }

    /**
     * Detailed information on surcharges and discounts on item gross price
     *
     * @param float $actualAmount
     * Discount on the item price. The total discount subtracted from the gross price to calculate the
     * net price. Note: Only applies if the discount is given per unit and is not included in the gross price.
     * @param boolean $isCharge
     * Switch for surcharge/discount, if true then its an charge
     * @param string|null $reason
     * The reason for the order line item trade price charge expressed as text.
     * @param string|null $reasonCode
     * The reason for the order line item trade price charge, expressed as a code.
     * Use entries of the UNTDID 5189 and UNTDID 7161 code list . The order line level item trade price discount reason code
     * and the order line level item trade price discount reason shall indicate the same item trade price
     * charge reason. Example AEW for WEEE.
     * @return OrderDocumentBuilder
     */
    public function addDocumentPositionGrossPriceAllowanceChargeSimple(float $actualAmount, bool $isCharge, ?string $reason = null, ?string $reasonCode = null): OrderDocumentBuilder
    {
        $positionAgreement = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedLineTradeAgreement");
        $grossPrice = $this->objectHelper->tryCallAndReturn($positionAgreement, "getGrossPriceProductTradePrice");
        $allowanceCharge = $this->objectHelper->getTradeAllowanceChargeType($actualAmount, $isCharge, null, null, null, null, null, null, null, null, $reasonCode, $reason);
        $this->objectHelper->tryCallAll($grossPrice, ["addToAppliedTradeAllowanceCharge", "setAppliedTradeAllowanceCharge"], $allowanceCharge);
        return $this;
    }

    /**
     * Set detailed information on the net price of the item
     *
     * @param float $chargeAmount
     * The price of an item, exclusive of VAT, after subtracting item price discount.
     * The Item net price has to be equal with the Item gross price less the Item price discount.
     * @param float|null $basisQuantity
     * The number of item units to which the price applies.
     * @param string|null $basisQuantityUnitCode
     * The unit of measure that applies to the Item price base quantity.
     * The Item price base quantity unit of measure shall be the same as the requested quantity unit of measure.
     * @return OrderDocumentBuilder
     */
    public function setDocumentPositionNetPrice(float $chargeAmount, ?float $basisQuantity = null, ?string $basisQuantityUnitCode = null): OrderDocumentBuilder
    {
        $netPrice = $this->objectHelper->getTradePriceType($chargeAmount, $basisQuantity, $basisQuantityUnitCode);
        $positionAgreement = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedLineTradeAgreement");
        $this->objectHelper->tryCall($positionAgreement, "setNetPriceProductTradePrice", $netPrice);
        return $this;
    }

    /**
     * Set tax included in this trade price (for instance in case of other Tax, or B2C Order with VAT)
     *
     * @param string $categoryCode
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
     * @param string $typeCode
     * The code specifying the type of trade related tax, levy or duty, such as a code for a Value Added Tax (VAT).
     * Reference United Nations Code List (UNCL) 5153
     * Value = VAT for VAT, ENV for Environmental, EXC for excise duty
     * @param float|null $rateApplicablePercent
     * The VAT rate, represented as percentage that applies to the ordered item.
     * @param float|null $calculatedAmount
     * A monetary value resulting from the calculation of this trade related tax, levy or duty.
     * @param string|null $exemptionReason
     * Reason for tax exemption (free text)
     * @param string|null $exemptionReasonCode
     * Reason given in code form for the exemption of the amount from VAT. Note: Code list issued
     * and maintained by the Connecting Europe Facility.
     * @return OrderDocumentBuilder
     */
    public function setDocumentPositionNetPriceTax(string $categoryCode, string $typeCode, ?float $rateApplicablePercent = null, ?float $calculatedAmount = null, ?string $exemptionReason = null, ?string $exemptionReasonCode = null): OrderDocumentBuilder
    {
        $positionagreement = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedLineTradeAgreement");
        $netPrice = $this->objectHelper->tryCallAndReturn($positionagreement, "getNetPriceProductTradePrice");
        $tax = $this->objectHelper->getTradeTaxType($categoryCode, $typeCode, null, $calculatedAmount, $rateApplicablePercent, $exemptionReason, $exemptionReasonCode, null, null, null, null);
        $this->objectHelper->tryCallIfMethodExists($netPrice, "addToIncludedTradeTax", "setIncludedTradeTax", [$tax], $tax);
        return $this;
    }

    /**
     * Add a additional tax included in this trade price (for instance in case of other Tax, or B2C Order with VAT)
     * For detailed information see __setDocumentPositionNetPriceTax__
     *
     * @return OrderDocumentBuilder
     */
    public function addDocumentPositionNetPriceTax(string $categoryCode, string $typeCode, ?float $rateApplicablePercent = null, ?float $calculatedAmount = null, ?string $exemptionReason = null, ?string $exemptionReasonCode = null): OrderDocumentBuilder
    {
        $positionagreement = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedLineTradeAgreement");
        $netPrice = $this->objectHelper->tryCallAndReturn($positionagreement, "getNetPriceProductTradePrice");
        $tax = $this->objectHelper->getTradeTaxType($categoryCode, $typeCode, null, $calculatedAmount, $rateApplicablePercent, $exemptionReason, $exemptionReasonCode, null, null, null, null);
        $this->objectHelper->tryCall($netPrice, "addToIncludedTradeTax", $tax);
        return $this;
    }

    /**
     * Set the Referenced Catalog ID applied to this line
     *
     * @param string|null $catalogueRefId
     * Referenced Catalog ID applied to this line
     * @param string|null $catalogueRefLineId
     * Referenced Catalog LineID applied to this line
     * @param DateTime|null $catalogueRefDate
     * The formatted date or date time for the issuance of this referenced Catalog.
     * @return OrderDocumentBuilder
     */
    public function setDocumentPositionCatalogueReferencedDocument(?string $catalogueRefId = null, ?string $catalogueRefLineId = null, ?DateTime $catalogueRefDate = null): OrderDocumentBuilder
    {
        $quotationrefdoc = $this->objectHelper->getReferencedDocumentType($catalogueRefId, null, $catalogueRefLineId, null, null, null, $catalogueRefDate, null);
        $positionAgreement = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedLineTradeAgreement");
        $this->objectHelper->tryCallIfMethodExists($positionAgreement, "addToCatalogueReferencedDocument", "setCatalogueReferencedDocument", [$quotationrefdoc], $quotationrefdoc);
        return $this;
    }

    /**
     * Add a Referenced Catalog ID applied to this line
     *
     * @param string|null $catalogueRefId
     * Referenced Catalog ID applied to this line
     * @param string|null $catalogueRefLineId
     * Referenced Catalog LineID applied to this line
     * @param DateTime|null $catalogueRefDate
     * The formatted date or date time for the issuance of this referenced Catalog.
     * @return OrderDocumentBuilder
     */
    public function addDocumentPositionCatalogueReferencedDocument(?string $catalogueRefId = null, ?string $catalogueRefLineId = null, ?DateTime $catalogueRefDate = null): OrderDocumentBuilder
    {
        $quotationrefdoc = $this->objectHelper->getReferencedDocumentType($catalogueRefId, null, $catalogueRefLineId, null, null, null, $catalogueRefDate, null);
        $positionAgreement = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedLineTradeAgreement");
        $this->objectHelper->tryCall($positionAgreement, "addToCatalogueReferencedDocument", $quotationrefdoc);
        return $this;
    }

    /**
     * Set details of a blanket order referenced document on position-level
     *
     * @param string $blanketOrderRefLineId
     * The unique identifier of a line in the Blanket Order referenced document
     * @return OrderDocumentBuilder
     */
    public function setDocumentPositionBlanketOrderReferencedDocument(string $blanketOrderRefLineId): OrderDocumentBuilder
    {
        $blanketOrderRefDoc = $this->objectHelper->getReferencedDocumentType(null, null, $blanketOrderRefLineId, null, null, null, null);
        $positionAgreement = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedLineTradeAgreement");
        $this->objectHelper->tryCall($positionAgreement, "setBlanketOrderReferencedDocument", $blanketOrderRefDoc);
        return $this;
    }

    /**
     * The indication, at line level, of whether or not this trade delivery can be partially delivered.
     *
     * @param boolean $partialDelivery
     * If TRUE partial delivery is allowed
     * @return OrderDocumentBuilder
     */
    public function setDocumentPositionPartialDelivery(bool $partialDelivery = false): OrderDocumentBuilder
    {
        $indicator = $this->objectHelper->getIndicatorType($partialDelivery);
        $positionDelivery = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedLineTradeDelivery");
        $this->objectHelper->tryCall($positionDelivery, "setPartialDeliveryAllowedIndicator", $indicator);
        return $this;
    }

    /**
     * Set the quantity, at line level, requested for this trade delivery.
     *
     * @param float $requestedQuantity
     * The quantity, at line level, requested for this trade delivery.
     * @param string $requestedQuantityUnitCode
     * Unit Code for the requested quantity.
     * @return OrderDocumentBuilder
     */
    public function setDocumentPositionDeliverReqQuantity(float $requestedQuantity, string $requestedQuantityUnitCode): OrderDocumentBuilder
    {
        $quantity = $this->objectHelper->getQuantityType($requestedQuantity, $requestedQuantityUnitCode);
        $positionDelivery = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedLineTradeDelivery");
        $this->objectHelper->tryCall($positionDelivery, "setRequestedQuantity", $quantity);
        return $this;
    }

    /**
     * Set the number of packages, at line level, in this trade delivery.
     *
     * @param float $packageQuantity
     * The number of packages, at line level, in this trade delivery.
     * @param string $packageQuantityUnitCode
     * Unit Code for the package quantity.
     * @return OrderDocumentBuilder
     */
    public function setDocumentPositionDeliverPackageQuantity(float $packageQuantity, string $packageQuantityUnitCode): OrderDocumentBuilder
    {
        $quantity = $this->objectHelper->getQuantityType($packageQuantity, $packageQuantityUnitCode);
        $positionDelivery = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedLineTradeDelivery");
        $this->objectHelper->tryCall($positionDelivery, "setPackageQuantity", $quantity);
        return $this;
    }

    /**
     * Set the number of packages, at line level, in this trade delivery.
     *
     * @param float $perPackageQuantity
     * The number of packages, at line level, in this trade delivery.
     * @param string $perPackageQuantityUnitCode
     * Unit Code for the package quantity.
     * @return OrderDocumentBuilder
     */
    public function setDocumentPositionDeliverPerPackageQuantity(float $perPackageQuantity, string $perPackageQuantityUnitCode): OrderDocumentBuilder
    {
        $quantity = $this->objectHelper->getQuantityType($perPackageQuantity, $perPackageQuantityUnitCode);
        $positionDelivery = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedLineTradeDelivery");
        $this->objectHelper->tryCall($positionDelivery, "setPerPackageUnitQuantity", $quantity);
        return $this;
    }

    /**
     * Set the quantity, at line level, agreed for this trade delivery.
     *
     * @param float $agreedQuantity
     * The quantity, at line level, agreed for this trade delivery.
     * @param string $agreedQuantityUnitCode
     * Unit Code for the package quantity.
     * @return OrderDocumentBuilder
     */
    public function setDocumentPositionDeliverAgreedQuantity(float $agreedQuantity, string $agreedQuantityUnitCode): OrderDocumentBuilder
    {
        $quantity = $this->objectHelper->getQuantityType($agreedQuantity, $agreedQuantityUnitCode);
        $positionDelivery = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedLineTradeDelivery");
        $this->objectHelper->tryCall($positionDelivery, "setAgreedQuantity", $quantity);
        return $this;
    }

    /**
     * Get the requested date or period on which delivery is requested (on position level)
     *
     * @param DateTime|null $occurrenceDateTime
     * A Requested Date on which Delivery is requested
     * @param DateTime|null $startDateTime
     * The Start Date of he Requested Period on which Delivery is requested
     * @param DateTime|null $endDateTime
     * The End Date of he Requested Period on which Delivery is requested
     * @return OrderDocumentBuilder
     */
    public function setDocumentPositionRequestedDeliverySupplyChainEvent(?DateTime $occurrenceDateTime = null, ?DateTime $startDateTime = null, ?DateTime $endDateTime = null): OrderDocumentBuilder
    {
        $supplychainevent = $this->objectHelper->getDeliverySupplyChainEvent($occurrenceDateTime, $startDateTime, $endDateTime);
        $positionDelivery = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedLineTradeDelivery");
        $this->objectHelper->tryCallIfMethodExists($positionDelivery, "addToRequestedDeliverySupplyChainEvent", "setRequestedDeliverySupplyChainEvent", [$supplychainevent], $supplychainevent);
        return $this;
    }

    /**
     * Add an requested date or period on which delivery is requested (on position level)
     *
     * @param DateTime|null $occurrenceDateTime
     * @param DateTime|null $startDateTime
     * @param DateTime|null $endDateTime
     * @return OrderDocumentBuilder
     */
    public function addDocumentPositionRequestedDeliverySupplyChainEvent(?DateTime $occurrenceDateTime = null, ?DateTime $startDateTime = null, ?DateTime $endDateTime = null): OrderDocumentBuilder
    {
        $supplychainevent = $this->objectHelper->getDeliverySupplyChainEvent($occurrenceDateTime, $startDateTime, $endDateTime);
        $positionDelivery = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedLineTradeDelivery");
        $this->objectHelper->tryCall($positionDelivery, "addToRequestedDeliverySupplyChainEvent", $supplychainevent);
        return $this;
    }

    /**
     * Set the group of business terms providing information about the VAT applicable for the goods and
     * services ordered on the order line.
     *
     * @param string $categoryCode
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
     * @param string $typeCode
     * The code specifying the type of trade related tax, levy or duty, such as a code for a Value Added Tax (VAT).
     * Reference United Nations Code List (UNCL) 5153
     * Value = VAT for VAT, ENV for Environmental, EXC for excise duty
     * @param float $rateApplicablePercent
     * The VAT rate, represented as percentage that applies to the ordered item.
     * @param float|null $calculatedAmount
     * A monetary value resulting from the calculation of this trade related tax, levy or duty.
     * @param string|null $exemptionReason
     * Reason for tax exemption (free text)
     * @param string|null $exemptionReasonCode
     * Reason given in code form for the exemption of the amount from VAT. Note: Code list issued
     * and maintained by the Connecting Europe Facility.
     * @return OrderDocumentBuilder
     */
    public function setDocumentPositionTax(string $categoryCode, string $typeCode, float $rateApplicablePercent, ?float $calculatedAmount = null, ?string $exemptionReason = null, ?string $exemptionReasonCode = null): OrderDocumentBuilder
    {
        $tax = $this->objectHelper->getTradeTaxType($categoryCode, $typeCode, null, $calculatedAmount, $rateApplicablePercent, $exemptionReason, $exemptionReasonCode, null, null, null, null);
        $positionsettlement = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedLineTradeSettlement");
        $this->objectHelper->tryCallIfMethodExists($positionsettlement, "addToApplicableTradeTax", "setApplicableTradeTax", [$tax], $tax);
        return $this;
    }

    /**
     * Add a group of business terms providing information about the VAT applicable for the goods and
     * services ordered on the order line.
     *
     * @param string $categoryCode
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
     * @param string $typeCode
     * The code specifying the type of trade related tax, levy or duty, such as a code for a Value Added Tax (VAT).
     * Reference United Nations Code List (UNCL) 5153
     * Value = VAT for VAT, ENV for Environmental, EXC for excise duty
     * @param float $rateApplicablePercent
     * The VAT rate, represented as percentage that applies to the ordered item.
     * @param float|null $calculatedAmount
     * A monetary value resulting from the calculation of this trade related tax, levy or duty.
     * @param string|null $exemptionReason
     * Reason for tax exemption (free text)
     * @param string|null $exemptionReasonCode
     * Reason given in code form for the exemption of the amount from VAT. Note: Code list issued
     * and maintained by the Connecting Europe Facility.
     * @return OrderDocumentBuilder
     */
    public function addDocumentPositionTax(string $categoryCode, string $typeCode, float $rateApplicablePercent, ?float $calculatedAmount = null, ?string $exemptionReason = null, ?string $exemptionReasonCode = null): OrderDocumentBuilder
    {
        $tax = $this->objectHelper->getTradeTaxType($categoryCode, $typeCode, null, $calculatedAmount, $rateApplicablePercent, $exemptionReason, $exemptionReasonCode, null, null, null, null);
        $positionsettlement = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedLineTradeSettlement");
        $this->objectHelper->tryCall($positionsettlement, "addToApplicableTradeTax", $tax);
        return $this;
    }

    /**
     * Add surcharges and discounts on position level
     *
     * @param float $actualAmount
     * The amount of an allowance, without VAT.
     * @param boolean $isCharge
     * Indicator indicating whether the following data is for a charge or an allowance.
     * @param float|null $calculationPercent
     * The percentage that may be used, in conjunction with the order line allowance base amount,
     * to calculate the order line allowance amount.
     * @param float|null $basisAmount
     * The base amount that may be used, in conjunction with the order line allowance percentage,
     * to calculate the order line allowance amount.
     * @param string|null $reasonCode
     * The reason for the order line allowance, expressed as a code.
     * Use entries of the UNTDID 5189 code list. The order line level allowance reason code and the order
     * line level allowance reason shall indicate the same allowance reason.
     * @param string|null $reason
     * The reason for the order line allowance, expressed as text.
     * @return OrderDocumentBuilder
     */
    public function setDocumentPositionAllowanceCharge(float $actualAmount, bool $isCharge, ?float $calculationPercent = null, ?float $basisAmount = null, ?string $reasonCode = null, ?string $reason = null): OrderDocumentBuilder
    {
        $positionsettlement = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedLineTradeSettlement");
        $allowanceCharge = $this->objectHelper->getTradeAllowanceChargeType($actualAmount, $isCharge, null, null, null, null, $calculationPercent, $basisAmount, null, null, $reasonCode, $reason);
        $this->objectHelper->tryCallIfMethodExists($positionsettlement, "addToSpecifiedTradeAllowanceCharge", "setSpecifiedTradeAllowanceCharge", [$allowanceCharge], $allowanceCharge);
        return $this;
    }

    /**
     * Add surcharges and discounts on position level
     *
     * @param float $actualAmount
     * The amount of an allowance, without VAT.
     * @param boolean $isCharge
     * Indicator indicating whether the following data is for a charge or an allowance.
     * @param float|null $calculationPercent
     * The percentage that may be used, in conjunction with the order line allowance base amount,
     * to calculate the order line allowance amount.
     * @param float|null $basisAmount
     * The base amount that may be used, in conjunction with the order line allowance percentage,
     * to calculate the order line allowance amount.
     * @param string|null $reasonCode
     * The reason for the order line allowance, expressed as a code.
     * Use entries of the UNTDID 5189 code list. The order line level allowance reason code and the order
     * line level allowance reason shall indicate the same allowance reason.
     * @param string|null $reason
     * The reason for the order line allowance, expressed as text.
     * @return OrderDocumentBuilder
     */
    public function addDocumentPositionAllowanceCharge(float $actualAmount, bool $isCharge, ?float $calculationPercent = null, ?float $basisAmount = null, ?string $reasonCode = null, ?string $reason = null): OrderDocumentBuilder
    {
        $positionsettlement = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedLineTradeSettlement");
        $allowanceCharge = $this->objectHelper->getTradeAllowanceChargeType($actualAmount, $isCharge, null, null, null, null, $calculationPercent, $basisAmount, null, null, $reasonCode, $reason);
        $this->objectHelper->tryCall($positionsettlement, "addToSpecifiedTradeAllowanceCharge", $allowanceCharge);
        return $this;
    }

    /**
     * Set information on position totals
     *
     * @param float $lineTotalAmount
     * The total amount of the order line.
     * The amount is “net” without VAT, i.e. inclusive of line level allowances and charges as well as other relevant taxes.
     * @param float|null $totalAllowanceChargeAmount
     * A monetary value of a total allowance and charge reported in this trade settlement line monetary summation.
     * The amount is “net” without VAT, i.e. inclusive of line level allowances and charges as well as other relevant taxes.
     * @return OrderDocumentBuilder
     */
    public function setDocumentPositionLineSummation(float $lineTotalAmount, ?float $totalAllowanceChargeAmount = null): OrderDocumentBuilder
    {
        $positionsettlement = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedLineTradeSettlement");
        $summation = $this->objectHelper->getTradeSettlementLineMonetarySummationType($lineTotalAmount, $totalAllowanceChargeAmount);
        $this->objectHelper->tryCall($positionsettlement, "setSpecifiedTradeSettlementLineMonetarySummation", $summation);
        return $this;
    }

    /**
     * Set an AccountingAccount on position level
     *
     * @param string $id
     * The unique identifier for this trade accounting account.
     * @param string|null $typeCode
     * The code specifying the type of trade accounting account, such as
     * general (main), secondary, cost accounting or budget account.
     * @return OrderDocumentBuilder
     */
    public function setDocumentPositionReceivableTradeAccountingAccount(string $id, ?string $typeCode = null): OrderDocumentBuilder
    {
        $positionsettlement = $this->objectHelper->tryCallAndReturn($this->currentPosition, "getSpecifiedLineTradeSettlement");
        $account = $this->objectHelper->getTradeAccountingAccountType($id, $typeCode);
        $this->objectHelper->tryCall($positionsettlement, "setReceivableSpecifiedTradeAccountingAccount", $account);
        return $this;
    }
}
