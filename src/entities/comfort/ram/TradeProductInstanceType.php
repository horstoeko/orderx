<?php

namespace horstoeko\orderx\entities\comfort\ram;

/**
 * Class representing TradeProductInstanceType
 *
 * Trade Product Instance
 * XSD Type: TradeProductInstanceType
 */
class TradeProductInstanceType
{

    /**
     * Batch ID
     *
     * @var \horstoeko\orderx\entities\comfort\udt\IDType $batchID
     */
    private $batchID = null;

    /**
     * Serial ID
     *
     * @var \horstoeko\orderx\entities\comfort\udt\IDType $serialID
     */
    private $serialID = null;

    /**
     * Gets as batchID
     *
     * Batch ID
     *
     * @return \horstoeko\orderx\entities\comfort\udt\IDType
     */
    public function getBatchID()
    {
        return $this->batchID;
    }

    /**
     * Sets a new batchID
     *
     * Batch ID
     *
     * @param \horstoeko\orderx\entities\comfort\udt\IDType $batchID
     * @return self
     */
    public function setBatchID(?\horstoeko\orderx\entities\comfort\udt\IDType $batchID = null)
    {
        $this->batchID = $batchID;
        return $this;
    }

    /**
     * Gets as serialID
     *
     * Serial ID
     *
     * @return \horstoeko\orderx\entities\comfort\udt\IDType
     */
    public function getSerialID()
    {
        return $this->serialID;
    }

    /**
     * Sets a new serialID
     *
     * Serial ID
     *
     * @param \horstoeko\orderx\entities\comfort\udt\IDType $serialID
     * @return self
     */
    public function setSerialID(?\horstoeko\orderx\entities\comfort\udt\IDType $serialID = null)
    {
        $this->serialID = $serialID;
        return $this;
    }


}

