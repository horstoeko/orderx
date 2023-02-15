<?php

namespace horstoeko\orderx\entities\basic\ram;

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
     * @var \horstoeko\orderx\entities\basic\udt\IDType $issuerAssignedID
     */
    private $issuerAssignedID = null;

    /**
     * Line ID
     *
     * @var \horstoeko\orderx\entities\basic\udt\IDType $lineID
     */
    private $lineID = null;

    /**
     * Gets as issuerAssignedID
     *
     * Issuer Assigned ID
     *
     * @return \horstoeko\orderx\entities\basic\udt\IDType
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
     * @param  \horstoeko\orderx\entities\basic\udt\IDType $issuerAssignedID
     * @return self
     */
    public function setIssuerAssignedID(?\horstoeko\orderx\entities\basic\udt\IDType $issuerAssignedID = null)
    {
        $this->issuerAssignedID = $issuerAssignedID;
        return $this;
    }

    /**
     * Gets as lineID
     *
     * Line ID
     *
     * @return \horstoeko\orderx\entities\basic\udt\IDType
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
     * @param  \horstoeko\orderx\entities\basic\udt\IDType $lineID
     * @return self
     */
    public function setLineID(?\horstoeko\orderx\entities\basic\udt\IDType $lineID = null)
    {
        $this->lineID = $lineID;
        return $this;
    }
}
