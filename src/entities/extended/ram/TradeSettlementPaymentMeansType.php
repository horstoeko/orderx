<?php

namespace horstoeko\orderx\entities\extended\ram;

/**
 * Class representing TradeSettlementPaymentMeansType
 *
 * Trade Settlement Payment Means
 * XSD Type: TradeSettlementPaymentMeansType
 */
class TradeSettlementPaymentMeansType
{

    /**
     * Type Code
     *
     * @var string $typeCode
     */
    private $typeCode = null;

    /**
     * Information
     *
     * @var string[] $information
     */
    private $information = [
        
    ];

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
     * Adds as information
     *
     * Information
     *
     * @return self
     * @param  string $information
     */
    public function addToInformation($information)
    {
        $this->information[] = $information;
        return $this;
    }

    /**
     * isset information
     *
     * Information
     *
     * @param  int|string $index
     * @return bool
     */
    public function issetInformation($index)
    {
        return isset($this->information[$index]);
    }

    /**
     * unset information
     *
     * Information
     *
     * @param  int|string $index
     * @return void
     */
    public function unsetInformation($index)
    {
        unset($this->information[$index]);
    }

    /**
     * Gets as information
     *
     * Information
     *
     * @return string[]
     */
    public function getInformation()
    {
        return $this->information;
    }

    /**
     * Sets a new information
     *
     * Information
     *
     * @param  string $information
     * @return self
     */
    public function setInformation(array $information = null)
    {
        $this->information = $information;
        return $this;
    }
}
