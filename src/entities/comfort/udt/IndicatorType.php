<?php

namespace horstoeko\orderx\entities\comfort\udt;

/**
 * Class representing IndicatorType
 *
 *
 * XSD Type: IndicatorType
 */
class IndicatorType
{

    /**
     * @var string $indicatorString
     */
    private $indicatorString = null;

    /**
     * @var bool $indicator
     */
    private $indicator = null;

    /**
     * Gets as indicatorString
     *
     * @return string
     */
    public function getIndicatorString()
    {
        return $this->indicatorString;
    }

    /**
     * Sets a new indicatorString
     *
     * @param string $indicatorString
     * @return self
     */
    public function setIndicatorString($indicatorString)
    {
        $this->indicatorString = $indicatorString;
        return $this;
    }

    /**
     * Gets as indicator
     *
     * @return bool
     */
    public function getIndicator()
    {
        return $this->indicator;
    }

    /**
     * Sets a new indicator
     *
     * @param bool $indicator
     * @return self
     */
    public function setIndicator($indicator)
    {
        $this->indicator = $indicator;
        return $this;
    }


}

