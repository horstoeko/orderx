<?php

namespace horstoeko\orderx\entities\extended\ram;

/**
 * Class representing HeaderTradeSettlementType
 *
 * Header Trade Settlement
 * XSD Type: HeaderTradeSettlementType
 */
class HeaderTradeSettlementType
{

    /**
     * Tax Currency Code
     *
     * @var string $taxCurrencyCode
     */
    private $taxCurrencyCode = null;

    /**
     * Order Currency Code
     *
     * @var string $orderCurrencyCode
     */
    private $orderCurrencyCode = null;

    /**
     * Invoice Currency Code
     *
     * @var string $invoiceCurrencyCode
     */
    private $invoiceCurrencyCode = null;

    /**
     * Invoicer
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradePartyType $invoicerTradeParty
     */
    private $invoicerTradeParty = null;

    /**
     * Invoicee
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradePartyType $invoiceeTradeParty
     */
    private $invoiceeTradeParty = null;

    /**
     * Payment Means
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradeSettlementPaymentMeansType $specifiedTradeSettlementPaymentMeans
     */
    private $specifiedTradeSettlementPaymentMeans = null;

    /**
     * Trade Tax
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradeTaxType[] $applicableTradeTax
     */
    private $applicableTradeTax = [
        
    ];

    /**
     * Allowance/Charge
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradeAllowanceChargeType[] $specifiedTradeAllowanceCharge
     */
    private $specifiedTradeAllowanceCharge = [
        
    ];

    /**
     * Logistics Service Charge
     *
     * @var \horstoeko\orderx\entities\extended\ram\LogisticsServiceChargeType[] $specifiedLogisticsServiceCharge
     */
    private $specifiedLogisticsServiceCharge = [
        
    ];

    /**
     * Payment Terms
     *
     * @var string[] $specifiedTradePaymentTerms
     */
    private $specifiedTradePaymentTerms = null;

    /**
     * Monetary Summation
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradeSettlementHeaderMonetarySummationType $specifiedTradeSettlementHeaderMonetarySummation
     */
    private $specifiedTradeSettlementHeaderMonetarySummation = null;

    /**
     * Accounts Receivable
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradeAccountingAccountType $receivableSpecifiedTradeAccountingAccount
     */
    private $receivableSpecifiedTradeAccountingAccount = null;

    /**
     * Gets as taxCurrencyCode
     *
     * Tax Currency Code
     *
     * @return string
     */
    public function getTaxCurrencyCode()
    {
        return $this->taxCurrencyCode;
    }

    /**
     * Sets a new taxCurrencyCode
     *
     * Tax Currency Code
     *
     * @param string $taxCurrencyCode
     * @return self
     */
    public function setTaxCurrencyCode($taxCurrencyCode)
    {
        $this->taxCurrencyCode = $taxCurrencyCode;
        return $this;
    }

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
     * @param string $orderCurrencyCode
     * @return self
     */
    public function setOrderCurrencyCode($orderCurrencyCode)
    {
        $this->orderCurrencyCode = $orderCurrencyCode;
        return $this;
    }

    /**
     * Gets as invoiceCurrencyCode
     *
     * Invoice Currency Code
     *
     * @return string
     */
    public function getInvoiceCurrencyCode()
    {
        return $this->invoiceCurrencyCode;
    }

    /**
     * Sets a new invoiceCurrencyCode
     *
     * Invoice Currency Code
     *
     * @param string $invoiceCurrencyCode
     * @return self
     */
    public function setInvoiceCurrencyCode($invoiceCurrencyCode)
    {
        $this->invoiceCurrencyCode = $invoiceCurrencyCode;
        return $this;
    }

    /**
     * Gets as invoicerTradeParty
     *
     * Invoicer
     *
     * @return \horstoeko\orderx\entities\extended\ram\TradePartyType
     */
    public function getInvoicerTradeParty()
    {
        return $this->invoicerTradeParty;
    }

    /**
     * Sets a new invoicerTradeParty
     *
     * Invoicer
     *
     * @param \horstoeko\orderx\entities\extended\ram\TradePartyType $invoicerTradeParty
     * @return self
     */
    public function setInvoicerTradeParty(?\horstoeko\orderx\entities\extended\ram\TradePartyType $invoicerTradeParty = null)
    {
        $this->invoicerTradeParty = $invoicerTradeParty;
        return $this;
    }

    /**
     * Gets as invoiceeTradeParty
     *
     * Invoicee
     *
     * @return \horstoeko\orderx\entities\extended\ram\TradePartyType
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
     * @param \horstoeko\orderx\entities\extended\ram\TradePartyType $invoiceeTradeParty
     * @return self
     */
    public function setInvoiceeTradeParty(?\horstoeko\orderx\entities\extended\ram\TradePartyType $invoiceeTradeParty = null)
    {
        $this->invoiceeTradeParty = $invoiceeTradeParty;
        return $this;
    }

    /**
     * Gets as specifiedTradeSettlementPaymentMeans
     *
     * Payment Means
     *
     * @return \horstoeko\orderx\entities\extended\ram\TradeSettlementPaymentMeansType
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
     * @param \horstoeko\orderx\entities\extended\ram\TradeSettlementPaymentMeansType $specifiedTradeSettlementPaymentMeans
     * @return self
     */
    public function setSpecifiedTradeSettlementPaymentMeans(?\horstoeko\orderx\entities\extended\ram\TradeSettlementPaymentMeansType $specifiedTradeSettlementPaymentMeans = null)
    {
        $this->specifiedTradeSettlementPaymentMeans = $specifiedTradeSettlementPaymentMeans;
        return $this;
    }

    /**
     * Adds as applicableTradeTax
     *
     * Trade Tax
     *
     * @return self
     * @param \horstoeko\orderx\entities\extended\ram\TradeTaxType $applicableTradeTax
     */
    public function addToApplicableTradeTax(\horstoeko\orderx\entities\extended\ram\TradeTaxType $applicableTradeTax)
    {
        $this->applicableTradeTax[] = $applicableTradeTax;
        return $this;
    }

    /**
     * isset applicableTradeTax
     *
     * Trade Tax
     *
     * @param int|string $index
     * @return bool
     */
    public function issetApplicableTradeTax($index)
    {
        return isset($this->applicableTradeTax[$index]);
    }

    /**
     * unset applicableTradeTax
     *
     * Trade Tax
     *
     * @param int|string $index
     * @return void
     */
    public function unsetApplicableTradeTax($index)
    {
        unset($this->applicableTradeTax[$index]);
    }

    /**
     * Gets as applicableTradeTax
     *
     * Trade Tax
     *
     * @return \horstoeko\orderx\entities\extended\ram\TradeTaxType[]
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
     * @param \horstoeko\orderx\entities\extended\ram\TradeTaxType[] $applicableTradeTax
     * @return self
     */
    public function setApplicableTradeTax(array $applicableTradeTax = null)
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
     * Adds as specifiedLogisticsServiceCharge
     *
     * Logistics Service Charge
     *
     * @return self
     * @param \horstoeko\orderx\entities\extended\ram\LogisticsServiceChargeType $specifiedLogisticsServiceCharge
     */
    public function addToSpecifiedLogisticsServiceCharge(\horstoeko\orderx\entities\extended\ram\LogisticsServiceChargeType $specifiedLogisticsServiceCharge)
    {
        $this->specifiedLogisticsServiceCharge[] = $specifiedLogisticsServiceCharge;
        return $this;
    }

    /**
     * isset specifiedLogisticsServiceCharge
     *
     * Logistics Service Charge
     *
     * @param int|string $index
     * @return bool
     */
    public function issetSpecifiedLogisticsServiceCharge($index)
    {
        return isset($this->specifiedLogisticsServiceCharge[$index]);
    }

    /**
     * unset specifiedLogisticsServiceCharge
     *
     * Logistics Service Charge
     *
     * @param int|string $index
     * @return void
     */
    public function unsetSpecifiedLogisticsServiceCharge($index)
    {
        unset($this->specifiedLogisticsServiceCharge[$index]);
    }

    /**
     * Gets as specifiedLogisticsServiceCharge
     *
     * Logistics Service Charge
     *
     * @return \horstoeko\orderx\entities\extended\ram\LogisticsServiceChargeType[]
     */
    public function getSpecifiedLogisticsServiceCharge()
    {
        return $this->specifiedLogisticsServiceCharge;
    }

    /**
     * Sets a new specifiedLogisticsServiceCharge
     *
     * Logistics Service Charge
     *
     * @param \horstoeko\orderx\entities\extended\ram\LogisticsServiceChargeType[] $specifiedLogisticsServiceCharge
     * @return self
     */
    public function setSpecifiedLogisticsServiceCharge(array $specifiedLogisticsServiceCharge = null)
    {
        $this->specifiedLogisticsServiceCharge = $specifiedLogisticsServiceCharge;
        return $this;
    }

    /**
     * Adds as description
     *
     * Payment Terms
     *
     * @return self
     * @param string $description
     */
    public function addToSpecifiedTradePaymentTerms($description)
    {
        $this->specifiedTradePaymentTerms[] = $description;
        return $this;
    }

    /**
     * isset specifiedTradePaymentTerms
     *
     * Payment Terms
     *
     * @param int|string $index
     * @return bool
     */
    public function issetSpecifiedTradePaymentTerms($index)
    {
        return isset($this->specifiedTradePaymentTerms[$index]);
    }

    /**
     * unset specifiedTradePaymentTerms
     *
     * Payment Terms
     *
     * @param int|string $index
     * @return void
     */
    public function unsetSpecifiedTradePaymentTerms($index)
    {
        unset($this->specifiedTradePaymentTerms[$index]);
    }

    /**
     * Gets as specifiedTradePaymentTerms
     *
     * Payment Terms
     *
     * @return string[]
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
     * @param string $specifiedTradePaymentTerms
     * @return self
     */
    public function setSpecifiedTradePaymentTerms(array $specifiedTradePaymentTerms = null)
    {
        $this->specifiedTradePaymentTerms = $specifiedTradePaymentTerms;
        return $this;
    }

    /**
     * Gets as specifiedTradeSettlementHeaderMonetarySummation
     *
     * Monetary Summation
     *
     * @return \horstoeko\orderx\entities\extended\ram\TradeSettlementHeaderMonetarySummationType
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
     * @param \horstoeko\orderx\entities\extended\ram\TradeSettlementHeaderMonetarySummationType $specifiedTradeSettlementHeaderMonetarySummation
     * @return self
     */
    public function setSpecifiedTradeSettlementHeaderMonetarySummation(\horstoeko\orderx\entities\extended\ram\TradeSettlementHeaderMonetarySummationType $specifiedTradeSettlementHeaderMonetarySummation)
    {
        $this->specifiedTradeSettlementHeaderMonetarySummation = $specifiedTradeSettlementHeaderMonetarySummation;
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

