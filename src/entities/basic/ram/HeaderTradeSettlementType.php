<?php

namespace horstoeko\orderx\entities\basic\ram;

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
     * Monetary Summation
     *
     * @var \horstoeko\orderx\entities\basic\ram\TradeSettlementHeaderMonetarySummationType $specifiedTradeSettlementHeaderMonetarySummation
     */
    private $specifiedTradeSettlementHeaderMonetarySummation = null;

    /**
     * Accounts Receivable
     *
     * @var \horstoeko\orderx\entities\basic\ram\TradeAccountingAccountType $receivableSpecifiedTradeAccountingAccount
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
     * Gets as specifiedTradeSettlementHeaderMonetarySummation
     *
     * Monetary Summation
     *
     * @return \horstoeko\orderx\entities\basic\ram\TradeSettlementHeaderMonetarySummationType
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
     * @param  \horstoeko\orderx\entities\basic\ram\TradeSettlementHeaderMonetarySummationType $specifiedTradeSettlementHeaderMonetarySummation
     * @return self
     */
    public function setSpecifiedTradeSettlementHeaderMonetarySummation(\horstoeko\orderx\entities\basic\ram\TradeSettlementHeaderMonetarySummationType $specifiedTradeSettlementHeaderMonetarySummation)
    {
        $this->specifiedTradeSettlementHeaderMonetarySummation = $specifiedTradeSettlementHeaderMonetarySummation;
        return $this;
    }

    /**
     * Gets as receivableSpecifiedTradeAccountingAccount
     *
     * Accounts Receivable
     *
     * @return \horstoeko\orderx\entities\basic\ram\TradeAccountingAccountType
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
     * @param  \horstoeko\orderx\entities\basic\ram\TradeAccountingAccountType $receivableSpecifiedTradeAccountingAccount
     * @return self
     */
    public function setReceivableSpecifiedTradeAccountingAccount(?\horstoeko\orderx\entities\basic\ram\TradeAccountingAccountType $receivableSpecifiedTradeAccountingAccount = null)
    {
        $this->receivableSpecifiedTradeAccountingAccount = $receivableSpecifiedTradeAccountingAccount;
        return $this;
    }
}
