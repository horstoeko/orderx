<?php

namespace horstoeko\orderx\entities\comfort\ram;

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
     * @var string $information
     */
    private $information = null;

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
     * Gets as information
     *
     * Information
     *
     * @return string
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
    public function setInformation($information)
    {
        $this->information = $information;
        return $this;
    }
}
