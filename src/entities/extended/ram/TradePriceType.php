<?php

namespace horstoeko\orderx\entities\extended\ram;

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
     * @var \horstoeko\orderx\entities\extended\udt\AmountType $chargeAmount
     */
    private $chargeAmount = null;

    /**
     * Basis Quantity
     *
     * @var \horstoeko\orderx\entities\extended\udt\QuantityType $basisQuantity
     */
    private $basisQuantity = null;

    /**
     * Minimum Quantity
     *
     * @var \horstoeko\orderx\entities\extended\udt\QuantityType $minimumQuantity
     */
    private $minimumQuantity = null;

    /**
     * Maximum Quantity
     *
     * @var \horstoeko\orderx\entities\extended\udt\QuantityType $maximumQuantity
     */
    private $maximumQuantity = null;

    /**
     * Applied Allowance/Charge
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradeAllowanceChargeType[] $appliedTradeAllowanceCharge
     */
    private $appliedTradeAllowanceCharge = [
        
    ];

    /**
     * Included Tax
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradeTaxType[] $includedTradeTax
     */
    private $includedTradeTax = [
        
    ];

    /**
     * Gets as chargeAmount
     *
     * Charge Amount
     *
     * @return \horstoeko\orderx\entities\extended\udt\AmountType
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
     * @param  \horstoeko\orderx\entities\extended\udt\AmountType $chargeAmount
     * @return self
     */
    public function setChargeAmount(\horstoeko\orderx\entities\extended\udt\AmountType $chargeAmount)
    {
        $this->chargeAmount = $chargeAmount;
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
     * @param  \horstoeko\orderx\entities\extended\udt\QuantityType $basisQuantity
     * @return self
     */
    public function setBasisQuantity(?\horstoeko\orderx\entities\extended\udt\QuantityType $basisQuantity = null)
    {
        $this->basisQuantity = $basisQuantity;
        return $this;
    }

    /**
     * Gets as minimumQuantity
     *
     * Minimum Quantity
     *
     * @return \horstoeko\orderx\entities\extended\udt\QuantityType
     */
    public function getMinimumQuantity()
    {
        return $this->minimumQuantity;
    }

    /**
     * Sets a new minimumQuantity
     *
     * Minimum Quantity
     *
     * @param  \horstoeko\orderx\entities\extended\udt\QuantityType $minimumQuantity
     * @return self
     */
    public function setMinimumQuantity(?\horstoeko\orderx\entities\extended\udt\QuantityType $minimumQuantity = null)
    {
        $this->minimumQuantity = $minimumQuantity;
        return $this;
    }

    /**
     * Gets as maximumQuantity
     *
     * Maximum Quantity
     *
     * @return \horstoeko\orderx\entities\extended\udt\QuantityType
     */
    public function getMaximumQuantity()
    {
        return $this->maximumQuantity;
    }

    /**
     * Sets a new maximumQuantity
     *
     * Maximum Quantity
     *
     * @param  \horstoeko\orderx\entities\extended\udt\QuantityType $maximumQuantity
     * @return self
     */
    public function setMaximumQuantity(?\horstoeko\orderx\entities\extended\udt\QuantityType $maximumQuantity = null)
    {
        $this->maximumQuantity = $maximumQuantity;
        return $this;
    }

    /**
     * Adds as appliedTradeAllowanceCharge
     *
     * Applied Allowance/Charge
     *
     * @return self
     * @param  \horstoeko\orderx\entities\extended\ram\TradeAllowanceChargeType $appliedTradeAllowanceCharge
     */
    public function addToAppliedTradeAllowanceCharge(\horstoeko\orderx\entities\extended\ram\TradeAllowanceChargeType $appliedTradeAllowanceCharge)
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
     * @return \horstoeko\orderx\entities\extended\ram\TradeAllowanceChargeType[]
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
     * @param  \horstoeko\orderx\entities\extended\ram\TradeAllowanceChargeType[] $appliedTradeAllowanceCharge
     * @return self
     */
    public function setAppliedTradeAllowanceCharge(array $appliedTradeAllowanceCharge = null)
    {
        $this->appliedTradeAllowanceCharge = $appliedTradeAllowanceCharge;
        return $this;
    }

    /**
     * Adds as includedTradeTax
     *
     * Included Tax
     *
     * @return self
     * @param  \horstoeko\orderx\entities\extended\ram\TradeTaxType $includedTradeTax
     */
    public function addToIncludedTradeTax(\horstoeko\orderx\entities\extended\ram\TradeTaxType $includedTradeTax)
    {
        $this->includedTradeTax[] = $includedTradeTax;
        return $this;
    }

    /**
     * isset includedTradeTax
     *
     * Included Tax
     *
     * @param  int|string $index
     * @return bool
     */
    public function issetIncludedTradeTax($index)
    {
        return isset($this->includedTradeTax[$index]);
    }

    /**
     * unset includedTradeTax
     *
     * Included Tax
     *
     * @param  int|string $index
     * @return void
     */
    public function unsetIncludedTradeTax($index)
    {
        unset($this->includedTradeTax[$index]);
    }

    /**
     * Gets as includedTradeTax
     *
     * Included Tax
     *
     * @return \horstoeko\orderx\entities\extended\ram\TradeTaxType[]
     */
    public function getIncludedTradeTax()
    {
        return $this->includedTradeTax;
    }

    /**
     * Sets a new includedTradeTax
     *
     * Included Tax
     *
     * @param  \horstoeko\orderx\entities\extended\ram\TradeTaxType[] $includedTradeTax
     * @return self
     */
    public function setIncludedTradeTax(array $includedTradeTax = null)
    {
        $this->includedTradeTax = $includedTradeTax;
        return $this;
    }
}
