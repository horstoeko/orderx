<?php

namespace horstoeko\orderx\entities\basic\ram;

/**
 * Class representing TradePartyType
 *
 * Trade Party
 * XSD Type: TradePartyType
 */
class TradePartyType
{

    /**
     * ID
     *
     * @var \horstoeko\orderx\entities\basic\udt\IDType $iD
     */
    private $iD = null;

    /**
     * Global ID
     *
     * @var \horstoeko\orderx\entities\basic\udt\IDType[] $globalID
     */
    private $globalID = [
        
    ];

    /**
     * Name
     *
     * @var string $name
     */
    private $name = null;

    /**
     * Legal Organization
     *
     * @var \horstoeko\orderx\entities\basic\ram\LegalOrganizationType $specifiedLegalOrganization
     */
    private $specifiedLegalOrganization = null;

    /**
     * Defined Contact Details
     *
     * @var \horstoeko\orderx\entities\basic\ram\TradeContactType $definedTradeContact
     */
    private $definedTradeContact = null;

    /**
     * Postal Address
     *
     * @var \horstoeko\orderx\entities\basic\ram\TradeAddressType $postalTradeAddress
     */
    private $postalTradeAddress = null;

    /**
     * URI
     *
     * @var \horstoeko\orderx\entities\basic\ram\UniversalCommunicationType $uRIUniversalCommunication
     */
    private $uRIUniversalCommunication = null;

    /**
     * Tax Registration
     *
     * @var \horstoeko\orderx\entities\basic\ram\TaxRegistrationType $specifiedTaxRegistration
     */
    private $specifiedTaxRegistration = null;

    /**
     * Gets as iD
     *
     * ID
     *
     * @return \horstoeko\orderx\entities\basic\udt\IDType
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
     * @param \horstoeko\orderx\entities\basic\udt\IDType $iD
     * @return self
     */
    public function setID(?\horstoeko\orderx\entities\basic\udt\IDType $iD = null)
    {
        $this->iD = $iD;
        return $this;
    }

    /**
     * Adds as globalID
     *
     * Global ID
     *
     * @return self
     * @param \horstoeko\orderx\entities\basic\udt\IDType $globalID
     */
    public function addToGlobalID(\horstoeko\orderx\entities\basic\udt\IDType $globalID)
    {
        $this->globalID[] = $globalID;
        return $this;
    }

    /**
     * isset globalID
     *
     * Global ID
     *
     * @param int|string $index
     * @return bool
     */
    public function issetGlobalID($index)
    {
        return isset($this->globalID[$index]);
    }

    /**
     * unset globalID
     *
     * Global ID
     *
     * @param int|string $index
     * @return void
     */
    public function unsetGlobalID($index)
    {
        unset($this->globalID[$index]);
    }

    /**
     * Gets as globalID
     *
     * Global ID
     *
     * @return \horstoeko\orderx\entities\basic\udt\IDType[]
     */
    public function getGlobalID()
    {
        return $this->globalID;
    }

    /**
     * Sets a new globalID
     *
     * Global ID
     *
     * @param \horstoeko\orderx\entities\basic\udt\IDType[] $globalID
     * @return self
     */
    public function setGlobalID(array $globalID = null)
    {
        $this->globalID = $globalID;
        return $this;
    }

    /**
     * Gets as name
     *
     * Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * Name
     *
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets as specifiedLegalOrganization
     *
     * Legal Organization
     *
     * @return \horstoeko\orderx\entities\basic\ram\LegalOrganizationType
     */
    public function getSpecifiedLegalOrganization()
    {
        return $this->specifiedLegalOrganization;
    }

    /**
     * Sets a new specifiedLegalOrganization
     *
     * Legal Organization
     *
     * @param \horstoeko\orderx\entities\basic\ram\LegalOrganizationType $specifiedLegalOrganization
     * @return self
     */
    public function setSpecifiedLegalOrganization(?\horstoeko\orderx\entities\basic\ram\LegalOrganizationType $specifiedLegalOrganization = null)
    {
        $this->specifiedLegalOrganization = $specifiedLegalOrganization;
        return $this;
    }

    /**
     * Gets as definedTradeContact
     *
     * Defined Contact Details
     *
     * @return \horstoeko\orderx\entities\basic\ram\TradeContactType
     */
    public function getDefinedTradeContact()
    {
        return $this->definedTradeContact;
    }

    /**
     * Sets a new definedTradeContact
     *
     * Defined Contact Details
     *
     * @param \horstoeko\orderx\entities\basic\ram\TradeContactType $definedTradeContact
     * @return self
     */
    public function setDefinedTradeContact(?\horstoeko\orderx\entities\basic\ram\TradeContactType $definedTradeContact = null)
    {
        $this->definedTradeContact = $definedTradeContact;
        return $this;
    }

    /**
     * Gets as postalTradeAddress
     *
     * Postal Address
     *
     * @return \horstoeko\orderx\entities\basic\ram\TradeAddressType
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
     * @param \horstoeko\orderx\entities\basic\ram\TradeAddressType $postalTradeAddress
     * @return self
     */
    public function setPostalTradeAddress(?\horstoeko\orderx\entities\basic\ram\TradeAddressType $postalTradeAddress = null)
    {
        $this->postalTradeAddress = $postalTradeAddress;
        return $this;
    }

    /**
     * Gets as uRIUniversalCommunication
     *
     * URI
     *
     * @return \horstoeko\orderx\entities\basic\ram\UniversalCommunicationType
     */
    public function getURIUniversalCommunication()
    {
        return $this->uRIUniversalCommunication;
    }

    /**
     * Sets a new uRIUniversalCommunication
     *
     * URI
     *
     * @param \horstoeko\orderx\entities\basic\ram\UniversalCommunicationType $uRIUniversalCommunication
     * @return self
     */
    public function setURIUniversalCommunication(?\horstoeko\orderx\entities\basic\ram\UniversalCommunicationType $uRIUniversalCommunication = null)
    {
        $this->uRIUniversalCommunication = $uRIUniversalCommunication;
        return $this;
    }

    /**
     * Gets as specifiedTaxRegistration
     *
     * Tax Registration
     *
     * @return \horstoeko\orderx\entities\basic\ram\TaxRegistrationType
     */
    public function getSpecifiedTaxRegistration()
    {
        return $this->specifiedTaxRegistration;
    }

    /**
     * Sets a new specifiedTaxRegistration
     *
     * Tax Registration
     *
     * @param \horstoeko\orderx\entities\basic\ram\TaxRegistrationType $specifiedTaxRegistration
     * @return self
     */
    public function setSpecifiedTaxRegistration(?\horstoeko\orderx\entities\basic\ram\TaxRegistrationType $specifiedTaxRegistration = null)
    {
        $this->specifiedTaxRegistration = $specifiedTaxRegistration;
        return $this;
    }


}

