<?php

namespace horstoeko\orderx\entities\comfort\ram;

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
     * @var \horstoeko\orderx\entities\comfort\udt\AmountType $chargeAmount
     */
    private $chargeAmount = null;

    /**
     * Basis Quantity
     *
     * @var \horstoeko\orderx\entities\comfort\udt\QuantityType $basisQuantity
     */
    private $basisQuantity = null;

    /**
     * Applied Allowance/Charge
     *
     * @var \horstoeko\orderx\entities\comfort\ram\TradeAllowanceChargeType[] $appliedTradeAllowanceCharge
     */
    private $appliedTradeAllowanceCharge = [
        
    ];

    /**
     * Gets as chargeAmount
     *
     * Charge Amount
     *
     * @return \horstoeko\orderx\entities\comfort\udt\AmountType
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
     * @param  \horstoeko\orderx\entities\comfort\udt\AmountType $chargeAmount
     * @return self
     */
    public function setChargeAmount(\horstoeko\orderx\entities\comfort\udt\AmountType $chargeAmount)
    {
        $this->chargeAmount = $chargeAmount;
        return $this;
    }

    /**
     * Gets as basisQuantity
     *
     * Basis Quantity
     *
     * @return \horstoeko\orderx\entities\comfort\udt\QuantityType
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
     * @param  \horstoeko\orderx\entities\comfort\udt\QuantityType $basisQuantity
     * @return self
     */
    public function setBasisQuantity(?\horstoeko\orderx\entities\comfort\udt\QuantityType $basisQuantity = null)
    {
        $this->basisQuantity = $basisQuantity;
        return $this;
    }

    /**
     * Adds as appliedTradeAllowanceCharge
     *
     * Applied Allowance/Charge
     *
     * @return self
     * @param  \horstoeko\orderx\entities\comfort\ram\TradeAllowanceChargeType $appliedTradeAllowanceCharge
     */
    public function addToAppliedTradeAllowanceCharge(\horstoeko\orderx\entities\comfort\ram\TradeAllowanceChargeType $appliedTradeAllowanceCharge)
    {
        $this->appliedTradeAllowanceCharge[] = $appliedTradeAllowanceCharge;
        return $this;
    }

    /**
     * isset appliedTradeAllowanceCharge
     *
     * Applied Allowance/Charge
     *
     * @param  int|string $index
     * @return bool
     */
    public function issetAppliedTradeAllowanceCharge($index)
    {
        return isset($this->appliedTradeAllowanceCharge[$index]);
    }

    /**
     * unset appliedTradeAllowanceCharge
     *
     * Applied Allowance/Charge
     *
     * @param  int|string $index
     * @return void
     */
    public function unsetAppliedTradeAllowanceCharge($index)
    {
        unset($this->appliedTradeAllowanceCharge[$index]);
    }

    /**
     * Gets as appliedTradeAllowanceCharge
     *
     * Applied Allowance/Charge
     *
     * @return \horstoeko\orderx\entities\comfort\ram\TradeAllowanceChargeType[]
     */
    public function getAppliedTradeAllowanceCharge()
    {
        return $this->appliedTradeAllowanceCharge;
    }

    /**
     * Sets a new appliedTradeAllowanceCharge
     *
     * Applied Allowance/Charge
     *
     * @param  \horstoeko\orderx\entities\comfort\ram\TradeAllowanceChargeType[] $appliedTradeAllowanceCharge
     * @return self
     */
    public function setAppliedTradeAllowanceCharge(array $appliedTradeAllowanceCharge = null)
    {
        $this->appliedTradeAllowanceCharge = $appliedTradeAllowanceCharge;
        return $this;
    }
}
