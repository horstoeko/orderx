<?php

namespace horstoeko\orderx\entities\basic\ram;

/**
 * Class representing LineTradeDeliveryType
 *
 * Line Trade Delivery
 * XSD Type: LineTradeDeliveryType
 */
class LineTradeDeliveryType
{

    /**
     * Partial Delivery Allowed Indicator
     *
     * @var \horstoeko\orderx\entities\basic\udt\IndicatorType $partialDeliveryAllowedIndicator
     */
    private $partialDeliveryAllowedIndicator = null;

    /**
     * Requested Quantity
     *
     * @var \horstoeko\orderx\entities\basic\udt\QuantityType $requestedQuantity
     */
    private $requestedQuantity = null;

    /**
     * Agreed Quantity
     *
     * @var \horstoeko\orderx\entities\basic\udt\QuantityType $agreedQuantity
     */
    private $agreedQuantity = null;

    /**
     * Gets as partialDeliveryAllowedIndicator
     *
     * Partial Delivery Allowed Indicator
     *
     * @return \horstoeko\orderx\entities\basic\udt\IndicatorType
     */
    public function getPartialDeliveryAllowedIndicator()
    {
        return $this->partialDeliveryAllowedIndicator;
    }

    /**
     * Sets a new partialDeliveryAllowedIndicator
     *
     * Partial Delivery Allowed Indicator
     *
     * @param \horstoeko\orderx\entities\basic\udt\IndicatorType $partialDeliveryAllowedIndicator
     * @return self
     */
    public function setPartialDeliveryAllowedIndicator(?\horstoeko\orderx\entities\basic\udt\IndicatorType $partialDeliveryAllowedIndicator = null)
    {
        $this->partialDeliveryAllowedIndicator = $partialDeliveryAllowedIndicator;
        return $this;
    }

    /**
     * Gets as requestedQuantity
     *
     * Requested Quantity
     *
     * @return \horstoeko\orderx\entities\basic\udt\QuantityType
     */
    public function getRequestedQuantity()
    {
        return $this->requestedQuantity;
    }

    /**
     * Sets a new requestedQuantity
     *
     * Requested Quantity
     *
     * @param \horstoeko\orderx\entities\basic\udt\QuantityType $requestedQuantity
     * @return self
     */
    public function setRequestedQuantity(\horstoeko\orderx\entities\basic\udt\QuantityType $requestedQuantity)
    {
        $this->requestedQuantity = $requestedQuantity;
        return $this;
    }

    /**
     * Gets as agreedQuantity
     *
     * Agreed Quantity
     *
     * @return \horstoeko\orderx\entities\basic\udt\QuantityType
     */
    public function getAgreedQuantity()
    {
        return $this->agreedQuantity;
    }

    /**
     * Sets a new agreedQuantity
     *
     * Agreed Quantity
     *
     * @param \horstoeko\orderx\entities\basic\udt\QuantityType $agreedQuantity
     * @return self
     */
    public function setAgreedQuantity(?\horstoeko\orderx\entities\basic\udt\QuantityType $agreedQuantity = null)
    {
        $this->agreedQuantity = $agreedQuantity;
        return $this;
    }


}

