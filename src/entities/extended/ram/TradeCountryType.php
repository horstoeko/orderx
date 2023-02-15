<?php

namespace horstoeko\orderx\entities\extended\ram;

/**
 * Class representing TradeCountryType
 *
 * Trade Country
 * XSD Type: TradeCountryType
 */
class TradeCountryType
{

    /**
     * Code
     *
     * @var string $iD
     */
    private $iD = null;

    /**
     * Gets as iD
     *
     * Code
     *
     * @return string
     */
    public function getID()
    {
        return $this->iD;
    }

    /**
     * Sets a new iD
     *
     * Code
     *
     * @param  string $iD
     * @return self
     */
    public function setID($iD)
    {
        $this->iD = $iD;
        return $this;
    }
}
