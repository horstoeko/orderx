<?php

namespace horstoeko\orderx\entities\extended\ram;

/**
 * Class representing TradeAllowanceChargeType
 *
 * Trade Allowance/Charge
 * XSD Type: TradeAllowanceChargeType
 */
class TradeAllowanceChargeType
{

    /**
     * Charge Indicator
     *
     * @var \horstoeko\orderx\entities\extended\udt\IndicatorType $chargeIndicator
     */
    private $chargeIndicator = null;

    /**
     * Sequence Number
     *
     * @var float $sequenceNumeric
     */
    private $sequenceNumeric = null;

    /**
     * Calculation Percent
     *
     * @var float $calculationPercent
     */
    private $calculationPercent = null;

    /**
     * Basis Amount
     *
     * @var \horstoeko\orderx\entities\extended\udt\AmountType $basisAmount
     */
    private $basisAmount = null;

    /**
     * Basis Quantity
     *
     * @var \horstoeko\orderx\entities\extended\udt\QuantityType $basisQuantity
     */
    private $basisQuantity = null;

    /**
     * Actual Amount
     *
     * @var \horstoeko\orderx\entities\extended\udt\AmountType $actualAmount
     */
    private $actualAmount = null;

    /**
     * Reason Code
     *
     * @var string $reasonCode
     */
    private $reasonCode = null;

    /**
     * Reason Text
     *
     * @var string $reason
     */
    private $reason = null;

    /**
     * Tax Category
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradeTaxType $categoryTradeTax
     */
    private $categoryTradeTax = null;

    /**
     * Gets as chargeIndicator
     *
     * Charge Indicator
     *
     * @return \horstoeko\orderx\entities\extended\udt\IndicatorType
     */
    public function getChargeIndicator()
    {
        return $this->chargeIndicator;
    }

    /**
     * Sets a new chargeIndicator
     *
     * Charge Indicator
     *
     * @param \horstoeko\orderx\entities\extended\udt\IndicatorType $chargeIndicator
     * @return self
     */
    public function setChargeIndicator(\horstoeko\orderx\entities\extended\udt\IndicatorType $chargeIndicator)
    {
        $this->chargeIndicator = $chargeIndicator;
        return $this;
    }

    /**
     * Gets as sequenceNumeric
     *
     * Sequence Number
     *
     * @return float
     */
    public function getSequenceNumeric()
    {
        return $this->sequenceNumeric;
    }

    /**
     * Sets a new sequenceNumeric
     *
     * Sequence Number
     *
     * @param float $sequenceNumeric
     * @return self
     */
    public function setSequenceNumeric($sequenceNumeric)
    {
        $this->sequenceNumeric = $sequenceNumeric;
        return $this;
    }

    /**
     * Gets as calculationPercent
     *
     * Calculation Percent
     *
     * @return float
     */
    public function getCalculationPercent()
    {
        return $this->calculationPercent;
    }

    /**
     * Sets a new calculationPercent
     *
     * Calculation Percent
     *
     * @param float $calculationPercent
     * @return self
     */
    public function setCalculationPercent($calculationPercent)
    {
        $this->calculationPercent = $calculationPercent;
        return $this;
    }

    /**
     * Gets as basisAmount
     *
     * Basis Amount
     *
     * @return \horstoeko\orderx\entities\extended\udt\AmountType
     */
    public function getBasisAmount()
    {
        return $this->basisAmount;
    }

    /**
     * Sets a new basisAmount
     *
     * Basis Amount
     *
     * @param \horstoeko\orderx\entities\extended\udt\AmountType $basisAmount
     * @return self
     */
    public function setBasisAmount(?\horstoeko\orderx\entities\extended\udt\AmountType $basisAmount = null)
    {
        $this->basisAmount = $basisAmount;
        return $this;
    }

    /**
     * Gets as basisQuantity
     *
     * Basis Quantity
     *
     * @return \horstoeko\orderx\entities\extended\udt\QuantityType
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
     * @param \horstoeko\orderx\entities\extended\udt\QuantityType $basisQuantity
     * @return self
     */
    public function setBasisQuantity(?\horstoeko\orderx\entities\extended\udt\QuantityType $basisQuantity = null)
    {
        $this->basisQuantity = $basisQuantity;
        return $this;
    }

    /**
     * Gets as actualAmount
     *
     * Actual Amount
     *
     * @return \horstoeko\orderx\entities\extended\udt\AmountType
     */
    public function getActualAmount()
    {
        return $this->actualAmount;
    }

    /**
     * Sets a new actualAmount
     *
     * Actual Amount
     *
     * @param \horstoeko\orderx\entities\extended\udt\AmountType $actualAmount
     * @return self
     */
    public function setActualAmount(\horstoeko\orderx\entities\extended\udt\AmountType $actualAmount)
    {
        $this->actualAmount = $actualAmount;
        return $this;
    }

    /**
     * Gets as reasonCode
     *
     * Reason Code
     *
     * @return string
     */
    public function getReasonCode()
    {
        return $this->reasonCode;
    }

    /**
     * Sets a new reasonCode
     *
     * Reason Code
     *
     * @param string $reasonCode
     * @return self
     */
    public function setReasonCode($reasonCode)
    {
        $this->reasonCode = $reasonCode;
        return $this;
    }

    /**
     * Gets as reason
     *
     * Reason Text
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Sets a new reason
     *
     * Reason Text
     *
     * @param string $reason
     * @return self
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
        return $this;
    }

    /**
     * Gets as categoryTradeTax
     *
     * Tax Category
     *
     * @return \horstoeko\orderx\entities\extended\ram\TradeTaxType
     */
    public function getCategoryTradeTax()
    {
        return $this->categoryTradeTax;
    }

    /**
     * Sets a new categoryTradeTax
     *
     * Tax Category
     *
     * @param \horstoeko\orderx\entities\extended\ram\TradeTaxType $categoryTradeTax
     * @return self
     */
    public function setCategoryTradeTax(?\horstoeko\orderx\entities\extended\ram\TradeTaxType $categoryTradeTax = null)
    {
        $this->categoryTradeTax = $categoryTradeTax;
        return $this;
    }


}

