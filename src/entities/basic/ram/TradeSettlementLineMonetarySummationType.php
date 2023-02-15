<?php

namespace horstoeko\orderx\entities\basic\ram;

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
     * @var \horstoeko\orderx\entities\basic\udt\AmountType $lineTotalAmount
     */
    private $lineTotalAmount = null;

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
}
