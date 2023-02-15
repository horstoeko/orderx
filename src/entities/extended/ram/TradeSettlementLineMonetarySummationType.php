<?php

namespace horstoeko\orderx\entities\extended\ram;

/**
 * Class representing TradeSettlementLineMonetarySummationType
 *
 * Trade Settlement Line Monetary Summation
 * XSD Type: TradeSettlementLineMonetarySummationType
 */
class TradeSettlementLineMonetarySummationType
{

    /**
     * Line Total Amount
     *
     * @var \horstoeko\orderx\entities\extended\udt\AmountType $lineTotalAmount
     */
    private $lineTotalAmount = null;

    /**
     * Charge Total Amount
     *
     * @var \horstoeko\orderx\entities\extended\udt\AmountType $chargeTotalAmount
     */
    private $chargeTotalAmount = null;

    /**
     * Allowance Total Amount
     *
     * @var \horstoeko\orderx\entities\extended\udt\AmountType $allowanceTotalAmount
     */
    private $allowanceTotalAmount = null;

    /**
     * Tax Total Amount
     *
     * @var \horstoeko\orderx\entities\extended\udt\AmountType $taxTotalAmount
     */
    private $taxTotalAmount = null;

    /**
     * Total Allowance/Charge Amount
     *
     * @var \horstoeko\orderx\entities\extended\udt\AmountType $totalAllowanceChargeAmount
     */
    private $totalAllowanceChargeAmount = null;

    /**
     * Gets as lineTotalAmount
     *
     * Line Total Amount
     *
     * @return \horstoeko\orderx\entities\extended\udt\AmountType
     */
    public function getLineTotalAmount()
    {
        return $this->lineTotalAmount;
    }

    /**
     * Sets a new lineTotalAmount
     *
     * Line Total Amount
     *
     * @param  \horstoeko\orderx\entities\extended\udt\AmountType $lineTotalAmount
     * @return self
     */
    public function setLineTotalAmount(\horstoeko\orderx\entities\extended\udt\AmountType $lineTotalAmount)
    {
        $this->lineTotalAmount = $lineTotalAmount;
        return $this;
    }

    /**
     * Gets as chargeTotalAmount
     *
     * Charge Total Amount
     *
     * @return \horstoeko\orderx\entities\extended\udt\AmountType
     */
    public function getChargeTotalAmount()
    {
        return $this->chargeTotalAmount;
    }

    /**
     * Sets a new chargeTotalAmount
     *
     * Charge Total Amount
     *
     * @param  \horstoeko\orderx\entities\extended\udt\AmountType $chargeTotalAmount
     * @return self
     */
    public function setChargeTotalAmount(?\horstoeko\orderx\entities\extended\udt\AmountType $chargeTotalAmount = null)
    {
        $this->chargeTotalAmount = $chargeTotalAmount;
        return $this;
    }

    /**
     * Gets as allowanceTotalAmount
     *
     * Allowance Total Amount
     *
     * @return \horstoeko\orderx\entities\extended\udt\AmountType
     */
    public function getAllowanceTotalAmount()
    {
        return $this->allowanceTotalAmount;
    }

    /**
     * Sets a new allowanceTotalAmount
     *
     * Allowance Total Amount
     *
     * @param  \horstoeko\orderx\entities\extended\udt\AmountType $allowanceTotalAmount
     * @return self
     */
    public function setAllowanceTotalAmount(?\horstoeko\orderx\entities\extended\udt\AmountType $allowanceTotalAmount = null)
    {
        $this->allowanceTotalAmount = $allowanceTotalAmount;
        return $this;
    }

    /**
     * Gets as taxTotalAmount
     *
     * Tax Total Amount
     *
     * @return \horstoeko\orderx\entities\extended\udt\AmountType
     */
    public function getTaxTotalAmount()
    {
        return $this->taxTotalAmount;
    }

    /**
     * Sets a new taxTotalAmount
     *
     * Tax Total Amount
     *
     * @param  \horstoeko\orderx\entities\extended\udt\AmountType $taxTotalAmount
     * @return self
     */
    public function setTaxTotalAmount(?\horstoeko\orderx\entities\extended\udt\AmountType $taxTotalAmount = null)
    {
        $this->taxTotalAmount = $taxTotalAmount;
        return $this;
    }

    /**
     * Gets as totalAllowanceChargeAmount
     *
     * Total Allowance/Charge Amount
     *
     * @return \horstoeko\orderx\entities\extended\udt\AmountType
     */
    public function getTotalAllowanceChargeAmount()
    {
        return $this->totalAllowanceChargeAmount;
    }

    /**
     * Sets a new totalAllowanceChargeAmount
     *
     * Total Allowance/Charge Amount
     *
     * @param  \horstoeko\orderx\entities\extended\udt\AmountType $totalAllowanceChargeAmount
     * @return self
     */
    public function setTotalAllowanceChargeAmount(?\horstoeko\orderx\entities\extended\udt\AmountType $totalAllowanceChargeAmount = null)
    {
        $this->totalAllowanceChargeAmount = $totalAllowanceChargeAmount;
        return $this;
    }
}
