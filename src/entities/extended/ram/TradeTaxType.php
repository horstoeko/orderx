<?php

namespace horstoeko\orderx\entities\extended\ram;

/**
 * Class representing TradeTaxType
 *
 * Trade Tax
 * XSD Type: TradeTaxType
 */
class TradeTaxType
{

    /**
     * Calculated Amount
     *
     * @var \horstoeko\orderx\entities\extended\udt\AmountType $calculatedAmount
     */
    private $calculatedAmount = null;

    /**
     * Type Code
     *
     * @var string $typeCode
     */
    private $typeCode = null;

    /**
     * Exemption Reason Text
     *
     * @var string $exemptionReason
     */
    private $exemptionReason = null;

    /**
     * Basis Amount
     *
     * @var \horstoeko\orderx\entities\extended\udt\AmountType $basisAmount
     */
    private $basisAmount = null;

    /**
     * Line Total Basis Amount
     *
     * @var \horstoeko\orderx\entities\extended\udt\AmountType $lineTotalBasisAmount
     */
    private $lineTotalBasisAmount = null;

    /**
     * Allowance/Charge Basis Amount
     *
     * @var \horstoeko\orderx\entities\extended\udt\AmountType $allowanceChargeBasisAmount
     */
    private $allowanceChargeBasisAmount = null;

    /**
     * Category Code
     *
     * @var string $categoryCode
     */
    private $categoryCode = null;

    /**
     * Exemption Reason Code
     *
     * @var \horstoeko\orderx\entities\extended\udt\CodeType $exemptionReasonCode
     */
    private $exemptionReasonCode = null;

    /**
     * Due Date Type Code
     *
     * @var string $dueDateTypeCode
     */
    private $dueDateTypeCode = null;

    /**
     * Applicable Rate Percent
     *
     * @var \horstoeko\orderx\entities\extended\udt\PercentType $rateApplicablePercent
     */
    private $rateApplicablePercent = null;

    /**
     * Gets as calculatedAmount
     *
     * Calculated Amount
     *
     * @return \horstoeko\orderx\entities\extended\udt\AmountType
     */
    public function getCalculatedAmount()
    {
        return $this->calculatedAmount;
    }

    /**
     * Sets a new calculatedAmount
     *
     * Calculated Amount
     *
     * @param  \horstoeko\orderx\entities\extended\udt\AmountType $calculatedAmount
     * @return self
     */
    public function setCalculatedAmount(?\horstoeko\orderx\entities\extended\udt\AmountType $calculatedAmount = null)
    {
        $this->calculatedAmount = $calculatedAmount;
        return $this;
    }

    /**
     * Gets as typeCode
     *
     * Type Code
     *
     * @return string
     */
    public function getTypeCode()
    {
        return $this->typeCode;
    }

    /**
     * Sets a new typeCode
     *
     * Type Code
     *
     * @param  string $typeCode
     * @return self
     */
    public function setTypeCode($typeCode)
    {
        $this->typeCode = $typeCode;
        return $this;
    }

    /**
     * Gets as exemptionReason
     *
     * Exemption Reason Text
     *
     * @return string
     */
    public function getExemptionReason()
    {
        return $this->exemptionReason;
    }

    /**
     * Sets a new exemptionReason
     *
     * Exemption Reason Text
     *
     * @param  string $exemptionReason
     * @return self
     */
    public function setExemptionReason($exemptionReason)
    {
        $this->exemptionReason = $exemptionReason;
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
     * @param  \horstoeko\orderx\entities\extended\udt\AmountType $basisAmount
     * @return self
     */
    public function setBasisAmount(?\horstoeko\orderx\entities\extended\udt\AmountType $basisAmount = null)
    {
        $this->basisAmount = $basisAmount;
        return $this;
    }

    /**
     * Gets as lineTotalBasisAmount
     *
     * Line Total Basis Amount
     *
     * @return \horstoeko\orderx\entities\extended\udt\AmountType
     */
    public function getLineTotalBasisAmount()
    {
        return $this->lineTotalBasisAmount;
    }

    /**
     * Sets a new lineTotalBasisAmount
     *
     * Line Total Basis Amount
     *
     * @param  \horstoeko\orderx\entities\extended\udt\AmountType $lineTotalBasisAmount
     * @return self
     */
    public function setLineTotalBasisAmount(?\horstoeko\orderx\entities\extended\udt\AmountType $lineTotalBasisAmount = null)
    {
        $this->lineTotalBasisAmount = $lineTotalBasisAmount;
        return $this;
    }

    /**
     * Gets as allowanceChargeBasisAmount
     *
     * Allowance/Charge Basis Amount
     *
     * @return \horstoeko\orderx\entities\extended\udt\AmountType
     */
    public function getAllowanceChargeBasisAmount()
    {
        return $this->allowanceChargeBasisAmount;
    }

    /**
     * Sets a new allowanceChargeBasisAmount
     *
     * Allowance/Charge Basis Amount
     *
     * @param  \horstoeko\orderx\entities\extended\udt\AmountType $allowanceChargeBasisAmount
     * @return self
     */
    public function setAllowanceChargeBasisAmount(?\horstoeko\orderx\entities\extended\udt\AmountType $allowanceChargeBasisAmount = null)
    {
        $this->allowanceChargeBasisAmount = $allowanceChargeBasisAmount;
        return $this;
    }

    /**
     * Gets as categoryCode
     *
     * Category Code
     *
     * @return string
     */
    public function getCategoryCode()
    {
        return $this->categoryCode;
    }

    /**
     * Sets a new categoryCode
     *
     * Category Code
     *
     * @param  string $categoryCode
     * @return self
     */
    public function setCategoryCode($categoryCode)
    {
        $this->categoryCode = $categoryCode;
        return $this;
    }

    /**
     * Gets as exemptionReasonCode
     *
     * Exemption Reason Code
     *
     * @return \horstoeko\orderx\entities\extended\udt\CodeType
     */
    public function getExemptionReasonCode()
    {
        return $this->exemptionReasonCode;
    }

    /**
     * Sets a new exemptionReasonCode
     *
     * Exemption Reason Code
     *
     * @param  \horstoeko\orderx\entities\extended\udt\CodeType $exemptionReasonCode
     * @return self
     */
    public function setExemptionReasonCode(?\horstoeko\orderx\entities\extended\udt\CodeType $exemptionReasonCode = null)
    {
        $this->exemptionReasonCode = $exemptionReasonCode;
        return $this;
    }

    /**
     * Gets as dueDateTypeCode
     *
     * Due Date Type Code
     *
     * @return string
     */
    public function getDueDateTypeCode()
    {
        return $this->dueDateTypeCode;
    }

    /**
     * Sets a new dueDateTypeCode
     *
     * Due Date Type Code
     *
     * @param  string $dueDateTypeCode
     * @return self
     */
    public function setDueDateTypeCode($dueDateTypeCode)
    {
        $this->dueDateTypeCode = $dueDateTypeCode;
        return $this;
    }

    /**
     * Gets as rateApplicablePercent
     *
     * Applicable Rate Percent
     *
     * @return \horstoeko\orderx\entities\extended\udt\PercentType
     */
    public function getRateApplicablePercent()
    {
        return $this->rateApplicablePercent;
    }

    /**
     * Sets a new rateApplicablePercent
     *
     * Applicable Rate Percent
     *
     * @param  \horstoeko\orderx\entities\extended\udt\PercentType $rateApplicablePercent
     * @return self
     */
    public function setRateApplicablePercent($rateApplicablePercent)
    {
        $this->rateApplicablePercent = $rateApplicablePercent;
        return $this;
    }
}
