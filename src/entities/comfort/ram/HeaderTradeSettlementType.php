<?php

namespace horstoeko\orderx\entities\comfort\ram;

/**
 * Class representing HeaderTradeSettlementType
 *
 * Header Trade Settlement
 * XSD Type: HeaderTradeSettlementType
 */
class HeaderTradeSettlementType
{

    /**
     * Order Currency Code
     *
     * @var string $orderCurrencyCode
     */
    private $orderCurrencyCode = null;

    /**
     * Invoicee
     *
     * @var \horstoeko\orderx\entities\comfort\ram\TradePartyType $invoiceeTradeParty
     */
    private $invoiceeTradeParty = null;

    /**
     * Payment Means
     *
     * @var \horstoeko\orderx\entities\comfort\ram\TradeSettlementPaymentMeansType $specifiedTradeSettlementPaymentMeans
     */
    private $specifiedTradeSettlementPaymentMeans = null;

    /**
     * Allowance/Charge
     *
     * @var \horstoeko\orderx\entities\comfort\ram\TradeAllowanceChargeType[] $specifiedTradeAllowanceCharge
     */
    private $specifiedTradeAllowanceCharge = [

    ];

    /**
     * Payment Terms
     *
     * @var \horstoeko\orderx\entities\comfort\ram\TradePaymentTermsType $specifiedTradePaymentTerms
     */
    private $specifiedTradePaymentTerms = null;

    /**
     * Monetary Summation
     *
     * @var \horstoeko\orderx\entities\comfort\ram\TradeSettlementHeaderMonetarySummationType $specifiedTradeSettlementHeaderMonetarySummation
     */
    private $specifiedTradeSettlementHeaderMonetarySummation = null;

    /**
     * Accounts Receivable
     *
     * @var \horstoeko\orderx\entities\comfort\ram\TradeAccountingAccountType $receivableSpecifiedTradeAccountingAccount
     */
    private $receivableSpecifiedTradeAccountingAccount = null;

    /**
     * Gets as orderCurrencyCode
     *
     * Order Currency Code
     *
     * @return string
     */
    public function getOrderCurrencyCode()
    {
        return $this->orderCurrencyCode;
    }

    /**
     * Sets a new orderCurrencyCode
     *
     * Order Currency Code
     *
     * @param  string $orderCurrencyCode
     * @return self
     */
    public function setOrderCurrencyCode($orderCurrencyCode)
    {
        $this->orderCurrencyCode = $orderCurrencyCode;
        return $this;
    }

    /**
     * Gets as invoiceeTradeParty
     *
     * Invoicee
     *
     * @return \horstoeko\orderx\entities\comfort\ram\TradePartyType
     */
    public function getInvoiceeTradeParty()
    {
        return $this->invoiceeTradeParty;
    }

    /**
     * Sets a new invoiceeTradeParty
     *
     * Invoicee
     *
     * @param  \horstoeko\orderx\entities\comfort\ram\TradePartyType $invoiceeTradeParty
     * @return self
     */
    public function setInvoiceeTradeParty(?\horstoeko\orderx\entities\comfort\ram\TradePartyType $invoiceeTradeParty = null)
    {
        $this->invoiceeTradeParty = $invoiceeTradeParty;
        return $this;
    }

    /**
     * Gets as specifiedTradeSettlementPaymentMeans
     *
     * Payment Means
     *
     * @return \horstoeko\orderx\entities\comfort\ram\TradeSettlementPaymentMeansType
     */
    public function getSpecifiedTradeSettlementPaymentMeans()
    {
        return $this->specifiedTradeSettlementPaymentMeans;
    }

    /**
     * Sets a new specifiedTradeSettlementPaymentMeans
     *
     * Payment Means
     *
     * @param  \horstoeko\orderx\entities\comfort\ram\TradeSettlementPaymentMeansType $specifiedTradeSettlementPaymentMeans
     * @return self
     */
    public function setSpecifiedTradeSettlementPaymentMeans(?\horstoeko\orderx\entities\comfort\ram\TradeSettlementPaymentMeansType $specifiedTradeSettlementPaymentMeans = null)
    {
        $this->specifiedTradeSettlementPaymentMeans = $specifiedTradeSettlementPaymentMeans;
        return $this;
    }

    /**
     * Adds as specifiedTradeAllowanceCharge
     *
     * Allowance/Charge
     *
     * @return self
     * @param  \horstoeko\orderx\entities\comfort\ram\TradeAllowanceChargeType $specifiedTradeAllowanceCharge
     */
    public function addToSpecifiedTradeAllowanceCharge(\horstoeko\orderx\entities\comfort\ram\TradeAllowanceChargeType $specifiedTradeAllowanceCharge)
    {
        $this->specifiedTradeAllowanceCharge[] = $specifiedTradeAllowanceCharge;
        return $this;
    }

    /**
     * isset specifiedTradeAllowanceCharge
     *
     * Allowance/Charge
     *
     * @param  int|string $index
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
     * @param  int|string $index
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
     * @return \horstoeko\orderx\entities\comfort\ram\TradeAllowanceChargeType[]
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
     * @param  \horstoeko\orderx\entities\comfort\ram\TradeAllowanceChargeType[] $specifiedTradeAllowanceCharge
     * @return self
     */
    public function setSpecifiedTradeAllowanceCharge(array $specifiedTradeAllowanceCharge = null)
    {
        $this->specifiedTradeAllowanceCharge = $specifiedTradeAllowanceCharge;
        return $this;
    }

    /**
     * Gets as specifiedTradePaymentTerms
     *
     * Payment Terms
     *
     * @return \horstoeko\orderx\entities\comfort\ram\TradePaymentTermsType
     */
    public function getSpecifiedTradePaymentTerms()
    {
        return $this->specifiedTradePaymentTerms;
    }

    /**
     * Sets a new specifiedTradePaymentTerms
     *
     * Payment Terms
     *
     * @param  \horstoeko\orderx\entities\comfort\ram\TradePaymentTermsType $specifiedTradePaymentTerms
     * @return self
     */
    public function setSpecifiedTradePaymentTerms(?\horstoeko\orderx\entities\comfort\ram\TradePaymentTermsType $specifiedTradePaymentTerms = null)
    {
        $this->specifiedTradePaymentTerms = $specifiedTradePaymentTerms;
        return $this;
    }

    /**
     * Gets as specifiedTradeSettlementHeaderMonetarySummation
     *
     * Monetary Summation
     *
     * @return \horstoeko\orderx\entities\comfort\ram\TradeSettlementHeaderMonetarySummationType
     */
    public function getSpecifiedTradeSettlementHeaderMonetarySummation()
    {
        return $this->specifiedTradeSettlementHeaderMonetarySummation;
    }

    /**
     * Sets a new specifiedTradeSettlementHeaderMonetarySummation
     *
     * Monetary Summation
     *
     * @param  \horstoeko\orderx\entities\comfort\ram\TradeSettlementHeaderMonetarySummationType $specifiedTradeSettlementHeaderMonetarySummation
     * @return self
     */
    public function setSpecifiedTradeSettlementHeaderMonetarySummation(\horstoeko\orderx\entities\comfort\ram\TradeSettlementHeaderMonetarySummationType $specifiedTradeSettlementHeaderMonetarySummation)
    {
        $this->specifiedTradeSettlementHeaderMonetarySummation = $specifiedTradeSettlementHeaderMonetarySummation;
        return $this;
    }

    /**
     * Gets as receivableSpecifiedTradeAccountingAccount
     *
     * Accounts Receivable
     *
     * @return \horstoeko\orderx\entities\comfort\ram\TradeAccountingAccountType
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
     * @param  \horstoeko\orderx\entities\comfort\ram\TradeAccountingAccountType $receivableSpecifiedTradeAccountingAccount
     * @return self
     */
    public function setReceivableSpecifiedTradeAccountingAccount(?\horstoeko\orderx\entities\comfort\ram\TradeAccountingAccountType $receivableSpecifiedTradeAccountingAccount = null)
    {
        $this->receivableSpecifiedTradeAccountingAccount = $receivableSpecifiedTradeAccountingAccount;
        return $this;
    }
}
