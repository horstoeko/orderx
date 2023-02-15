<?php

namespace horstoeko\orderx\entities\comfort\ram;

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
     * @var \horstoeko\orderx\entities\comfort\udt\IDType $iD
     */
    private $iD = null;

    /**
     * Trading Name
     *
     * @var string $tradingBusinessName
     */
    private $tradingBusinessName = null;

    /**
     * Gets as iD
     *
     * ID
     *
     * @return \horstoeko\orderx\entities\comfort\udt\IDType
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
     * @param  \horstoeko\orderx\entities\comfort\udt\IDType $iD
     * @return self
     */
    public function setID(?\horstoeko\orderx\entities\comfort\udt\IDType $iD = null)
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
}
