<?php

namespace horstoeko\orderx\entities\comfort\ram;

/**
 * Class representing UniversalCommunicationType
 *
 * Communication
 * XSD Type: UniversalCommunicationType
 */
class UniversalCommunicationType
{

    /**
     * URI
     *
     * @var \horstoeko\orderx\entities\comfort\udt\IDType $uRIID
     */
    private $uRIID = null;

    /**
     * Complete Number
     *
     * @var string $completeNumber
     */
    private $completeNumber = null;

    /**
     * Gets as uRIID
     *
     * URI
     *
     * @return \horstoeko\orderx\entities\comfort\udt\IDType
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
     * @param  \horstoeko\orderx\entities\comfort\udt\IDType $uRIID
     * @return self
     */
    public function setURIID(?\horstoeko\orderx\entities\comfort\udt\IDType $uRIID = null)
    {
        $this->uRIID = $uRIID;
        return $this;
    }

    /**
     * Gets as completeNumber
     *
     * Complete Number
     *
     * @return string
     */
    public function getCompleteNumber()
    {
        return $this->completeNumber;
    }

    /**
     * Sets a new completeNumber
     *
     * Complete Number
     *
     * @param  string $completeNumber
     * @return self
     */
    public function setCompleteNumber($completeNumber)
    {
        $this->completeNumber = $completeNumber;
        return $this;
    }
}
