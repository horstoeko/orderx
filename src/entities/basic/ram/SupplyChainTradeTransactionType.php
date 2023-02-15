<?php

namespace horstoeko\orderx\entities\basic\ram;

/**
 * Class representing SupplyChainTradeTransactionType
 *
 * Supply Chain Trade Transaction
 * XSD Type: SupplyChainTradeTransactionType
 */
class SupplyChainTradeTransactionType
{

    /**
     * Included Trade Line Item
     *
     * @var \horstoeko\orderx\entities\basic\ram\SupplyChainTradeLineItemType[] $includedSupplyChainTradeLineItem
     */
    private $includedSupplyChainTradeLineItem = [
        
    ];

    /**
     * Header Trade Agreement
     *
     * @var \horstoeko\orderx\entities\basic\ram\HeaderTradeAgreementType $applicableHeaderTradeAgreement
     */
    private $applicableHeaderTradeAgreement = null;

    /**
     * Header Trade Delivery
     *
     * @var \horstoeko\orderx\entities\basic\ram\HeaderTradeDeliveryType $applicableHeaderTradeDelivery
     */
    private $applicableHeaderTradeDelivery = null;

    /**
     * Header Trade Settlement
     *
     * @var \horstoeko\orderx\entities\basic\ram\HeaderTradeSettlementType $applicableHeaderTradeSettlement
     */
    private $applicableHeaderTradeSettlement = null;

    /**
     * Adds as includedSupplyChainTradeLineItem
     *
     * Included Trade Line Item
     *
     * @return self
     * @param  \horstoeko\orderx\entities\basic\ram\SupplyChainTradeLineItemType $includedSupplyChainTradeLineItem
     */
    public function addToIncludedSupplyChainTradeLineItem(\horstoeko\orderx\entities\basic\ram\SupplyChainTradeLineItemType $includedSupplyChainTradeLineItem)
    {
        $this->includedSupplyChainTradeLineItem[] = $includedSupplyChainTradeLineItem;
        return $this;
    }

    /**
     * isset includedSupplyChainTradeLineItem
     *
     * Included Trade Line Item
     *
     * @param  int|string $index
     * @return bool
     */
    public function issetIncludedSupplyChainTradeLineItem($index)
    {
        return isset($this->includedSupplyChainTradeLineItem[$index]);
    }

    /**
     * unset includedSupplyChainTradeLineItem
     *
     * Included Trade Line Item
     *
     * @param  int|string $index
     * @return void
     */
    public function unsetIncludedSupplyChainTradeLineItem($index)
    {
        unset($this->includedSupplyChainTradeLineItem[$index]);
    }

    /**
     * Gets as includedSupplyChainTradeLineItem
     *
     * Included Trade Line Item
     *
     * @return \horstoeko\orderx\entities\basic\ram\SupplyChainTradeLineItemType[]
     */
    public function getIncludedSupplyChainTradeLineItem()
    {
        return $this->includedSupplyChainTradeLineItem;
    }

    /**
     * Sets a new includedSupplyChainTradeLineItem
     *
     * Included Trade Line Item
     *
     * @param  \horstoeko\orderx\entities\basic\ram\SupplyChainTradeLineItemType[] $includedSupplyChainTradeLineItem
     * @return self
     */
    public function setIncludedSupplyChainTradeLineItem(array $includedSupplyChainTradeLineItem = null)
    {
        $this->includedSupplyChainTradeLineItem = $includedSupplyChainTradeLineItem;
        return $this;
    }

    /**
     * Gets as applicableHeaderTradeAgreement
     *
     * Header Trade Agreement
     *
     * @return \horstoeko\orderx\entities\basic\ram\HeaderTradeAgreementType
     */
    public function getApplicableHeaderTradeAgreement()
    {
        return $this->applicableHeaderTradeAgreement;
    }

    /**
     * Sets a new applicableHeaderTradeAgreement
     *
     * Header Trade Agreement
     *
     * @param  \horstoeko\orderx\entities\basic\ram\HeaderTradeAgreementType $applicableHeaderTradeAgreement
     * @return self
     */
    public function setApplicableHeaderTradeAgreement(\horstoeko\orderx\entities\basic\ram\HeaderTradeAgreementType $applicableHeaderTradeAgreement)
    {
        $this->applicableHeaderTradeAgreement = $applicableHeaderTradeAgreement;
        return $this;
    }

    /**
     * Gets as applicableHeaderTradeDelivery
     *
     * Header Trade Delivery
     *
     * @return \horstoeko\orderx\entities\basic\ram\HeaderTradeDeliveryType
     */
    public function getApplicableHeaderTradeDelivery()
    {
        return $this->applicableHeaderTradeDelivery;
    }

    /**
     * Sets a new applicableHeaderTradeDelivery
     *
     * Header Trade Delivery
     *
     * @param  \horstoeko\orderx\entities\basic\ram\HeaderTradeDeliveryType $applicableHeaderTradeDelivery
     * @return self
     */
    public function setApplicableHeaderTradeDelivery(\horstoeko\orderx\entities\basic\ram\HeaderTradeDeliveryType $applicableHeaderTradeDelivery)
    {
        $this->applicableHeaderTradeDelivery = $applicableHeaderTradeDelivery;
        return $this;
    }

    /**
     * Gets as applicableHeaderTradeSettlement
     *
     * Header Trade Settlement
     *
     * @return \horstoeko\orderx\entities\basic\ram\HeaderTradeSettlementType
     */
    public function getApplicableHeaderTradeSettlement()
    {
        return $this->applicableHeaderTradeSettlement;
    }

    /**
     * Sets a new applicableHeaderTradeSettlement
     *
     * Header Trade Settlement
     *
     * @param  \horstoeko\orderx\entities\basic\ram\HeaderTradeSettlementType $applicableHeaderTradeSettlement
     * @return self
     */
    public function setApplicableHeaderTradeSettlement(\horstoeko\orderx\entities\basic\ram\HeaderTradeSettlementType $applicableHeaderTradeSettlement)
    {
        $this->applicableHeaderTradeSettlement = $applicableHeaderTradeSettlement;
        return $this;
    }
}
