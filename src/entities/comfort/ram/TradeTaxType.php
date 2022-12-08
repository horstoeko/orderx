<?php

namespace horstoeko\orderx\entities\comfort\ram;

/**
 * Class representing TradeTaxType
 *
 * Trade Tax
 * XSD Type: TradeTaxType
 */
class TradeTaxType
{

    /**
     * Type Code
     *
     * @var string $typeCode
     */
    private $typeCode = null;

    /**
     * Category Code
     *
     * @var string $categoryCode
     */
    private $categoryCode = null;

    /**
     * Applicable Rate Percent
     *
     * @var float $rateApplicablePercent
     */
    private $rateApplicablePercent = null;

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
     * @param string $typeCode
     * @return self
     */
    public function setTypeCode($typeCode)
    {
        $this->typeCode = $typeCode;
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
     * @param string $categoryCode
     * @return self
     */
    public function setCategoryCode($categoryCode)
    {
        $this->categoryCode = $categoryCode;
        return $this;
    }

    /**
     * Gets as rateApplicablePercent
     *
     * Applicable Rate Percent
     *
     * @return float
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
     * @param float $rateApplicablePercent
     * @return self
     */
    public function setRateApplicablePercent($rateApplicablePercent)
    {
        $this->rateApplicablePercent = $rateApplicablePercent;
        return $this;
    }


}

