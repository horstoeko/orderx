<?php

namespace horstoeko\orderx\entities\extended\ram;

/**
 * Class representing ReferencedDocumentType
 *
 * Referenced Document
 * XSD Type: ReferencedDocumentType
 */
class ReferencedDocumentType
{

    /**
     * Issuer Assigned ID
     *
     * @var \horstoeko\orderx\entities\extended\udt\IDType $issuerAssignedID
     */
    private $issuerAssignedID = null;

    /**
     * URI
     *
     * @var \horstoeko\orderx\entities\extended\udt\IDType $uRIID
     */
    private $uRIID = null;

    /**
     * Line ID
     *
     * @var \horstoeko\orderx\entities\extended\udt\IDType $lineID
     */
    private $lineID = null;

    /**
     * Type Code
     *
     * @var string $typeCode
     */
    private $typeCode = null;

    /**
     * Name
     *
     * @var string $name
     */
    private $name = null;

    /**
     * Attached Binary Object
     *
     * @var \horstoeko\orderx\entities\extended\udt\BinaryObjectType $attachmentBinaryObject
     */
    private $attachmentBinaryObject = null;

    /**
     * Reference Type Code
     *
     * @var string $referenceTypeCode
     */
    private $referenceTypeCode = null;

    /**
     * Formatted Issue Date Time
     *
     * @var \horstoeko\orderx\entities\extended\qdt\FormattedDateTimeType $formattedIssueDateTime
     */
    private $formattedIssueDateTime = null;

    /**
     * Gets as issuerAssignedID
     *
     * Issuer Assigned ID
     *
     * @return \horstoeko\orderx\entities\extended\udt\IDType
     */
    public function getIssuerAssignedID()
    {
        return $this->issuerAssignedID;
    }

    /**
     * Sets a new issuerAssignedID
     *
     * Issuer Assigned ID
     *
     * @param  \horstoeko\orderx\entities\extended\udt\IDType $issuerAssignedID
     * @return self
     */
    public function setIssuerAssignedID(?\horstoeko\orderx\entities\extended\udt\IDType $issuerAssignedID = null)
    {
        $this->issuerAssignedID = $issuerAssignedID;
        return $this;
    }

    /**
     * Gets as uRIID
     *
     * URI
     *
     * @return \horstoeko\orderx\entities\extended\udt\IDType
     */
    public function getURIID()
    {
        return $this->uRIID;
    }

    /**
     * Sets a new uRIID
     *
     * URI
     *
     * @param  \horstoeko\orderx\entities\extended\udt\IDType $uRIID
     * @return self
     */
    public function setURIID(?\horstoeko\orderx\entities\extended\udt\IDType $uRIID = null)
    {
        $this->uRIID = $uRIID;
        return $this;
    }

    /**
     * Gets as lineID
     *
     * Line ID
     *
     * @return \horstoeko\orderx\entities\extended\udt\IDType
     */
    public function getLineID()
    {
        return $this->lineID;
    }

    /**
     * Sets a new lineID
     *
     * Line ID
     *
     * @param  \horstoeko\orderx\entities\extended\udt\IDType $lineID
     * @return self
     */
    public function setLineID(?\horstoeko\orderx\entities\extended\udt\IDType $lineID = null)
    {
        $this->lineID = $lineID;
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
     * Gets as attachmentBinaryObject
     *
     * Attached Binary Object
     *
     * @return \horstoeko\orderx\entities\extended\udt\BinaryObjectType
     */
    public function getAttachmentBinaryObject()
    {
        return $this->attachmentBinaryObject;
    }

    /**
     * Sets a new attachmentBinaryObject
     *
     * Attached Binary Object
     *
     * @param  \horstoeko\orderx\entities\extended\udt\BinaryObjectType $attachmentBinaryObject
     * @return self
     */
    public function setAttachmentBinaryObject(?\horstoeko\orderx\entities\extended\udt\BinaryObjectType $attachmentBinaryObject = null)
    {
        $this->attachmentBinaryObject = $attachmentBinaryObject;
        return $this;
    }

    /**
     * Gets as referenceTypeCode
     *
     * Reference Type Code
     *
     * @return string
     */
    public function getReferenceTypeCode()
    {
        return $this->referenceTypeCode;
    }

    /**
     * Sets a new referenceTypeCode
     *
     * Reference Type Code
     *
     * @param  string $referenceTypeCode
     * @return self
     */
    public function setReferenceTypeCode($referenceTypeCode)
    {
        $this->referenceTypeCode = $referenceTypeCode;
        return $this;
    }

    /**
     * Gets as formattedIssueDateTime
     *
     * Formatted Issue Date Time
     *
     * @return \horstoeko\orderx\entities\extended\qdt\FormattedDateTimeType
     */
    public function getFormattedIssueDateTime()
    {
        return $this->formattedIssueDateTime;
    }

    /**
     * Sets a new formattedIssueDateTime
     *
     * Formatted Issue Date Time
     *
     * @param  \horstoeko\orderx\entities\extended\qdt\FormattedDateTimeType $formattedIssueDateTime
     * @return self
     */
    public function setFormattedIssueDateTime(?\horstoeko\orderx\entities\extended\qdt\FormattedDateTimeType $formattedIssueDateTime = null)
    {
        $this->formattedIssueDateTime = $formattedIssueDateTime;
        return $this;
    }
}
