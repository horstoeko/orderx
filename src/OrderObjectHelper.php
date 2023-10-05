<?php

/**
 * This file is a part of horstoeko/orderx.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\orderx;

use DateTime;
use horstoeko\orderx\exception\OrderMimeTypeNotSupportedException;
use horstoeko\orderx\exception\OrderUnknownDateFormatException;
use horstoeko\stringmanagement\FileUtils;
use horstoeko\stringmanagement\StringUtils;
use MimeTyper\Repository\MimeDbRepository;
use OutOfRangeException;

/**
 * Class representing a collection of common helpers and class factories
 *
 * @category Order-X
 * @package  Order-X
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/orderx
 */
class OrderObjectHelper
{
    /**
     * Internal profile id (see OrderProfiles.php)
     *
     * @var integer
     */
    public $profile = -1;

    /**
     * Internal profile definition (see OrderProfiles.php)
     *
     * @var array
     */
    public $profiledef = [];

    /**
     * A list of supported mimetypes by binaryattachments
     */
    const SUPPORTEDTMIMETYPES = [
        "application/pdf",
        "image/png",
        "image/jpeg",
        "text/csv",
        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        "application/vnd.oasis.opendocument.spreadsheet",
        "application/xml",
    ];

    /**
     * Constructor
     *
     * @param integer $profile
     */
    public function __construct(int $profile)
    {
        $this->profile = $profile;
        $this->profiledef = OrderProfiles::PROFILEDEF[$profile];
    }

    /**
     * Creates an instance of DocumentCodeType
     *
     * @param  string|null $value
     * @return object|null
     */
    public function getDocumentCodeType(?string $value = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        return $this->createClassInstance('qdt\DocumentCodeType', $value);
    }

    /**
     * Creates an instance of DocumentStatusCodeType
     *
     * @param  string|null $value
     * @return object|null
     */
    public function getDocumentStatusCodeType(?string $value = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        return $this->createClassInstance('qdt\DocumentStatusCodeType', $value);
    }

    /**
     * Creates an instance of CurrencyCodeType
     *
     * @param  string|null $value
     * @return object|null
     */
    public function getCurrencyCodeType(?string $value = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        return $this->createClassInstance('qdt\CurrencyCodeType', $value);
    }

    /**
     * Creates an instance of CurrencyCodeType
     *
     * @param  string|null $value
     * @return object|null
     */
    public function getMessageFunctionCodeType(?string $value = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        return $this->createClassInstance('qdt\MessageFunctionCodeType', $value);
    }

    /**
     * Creates an instance of IDType
     *
     * @param  string|null $value
     * @param  string|null $schemeId
     * @return object
     */
    public function getIdType(?string $value = null, ?string $schemeId = null): ?object
    {
        if (self::isNullOrEmpty($value)) {
            return null;
        }

        $idType = $this->createClassInstance('udt\IDType', $value);

        $this->tryCall($idType, "setSchemeID", $schemeId);

        return $idType;
    }

    /**
     * Creates an instance of TextType
     *
     * @param  string|null $value
     * @return object
     */
    public function getTextType(?string $value = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        return $this->createClassInstance('udt\TextType', $value);
    }

    /**
     * Creates an instance of CodeType
     *
     * @param  string|null $value
     * @return object|null
     */
    public function getCodeType(?string $value = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        return $this->createClassInstance('udt\CodeType', $value);
    }

    /**
     * Creates an instance of AccountingAccountTypeCodeType
     *
     * @param  string|null $value
     * @return object|null
     */
    public function getAccountingAccountTypeCodeType(?string $value = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        return $this->createClassInstance('qdt\AccountingAccountTypeCodeType', $value);
    }

    /**
     * Creates an instance of CodeType with extended list
     * information
     *
     * @param  string|null $value
     * @param  string|null $listID
     * @param  string|null $listVersionID
     * @return object|null
     */
    public function getCodeType2(?string $value = null, ?string $listID = null, ?string $listVersionID = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $codetype = $this->createClassInstance('udt\CodeType', $value);

        $this->tryCall($codetype, 'setListID', $listID);
        $this->tryCall($codetype, 'setListVersionID', $listVersionID);

        return $codetype;
    }

    /**
     * Get indicator type
     *
     * @param  bool|null $value
     * @return object|null
     */
    public function getIndicatorType(?bool $value = null): ?object
    {
        if ($value === null) {
            return null;
        }

        $indicator = $this->createClassInstance('udt\IndicatorType');

        $this->tryCall($indicator, 'setIndicator', $value);

        return $indicator;
    }

    /**
     * Get Note type
     *
     * @param  string|null $content
     * @param  string|null $contentCode
     * @param  string|null $subjectCode
     * @return object|null
     */
    public function getNoteType(?string $content = null, ?string $contentCode = null, ?string $subjectCode = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }
        if (self::isNullOrEmpty($content)) {
            return null;
        }

        $note = $this->createClassInstance('ram\NoteType');

        $this->tryCall($note, 'setContentCode', $this->getCodeType($contentCode));
        $this->tryCall($note, 'setSubjectCode', $this->getCodeType($subjectCode));
        $this->tryCallIfMethodExists($note, 'unsetContent', 'setContent', [$this->getTextType($content)], $this->getTextType($content));

        return $note;
    }

    /**
     * Get formatted issue date
     *
     * @param  DateTime|null $datetime
     * @return object|null
     */
    public function getFormattedDateTimeType(?DateTime $datetime = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $date2 = $this->createClassInstance('qdt\FormattedDateTimeType\DateTimeStringAType');
        $this->tryCall($date2, "value", $datetime->format("Ymd"));
        $this->tryCall($date2, "setFormat", "102");

        $date = $this->createClassInstance('qdt\FormattedDateTimeType');
        $this->tryCall($date, "setDateTimeString", $date2);

        return $date;
    }

    /**
     * Get formatted issue date
     *
     * @param  DateTime|null $datetime
     * @return object|null
     */
    public function getDateTimeType(?DateTime $datetime = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $date2 = $this->createClassInstance('udt\DateTimeType\DateTimeStringAType');
        $this->tryCall($date2, "value", $datetime->format("Ymd"));
        $this->tryCall($date2, "setFormat", "102");

        $date = $this->createClassInstance('udt\DateTimeType');
        $this->tryCall($date, "setDateTimeString", $date2);

        return $date;
    }

    /**
     * Representation of Amount
     *
     * @param  float|null  $value
     * @param  string|null $currencyCode
     * @return object|null
     */
    public function getAmountType(?float $value, ?string $currencyCode = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }
        if (self::isNullOrEmpty($value)) {
            return null;
        }

        $amount = $this->createClassInstance('udt\AmountType');

        $this->tryCall($amount, "value", $value);
        $this->tryCall($amount, "setCurrencyID", $this->getCurrencyCodeType($currencyCode));

        return $amount;
    }

    /**
     * Representation of Percdnt
     *
     * @param  float|null $value
     * @return object|null
     */
    public function getPercentType(?float $value): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $amount = $this->createClassInstance('udt\PercentType');

        $this->tryCall($amount, "value", $value);

        return $amount;
    }

    /**
     * Representation of Quantity
     *
     * @param  float|null  $value
     * @param  string|null $unitCode
     * @return object|null
     */
    public function getQuantityType(?float $value, ?string $unitCode = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }
        if (self::isNullOrEmpty($value)) {
            return null;
        }

        $amount = $this->createClassInstance('udt\QuantityType');

        $this->tryCall($amount, "value", $value);
        $this->tryCall($amount, "setUnitCode", $unitCode);

        return $amount;
    }

    /**
     * Representation of Quantity Measure
     *
     * @param  float|null  $value
     * @param  string|null $unitCode
     * @return object|null
     */
    public function getMeasureType(?float $value, ?string $unitCode = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }
        if (self::isNullOrEmpty($value)) {
            return null;
        }

        $measure = $this->createClassInstance('udt\MeasureType');

        $this->tryCall($measure, "value", $value);
        $this->tryCall($measure, "setUnitCode", $unitCode);

        return $measure;
    }

    /**
     * Get an instance of GetNumericType
     *
     * @param  float|null $value
     * @return object|null
     */
    public function getNumericType(?float $value = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $numericType = $this->createClassInstance('udt\NumericType');

        $this->tryCall($numericType, 'value', $value);

        return $numericType;
    }

    /**
     * Representation of Tax Category
     *
     * @param  string|null $taxCategoryCode
     * @return object|null
     */
    public function getTaxCategoryCodeType(?string $taxCategoryCode = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $category = $this->createClassInstance('qdt\TaxCategoryCodeType');

        $this->tryCall($category, "value", $taxCategoryCode);

        return $category;
    }

    /**
     * Representation of Tax Type
     *
     * @param  string|null $taxTypeCode
     * @return object|null
     */
    public function getTaxTypeCodeType(?string $taxTypeCode = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $category = $this->createClassInstance('qdt\TaxTypeCodeType');

        $this->tryCall($category, "value", $taxTypeCode);

        return $category;
    }

    /**
     * Representation of Time Reference Code
     *
     * @param  string|null $value
     * @return object|null
     */
    public function getTimeReferenceCodeType(?string $value = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $timeReference = $this->createClassInstance('qdt\TimeReferenceCodeType');

        $this->tryCall($timeReference, "value", $value);

        return $timeReference;
    }

    /**
     * Get Specified Period type
     *
     * @param  DateTime|null $startdate
     * @param  DateTime|null $endDate
     * @return object|null
     */
    public function getSpecifiedPeriodType(?DateTime $startdate = null, ?DateTime $endDate = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $period = $this->createClassInstance('ram\SpecifiedPeriodType');

        $this->tryCall($period, 'setStartDateTime', $this->getDateTimeType($startdate));
        $this->tryCall($period, 'setEndDateTime', $this->getDateTimeType($endDate));

        return $period;
    }

    /**
     * Get a BinaryObjectType object
     *
     * @param  string|null $binarydata
     * @param  string|null $mimetype
     * @param  string|null $filename
     * @return object|null
     */
    public function getBinaryObjectType(?string $binarydata = null, ?string $mimetype = null, ?string $filename = null): ?object
    {
        if (self::isNullOrEmpty($binarydata) || self::isNullOrEmpty($mimetype) || self::isNullOrEmpty($filename)) {
            return null;
        }

        $binaryobject = $this->createClassInstance('udt\BinaryObjectType');

        $this->tryCall($binaryobject, "value", $binarydata);
        $this->tryCall($binaryobject, "setMimeCode", $mimetype);
        $this->tryCall($binaryobject, "setFilename", $filename);

        return $binaryobject;
    }

    /**
     * Get a reference document object
     *
     * @param  string|null   $issuerassignedid
     * @param  string|null   $uriid
     * @param  string|null   $lineid
     * @param  string|null   $typecode
     * @param  string|null   $name
     * @param  string|null   $reftypecode
     * @param  DateTime|null $issueddate
     * @param  string|null   $binarydatafilename
     * @return object|null
     */
    public function getReferencedDocumentType(?string $issuerassignedid = null, ?string $uriid = null, ?string $lineid = null, ?string $typecode = null, ?string $name = null, ?string $reftypecode = null, ?DateTime $issueddate = null, ?string $binarydatafilename = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $refdoctype = $this->createClassInstance('ram\ReferencedDocumentType', $issuerassignedid);

        $this->tryCall($refdoctype, 'setIssuerAssignedID', $this->getIdType($issuerassignedid));
        $this->tryCall($refdoctype, 'setURIID', $this->getIdType($uriid));
        $this->tryCall($refdoctype, 'setLineID', $this->getIdType($lineid));
        $this->tryCall($refdoctype, 'setTypeCode', $this->getCodeType($typecode));
        $this->tryCall($refdoctype, 'setReferenceTypeCode', $this->getReferenceCodeType($reftypecode));
        $this->tryCall($refdoctype, 'setFormattedIssueDateTime', $this->getFormattedDateTimeType($issueddate));

        foreach ($this->ensureStringArray($name) as $name) {
            $this->tryCallAll($refdoctype, ['addToName', 'setName'], $this->getTextType($name));
        }

        if (StringUtils::stringIsNullOrEmpty($binarydatafilename) === false) {
            if (FileUtils::fileExists($binarydatafilename)) {
                $mimetyper = new MimeDbRepository();
                $mimeType = $mimetyper->findType(FileUtils::getFileExtension($binarydatafilename));

                if (in_array($mimeType, self::SUPPORTEDTMIMETYPES)) {
                    $content = FileUtils::fileToBase64($binarydatafilename);
                    $this->tryCall(
                        $refdoctype,
                        'setAttachmentBinaryObject',
                        $this->getBinaryObjectType($content, $mimeType, FileUtils::getFilenameWithExtension($binarydatafilename))
                    );
                } else {
                    throw new OrderMimeTypeNotSupportedException($mimeType);
                }
            }
        }

        return $refdoctype;
    }

    /**
     * Get instance of CountryID
     *
     * @param  string|null $id
     * @return object|null
     */
    public function getCountryIDType(?string $id = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $countryId = $this->createClassInstance('qdt\CountryIDType', $id);

        return $countryId;
    }

    /**
     * Get instance of TradeCountry
     *
     * @param  string|null $id
     * @return object|null
     */
    public function getTradeCountryType(?string $id = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $tradeCountry = $this->createClassInstance('ram\TradeCountryType');

        $this->tryCall($tradeCountry, 'setID', $this->getCountryIDType($id));

        return $tradeCountry;
    }

    /**
     * Undocumented function
     *
     * @return \horstoeko\orderx\entities\basic\rsm\SCRDMCCBDACIOMessageStructure|\horstoeko\orderx\entities\comfort\rsm\SCRDMCCBDACIOMessageStructure|\horstoeko\orderx\entities\extended\rsm\SCRDMCCBDACIOMessageStructure
     */
    public function getOrderX()
    {
        $result = $this->createClassInstance('rsm\SCRDMCCBDACIOMessageStructure');
        $result->setExchangedDocumentContext($this->createClassInstance('ram\ExchangedDocumentContextType'));
        $result->setExchangedDocument($this->createClassInstance('ram\ExchangedDocumentType'));
        $result->setSupplyChainTradeTransaction($this->createClassInstance('ram\SupplyChainTradeTransactionType'));
        $result->getExchangedDocumentContext()->setGuidelineSpecifiedDocumentContextParameter($this->createClassInstance('ram\DocumentContextParameterType'));
        $result->getExchangedDocumentContext()->getGuidelineSpecifiedDocumentContextParameter()->setID($this->getIdType($this->profiledef['contextparameter']));
        $result->getSupplyChainTradeTransaction()->setApplicableHeaderTradeAgreement($this->createClassInstance('ram\HeaderTradeAgreementType'));
        $result->getSupplyChainTradeTransaction()->setApplicableHeaderTradeDelivery($this->createClassInstance('ram\HeaderTradeDeliveryType'));
        $result->getSupplyChainTradeTransaction()->setApplicableHeaderTradeSettlement($this->createClassInstance('ram\HeaderTradeSettlementType'));

        return $result;
    }

    /**
     * Tradeparty type^^
     *
     * @param  string|null $name
     * @param  string|null $ID
     * @param  string|null $description
     * @return object|null
     */
    public function getTradeParty(?string $name = null, ?string $ID = null, ?string $description = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $tradeParty = $this->createClassInstance('ram\TradePartyType');

        $this->tryCall($tradeParty, "setID", $this->getIdType($ID));
        $this->tryCall($tradeParty, "setName", $this->getTextType($name));
        $this->tryCall($tradeParty, "setDescription", $this->getTextType($description));

        return $tradeParty;
    }

    /**
     * Tradelocation type
     *
     * @param  string|null $ID
     * @param  string|null $name
     * @return object|null
     */
    public function getTradeLocation(?string $ID = null, ?string $name = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $tradeLocation = $this->createClassInstance('ram\TradeLocationType');

        $this->tryCall($tradeLocation, "setID", $this->getIdType($ID));
        $this->tryCall($tradeLocation, "setName", $this->getTextType($name));

        return $tradeLocation;
    }

    /**
     * Address type
     *
     * @param  string|null $lineone
     * @param  string|null $linetwo
     * @param  string|null $linethree
     * @param  string|null $postcode
     * @param  string|null $city
     * @param  string|null $country
     * @param  string|null $subdivision
     * @return object|null
     */
    public function getTradeAddress(?string $lineone = null, ?string $linetwo = null, ?string $linethree = null, ?string $postcode = null, ?string $city = null, ?string $country = null, ?string $subdivision = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $address = $this->createClassInstance('ram\TradeAddressType');

        $this->tryCall($address, "setLineOne", $this->getTextType($lineone));
        $this->tryCall($address, "setLineTwo", $this->getTextType($linetwo));
        $this->tryCall($address, "setLineThree", $this->getTextType($linethree));
        $this->tryCall($address, "setPostcodeCode", $this->getCodeType($postcode));
        $this->tryCall($address, "setCityName", $this->getTextType($city));
        $this->tryCall($address, "setCountryID", $this->getCountryIDType($country));
        $this->tryCallAll($address, ["addToCountrySubDivisionName", "setCountrySubDivisionName"], $this->getTextType($subdivision));

        return $address;
    }

    /**
     * Legal organization type
     *
     * @param  string|null $legalorgid
     * @param  string|null $legalorgtype
     * @param  string|null $legalorgname
     * @return object|null
     */
    public function getLegalOrganization(?string $legalorgid = null, ?string $legalorgtype = null, ?string $legalorgname = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $legalorg = $this->createClassInstance('ram\LegalOrganizationType', $legalorgname);

        $this->tryCall($legalorg, "setID", $this->getIdType($legalorgid, $legalorgtype));
        $this->tryCall($legalorg, "setTradingBusinessName", $this->getTextType($legalorgname));

        return $legalorg;
    }

    /**
     * Contact type
     *
     * @param  string|null $contactpersonname
     * @param  string|null $contactdepartmentname
     * @param  string|null $contactphoneno
     * @param  string|null $contactfaxno
     * @param  string|null $contactemailaddr
     * @param  string|null $contactTypeCode
     * @return object|null
     */
    public function getTradeContact(?string $contactpersonname = null, ?string $contactdepartmentname = null, ?string $contactphoneno = null, ?string $contactfaxno = null, ?string $contactemailaddr = null, ?string $contactTypeCode = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $contact = $this->createClassInstance('ram\TradeContactType', $contactpersonname);
        $contactphone = $this->getUniversalCommunicationType($contactphoneno, null, null);
        $contactfax = $this->getUniversalCommunicationType($contactfaxno, null, null);
        $contactemail = $this->getUniversalCommunicationType(null, $contactemailaddr);

        $this->tryCall($contact, "setPersonName", $this->getTextType($contactpersonname));
        $this->tryCall($contact, "setDepartmentName", $this->getTextType($contactdepartmentname));
        $this->tryCall($contact, "setTelephoneUniversalCommunication", $contactphone);
        $this->tryCall($contact, "setFaxUniversalCommunication", $contactfax);
        $this->tryCall($contact, "setEmailURIUniversalCommunication", $contactemail);
        $this->tryCall($contact, "setTypeCode", $this->getContactTypeCodeType($contactTypeCode));

        return $contact;
    }

    /**
     * Communication type
     *
     * @param  string|null $number
     * @param  string|null $uriid
     * @param  string|null $urischeme
     * @return object|null
     */
    public function getUniversalCommunicationType(?string $number = null, ?string $uriid = null, ?string $urischeme = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $communication = $this->createClassInstance('ram\UniversalCommunicationType');

        $this->tryCall($communication, "setCompleteNumber", $this->getTextType($number));
        $this->tryCall($communication, "setURIID", $this->getIdType($uriid, $urischeme));

        return $communication;
    }

    /**
     * Tax registration type
     *
     * @param  string|null $taxregtype
     * @param  string|null $taxregid
     * @return object|null
     */
    public function getTaxRegistrationType(?string $taxregtype = null, ?string $taxregid = null): ?object
    {
        if (self::isNullOrEmpty($taxregtype)) {
            return null;
        }

        if (self::isNullOrEmpty($taxregid)) {
            return null;
        }

        $taxreg = $this->createClassInstance('ram\TaxRegistrationType');

        $this->tryCall($taxreg, "setID", $this->getIdType($taxregid, $taxregtype));

        return $taxreg;
    }

    /**
     * Delivery terms type
     *
     * @param  string|null $code
     * @param  string|null $description
     * @param  string|null $functionCode
     * @param  string|null $relevantTradeLocationId
     * @param  string|null $relevantTradeLocationName
     * @return object|null
     */
    public function getTradeDeliveryTermsType(?string $code = null, ?string $description = null, ?string $functionCode = null, ?string $relevantTradeLocationId = null, ?string $relevantTradeLocationName = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $deliveryterms = $this->createClassInstance('ram\TradeDeliveryTermsType');

        $this->tryCall($deliveryterms, "setDeliveryTypeCode", $this->getDeliveryTermsCodeType($code));
        $this->tryCall($deliveryterms, "setDescription", $this->getTextType($description));
        $this->tryCall($deliveryterms, "setFunctionCode", $this->getDeliveryTermsFunctionCodeType($functionCode));
        $this->tryCall($deliveryterms, "setRelevantTradeLocation", $this->getTradeLocation($relevantTradeLocationId, $relevantTradeLocationName));

        return $deliveryterms;
    }

    /**
     * Procuring project type
     *
     * @param  string|null $id
     * @param  string|null $name
     * @return object|null
     */
    public function getProcuringProjectType(?string $id = null, ?string $name = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $procuringproject = $this->createClassInstance('ram\ProcuringProjectType');

        $this->tryCall($procuringproject, "setID", $this->getIdType($id));
        $this->tryCall($procuringproject, "setName", $this->getTextType($name));

        return $procuringproject;
    }

    /**
     * Undocumented function
     *
     * @param  DateTime|null $occurrenceDateTime
     * @return object|null
     */
    public function getSupplyChainEventType(?DateTime $occurrenceDateTime = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $supplychainevent = $this->createClassInstance('ram\SupplyChainEventType');

        $this->tryCall($supplychainevent, "setOccurrenceDateTime", $this->getDateTimeType($occurrenceDateTime));

        return $supplychainevent;
    }

    /**
     * Get a DeliverySupplyChainEvent
     *
     * @param  DateTime|null $occurrenceDateTime
     * @param  DateTime|null $startDateTime
     * @param  DateTime|null $endDateTime
     * @param  float|null    $unitQuantity
     * @param  string|null   $unitCode
     * @return object|null
     */
    public function getDeliverySupplyChainEvent(?DateTime $occurrenceDateTime = null, ?DateTime $startDateTime = null, ?DateTime $endDateTime = null, ?float $unitQuantity = null, ?string $unitCode = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $supplychainevent = $this->createClassInstance('ram\SupplyChainEventType');
        $period = $this->getSpecifiedPeriodType($startDateTime, $endDateTime);

        $this->tryCall($supplychainevent, "setOccurrenceDateTime", $this->getDateTimeType($occurrenceDateTime));
        $this->tryCall($supplychainevent, "setOccurrenceSpecifiedPeriod", $period);
        $this->tryCall($supplychainevent, "setUnitQuantity", $this->getQuantityType($unitQuantity, $unitCode));

        return $supplychainevent;
    }

    /**
     * Get instance of TradeSettlementPaymentMeansType
     *
     * @param  string|null $typecode
     * @param  string|null $information
     * @return object|null
     */
    public function getTradeSettlementPaymentMeansType(?string $typecode = null, ?string $information = null): ?object
    {
        if (self::isNullOrEmpty($typecode)) {
            return null;
        }

        $paymentMeans = $this->createClassInstance('ram\TradeSettlementPaymentMeansType');

        $this->tryCall($paymentMeans, "setTypeCode", $this->getPaymentMeansCodeType($typecode));
        $this->tryCallIfMethodExists($paymentMeans, "unsetInformation", "setInformation", [$this->getTextType($information)], $this->getTextType($information));

        return $paymentMeans;
    }

    /**
     * Get instance of TradePaymentTermsType
     *
     * @param  string|null $description
     * @return object|null
     */
    public function getTradePaymentTermsType(?string $description = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $paymentTerms = $this->createClassInstance('ram\TradePaymentTermsType');

        $this->tryCallIfMethodExists($paymentTerms, "unsetDescription", "setDescription", [$this->getTextType($description)], $this->getTextType($description));

        return $paymentTerms;
    }

    /**
     * Get instance of TradeTaxType
     * Sales tax breakdown, Umsatzsteueraufschlüsselung
     *
     * @param  string|null $categoryCode
     * @param  string|null $typeCode
     * @param  float|null  $basisAmount
     * @param  float|null  $calculatedAmount
     * @param  float|null  $rateApplicablePercent
     * @param  string|null $exemptionReason
     * @param  string|null $exemptionReasonCode
     * @param  float|null  $lineTotalBasisAmount
     * @param  float|null  $allowanceChargeBasisAmount
     * @param  string|null $dueDateTypeCode
     * @return object|null
     */
    public function getTradeTaxType(?string $categoryCode = null, ?string $typeCode = null, ?float $basisAmount = null, ?float $calculatedAmount = null, ?float $rateApplicablePercent = null, ?string $exemptionReason = null, ?string $exemptionReasonCode = null, ?float $lineTotalBasisAmount = null, ?float $allowanceChargeBasisAmount = null, ?string $dueDateTypeCode = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $taxBreakdown = $this->createClassInstance('ram\TradeTaxType');

        $this->tryCall($taxBreakdown, "setCalculatedAmount", $this->getAmountType($calculatedAmount));
        $this->tryCall($taxBreakdown, "setTypeCode", $this->getTaxTypeCodeType($typeCode));
        $this->tryCall($taxBreakdown, "setExemptionReason", $this->getTextType($exemptionReason));
        $this->tryCall($taxBreakdown, "setBasisAmount", $this->getAmountType($basisAmount));
        $this->tryCall($taxBreakdown, "setLineTotalBasisAmount", $this->getAmountType($lineTotalBasisAmount));
        $this->tryCall($taxBreakdown, "setAllowanceChargeBasisAmount", $this->getAmountType($allowanceChargeBasisAmount));
        $this->tryCall($taxBreakdown, "setCategoryCode", $this->getTaxCategoryCodeType($categoryCode));
        $this->tryCall($taxBreakdown, "setExemptionReasonCode", $this->getCodeType($exemptionReasonCode));
        $this->tryCall($taxBreakdown, "setDueDateTypeCode", $this->getTimeReferenceCodeType($dueDateTypeCode));
        $this->tryCall($taxBreakdown, "setRateApplicablePercent", $this->getPercentType($rateApplicablePercent));

        return $taxBreakdown;
    }

    /**
     * Get Allowance/Charge type
     * Zu- und Abschläge
     *
     * @param  float|null   $actualAmount
     * @param  boolean|null $isCharge
     * @param  string|null  $taxTypeCode
     * @param  string|null  $taxCategoryCode
     * @param  float|null   $rateApplicablePercent
     * @param  float|null   $sequence
     * @param  float|null   $calculationPercent
     * @param  float|null   $basisAmount
     * @param  float|null   $basisQuantity
     * @param  string|null  $basisQuantityUnitCode
     * @param  string|null  $reasonCode
     * @param  string|null  $reason
     * @return object|null
     */
    public function getTradeAllowanceChargeType(?float $actualAmount = null, ?bool $isCharge = null, ?string $taxTypeCode = null, ?string $taxCategoryCode = null, ?float $rateApplicablePercent = null, ?float $sequence = null, ?float $calculationPercent = null, ?float $basisAmount = null, ?float $basisQuantity = null, ?string $basisQuantityUnitCode = null, ?string $reasonCode = null, ?string $reason = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $allowanceCharge = $this->createClassInstance('ram\TradeAllowanceChargeType');

        $this->tryCall($allowanceCharge, "setChargeIndicator", $this->getIndicatorType($isCharge));
        $this->tryCall($allowanceCharge, "setSequenceNumeric", $sequence);
        $this->tryCall($allowanceCharge, "setCalculationPercent", $this->getPercentType($calculationPercent));
        $this->tryCall($allowanceCharge, "setBasisAmount", $this->getAmountType($basisAmount));
        $this->tryCall($allowanceCharge, "setBasisQuantity", $this->getQuantityType($basisQuantity, $basisQuantityUnitCode));
        $this->tryCall($allowanceCharge, "setActualAmount", $this->getAmountType($actualAmount));
        $this->tryCall($allowanceCharge, "setReasonCode", $this->getAllowanceChargeReasonCodeType($reasonCode));
        $this->tryCall($allowanceCharge, "setReason", $this->getTextType($reason));

        if (!is_null($taxCategoryCode) && !is_null($taxTypeCode) && !is_null($rateApplicablePercent)) {
            $this->tryCall($allowanceCharge, "setCategoryTradeTax", $this->getTradeTaxType($taxCategoryCode, $taxTypeCode, null, null, $rateApplicablePercent));
        }

        return $allowanceCharge;
    }

    /**
     * Get instance of
     *
     * @param  string|null $description
     * @param  float|null  $appliedAmount
     * @param  array|null  $taxTypeCodes
     * @param  array|null  $taxCategpryCodes
     * @param  array|null  $rateApplicablePercents
     * @return object|null
     */
    public function getLogisticsServiceChargeType(?string $description = null, ?float $appliedAmount = null, ?array $taxTypeCodes = null, ?array $taxCategpryCodes = null, ?array $rateApplicablePercents = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $logCharge = $this->createClassInstance('ram\LogisticsServiceChargeType');

        $this->tryCall($logCharge, "setDescription", $this->getTextType($description));
        $this->tryCall($logCharge, "setAppliedAmount", $this->getAmountType($appliedAmount));

        if (!is_null($taxCategpryCodes) && !is_null($taxTypeCodes) && !is_null($rateApplicablePercents)) {
            foreach ($rateApplicablePercents as $index => $rateApplicablePercent) {
                $taxBreakdown = $this->getTradeTaxType($taxCategpryCodes[$index], $taxTypeCodes[$index], null, null, $rateApplicablePercent);
                $this->tryCall($logCharge, "addToAppliedTradeTax", $taxBreakdown);
            }
        }

        return $logCharge;
    }

    /**
     * Get instance of TradeSettlementHeaderMonetarySummationType
     *
     * @param  float|null $grandTotalAmount
     * @param  float|null $lineTotalAmount
     * @param  float|null $chargeTotalAmount
     * @param  float|null $allowanceTotalAmount
     * @param  float|null $taxBasisTotalAmount
     * @param  float|null $taxTotalAmount
     * @return object|null
     */
    public function getTradeSettlementHeaderMonetarySummationType(?float $grandTotalAmount = null, ?float $lineTotalAmount = null, ?float $chargeTotalAmount = null, ?float $allowanceTotalAmount = null, ?float $taxBasisTotalAmount = null, ?float $taxTotalAmount = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $summation = $this->createClassInstance('ram\TradeSettlementHeaderMonetarySummationType');

        $this->tryCall($summation, "setLineTotalAmount", $this->getAmountType($lineTotalAmount));
        $this->tryCall($summation, "setChargeTotalAmount", $this->getAmountType($chargeTotalAmount));
        $this->tryCall($summation, "setAllowanceTotalAmount", $this->getAmountType($allowanceTotalAmount));
        $this->tryCallAll($summation, ["addToTaxBasisTotalAmount", "setTaxBasisTotalAmount"], $this->getAmountType($taxBasisTotalAmount));
        $this->tryCallAll($summation, ["addToTaxTotalAmount", "setTaxTotalAmount"], $this->getAmountType($taxTotalAmount));
        $this->tryCallAll($summation, ["addToGrandTotalAmount", "setGrandTotalAmount"], $this->getAmountType($grandTotalAmount));

        return $summation;
    }

    /**
     * Get an instance of TradeAccountingAccountType
     *
     * @param  string|null $id
     * @param  string|null $typeCode
     * @return object|null
     */
    public function getTradeAccountingAccountType(?string $id = null, ?string $typeCode = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $account = $this->createClassInstance('ram\TradeAccountingAccountType');

        $this->tryCall($account, "setID", $this->getIdType($id));
        $this->tryCall($account, "setTypeCode", $this->getAccountingAccountTypeCodeType($typeCode));

        return $account;
    }

    /**
     * Get Document line
     *
     * @param  string|null $lineid
     * @return object|null
     */
    public function getDocumentLineDocumentType(?string $lineid = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $doclinedoc = $this->createClassInstance('ram\DocumentLineDocumentType');

        $this->tryCall($doclinedoc, "setLineID", $this->getIdType($lineid));

        return $doclinedoc;
    }

    /**
     * Get instance of SupplyChainTradeLineItemType
     *
     * @param  string|null $lineid
     * @param  string|null $lineStatusCode
     * @param  boolean     $isTextPosition
     * @return object|null
     */
    public function getSupplyChainTradeLineItemType(?string $lineid = null, ?string $lineStatusCode = null, bool $isTextPosition = false): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $line = $this->createClassInstance('ram\SupplyChainTradeLineItemType');
        $doclinedoc = $this->getDocumentLineDocumentType($lineid);
        $lineAgreement = $this->createClassInstance('ram\LineTradeAgreementType');
        $lineDelivery = $this->createClassInstance('ram\LineTradeDeliveryType');
        $lineSettlement = $this->createClassInstance('ram\LineTradeSettlementType');

        $this->tryCall($line, "setAssociatedDocumentLineDocument", $doclinedoc);
        $this->tryCall($doclinedoc, "setLineStatusCode", $this->getLineStatusCodeType($lineStatusCode));
        if ($isTextPosition == false) {
            $this->tryCall($line, "setSpecifiedLineTradeAgreement", $lineAgreement);
            $this->tryCall($line, "setSpecifiedLineTradeDelivery", $lineDelivery);
        }
        $this->tryCall($line, "setSpecifiedLineTradeSettlement", $lineSettlement);

        return $line;
    }

    /**
     * Get product specification
     *
     * @param  string|null $name
     * @param  string|null $description
     * @param  string|null $sellerAssignedID
     * @param  string|null $buyerAssignedID
     * @param  string|null $globalIDType
     * @param  string|null $globalID
     * @param  string|null $batchId
     * @param  string|null $brandName
     * @return object|null
     */
    public function getTradeProductType(?string $name = null, ?string $description = null, ?string $sellerAssignedID = null, ?string $buyerAssignedID = null, ?string $globalIDType = null, ?string $globalID = null, ?string $batchId = null, ?string $brandName = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $product = $this->createClassInstance('ram\TradeProductType');

        $this->tryCallIfMethodExists($product, "unsetGlobalID", "setGlobalID", [$this->getIdType($globalID, $globalIDType)], $this->getIdType($globalID, $globalIDType));
        $this->tryCall($product, "setSellerAssignedID", $this->getIdType($sellerAssignedID));
        $this->tryCall($product, "setBuyerAssignedID", $this->getIdType($buyerAssignedID));
        $this->tryCall($product, "setName", $this->getTextType($name));
        $this->tryCall($product, "setDescription", $this->getTextType($description));
        $this->tryCall($product, "setBatchID", $this->getIdType($batchId));
        $this->tryCall($product, "setBrandName", $this->getTextType($brandName));

        return $product;
    }

    /**
     * Get Product Characteristic
     *
     * @param  string|null $typeCode
     * @param  string|null $description
     * @param  string|null $value
     * @param  float|null  $measureValue
     * @param  string|null $measureUnitCode
     * @return object|null
     */
    public function getProductCharacteristicType(?string $typeCode = null, ?string $description = null, ?string $value = null, ?float $measureValue = null, ?string $measureUnitCode = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $productCharacteristic = $this->createClassInstance('ram\ProductCharacteristicType');

        $this->tryCall($productCharacteristic, "setTypeCode", $this->getCodeType($typeCode));
        $this->tryCallIfMethodExists($productCharacteristic, "unsetDescription", "setDescription", [$this->getTextType($description)], $this->getTextType($description));
        $this->tryCallIfMethodExists($productCharacteristic, "unsetValue", "setValue", [$this->getTextType($value)], $this->getTextType($value));
        $this->tryCall($productCharacteristic, "setValueMeasure", $this->getMeasureType($measureValue, $measureUnitCode));

        return $productCharacteristic;
    }

    /**
     * Get Product Classification
     *
     * @param  string|null $classCode
     * @param  string|null $className
     * @param  string|null $listID
     * @param  string|null $listVersionID
     * @return object|null
     */
    public function getProductClassificationType(?string $classCode = null, ?string $className = null, ?string $listID = null, ?string $listVersionID = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $productClassification = $this->createClassInstance('ram\ProductClassificationType');

        $this->tryCall($productClassification, "setClassCode", $this->getCodeType2($classCode, $listID, $listVersionID));
        $this->tryCall($productClassification, "setClassName", $this->getTextType($className));

        return $productClassification;
    }

    /**
     * Get product reference product
     *
     * @param  string|null $globalID
     * @param  string|null $globalIDType
     * @param  string|null $sellerAssignedID
     * @param  string|null $buyerAssignedID
     * @param  string|null $name
     * @param  string|null $description
     * @param  float|null  $unitQuantity
     * @param  string|null $unitCode
     * @return object|null
     */
    public function getReferencedProductType(?string $globalID = null, ?string $globalIDType = null, ?string $sellerAssignedID = null, ?string $buyerAssignedID = null, ?string $name = null, ?string $description = null, ?float $unitQuantity = null, ?string $unitCode = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $referencedProduct = $this->createClassInstance('ram\ReferencedProductType');

        $this->tryCallAll($referencedProduct, ["addToGlobalID", "setGlobalID"], $this->getIdType($globalID, $globalIDType));
        $this->tryCall($referencedProduct, "setSellerAssignedID", $this->getIdType($sellerAssignedID));
        $this->tryCall($referencedProduct, "setBuyerAssignedID", $this->getIdType($buyerAssignedID));
        $this->tryCall($referencedProduct, "setName", $this->getTextType($name));
        $this->tryCall($referencedProduct, "setDescription", $this->getTextType($description));
        $this->tryCall($referencedProduct, "setUnitQuantity", $this->getQuantityType($unitQuantity, $unitCode));

        return $referencedProduct;
    }

    /**
     * Get product instance type
     *
     * @param  string|null $batchID
     * @param  string|null $serialId
     * @return object|null
     */
    public function getTradeProductInstanceType(?string $batchID = null, ?string $serialId = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $instance = $this->createClassInstance('ram\TradeProductInstanceType');

        $this->tryCall($instance, "setBatchID", $this->getIdType($batchID));
        $this->tryCall($instance, "setSerialID", $this->getIdType($serialId));

        return $instance;
    }

    /**
     * Get the packaging type
     *
     * @param  string|null $typeCode
     * @param  float|null  $width
     * @param  string|null $widthUnitCode
     * @param  float|null  $length
     * @param  string|null $lengthUnitCode
     * @param  float|null  $height
     * @param  string|null $heightUnitCode
     * @return object|null
     */
    public function getSupplyChainPackagingType(?string $typeCode = null, ?float $width = null, ?string $widthUnitCode = null, ?float $length = null, ?string $lengthUnitCode = null, ?float $height = null, ?string $heightUnitCode = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $spatialDimension = $this->createClassInstance('ram\SpatialDimensionType');

        $this->tryCall($spatialDimension, "setWidthMeasure", $this->getMeasureType($width, $widthUnitCode));
        $this->tryCall($spatialDimension, "setLengthMeasure", $this->getMeasureType($length, $lengthUnitCode));
        $this->tryCall($spatialDimension, "setHeightMeasure", $this->getMeasureType($height, $heightUnitCode));

        $packaging = $this->createClassInstance('ram\SupplyChainPackagingType');

        $this->tryCall($packaging, "setTypeCode", $this->getPackageTypeCodeType($typeCode));
        $this->tryCall($packaging, "setLinearSpatialDimension", $spatialDimension);

        return $packaging;
    }

    /**
     * Get trade price
     *
     * @param  float|null  $amount
     * @param  float|null  $basisQuantity
     * @param  string|null $basisQuantityUnitCode
     * @return object|null
     */
    public function getTradePriceType(?float $amount = null, ?float $basisQuantity = null, ?string $basisQuantityUnitCode = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $tradePrice = $this->createClassInstance('ram\TradePriceType');

        $this->tryCall($tradePrice, "setChargeAmount", $this->getAmountType($amount));
        $this->tryCall($tradePrice, "setBasisQuantity", $this->getQuantityType($basisQuantity, $basisQuantityUnitCode));

        return $tradePrice;
    }

    /**
     * Get Line Summation
     *
     * @param  float|null $lineTotalAmount
     * @param  float|null $totalAllowanceChargeAmount
     * @return object|null
     */
    public function getTradeSettlementLineMonetarySummationType(?float $lineTotalAmount = null, ?float $totalAllowanceChargeAmount = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $summation = $this->createClassInstance('ram\TradeSettlementLineMonetarySummationType');

        $this->tryCall($summation, "setLineTotalAmount", $this->getAmountType($lineTotalAmount));
        $this->tryCall($summation, "setTotalAllowanceChargeAmount", $this->getAmountType($totalAllowanceChargeAmount));

        return $summation;
    }

    /**
     * Set contect parameter
     *
     * @param  string|null $id
     * @return object|null
     */
    public function getDocumentContextParameterType(?string $id = null): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        $contextParameter = $this->createClassInstance('ram\DocumentContextParameterType');

        $this->tryCall($contextParameter, "setID", $this->getIdType($id));

        return $contextParameter;
    }

    /**
     * Create a datetime object
     *
     * @param  string|null $dateTimeString
     * @param  string|null $format
     * @return DateTime|null
     * @throws \Exception
     */
    public function toDateTime(?string $dateTimeString = null, ?string $format = null): ?DateTime
    {
        if (self::isNullOrEmpty($dateTimeString) || self::isNullOrEmpty($format)) {
            return null;
        }
        if ($format == "102") {
            return DateTime::createFromFormat("Ymd", $dateTimeString);
        } elseif ($format == "101") {
            return DateTime::createFromFormat("ymd", $dateTimeString);
        } elseif ($format == "201") {
            return DateTime::createFromFormat("ymdHi", $dateTimeString);
        } elseif ($format == "202") {
            return DateTime::createFromFormat("ymdHis", $dateTimeString);
        } elseif ($format == "203") {
            return DateTime::createFromFormat("YmdHi", $dateTimeString);
        } elseif ($format == "204") {
            return DateTime::createFromFormat("YmdHis", $dateTimeString);
        } else {
            throw new OrderUnknownDateFormatException($format);
        }
    }

    /**
     * Get Exchange rate type instance
     *
     * @param  float|null $rateValue
     * @return object|null
     */
    public function getRateType(?float $rateValue = null): ?object
    {
        $rate = $this->createClassInstance('udt\RateType');
        $this->tryCall($rate, "value", $rateValue);
        return $rate;
    }

    /**
     * Get Allowance Charge ReasonCode type instance
     *
     * @param string|null $reason
     * @return object|null
     */
    public function getAllowanceChargeReasonCodeType(?string $reason): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        return $this->createClassInstance('qdt\AllowanceChargeReasonCodeType', $reason);
    }

    /**
     * Get contact type code type instance
     *
     * @param string|null $reason
     * @return object|null
     */
    public function getContactTypeCodeType(?string $value): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        return $this->createClassInstance('qdt\AllowanceChargeReasonCodeType', $value);
    }

    /**
     * Get delivery terms code type instance
     *
     * @param string|null $reason
     * @return object|null
     */
    public function getDeliveryTermsCodeType(?string $value): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        return $this->createClassInstance('qdt\DeliveryTermsCodeType', $value);
    }

    /**
     * Get delivery terms function code type instance
     *
     * @param string|null $reason
     * @return object|null
     */
    public function getDeliveryTermsFunctionCodeType(?string $value): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        return $this->createClassInstance('qdt\DeliveryTermsFunctionCodeType', $value);
    }

    /**
     * Get line status code type instance
     *
     * @param string|null $reason
     * @return object|null
     */
    public function getLineStatusCodeType(?string $value): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        return $this->createClassInstance('qdt\LineStatusCodeType', $value);
    }

    /**
     * Get package type code type instance
     *
     * @param string|null $reason
     * @return object|null
     */
    public function getPackageTypeCodeType(?string $value): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        return $this->createClassInstance('qdt\PackageTypeCodeType', $value);
    }

    /**
     * Get payment means code type instance
     *
     * @param string|null $reason
     * @return object|null
     */
    public function getPaymentMeansCodeType(?string $value): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        return $this->createClassInstance('qdt\PaymentMeansCodeType', $value);
    }

    /**
     * Get reference code type instance
     *
     * @param string|null $reason
     * @return object|null
     */
    public function getReferenceCodeType(?string $value): ?object
    {
        if (self::isAllNullOrEmpty(func_get_args())) {
            return null;
        }

        return $this->createClassInstance('qdt\ReferenceCodeType', $value);
    }

    /**
     * Creates an instance of a class needed by $orderobject
     *
     * @param  string $classname
     * @param  mixed  $constructorvalue
     * @return object|null
     */
    public function createClassInstance($classname, $constructorvalue = null): ?object
    {
        $className = 'horstoeko\orderx\entities\\' . $this->profiledef["name"] . '\\' . $classname;

        if (!class_exists($className)) {
            return null;
        }

        return new $className($constructorvalue);
    }

    /**
     * Tries to call a method
     *
     * @param  object|null $instance
     * @param  string      $method
     * @param  mixed       $value
     * @return OrderObjectHelper
     */
    public function tryCall($instance, string $method, $value): OrderObjectHelper
    {
        if (!$instance) {
            return $this;
        }
        if (!$method) {
            return $this;
        }
        if (self::isNullOrEmpty($value) && !is_bool($value)) {
            return $this;
        }
        if ($this->methodExists($instance, $method)) {
            $instance->$method($value);
        }
        return $this;
    }

    /**
     * Tries to call a method with two parameters
     *
     * @param  object|null $instance
     * @param  string      $method
     * @param  mixed       $value1
     * @param  mixed       $value2
     * @return OrderObjectHelper
     */
    public function tryCall2($instance, string $method, $value1, $value2): OrderObjectHelper
    {
        if (!$instance) {
            return $this;
        }
        if (!$method) {
            return $this;
        }
        if (self::isNullOrEmpty($value1) && !is_bool($value1)) {
            return $this;
        }
        if (self::isNullOrEmpty($value2) && !is_bool($value2)) {
            return $this;
        }
        if ($this->methodExists($instance, $method)) {
            $instance->$method($value1, $value2);
        }
        return $this;
    }

    /**
     * Try call all methods
     *
     * @param  object|null $instance
     * @param  string[]    $methods
     * @param  mixed       $value
     * @return OrderObjectHelper
     */
    public function tryCallAll($instance, array $methods, $value): OrderObjectHelper
    {
        if (!$instance) {
            return $this;
        }
        if (self::isNullOrEmpty($value)) {
            return $this;
        }
        foreach ($methods as $method) {
            if ($this->methodExists($instance, $method)) {
                $instance->$method($value);
                return $this;
            }
        }
        return $this;
    }

    /**
     * Tries to call a method and return the returnvalue from call to $method
     * in object $instance
     *
     * @param  object|null $instance
     * @param  string      $method
     * @return mixed
     */
    public function tryCallAndReturn($instance, string $method)
    {
        if (!$instance) {
            return null;
        }
        if (!$method) {
            return null;
        }
        if ($this->methodExists($instance, $method)) {
            return $instance->$method();
        }
        return null;
    }

    /**
     * Try call methods in a form .object.method1.method2.method3
     *
     * @param  object|null $instance
     * @param  string      $methods
     * @return mixed
     */
    public function tryCallByPathAndReturn($instance, string $methods)
    {
        $result = null;
        $methods = explode(".", $methods);

        foreach ($methods as $method) {
            $result = $this->tryCallAndReturn($instance, $method);
            $instance = $result;
        }

        return $result;
    }

    /**
     * Call $method if exists, otherwise $method2 is calles with $value
     *
     * @param  object|null $instance
     * @param  string      $methodToLookFor
     * @param  string      $methodToCall
     * @param  mixed       $value
     * @param  mixed       $value2
     * @return OrderObjectHelper
     */
    public function tryCallIfMethodExists($instance, string $methodToLookFor, string $methodToCall, $value, $value2): OrderObjectHelper
    {
        if (!$instance) {
            return $this;
        }
        if (!$methodToLookFor) {
            return $this;
        }
        if (!$methodToCall) {
            return $this;
        }
        if (!$this->methodExists($instance, $methodToCall)) {
            return $this;
        }
        if ($this->methodExists($instance, $methodToLookFor)) {
            $instance->$methodToCall($value);
        } else {
            $instance->$methodToCall($value2);
        }
        return $this;
    }

    /**
     * Ensure that $input is an array
     *
     * @param  mixed $input
     * @return array
     */
    public function ensureStringArray($input): array
    {
        if (is_array($input)) {
            return $input;
        }
        return [(string)$input];
    }

    /**
     * Ensure array
     *
     * @param  mixed $value
     * @return array
     */
    public function ensureArray($value): array
    {
        if (!is_array($value)) {
            if (!is_null($value)) {
                return [$value];
            }
            return [];
        }
        return $value;
    }

    /**
     * Test if a value is null or empty
     *
     * @param  mixed $value
     * @return boolean
     */
    public static function isNullOrEmpty($value)
    {
        if ($value === null) {
            return true;
        }
        if (!is_object($value)) {
            if ((string)$value === "") {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks if all function arguments are null or empty
     *
     * @param  array $args
     * @return boolean
     */
    public static function isAllNullOrEmpty(array $args): bool
    {
        foreach ($args as $arg) {
            if ($arg instanceof DateTime) {
                return false;
            } else {
                if (!self::isNullOrEmpty($arg)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Wrapper for method_exists for use in PHP8
     *
     * @param  string|object|null $instance
     * @param  string             $method
     * @return boolean
     */
    public function methodExists($instance, $method): bool
    {
        if ($instance == null) {
            return false;
        }
        if (!(is_object($instance) || is_string($instance))) {
            return false;
        }
        return method_exists($instance, $method);
    }

    /**
     * If $index is not found in $array an exception is raised.
     *
     * @param  array   $arr
     * @param  integer $index
     * @return void
     * @throws OutOfRangeException
     */
    public function checkArrayIndex(array $arr, int $index): void
    {
        if (!isset($arr[$index])) {
            throw new OutOfRangeException();
        }
    }

    /**
     * Get the array from $arr element at position $index. If $index is not found in $array an exception is raised.
     *
     * @param  array   $arr
     * @param  integer $index
     * @return mixed
     * @throws OutOfRangeException
     */
    public function getArrayIndex(array $arr, int $index)
    {
        $this->checkArrayIndex($arr, $index);
        return $arr[$index];
    }
}
