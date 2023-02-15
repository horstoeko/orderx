<?php

namespace horstoeko\orderx\entities\extended\ram;

/**
 * Class representing ExchangedDocumentType
 *
 * Exchanged Document
 * XSD Type: ExchangedDocumentType
 */
class ExchangedDocumentType
{

    /**
     * ID
     *
     * @var \horstoeko\orderx\entities\extended\udt\IDType $iD
     */
    private $iD = null;

    /**
     * Name
     *
     * @var string $name
     */
    private $name = null;

    /**
     * Type Code
     *
     * @var string $typeCode
     */
    private $typeCode = null;

    /**
     * Status Code
     *
     * @var string $statusCode
     */
    private $statusCode = null;

    /**
     * Issue Date Time
     *
     * @var \horstoeko\orderx\entities\extended\udt\DateTimeType $issueDateTime
     */
    private $issueDateTime = null;

    /**
     * @var \horstoeko\orderx\entities\extended\udt\IndicatorType $copyIndicator
     */
    private $copyIndicator = null;

    /**
     * Language Code
     *
     * @var \horstoeko\orderx\entities\extended\udt\IDType[] $languageID
     */
    private $languageID = [
        
    ];

    /**
     * Purpose Code
     *
     * @var string $purposeCode
     */
    private $purposeCode = null;

    /**
     * Response Request Type Code
     *
     * @var \horstoeko\orderx\entities\extended\udt\CodeType $requestedResponseTypeCode
     */
    private $requestedResponseTypeCode = null;

    /**
     * Note
     *
     * @var \horstoeko\orderx\entities\extended\ram\NoteType[] $includedNote
     */
    private $includedNote = [
        
    ];

    /**
     * Effective Period
     *
     * @var \horstoeko\orderx\entities\extended\ram\SpecifiedPeriodType $effectiveSpecifiedPeriod
     */
    private $effectiveSpecifiedPeriod = null;

    /**
     * Gets as iD
     *
     * ID
     *
     * @return \horstoeko\orderx\entities\extended\udt\IDType
     */
    public function getID()
    {
        return $this->iD;
    }

    /**
     * Sets a new iD
     *
     * ID
     *
     * @param  \horstoeko\orderx\entities\extended\udt\IDType $iD
     * @return self
     */
    public function setID(\horstoeko\orderx\entities\extended\udt\IDType $iD)
    {
        $this->iD = $iD;
        return $this;
    }

    /**
     * Gets as name
     *
     * Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * Name
     *
     * @param  string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets as typeCode
     *
     * Type Code
     *
     * @return string
     */
    public function getTypeCode()
    {
        return $this->typeCode;
    }

    /**
     * Sets a new typeCode
     *
     * Type Code
     *
     * @param  string $typeCode
     * @return self
     */
    public function setTypeCode($typeCode)
    {
        $this->typeCode = $typeCode;
        return $this;
    }

    /**
     * Gets as statusCode
     *
     * Status Code
     *
     * @return string
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Sets a new statusCode
     *
     * Status Code
     *
     * @param  string $statusCode
     * @return self
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * Gets as issueDateTime
     *
     * Issue Date Time
     *
     * @return \horstoeko\orderx\entities\extended\udt\DateTimeType
     */
    public function getIssueDateTime()
    {
        return $this->issueDateTime;
    }

    /**
     * Sets a new issueDateTime
     *
     * Issue Date Time
     *
     * @param  \horstoeko\orderx\entities\extended\udt\DateTimeType $issueDateTime
     * @return self
     */
    public function setIssueDateTime(\horstoeko\orderx\entities\extended\udt\DateTimeType $issueDateTime)
    {
        $this->issueDateTime = $issueDateTime;
        return $this;
    }

    /**
     * Gets as copyIndicator
     *
     * @return \horstoeko\orderx\entities\extended\udt\IndicatorType
     */
    public function getCopyIndicator()
    {
        return $this->copyIndicator;
    }

    /**
     * Sets a new copyIndicator
     *
     * @param  \horstoeko\orderx\entities\extended\udt\IndicatorType $copyIndicator
     * @return self
     */
    public function setCopyIndicator(?\horstoeko\orderx\entities\extended\udt\IndicatorType $copyIndicator = null)
    {
        $this->copyIndicator = $copyIndicator;
        return $this;
    }

    /**
     * Adds as languageID
     *
     * Language Code
     *
     * @return self
     * @param  \horstoeko\orderx\entities\extended\udt\IDType $languageID
     */
    public function addToLanguageID(\horstoeko\orderx\entities\extended\udt\IDType $languageID)
    {
        $this->languageID[] = $languageID;
        return $this;
    }

    /**
     * isset languageID
     *
     * Language Code
     *
     * @param  int|string $index
     * @return bool
     */
    public function issetLanguageID($index)
    {
        return isset($this->languageID[$index]);
    }

    /**
     * unset languageID
     *
     * Language Code
     *
     * @param  int|string $index
     * @return void
     */
    public function unsetLanguageID($index)
    {
        unset($this->languageID[$index]);
    }

    /**
     * Gets as languageID
     *
     * Language Code
     *
     * @return \horstoeko\orderx\entities\extended\udt\IDType[]
     */
    public function getLanguageID()
    {
        return $this->languageID;
    }

    /**
     * Sets a new languageID
     *
     * Language Code
     *
     * @param  \horstoeko\orderx\entities\extended\udt\IDType[] $languageID
     * @return self
     */
    public function setLanguageID(array $languageID = null)
    {
        $this->languageID = $languageID;
        return $this;
    }

    /**
     * Gets as purposeCode
     *
     * Purpose Code
     *
     * @return string
     */
    public function getPurposeCode()
    {
        return $this->purposeCode;
    }

    /**
     * Sets a new purposeCode
     *
     * Purpose Code
     *
     * @param  string $purposeCode
     * @return self
     */
    public function setPurposeCode($purposeCode)
    {
        $this->purposeCode = $purposeCode;
        return $this;
    }

    /**
     * Gets as requestedResponseTypeCode
     *
     * Response Request Type Code
     *
     * @return \horstoeko\orderx\entities\extended\udt\CodeType
     */
    public function getRequestedResponseTypeCode()
    {
        return $this->requestedResponseTypeCode;
    }

    /**
     * Sets a new requestedResponseTypeCode
     *
     * Response Request Type Code
     *
     * @param  \horstoeko\orderx\entities\extended\udt\CodeType $requestedResponseTypeCode
     * @return self
     */
    public function setRequestedResponseTypeCode(?\horstoeko\orderx\entities\extended\udt\CodeType $requestedResponseTypeCode = null)
    {
        $this->requestedResponseTypeCode = $requestedResponseTypeCode;
        return $this;
    }

    /**
     * Adds as includedNote
     *
     * Note
     *
     * @return self
     * @param  \horstoeko\orderx\entities\extended\ram\NoteType $includedNote
     */
    public function addToIncludedNote(\horstoeko\orderx\entities\extended\ram\NoteType $includedNote)
    {
        $this->includedNote[] = $includedNote;
        return $this;
    }

    /**
     * isset includedNote
     *
     * Note
     *
     * @param  int|string $index
     * @return bool
     */
    public function issetIncludedNote($index)
    {
        return isset($this->includedNote[$index]);
    }

    /**
     * unset includedNote
     *
     * Note
     *
     * @param  int|string $index
     * @return void
     */
    public function unsetIncludedNote($index)
    {
        unset($this->includedNote[$index]);
    }

    /**
     * Gets as includedNote
     *
     * Note
     *
     * @return \horstoeko\orderx\entities\extended\ram\NoteType[]
     */
    public function getIncludedNote()
    {
        return $this->includedNote;
    }

    /**
     * Sets a new includedNote
     *
     * Note
     *
     * @param  \horstoeko\orderx\entities\extended\ram\NoteType[] $includedNote
     * @return self
     */
    public function setIncludedNote(array $includedNote = null)
    {
        $this->includedNote = $includedNote;
        return $this;
    }

    /**
     * Gets as effectiveSpecifiedPeriod
     *
     * Effective Period
     *
     * @return \horstoeko\orderx\entities\extended\ram\SpecifiedPeriodType
     */
    public function getEffectiveSpecifiedPeriod()
    {
        return $this->effectiveSpecifiedPeriod;
    }

    /**
     * Sets a new effectiveSpecifiedPeriod
     *
     * Effective Period
     *
     * @param  \horstoeko\orderx\entities\extended\ram\SpecifiedPeriodType $effectiveSpecifiedPeriod
     * @return self
     */
    public function setEffectiveSpecifiedPeriod(?\horstoeko\orderx\entities\extended\ram\SpecifiedPeriodType $effectiveSpecifiedPeriod = null)
    {
        $this->effectiveSpecifiedPeriod = $effectiveSpecifiedPeriod;
        return $this;
    }
}
