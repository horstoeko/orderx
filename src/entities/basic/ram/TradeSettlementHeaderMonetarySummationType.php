<?php

namespace horstoeko\orderx\entities\basic\ram;

/**
 * Class representing TradeSettlementHeaderMonetarySummationType
 *
 * Trade Settlement Header Monetary Summation
 * XSD Type: TradeSettlementHeaderMonetarySummationType
 */
class TradeSettlementHeaderMonetarySummationType
{

    /**
     * Line Total Amount
     *
     * @var \horstoeko\orderx\entities\basic\udt\AmountType $lineTotalAmount
     */
    private $lineTotalAmount = null;

    /**
     * Charge Total Amount
     *
     * @var \horstoeko\orderx\entities\basic\udt\AmountType $chargeTotalAmount
     */
    private $chargeTotalAmount = null;

    /**
     * Allowance Total Amount
     *
     * @var \horstoeko\orderx\entities\basic\udt\AmountType $allowanceTotalAmount
     */
    private $allowanceTotalAmount = null;

    /**
     * Tax Basis Total Amount
     *
     * @var \horstoeko\orderx\entities\basic\udt\AmountType $taxBasisTotalAmount
     */
    private $taxBasisTotalAmount = null;

    /**
     * Tax Total Amount
     *
     * @var \horstoeko\orderx\entities\basic\udt\AmountType $taxTotalAmount
     */
    private $taxTotalAmount = null;

    /**
     * Grand Total Amount
     *
     * @var \horstoeko\orderx\entities\basic\udt\AmountType $grandTotalAmount
     */
    private $grandTotalAmount = null;

    /**
     * Gets as lineTotalAmount
     *
     * Line Total Amount
     *
     * @return \horstoeko\orderx\entities\basic\udt\AmountType
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
     * @param  \horstoeko\orderx\entities\basic\udt\AmountType $lineTotalAmount
     * @return self
     */
    public function setLineTotalAmount(\horstoeko\orderx\entities\basic\udt\AmountType $lineTotalAmount)
    {
        $this->lineTotalAmount = $lineTotalAmount;
        return $this;
    }

    /**
     * Gets as chargeTotalAmount
     *
     * Charge Total Amount
     *
     * @return \horstoeko\orderx\entities\basic\udt\AmountType
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
     * @param  \horstoeko\orderx\entities\basic\udt\AmountType $chargeTotalAmount
     * @return self
     */
    public function setChargeTotalAmount(?\horstoeko\orderx\entities\basic\udt\AmountType $chargeTotalAmount = null)
    {
        $this->chargeTotalAmount = $chargeTotalAmount;
        return $this;
    }

    /**
     * Gets as allowanceTotalAmount
     *
     * Allowance Total Amount
     *
     * @return \horstoeko\orderx\entities\basic\udt\AmountType
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
     * @param  \horstoeko\orderx\entities\basic\udt\AmountType $allowanceTotalAmount
     * @return self
     */
    public function setAllowanceTotalAmount(?\horstoeko\orderx\entities\basic\udt\AmountType $allowanceTotalAmount = null)
    {
        $this->allowanceTotalAmount = $allowanceTotalAmount;
        return $this;
    }

    /**
     * Gets as taxBasisTotalAmount
     *
     * Tax Basis Total Amount
     *
     * @return \horstoeko\orderx\entities\basic\udt\AmountType
     */
    public function getTaxBasisTotalAmount()
    {
        return $this->taxBasisTotalAmount;
    }

    /**
     * Sets a new taxBasisTotalAmount
     *
     * Tax Basis Total Amount
     *
     * @param  \horstoeko\orderx\entities\basic\udt\AmountType $taxBasisTotalAmount
     * @return self
     */
    public function setTaxBasisTotalAmount(\horstoeko\orderx\entities\basic\udt\AmountType $taxBasisTotalAmount)
    {
        $this->taxBasisTotalAmount = $taxBasisTotalAmount;
        return $this;
    }

    /**
     * Gets as taxTotalAmount
     *
     * Tax Total Amount
     *
     * @return \horstoeko\orderx\entities\basic\udt\AmountType
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
     * @param  \horstoeko\orderx\entities\basic\udt\AmountType $taxTotalAmount
     * @return self
     */
    public function setTaxTotalAmount(?\horstoeko\orderx\entities\basic\udt\AmountType $taxTotalAmount = null)
    {
        $this->taxTotalAmount = $taxTotalAmount;
        return $this;
    }

    /**
     * Gets as grandTotalAmount
     *
     * Grand Total Amount
     *
     * @return \horstoeko\orderx\entities\basic\udt\AmountType
     */
    public function getGrandTotalAmount()
    {
        return $this->grandTotalAmount;
    }

    /**
     * Sets a new grandTotalAmount
     *
     * Grand Total Amount
     *
     * @param  \horstoeko\orderx\entities\basic\udt\AmountType $grandTotalAmount
     * @return self
     */
    public function setGrandTotalAmount(?\horstoeko\orderx\entities\basic\udt\AmountType $grandTotalAmount = null)
    {
        $this->grandTotalAmount = $grandTotalAmount;
        return $this;
    }
}
