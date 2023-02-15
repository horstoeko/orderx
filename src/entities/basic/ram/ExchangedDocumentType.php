<?php

namespace horstoeko\orderx\entities\basic\ram;

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
     * @var \horstoeko\orderx\entities\basic\udt\IDType $iD
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
     * @var \horstoeko\orderx\entities\basic\udt\DateTimeType $issueDateTime
     */
    private $issueDateTime = null;

    /**
     * @var \horstoeko\orderx\entities\basic\udt\IndicatorType $copyIndicator
     */
    private $copyIndicator = null;

    /**
     * Purpose Code
     *
     * @var string $purposeCode
     */
    private $purposeCode = null;

    /**
     * Response Request Type Code
     *
     * @var string $requestedResponseTypeCode
     */
    private $requestedResponseTypeCode = null;

    /**
     * Note
     *
     * @var \horstoeko\orderx\entities\basic\ram\NoteType[] $includedNote
     */
    private $includedNote = [
        
    ];

    /**
     * Gets as iD
     *
     * ID
     *
     * @return \horstoeko\orderx\entities\basic\udt\IDType
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
     * @param  \horstoeko\orderx\entities\basic\udt\IDType $iD
     * @return self
     */
    public function setID(\horstoeko\orderx\entities\basic\udt\IDType $iD)
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
     * @return \horstoeko\orderx\entities\basic\udt\DateTimeType
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
     * @param  \horstoeko\orderx\entities\basic\udt\DateTimeType $issueDateTime
     * @return self
     */
    public function setIssueDateTime(\horstoeko\orderx\entities\basic\udt\DateTimeType $issueDateTime)
    {
        $this->issueDateTime = $issueDateTime;
        return $this;
    }

    /**
     * Gets as copyIndicator
     *
     * @return \horstoeko\orderx\entities\basic\udt\IndicatorType
     */
    public function getCopyIndicator()
    {
        return $this->copyIndicator;
    }

    /**
     * Sets a new copyIndicator
     *
     * @param  \horstoeko\orderx\entities\basic\udt\IndicatorType $copyIndicator
     * @return self
     */
    public function setCopyIndicator(?\horstoeko\orderx\entities\basic\udt\IndicatorType $copyIndicator = null)
    {
        $this->copyIndicator = $copyIndicator;
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
     * @return string
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
     * @param  string $requestedResponseTypeCode
     * @return self
     */
    public function setRequestedResponseTypeCode($requestedResponseTypeCode)
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
     * @param  \horstoeko\orderx\entities\basic\ram\NoteType $includedNote
     */
    public function addToIncludedNote(\horstoeko\orderx\entities\basic\ram\NoteType $includedNote)
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
     * @return \horstoeko\orderx\entities\basic\ram\NoteType[]
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
     * @param  \horstoeko\orderx\entities\basic\ram\NoteType[] $includedNote
     * @return self
     */
    public function setIncludedNote(array $includedNote = null)
    {
        $this->includedNote = $includedNote;
        return $this;
    }
}
