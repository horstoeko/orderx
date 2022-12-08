<?php

namespace horstoeko\orderx\entities\extended\ram;

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
     * @var \horstoeko\orderx\entities\extended\udt\IndicatorType $partialDeliveryAllowedIndicator
     */
    private $partialDeliveryAllowedIndicator = null;

    /**
     * Requested Quantity
     *
     * @var \horstoeko\orderx\entities\extended\udt\QuantityType $requestedQuantity
     */
    private $requestedQuantity = null;

    /**
     * Agreed Quantity
     *
     * @var \horstoeko\orderx\entities\extended\udt\QuantityType $agreedQuantity
     */
    private $agreedQuantity = null;

    /**
     * Package Quantity
     *
     * @var \horstoeko\orderx\entities\extended\udt\QuantityType $packageQuantity
     */
    private $packageQuantity = null;

    /**
     * Per Package Unit Quantity
     *
     * @var \horstoeko\orderx\entities\extended\udt\QuantityType $perPackageUnitQuantity
     */
    private $perPackageUnitQuantity = null;

    /**
     * Ship To Party
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradePartyType $shipToTradeParty
     */
    private $shipToTradeParty = null;

    /**
     * Ultimate Ship To Party
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradePartyType $ultimateShipToTradeParty
     */
    private $ultimateShipToTradeParty = null;

    /**
     * Ship From Party
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradePartyType $shipFromTradeParty
     */
    private $shipFromTradeParty = null;

    /**
     * Requested Despatch Event
     *
     * @var \horstoeko\orderx\entities\extended\ram\SupplyChainEventType[] $requestedDespatchSupplyChainEvent
     */
    private $requestedDespatchSupplyChainEvent = [
        
    ];

    /**
     * Requested Delivery Event
     *
     * @var \horstoeko\orderx\entities\extended\ram\SupplyChainEventType[] $requestedDeliverySupplyChainEvent
     */
    private $requestedDeliverySupplyChainEvent = [
        
    ];

    /**
     * Gets as partialDeliveryAllowedIndicator
     *
     * Partial Delivery Allowed Indicator
     *
     * @return \horstoeko\orderx\entities\extended\udt\IndicatorType
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
     * @param \horstoeko\orderx\entities\extended\udt\IndicatorType $partialDeliveryAllowedIndicator
     * @return self
     */
    public function setPartialDeliveryAllowedIndicator(?\horstoeko\orderx\entities\extended\udt\IndicatorType $partialDeliveryAllowedIndicator = null)
    {
        $this->partialDeliveryAllowedIndicator = $partialDeliveryAllowedIndicator;
        return $this;
    }

    /**
     * Gets as requestedQuantity
     *
     * Requested Quantity
     *
     * @return \horstoeko\orderx\entities\extended\udt\QuantityType
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
     * @param \horstoeko\orderx\entities\extended\udt\QuantityType $requestedQuantity
     * @return self
     */
    public function setRequestedQuantity(\horstoeko\orderx\entities\extended\udt\QuantityType $requestedQuantity)
    {
        $this->requestedQuantity = $requestedQuantity;
        return $this;
    }

    /**
     * Gets as agreedQuantity
     *
     * Agreed Quantity
     *
     * @return \horstoeko\orderx\entities\extended\udt\QuantityType
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
     * @param \horstoeko\orderx\entities\extended\udt\QuantityType $agreedQuantity
     * @return self
     */
    public function setAgreedQuantity(?\horstoeko\orderx\entities\extended\udt\QuantityType $agreedQuantity = null)
    {
        $this->agreedQuantity = $agreedQuantity;
        return $this;
    }

    /**
     * Gets as packageQuantity
     *
     * Package Quantity
     *
     * @return \horstoeko\orderx\entities\extended\udt\QuantityType
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
     * @param \horstoeko\orderx\entities\extended\udt\QuantityType $packageQuantity
     * @return self
     */
    public function setPackageQuantity(?\horstoeko\orderx\entities\extended\udt\QuantityType $packageQuantity = null)
    {
        $this->packageQuantity = $packageQuantity;
        return $this;
    }

    /**
     * Gets as perPackageUnitQuantity
     *
     * Per Package Unit Quantity
     *
     * @return \horstoeko\orderx\entities\extended\udt\QuantityType
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
     * @param \horstoeko\orderx\entities\extended\udt\QuantityType $perPackageUnitQuantity
     * @return self
     */
    public function setPerPackageUnitQuantity(?\horstoeko\orderx\entities\extended\udt\QuantityType $perPackageUnitQuantity = null)
    {
        $this->perPackageUnitQuantity = $perPackageUnitQuantity;
        return $this;
    }

    /**
     * Gets as shipToTradeParty
     *
     * Ship To Party
     *
     * @return \horstoeko\orderx\entities\extended\ram\TradePartyType
     */
    public function getShipToTradeParty()
    {
        return $this->shipToTradeParty;
    }

    /**
     * Sets a new shipToTradeParty
     *
     * Ship To Party
     *
     * @param \horstoeko\orderx\entities\extended\ram\TradePartyType $shipToTradeParty
     * @return self
     */
    public function setShipToTradeParty(?\horstoeko\orderx\entities\extended\ram\TradePartyType $shipToTradeParty = null)
    {
        $this->shipToTradeParty = $shipToTradeParty;
        return $this;
    }

    /**
     * Gets as ultimateShipToTradeParty
     *
     * Ultimate Ship To Party
     *
     * @return \horstoeko\orderx\entities\extended\ram\TradePartyType
     */
    public function getUltimateShipToTradeParty()
    {
        return $this->ultimateShipToTradeParty;
    }

    /**
     * Sets a new ultimateShipToTradeParty
     *
     * Ultimate Ship To Party
     *
     * @param \horstoeko\orderx\entities\extended\ram\TradePartyType $ultimateShipToTradeParty
     * @return self
     */
    public function setUltimateShipToTradeParty(?\horstoeko\orderx\entities\extended\ram\TradePartyType $ultimateShipToTradeParty = null)
    {
        $this->ultimateShipToTradeParty = $ultimateShipToTradeParty;
        return $this;
    }

    /**
     * Gets as shipFromTradeParty
     *
     * Ship From Party
     *
     * @return \horstoeko\orderx\entities\extended\ram\TradePartyType
     */
    public function getShipFromTradeParty()
    {
        return $this->shipFromTradeParty;
    }

    /**
     * Sets a new shipFromTradeParty
     *
     * Ship From Party
     *
     * @param \horstoeko\orderx\entities\extended\ram\TradePartyType $shipFromTradeParty
     * @return self
     */
    public function setShipFromTradeParty(?\horstoeko\orderx\entities\extended\ram\TradePartyType $shipFromTradeParty = null)
    {
        $this->shipFromTradeParty = $shipFromTradeParty;
        return $this;
    }

    /**
     * Adds as requestedDespatchSupplyChainEvent
     *
     * Requested Despatch Event
     *
     * @return self
     * @param \horstoeko\orderx\entities\extended\ram\SupplyChainEventType $requestedDespatchSupplyChainEvent
     */
    public function addToRequestedDespatchSupplyChainEvent(\horstoeko\orderx\entities\extended\ram\SupplyChainEventType $requestedDespatchSupplyChainEvent)
    {
        $this->requestedDespatchSupplyChainEvent[] = $requestedDespatchSupplyChainEvent;
        return $this;
    }

    /**
     * isset requestedDespatchSupplyChainEvent
     *
     * Requested Despatch Event
     *
     * @param int|string $index
     * @return bool
     */
    public function issetRequestedDespatchSupplyChainEvent($index)
    {
        return isset($this->requestedDespatchSupplyChainEvent[$index]);
    }

    /**
     * unset requestedDespatchSupplyChainEvent
     *
     * Requested Despatch Event
     *
     * @param int|string $index
     * @return void
     */
    public function unsetRequestedDespatchSupplyChainEvent($index)
    {
        unset($this->requestedDespatchSupplyChainEvent[$index]);
    }

    /**
     * Gets as requestedDespatchSupplyChainEvent
     *
     * Requested Despatch Event
     *
     * @return \horstoeko\orderx\entities\extended\ram\SupplyChainEventType[]
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
     * @param \horstoeko\orderx\entities\extended\ram\SupplyChainEventType[] $requestedDespatchSupplyChainEvent
     * @return self
     */
    public function setRequestedDespatchSupplyChainEvent(array $requestedDespatchSupplyChainEvent = null)
    {
        $this->requestedDespatchSupplyChainEvent = $requestedDespatchSupplyChainEvent;
        return $this;
    }

    /**
     * Adds as requestedDeliverySupplyChainEvent
     *
     * Requested Delivery Event
     *
     * @return self
     * @param \horstoeko\orderx\entities\extended\ram\SupplyChainEventType $requestedDeliverySupplyChainEvent
     */
    public function addToRequestedDeliverySupplyChainEvent(\horstoeko\orderx\entities\extended\ram\SupplyChainEventType $requestedDeliverySupplyChainEvent)
    {
        $this->requestedDeliverySupplyChainEvent[] = $requestedDeliverySupplyChainEvent;
        return $this;
    }

    /**
     * isset requestedDeliverySupplyChainEvent
     *
     * Requested Delivery Event
     *
     * @param int|string $index
     * @return bool
     */
    public function issetRequestedDeliverySupplyChainEvent($index)
    {
        return isset($this->requestedDeliverySupplyChainEvent[$index]);
    }

    /**
     * unset requestedDeliverySupplyChainEvent
     *
     * Requested Delivery Event
     *
     * @param int|string $index
     * @return void
     */
    public function unsetRequestedDeliverySupplyChainEvent($index)
    {
        unset($this->requestedDeliverySupplyChainEvent[$index]);
    }

    /**
     * Gets as requestedDeliverySupplyChainEvent
     *
     * Requested Delivery Event
     *
     * @return \horstoeko\orderx\entities\extended\ram\SupplyChainEventType[]
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
     * @param \horstoeko\orderx\entities\extended\ram\SupplyChainEventType[] $requestedDeliverySupplyChainEvent
     * @return self
     */
    public function setRequestedDeliverySupplyChainEvent(array $requestedDeliverySupplyChainEvent = null)
    {
        $this->requestedDeliverySupplyChainEvent = $requestedDeliverySupplyChainEvent;
        return $this;
    }


}

