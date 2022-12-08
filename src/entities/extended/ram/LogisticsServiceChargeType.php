<?php

namespace horstoeko\orderx\entities\extended\ram;

/**
 * Class representing LogisticsServiceChargeType
 *
 * Logistics Service Charge
 * XSD Type: LogisticsServiceChargeType
 */
class LogisticsServiceChargeType
{

    /**
     * Description
     *
     * @var string $description
     */
    private $description = null;

    /**
     * Applied Amount
     *
     * @var \horstoeko\orderx\entities\extended\udt\AmountType $appliedAmount
     */
    private $appliedAmount = null;

    /**
     * Trade Tax
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradeTaxType[] $appliedTradeTax
     */
    private $appliedTradeTax = [
        
    ];

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
     * Gets as appliedAmount
     *
     * Applied Amount
     *
     * @return \horstoeko\orderx\entities\extended\udt\AmountType
     */
    public function getAppliedAmount()
    {
        return $this->appliedAmount;
    }

    /**
     * Sets a new appliedAmount
     *
     * Applied Amount
     *
     * @param \horstoeko\orderx\entities\extended\udt\AmountType $appliedAmount
     * @return self
     */
    public function setAppliedAmount(\horstoeko\orderx\entities\extended\udt\AmountType $appliedAmount)
    {
        $this->appliedAmount = $appliedAmount;
        return $this;
    }

    /**
     * Adds as appliedTradeTax
     *
     * Trade Tax
     *
     * @return self
     * @param \horstoeko\orderx\entities\extended\ram\TradeTaxType $appliedTradeTax
     */
    public function addToAppliedTradeTax(\horstoeko\orderx\entities\extended\ram\TradeTaxType $appliedTradeTax)
    {
        $this->appliedTradeTax[] = $appliedTradeTax;
        return $this;
    }

    /**
     * isset appliedTradeTax
     *
     * Trade Tax
     *
     * @param int|string $index
     * @return bool
     */
    public function issetAppliedTradeTax($index)
    {
        return isset($this->appliedTradeTax[$index]);
    }

    /**
     * unset appliedTradeTax
     *
     * Trade Tax
     *
     * @param int|string $index
     * @return void
     */
    public function unsetAppliedTradeTax($index)
    {
        unset($this->appliedTradeTax[$index]);
    }

    /**
     * Gets as appliedTradeTax
     *
     * Trade Tax
     *
     * @return \horstoeko\orderx\entities\extended\ram\TradeTaxType[]
     */
    public function getAppliedTradeTax()
    {
        return $this->appliedTradeTax;
    }

    /**
     * Sets a new appliedTradeTax
     *
     * Trade Tax
     *
     * @param \horstoeko\orderx\entities\extended\ram\TradeTaxType[] $appliedTradeTax
     * @return self
     */
    public function setAppliedTradeTax(array $appliedTradeTax = null)
    {
        $this->appliedTradeTax = $appliedTradeTax;
        return $this;
    }


}

