<?php

namespace horstoeko\orderx\entities\basic\ram;

/**
 * Class representing LineTradeSettlementType
 *
 * Line Trade Settlement
 * XSD Type: LineTradeSettlementType
 */
class LineTradeSettlementType
{

    /**
     * Monetary Summation
     *
     * @var \horstoeko\orderx\entities\basic\ram\TradeSettlementLineMonetarySummationType $specifiedTradeSettlementLineMonetarySummation
     */
    private $specifiedTradeSettlementLineMonetarySummation = null;

    /**
     * Gets as specifiedTradeSettlementLineMonetarySummation
     *
     * Monetary Summation
     *
     * @return \horstoeko\orderx\entities\basic\ram\TradeSettlementLineMonetarySummationType
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
     * @param \horstoeko\orderx\entities\basic\ram\TradeSettlementLineMonetarySummationType $specifiedTradeSettlementLineMonetarySummation
     * @return self
     */
    public function setSpecifiedTradeSettlementLineMonetarySummation(\horstoeko\orderx\entities\basic\ram\TradeSettlementLineMonetarySummationType $specifiedTradeSettlementLineMonetarySummation)
    {
        $this->specifiedTradeSettlementLineMonetarySummation = $specifiedTradeSettlementLineMonetarySummation;
        return $this;
    }


}

