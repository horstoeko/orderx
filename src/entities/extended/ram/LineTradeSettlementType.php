<?php

namespace horstoeko\orderx\entities\extended\ram;

/**
 * Class representing LineTradeSettlementType
 *
 * Line Trade Settlement
 * XSD Type: LineTradeSettlementType
 */
class LineTradeSettlementType
{

    /**
     * Trade Tax
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradeTaxType $applicableTradeTax
     */
    private $applicableTradeTax = null;

    /**
     * Allowance/Charge
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradeAllowanceChargeType[] $specifiedTradeAllowanceCharge
     */
    private $specifiedTradeAllowanceCharge = [
        
    ];

    /**
     * Monetary Summation
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradeSettlementLineMonetarySummationType $specifiedTradeSettlementLineMonetarySummation
     */
    private $specifiedTradeSettlementLineMonetarySummation = null;

    /**
     * Accounts Receivable
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradeAccountingAccountType $receivableSpecifiedTradeAccountingAccount
     */
    private $receivableSpecifiedTradeAccountingAccount = null;

    /**
     * Gets as applicableTradeTax
     *
     * Trade Tax
     *
     * @return \horstoeko\orderx\entities\extended\ram\TradeTaxType
     */
    public function getApplicableTradeTax()
    {
        return $this->applicableTradeTax;
    }

    /**
     * Sets a new applicableTradeTax
     *
     * Trade Tax
     *
     * @param \horstoeko\orderx\entities\extended\ram\TradeTaxType $applicableTradeTax
     * @return self
     */
    public function setApplicableTradeTax(?\horstoeko\orderx\entities\extended\ram\TradeTaxType $applicableTradeTax = null)
    {
        $this->applicableTradeTax = $applicableTradeTax;
        return $this;
    }

    /**
     * Adds as specifiedTradeAllowanceCharge
     *
     * Allowance/Charge
     *
     * @return self
     * @param \horstoeko\orderx\entities\extended\ram\TradeAllowanceChargeType $specifiedTradeAllowanceCharge
     */
    public function addToSpecifiedTradeAllowanceCharge(\horstoeko\orderx\entities\extended\ram\TradeAllowanceChargeType $specifiedTradeAllowanceCharge)
    {
        $this->specifiedTradeAllowanceCharge[] = $specifiedTradeAllowanceCharge;
        return $this;
    }

    /**
     * isset specifiedTradeAllowanceCharge
     *
     * Allowance/Charge
     *
     * @param int|string $index
     * @return bool
     */
    public function issetSpecifiedTradeAllowanceCharge($index)
    {
        return isset($this->specifiedTradeAllowanceCharge[$index]);
    }

    /**
     * unset specifiedTradeAllowanceCharge
     *
     * Allowance/Charge
     *
     * @param int|string $index
     * @return void
     */
    public function unsetSpecifiedTradeAllowanceCharge($index)
    {
        unset($this->specifiedTradeAllowanceCharge[$index]);
    }

    /**
     * Gets as specifiedTradeAllowanceCharge
     *
     * Allowance/Charge
     *
     * @return \horstoeko\orderx\entities\extended\ram\TradeAllowanceChargeType[]
     */
    public function getSpecifiedTradeAllowanceCharge()
    {
        return $this->specifiedTradeAllowanceCharge;
    }

    /**
     * Sets a new specifiedTradeAllowanceCharge
     *
     * Allowance/Charge
     *
     * @param \horstoeko\orderx\entities\extended\ram\TradeAllowanceChargeType[] $specifiedTradeAllowanceCharge
     * @return self
     */
    public function setSpecifiedTradeAllowanceCharge(array $specifiedTradeAllowanceCharge = null)
    {
        $this->specifiedTradeAllowanceCharge = $specifiedTradeAllowanceCharge;
        return $this;
    }

    /**
     * Gets as specifiedTradeSettlementLineMonetarySummation
     *
     * Monetary Summation
     *
     * @return \horstoeko\orderx\entities\extended\ram\TradeSettlementLineMonetarySummationType
     */
    public function getSpecifiedTradeSettlementLineMonetarySummation()
    {
        return $this->specifiedTradeSettlementLineMonetarySummation;
    }

    /**
     * Sets a new specifiedTradeSettlementLineMonetarySummation
     *
     * Monetary Summation
     *
     * @param \horstoeko\orderx\entities\extended\ram\TradeSettlementLineMonetarySummationType $specifiedTradeSettlementLineMonetarySummation
     * @return self
     */
    public function setSpecifiedTradeSettlementLineMonetarySummation(\horstoeko\orderx\entities\extended\ram\TradeSettlementLineMonetarySummationType $specifiedTradeSettlementLineMonetarySummation)
    {
        $this->specifiedTradeSettlementLineMonetarySummation = $specifiedTradeSettlementLineMonetarySummation;
        return $this;
    }

    /**
     * Gets as receivableSpecifiedTradeAccountingAccount
     *
     * Accounts Receivable
     *
     * @return \horstoeko\orderx\entities\extended\ram\TradeAccountingAccountType
     */
    public function getReceivableSpecifiedTradeAccountingAccount()
    {
        return $this->receivableSpecifiedTradeAccountingAccount;
    }

    /**
     * Sets a new receivableSpecifiedTradeAccountingAccount
     *
     * Accounts Receivable
     *
     * @param \horstoeko\orderx\entities\extended\ram\TradeAccountingAccountType $receivableSpecifiedTradeAccountingAccount
     * @return self
     */
    public function setReceivableSpecifiedTradeAccountingAccount(?\horstoeko\orderx\entities\extended\ram\TradeAccountingAccountType $receivableSpecifiedTradeAccountingAccount = null)
    {
        $this->receivableSpecifiedTradeAccountingAccount = $receivableSpecifiedTradeAccountingAccount;
        return $this;
    }


}

