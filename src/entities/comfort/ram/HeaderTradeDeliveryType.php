<?php

namespace horstoeko\orderx\entities\comfort\ram;

/**
 * Class representing HeaderTradeDeliveryType
 *
 * Header Trade Delivery
 * XSD Type: HeaderTradeDeliveryType
 */
class HeaderTradeDeliveryType
{

    /**
     * Ship To Party
     *
     * @var \horstoeko\orderx\entities\comfort\ram\TradePartyType $shipToTradeParty
     */
    private $shipToTradeParty = null;

    /**
     * Ship From Party
     *
     * @var \horstoeko\orderx\entities\comfort\ram\TradePartyType $shipFromTradeParty
     */
    private $shipFromTradeParty = null;

    /**
     * Requested Delivery Event
     *
     * @var \horstoeko\orderx\entities\comfort\ram\SupplyChainEventType[] $requestedDeliverySupplyChainEvent
     */
    private $requestedDeliverySupplyChainEvent = [
        
    ];

    /**
     * Requested Despatch Event
     *
     * @var \horstoeko\orderx\entities\comfort\ram\SupplyChainEventType[] $requestedDespatchSupplyChainEvent
     */
    private $requestedDespatchSupplyChainEvent = [
        
    ];

    /**
     * Gets as shipToTradeParty
     *
     * Ship To Party
     *
     * @return \horstoeko\orderx\entities\comfort\ram\TradePartyType
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
     * @param \horstoeko\orderx\entities\comfort\ram\TradePartyType $shipToTradeParty
     * @return self
     */
    public function setShipToTradeParty(?\horstoeko\orderx\entities\comfort\ram\TradePartyType $shipToTradeParty = null)
    {
        $this->shipToTradeParty = $shipToTradeParty;
        return $this;
    }

    /**
     * Gets as shipFromTradeParty
     *
     * Ship From Party
     *
     * @return \horstoeko\orderx\entities\comfort\ram\TradePartyType
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
     * @param \horstoeko\orderx\entities\comfort\ram\TradePartyType $shipFromTradeParty
     * @return self
     */
    public function setShipFromTradeParty(?\horstoeko\orderx\entities\comfort\ram\TradePartyType $shipFromTradeParty = null)
    {
        $this->shipFromTradeParty = $shipFromTradeParty;
        return $this;
    }

    /**
     * Adds as requestedDeliverySupplyChainEvent
     *
     * Requested Delivery Event
     *
     * @return self
     * @param \horstoeko\orderx\entities\comfort\ram\SupplyChainEventType $requestedDeliverySupplyChainEvent
     */
    public function addToRequestedDeliverySupplyChainEvent(\horstoeko\orderx\entities\comfort\ram\SupplyChainEventType $requestedDeliverySupplyChainEvent)
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
     * @return \horstoeko\orderx\entities\comfort\ram\SupplyChainEventType[]
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
     * @param \horstoeko\orderx\entities\comfort\ram\SupplyChainEventType[] $requestedDeliverySupplyChainEvent
     * @return self
     */
    public function setRequestedDeliverySupplyChainEvent(array $requestedDeliverySupplyChainEvent = null)
    {
        $this->requestedDeliverySupplyChainEvent = $requestedDeliverySupplyChainEvent;
        return $this;
    }

    /**
     * Adds as requestedDespatchSupplyChainEvent
     *
     * Requested Despatch Event
     *
     * @return self
     * @param \horstoeko\orderx\entities\comfort\ram\SupplyChainEventType $requestedDespatchSupplyChainEvent
     */
    public function addToRequestedDespatchSupplyChainEvent(\horstoeko\orderx\entities\comfort\ram\SupplyChainEventType $requestedDespatchSupplyChainEvent)
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
     * @return \horstoeko\orderx\entities\comfort\ram\SupplyChainEventType[]
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
     * @param \horstoeko\orderx\entities\comfort\ram\SupplyChainEventType[] $requestedDespatchSupplyChainEvent
     * @return self
     */
    public function setRequestedDespatchSupplyChainEvent(array $requestedDespatchSupplyChainEvent = null)
    {
        $this->requestedDespatchSupplyChainEvent = $requestedDespatchSupplyChainEvent;
        return $this;
    }


}

