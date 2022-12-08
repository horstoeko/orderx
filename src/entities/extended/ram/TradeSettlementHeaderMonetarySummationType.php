<?php

namespace horstoeko\orderx\entities\extended\ram;

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
     * Tax Basis Total Amount
     *
     * @var \horstoeko\orderx\entities\extended\udt\AmountType $taxBasisTotalAmount
     */
    private $taxBasisTotalAmount = null;

    /**
     * Tax Total Amount
     *
     * @var \horstoeko\orderx\entities\extended\udt\AmountType[] $taxTotalAmount
     */
    private $taxTotalAmount = [
        
    ];

    /**
     * Rounding Amount
     *
     * @var \horstoeko\orderx\entities\extended\udt\AmountType $roundingAmount
     */
    private $roundingAmount = null;

    /**
     * Grand Total Amount
     *
     * @var \horstoeko\orderx\entities\extended\udt\AmountType $grandTotalAmount
     */
    private $grandTotalAmount = null;

    /**
     * Total Prepaid Amount
     *
     * @var \horstoeko\orderx\entities\extended\udt\AmountType $totalPrepaidAmount
     */
    private $totalPrepaidAmount = null;

    /**
     * Due Payable Amount
     *
     * @var \horstoeko\orderx\entities\extended\udt\AmountType $duePayableAmount
     */
    private $duePayableAmount = null;

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
     * @param \horstoeko\orderx\entities\extended\udt\AmountType $lineTotalAmount
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
     * @param \horstoeko\orderx\entities\extended\udt\AmountType $chargeTotalAmount
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
     * @param \horstoeko\orderx\entities\extended\udt\AmountType $allowanceTotalAmount
     * @return self
     */
    public function setAllowanceTotalAmount(?\horstoeko\orderx\entities\extended\udt\AmountType $allowanceTotalAmount = null)
    {
        $this->allowanceTotalAmount = $allowanceTotalAmount;
        return $this;
    }

    /**
     * Gets as taxBasisTotalAmount
     *
     * Tax Basis Total Amount
     *
     * @return \horstoeko\orderx\entities\extended\udt\AmountType
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
     * @param \horstoeko\orderx\entities\extended\udt\AmountType $taxBasisTotalAmount
     * @return self
     */
    public function setTaxBasisTotalAmount(\horstoeko\orderx\entities\extended\udt\AmountType $taxBasisTotalAmount)
    {
        $this->taxBasisTotalAmount = $taxBasisTotalAmount;
        return $this;
    }

    /**
     * Adds as taxTotalAmount
     *
     * Tax Total Amount
     *
     * @return self
     * @param \horstoeko\orderx\entities\extended\udt\AmountType $taxTotalAmount
     */
    public function addToTaxTotalAmount(\horstoeko\orderx\entities\extended\udt\AmountType $taxTotalAmount)
    {
        $this->taxTotalAmount[] = $taxTotalAmount;
        return $this;
    }

    /**
     * isset taxTotalAmount
     *
     * Tax Total Amount
     *
     * @param int|string $index
     * @return bool
     */
    public function issetTaxTotalAmount($index)
    {
        return isset($this->taxTotalAmount[$index]);
    }

    /**
     * unset taxTotalAmount
     *
     * Tax Total Amount
     *
     * @param int|string $index
     * @return void
     */
    public function unsetTaxTotalAmount($index)
    {
        unset($this->taxTotalAmount[$index]);
    }

    /**
     * Gets as taxTotalAmount
     *
     * Tax Total Amount
     *
     * @return \horstoeko\orderx\entities\extended\udt\AmountType[]
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
     * @param \horstoeko\orderx\entities\extended\udt\AmountType[] $taxTotalAmount
     * @return self
     */
    public function setTaxTotalAmount(array $taxTotalAmount = null)
    {
        $this->taxTotalAmount = $taxTotalAmount;
        return $this;
    }

    /**
     * Gets as roundingAmount
     *
     * Rounding Amount
     *
     * @return \horstoeko\orderx\entities\extended\udt\AmountType
     */
    public function getRoundingAmount()
    {
        return $this->roundingAmount;
    }

    /**
     * Sets a new roundingAmount
     *
     * Rounding Amount
     *
     * @param \horstoeko\orderx\entities\extended\udt\AmountType $roundingAmount
     * @return self
     */
    public function setRoundingAmount(?\horstoeko\orderx\entities\extended\udt\AmountType $roundingAmount = null)
    {
        $this->roundingAmount = $roundingAmount;
        return $this;
    }

    /**
     * Gets as grandTotalAmount
     *
     * Grand Total Amount
     *
     * @return \horstoeko\orderx\entities\extended\udt\AmountType
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
     * @param \horstoeko\orderx\entities\extended\udt\AmountType $grandTotalAmount
     * @return self
     */
    public function setGrandTotalAmount(?\horstoeko\orderx\entities\extended\udt\AmountType $grandTotalAmount = null)
    {
        $this->grandTotalAmount = $grandTotalAmount;
        return $this;
    }

    /**
     * Gets as totalPrepaidAmount
     *
     * Total Prepaid Amount
     *
     * @return \horstoeko\orderx\entities\extended\udt\AmountType
     */
    public function getTotalPrepaidAmount()
    {
        return $this->totalPrepaidAmount;
    }

    /**
     * Sets a new totalPrepaidAmount
     *
     * Total Prepaid Amount
     *
     * @param \horstoeko\orderx\entities\extended\udt\AmountType $totalPrepaidAmount
     * @return self
     */
    public function setTotalPrepaidAmount(?\horstoeko\orderx\entities\extended\udt\AmountType $totalPrepaidAmount = null)
    {
        $this->totalPrepaidAmount = $totalPrepaidAmount;
        return $this;
    }

    /**
     * Gets as duePayableAmount
     *
     * Due Payable Amount
     *
     * @return \horstoeko\orderx\entities\extended\udt\AmountType
     */
    public function getDuePayableAmount()
    {
        return $this->duePayableAmount;
    }

    /**
     * Sets a new duePayableAmount
     *
     * Due Payable Amount
     *
     * @param \horstoeko\orderx\entities\extended\udt\AmountType $duePayableAmount
     * @return self
     */
    public function setDuePayableAmount(?\horstoeko\orderx\entities\extended\udt\AmountType $duePayableAmount = null)
    {
        $this->duePayableAmount = $duePayableAmount;
        return $this;
    }


}

