<?php

namespace horstoeko\orderx\entities\extended\ram;

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
     * @var \horstoeko\orderx\entities\extended\udt\IDType $iD
     */
    private $iD = null;

    /**
     * Global ID
     *
     * @var \horstoeko\orderx\entities\extended\udt\IDType[] $globalID
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
     * Description
     *
     * @var string $description
     */
    private $description = null;

    /**
     * Legal Organization
     *
     * @var \horstoeko\orderx\entities\extended\ram\LegalOrganizationType $specifiedLegalOrganization
     */
    private $specifiedLegalOrganization = null;

    /**
     * Defined Contact Details
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradeContactType[] $definedTradeContact
     */
    private $definedTradeContact = [
        
    ];

    /**
     * Postal Address
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradeAddressType $postalTradeAddress
     */
    private $postalTradeAddress = null;

    /**
     * URI
     *
     * @var \horstoeko\orderx\entities\extended\ram\UniversalCommunicationType $uRIUniversalCommunication
     */
    private $uRIUniversalCommunication = null;

    /**
     * Tax Registration
     *
     * @var \horstoeko\orderx\entities\extended\ram\TaxRegistrationType[] $specifiedTaxRegistration
     */
    private $specifiedTaxRegistration = [
        
    ];

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
     * Adds as globalID
     *
     * Global ID
     *
     * @return self
     * @param  \horstoeko\orderx\entities\extended\udt\IDType $globalID
     */
    public function addToGlobalID(\horstoeko\orderx\entities\extended\udt\IDType $globalID)
    {
        $this->globalID[] = $globalID;
        return $this;
    }

    /**
     * isset globalID
     *
     * Global ID
     *
     * @param  int|string $index
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
     * @param  int|string $index
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
     * @return \horstoeko\orderx\entities\extended\udt\IDType[]
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
     * @param  \horstoeko\orderx\entities\extended\udt\IDType[] $globalID
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
     * @param  string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets as description
     *
     * Description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets a new description
     *
     * Description
     *
     * @param  string $description
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Gets as specifiedLegalOrganization
     *
     * Legal Organization
     *
     * @return \horstoeko\orderx\entities\extended\ram\LegalOrganizationType
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
     * @param  \horstoeko\orderx\entities\extended\ram\LegalOrganizationType $specifiedLegalOrganization
     * @return self
     */
    public function setSpecifiedLegalOrganization(?\horstoeko\orderx\entities\extended\ram\LegalOrganizationType $specifiedLegalOrganization = null)
    {
        $this->specifiedLegalOrganization = $specifiedLegalOrganization;
        return $this;
    }

    /**
     * Adds as definedTradeContact
     *
     * Defined Contact Details
     *
     * @return self
     * @param  \horstoeko\orderx\entities\extended\ram\TradeContactType $definedTradeContact
     */
    public function addToDefinedTradeContact(\horstoeko\orderx\entities\extended\ram\TradeContactType $definedTradeContact)
    {
        $this->definedTradeContact[] = $definedTradeContact;
        return $this;
    }

    /**
     * isset definedTradeContact
     *
     * Defined Contact Details
     *
     * @param  int|string $index
     * @return bool
     */
    public function issetDefinedTradeContact($index)
    {
        return isset($this->definedTradeContact[$index]);
    }

    /**
     * unset definedTradeContact
     *
     * Defined Contact Details
     *
     * @param  int|string $index
     * @return void
     */
    public function unsetDefinedTradeContact($index)
    {
        unset($this->definedTradeContact[$index]);
    }

    /**
     * Gets as definedTradeContact
     *
     * Defined Contact Details
     *
     * @return \horstoeko\orderx\entities\extended\ram\TradeContactType[]
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
     * @param  \horstoeko\orderx\entities\extended\ram\TradeContactType[] $definedTradeContact
     * @return self
     */
    public function setDefinedTradeContact(array $definedTradeContact = null)
    {
        $this->definedTradeContact = $definedTradeContact;
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

    /**
     * Gets as uRIUniversalCommunication
     *
     * URI
     *
     * @return \horstoeko\orderx\entities\extended\ram\UniversalCommunicationType
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
     * @param  \horstoeko\orderx\entities\extended\ram\UniversalCommunicationType $uRIUniversalCommunication
     * @return self
     */
    public function setURIUniversalCommunication(?\horstoeko\orderx\entities\extended\ram\UniversalCommunicationType $uRIUniversalCommunication = null)
    {
        $this->uRIUniversalCommunication = $uRIUniversalCommunication;
        return $this;
    }

    /**
     * Adds as specifiedTaxRegistration
     *
     * Tax Registration
     *
     * @return self
     * @param  \horstoeko\orderx\entities\extended\ram\TaxRegistrationType $specifiedTaxRegistration
     */
    public function addToSpecifiedTaxRegistration(\horstoeko\orderx\entities\extended\ram\TaxRegistrationType $specifiedTaxRegistration)
    {
        $this->specifiedTaxRegistration[] = $specifiedTaxRegistration;
        return $this;
    }

    /**
     * isset specifiedTaxRegistration
     *
     * Tax Registration
     *
     * @param  int|string $index
     * @return bool
     */
    public function issetSpecifiedTaxRegistration($index)
    {
        return isset($this->specifiedTaxRegistration[$index]);
    }

    /**
     * unset specifiedTaxRegistration
     *
     * Tax Registration
     *
     * @param  int|string $index
     * @return void
     */
    public function unsetSpecifiedTaxRegistration($index)
    {
        unset($this->specifiedTaxRegistration[$index]);
    }

    /**
     * Gets as specifiedTaxRegistration
     *
     * Tax Registration
     *
     * @return \horstoeko\orderx\entities\extended\ram\TaxRegistrationType[]
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
     * @param  \horstoeko\orderx\entities\extended\ram\TaxRegistrationType[] $specifiedTaxRegistration
     * @return self
     */
    public function setSpecifiedTaxRegistration(array $specifiedTaxRegistration = null)
    {
        $this->specifiedTaxRegistration = $specifiedTaxRegistration;
        return $this;
    }
}
