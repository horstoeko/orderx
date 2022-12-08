<?php

namespace horstoeko\orderx\entities\extended\ram;

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
     * Description
     *
     * @var string $description
     */
    private $description = null;

    /**
     * Function Code
     *
     * @var string $functionCode
     */
    private $functionCode = null;

    /**
     * Relevant Location
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradeLocationType $relevantTradeLocation
     */
    private $relevantTradeLocation = null;

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
     * Gets as description
     *
     * Description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets a new description
     *
     * Description
     *
     * @param string $description
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;
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

    /**
     * Gets as relevantTradeLocation
     *
     * Relevant Location
     *
     * @return \horstoeko\orderx\entities\extended\ram\TradeLocationType
     */
    public function getRelevantTradeLocation()
    {
        return $this->relevantTradeLocation;
    }

    /**
     * Sets a new relevantTradeLocation
     *
     * Relevant Location
     *
     * @param \horstoeko\orderx\entities\extended\ram\TradeLocationType $relevantTradeLocation
     * @return self
     */
    public function setRelevantTradeLocation(?\horstoeko\orderx\entities\extended\ram\TradeLocationType $relevantTradeLocation = null)
    {
        $this->relevantTradeLocation = $relevantTradeLocation;
        return $this;
    }


}

