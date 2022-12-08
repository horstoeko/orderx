<?php

namespace horstoeko\orderx\entities\comfort\ram;

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
     * @var \horstoeko\orderx\entities\comfort\udt\IndicatorType $partialDeliveryAllowedIndicator
     */
    private $partialDeliveryAllowedIndicator = null;

    /**
     * Requested Quantity
     *
     * @var \horstoeko\orderx\entities\comfort\udt\QuantityType $requestedQuantity
     */
    private $requestedQuantity = null;

    /**
     * Agreed Quantity
     *
     * @var \horstoeko\orderx\entities\comfort\udt\QuantityType $agreedQuantity
     */
    private $agreedQuantity = null;

    /**
     * Package Quantity
     *
     * @var \horstoeko\orderx\entities\comfort\udt\QuantityType $packageQuantity
     */
    private $packageQuantity = null;

    /**
     * Per Package Unit Quantity
     *
     * @var \horstoeko\orderx\entities\comfort\udt\QuantityType $perPackageUnitQuantity
     */
    private $perPackageUnitQuantity = null;

    /**
     * Requested Despatch Event
     *
     * @var \horstoeko\orderx\entities\comfort\ram\SupplyChainEventType $requestedDespatchSupplyChainEvent
     */
    private $requestedDespatchSupplyChainEvent = null;

    /**
     * Requested Delivery Event
     *
     * @var \horstoeko\orderx\entities\comfort\ram\SupplyChainEventType $requestedDeliverySupplyChainEvent
     */
    private $requestedDeliverySupplyChainEvent = null;

    /**
     * Gets as partialDeliveryAllowedIndicator
     *
     * Partial Delivery Allowed Indicator
     *
     * @return \horstoeko\orderx\entities\comfort\udt\IndicatorType
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
     * @param \horstoeko\orderx\entities\comfort\udt\IndicatorType $partialDeliveryAllowedIndicator
     * @return self
     */
    public function setPartialDeliveryAllowedIndicator(?\horstoeko\orderx\entities\comfort\udt\IndicatorType $partialDeliveryAllowedIndicator = null)
    {
        $this->partialDeliveryAllowedIndicator = $partialDeliveryAllowedIndicator;
        return $this;
    }

    /**
     * Gets as requestedQuantity
     *
     * Requested Quantity
     *
     * @return \horstoeko\orderx\entities\comfort\udt\QuantityType
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
     * @param \horstoeko\orderx\entities\comfort\udt\QuantityType $requestedQuantity
     * @return self
     */
    public function setRequestedQuantity(\horstoeko\orderx\entities\comfort\udt\QuantityType $requestedQuantity)
    {
        $this->requestedQuantity = $requestedQuantity;
        return $this;
    }

    /**
     * Gets as agreedQuantity
     *
     * Agreed Quantity
     *
     * @return \horstoeko\orderx\entities\comfort\udt\QuantityType
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
     * @param \horstoeko\orderx\entities\comfort\udt\QuantityType $agreedQuantity
     * @return self
     */
    public function setAgreedQuantity(?\horstoeko\orderx\entities\comfort\udt\QuantityType $agreedQuantity = null)
    {
        $this->agreedQuantity = $agreedQuantity;
        return $this;
    }

    /**
     * Gets as packageQuantity
     *
     * Package Quantity
     *
     * @return \horstoeko\orderx\entities\comfort\udt\QuantityType
     */
    public function getPackageQuantity()
    {
        return $this->packageQuantity;
    }

    /**
     * Sets a new packageQuantity
     *
     * Package Quantity
     *
     * @param \horstoeko\orderx\entities\comfort\udt\QuantityType $packageQuantity
     * @return self
     */
    public function setPackageQuantity(?\horstoeko\orderx\entities\comfort\udt\QuantityType $packageQuantity = null)
    {
        $this->packageQuantity = $packageQuantity;
        return $this;
    }

    /**
     * Gets as perPackageUnitQuantity
     *
     * Per Package Unit Quantity
     *
     * @return \horstoeko\orderx\entities\comfort\udt\QuantityType
     */
    public function getPerPackageUnitQuantity()
    {
        return $this->perPackageUnitQuantity;
    }

    /**
     * Sets a new perPackageUnitQuantity
     *
     * Per Package Unit Quantity
     *
     * @param \horstoeko\orderx\entities\comfort\udt\QuantityType $perPackageUnitQuantity
     * @return self
     */
    public function setPerPackageUnitQuantity(?\horstoeko\orderx\entities\comfort\udt\QuantityType $perPackageUnitQuantity = null)
    {
        $this->perPackageUnitQuantity = $perPackageUnitQuantity;
        return $this;
    }

    /**
     * Gets as requestedDespatchSupplyChainEvent
     *
     * Requested Despatch Event
     *
     * @return \horstoeko\orderx\entities\comfort\ram\SupplyChainEventType
     */
    public function getRequestedDespatchSupplyChainEvent()
    {
        return $this->requestedDespatchSupplyChainEvent;
    }

    /**
     * Sets a new requestedDespatchSupplyChainEvent
     *
     * Requested Despatch Event
     *
     * @param \horstoeko\orderx\entities\comfort\ram\SupplyChainEventType $requestedDespatchSupplyChainEvent
     * @return self
     */
    public function setRequestedDespatchSupplyChainEvent(?\horstoeko\orderx\entities\comfort\ram\SupplyChainEventType $requestedDespatchSupplyChainEvent = null)
    {
        $this->requestedDespatchSupplyChainEvent = $requestedDespatchSupplyChainEvent;
        return $this;
    }

    /**
     * Gets as requestedDeliverySupplyChainEvent
     *
     * Requested Delivery Event
     *
     * @return \horstoeko\orderx\entities\comfort\ram\SupplyChainEventType
     */
    public function getRequestedDeliverySupplyChainEvent()
    {
        return $this->requestedDeliverySupplyChainEvent;
    }

    /**
     * Sets a new requestedDeliverySupplyChainEvent
     *
     * Requested Delivery Event
     *
     * @param \horstoeko\orderx\entities\comfort\ram\SupplyChainEventType $requestedDeliverySupplyChainEvent
     * @return self
     */
    public function setRequestedDeliverySupplyChainEvent(?\horstoeko\orderx\entities\comfort\ram\SupplyChainEventType $requestedDeliverySupplyChainEvent = null)
    {
        $this->requestedDeliverySupplyChainEvent = $requestedDeliverySupplyChainEvent;
        return $this;
    }


}

