<?php

namespace horstoeko\orderx\entities\basic\ram;

/**
 * Class representing TradePriceType
 *
 * Trade Price
 * XSD Type: TradePriceType
 */
class TradePriceType
{

    /**
     * Charge Amount
     *
     * @var \horstoeko\orderx\entities\basic\udt\AmountType $chargeAmount
     */
    private $chargeAmount = null;

    /**
     * Basis Quantity
     *
     * @var \horstoeko\orderx\entities\basic\udt\QuantityType $basisQuantity
     */
    private $basisQuantity = null;

    /**
     * Gets as chargeAmount
     *
     * Charge Amount
     *
     * @return \horstoeko\orderx\entities\basic\udt\AmountType
     */
    public function getChargeAmount()
    {
        return $this->chargeAmount;
    }

    /**
     * Sets a new chargeAmount
     *
     * Charge Amount
     *
     * @param  \horstoeko\orderx\entities\basic\udt\AmountType $chargeAmount
     * @return self
     */
    public function setChargeAmount(\horstoeko\orderx\entities\basic\udt\AmountType $chargeAmount)
    {
        $this->chargeAmount = $chargeAmount;
        return $this;
    }

    /**
     * Gets as basisQuantity
     *
     * Basis Quantity
     *
     * @return \horstoeko\orderx\entities\basic\udt\QuantityType
     */
    public function getBasisQuantity()
    {
        return $this->basisQuantity;
    }

    /**
     * Sets a new basisQuantity
     *
     * Basis Quantity
     *
     * @param  \horstoeko\orderx\entities\basic\udt\QuantityType $basisQuantity
     * @return self
     */
    public function setBasisQuantity(?\horstoeko\orderx\entities\basic\udt\QuantityType $basisQuantity = null)
    {
        $this->basisQuantity = $basisQuantity;
        return $this;
    }
}
