<?php

namespace horstoeko\orderx\entities\basic\ram;

/**
 * Class representing TradeDeliveryTermsType
 *
 * Trade Delivery Terms
 * XSD Type: TradeDeliveryTermsType
 */
class TradeDeliveryTermsType
{

    /**
     * Code
     *
     * @var string $deliveryTypeCode
     */
    private $deliveryTypeCode = null;

    /**
     * Function Code
     *
     * @var string $functionCode
     */
    private $functionCode = null;

    /**
     * Gets as deliveryTypeCode
     *
     * Code
     *
     * @return string
     */
    public function getDeliveryTypeCode()
    {
        return $this->deliveryTypeCode;
    }

    /**
     * Sets a new deliveryTypeCode
     *
     * Code
     *
     * @param string $deliveryTypeCode
     * @return self
     */
    public function setDeliveryTypeCode($deliveryTypeCode)
    {
        $this->deliveryTypeCode = $deliveryTypeCode;
        return $this;
    }

    /**
     * Gets as functionCode
     *
     * Function Code
     *
     * @return string
     */
    public function getFunctionCode()
    {
        return $this->functionCode;
    }

    /**
     * Sets a new functionCode
     *
     * Function Code
     *
     * @param string $functionCode
     * @return self
     */
    public function setFunctionCode($functionCode)
    {
        $this->functionCode = $functionCode;
        return $this;
    }


}

