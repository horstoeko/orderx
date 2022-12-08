<?php

namespace horstoeko\orderx\entities\extended\ram;

/**
 * Class representing TradeContactType
 *
 * Trade Contact
 * XSD Type: TradeContactType
 */
class TradeContactType
{

    /**
     * Person Name
     *
     * @var string $personName
     */
    private $personName = null;

    /**
     * Department Name
     *
     * @var string $departmentName
     */
    private $departmentName = null;

    /**
     * Type Code
     *
     * @var string $typeCode
     */
    private $typeCode = null;

    /**
     * Telephone
     *
     * @var \horstoeko\orderx\entities\extended\ram\UniversalCommunicationType $telephoneUniversalCommunication
     */
    private $telephoneUniversalCommunication = null;

    /**
     * Fax
     *
     * @var \horstoeko\orderx\entities\extended\ram\UniversalCommunicationType $faxUniversalCommunication
     */
    private $faxUniversalCommunication = null;

    /**
     * Email Address
     *
     * @var \horstoeko\orderx\entities\extended\ram\UniversalCommunicationType $emailURIUniversalCommunication
     */
    private $emailURIUniversalCommunication = null;

    /**
     * Gets as personName
     *
     * Person Name
     *
     * @return string
     */
    public function getPersonName()
    {
        return $this->personName;
    }

    /**
     * Sets a new personName
     *
     * Person Name
     *
     * @param string $personName
     * @return self
     */
    public function setPersonName($personName)
    {
        $this->personName = $personName;
        return $this;
    }

    /**
     * Gets as departmentName
     *
     * Department Name
     *
     * @return string
     */
    public function getDepartmentName()
    {
        return $this->departmentName;
    }

    /**
     * Sets a new departmentName
     *
     * Department Name
     *
     * @param string $departmentName
     * @return self
     */
    public function setDepartmentName($departmentName)
    {
        $this->departmentName = $departmentName;
        return $this;
    }

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
     * Gets as telephoneUniversalCommunication
     *
     * Telephone
     *
     * @return \horstoeko\orderx\entities\extended\ram\UniversalCommunicationType
     */
    public function getTelephoneUniversalCommunication()
    {
        return $this->telephoneUniversalCommunication;
    }

    /**
     * Sets a new telephoneUniversalCommunication
     *
     * Telephone
     *
     * @param \horstoeko\orderx\entities\extended\ram\UniversalCommunicationType $telephoneUniversalCommunication
     * @return self
     */
    public function setTelephoneUniversalCommunication(?\horstoeko\orderx\entities\extended\ram\UniversalCommunicationType $telephoneUniversalCommunication = null)
    {
        $this->telephoneUniversalCommunication = $telephoneUniversalCommunication;
        return $this;
    }

    /**
     * Gets as faxUniversalCommunication
     *
     * Fax
     *
     * @return \horstoeko\orderx\entities\extended\ram\UniversalCommunicationType
     */
    public function getFaxUniversalCommunication()
    {
        return $this->faxUniversalCommunication;
    }

    /**
     * Sets a new faxUniversalCommunication
     *
     * Fax
     *
     * @param \horstoeko\orderx\entities\extended\ram\UniversalCommunicationType $faxUniversalCommunication
     * @return self
     */
    public function setFaxUniversalCommunication(?\horstoeko\orderx\entities\extended\ram\UniversalCommunicationType $faxUniversalCommunication = null)
    {
        $this->faxUniversalCommunication = $faxUniversalCommunication;
        return $this;
    }

    /**
     * Gets as emailURIUniversalCommunication
     *
     * Email Address
     *
     * @return \horstoeko\orderx\entities\extended\ram\UniversalCommunicationType
     */
    public function getEmailURIUniversalCommunication()
    {
        return $this->emailURIUniversalCommunication;
    }

    /**
     * Sets a new emailURIUniversalCommunication
     *
     * Email Address
     *
     * @param \horstoeko\orderx\entities\extended\ram\UniversalCommunicationType $emailURIUniversalCommunication
     * @return self
     */
    public function setEmailURIUniversalCommunication(?\horstoeko\orderx\entities\extended\ram\UniversalCommunicationType $emailURIUniversalCommunication = null)
    {
        $this->emailURIUniversalCommunication = $emailURIUniversalCommunication;
        return $this;
    }


}

