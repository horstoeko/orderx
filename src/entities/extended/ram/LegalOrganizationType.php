<?php

namespace horstoeko\orderx\entities\extended\ram;

/**
 * Class representing LegalOrganizationType
 *
 * Legal Organization
 * XSD Type: LegalOrganizationType
 */
class LegalOrganizationType
{

    /**
     * ID
     *
     * @var \horstoeko\orderx\entities\extended\udt\IDType $iD
     */
    private $iD = null;

    /**
     * Trading Name
     *
     * @var string $tradingBusinessName
     */
    private $tradingBusinessName = null;

    /**
     * Postal Address
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradeAddressType $postalTradeAddress
     */
    private $postalTradeAddress = null;

    /**
     * Gets as iD
     *
     * ID
     *
     * @return \horstoeko\orderx\entities\extended\udt\IDType
     */
    public function getID()
    {
        return $this->iD;
    }

    /**
     * Sets a new iD
     *
     * ID
     *
     * @param  \horstoeko\orderx\entities\extended\udt\IDType $iD
     * @return self
     */
    public function setID(?\horstoeko\orderx\entities\extended\udt\IDType $iD = null)
    {
        $this->iD = $iD;
        return $this;
    }

    /**
     * Gets as tradingBusinessName
     *
     * Trading Name
     *
     * @return string
     */
    public function getTradingBusinessName()
    {
        return $this->tradingBusinessName;
    }

    /**
     * Sets a new tradingBusinessName
     *
     * Trading Name
     *
     * @param  string $tradingBusinessName
     * @return self
     */
    public function setTradingBusinessName($tradingBusinessName)
    {
        $this->tradingBusinessName = $tradingBusinessName;
        return $this;
    }

    /**
     * Gets as postalTradeAddress
     *
     * Postal Address
     *
     * @return \horstoeko\orderx\entities\extended\ram\TradeAddressType
     */
    public function getPostalTradeAddress()
    {
        return $this->postalTradeAddress;
    }

    /**
     * Sets a new postalTradeAddress
     *
     * Postal Address
     *
     * @param  \horstoeko\orderx\entities\extended\ram\TradeAddressType $postalTradeAddress
     * @return self
     */
    public function setPostalTradeAddress(?\horstoeko\orderx\entities\extended\ram\TradeAddressType $postalTradeAddress = null)
    {
        $this->postalTradeAddress = $postalTradeAddress;
        return $this;
    }
}
