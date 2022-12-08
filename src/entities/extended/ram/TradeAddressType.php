<?php

namespace horstoeko\orderx\entities\extended\ram;

/**
 * Class representing TradeAddressType
 *
 * Trade Address
 * XSD Type: TradeAddressType
 */
class TradeAddressType
{

    /**
     * Postcode
     *
     * @var \horstoeko\orderx\entities\extended\udt\CodeType $postcodeCode
     */
    private $postcodeCode = null;

    /**
     * Line One
     *
     * @var string $lineOne
     */
    private $lineOne = null;

    /**
     * Line Two
     *
     * @var string $lineTwo
     */
    private $lineTwo = null;

    /**
     * Line Three
     *
     * @var string $lineThree
     */
    private $lineThree = null;

    /**
     * City Name
     *
     * @var string $cityName
     */
    private $cityName = null;

    /**
     * Country Code
     *
     * @var string $countryID
     */
    private $countryID = null;

    /**
     * Country Sub-Division Name
     *
     * @var string $countrySubDivisionName
     */
    private $countrySubDivisionName = null;

    /**
     * Gets as postcodeCode
     *
     * Postcode
     *
     * @return \horstoeko\orderx\entities\extended\udt\CodeType
     */
    public function getPostcodeCode()
    {
        return $this->postcodeCode;
    }

    /**
     * Sets a new postcodeCode
     *
     * Postcode
     *
     * @param \horstoeko\orderx\entities\extended\udt\CodeType $postcodeCode
     * @return self
     */
    public function setPostcodeCode(?\horstoeko\orderx\entities\extended\udt\CodeType $postcodeCode = null)
    {
        $this->postcodeCode = $postcodeCode;
        return $this;
    }

    /**
     * Gets as lineOne
     *
     * Line One
     *
     * @return string
     */
    public function getLineOne()
    {
        return $this->lineOne;
    }

    /**
     * Sets a new lineOne
     *
     * Line One
     *
     * @param string $lineOne
     * @return self
     */
    public function setLineOne($lineOne)
    {
        $this->lineOne = $lineOne;
        return $this;
    }

    /**
     * Gets as lineTwo
     *
     * Line Two
     *
     * @return string
     */
    public function getLineTwo()
    {
        return $this->lineTwo;
    }

    /**
     * Sets a new lineTwo
     *
     * Line Two
     *
     * @param string $lineTwo
     * @return self
     */
    public function setLineTwo($lineTwo)
    {
        $this->lineTwo = $lineTwo;
        return $this;
    }

    /**
     * Gets as lineThree
     *
     * Line Three
     *
     * @return string
     */
    public function getLineThree()
    {
        return $this->lineThree;
    }

    /**
     * Sets a new lineThree
     *
     * Line Three
     *
     * @param string $lineThree
     * @return self
     */
    public function setLineThree($lineThree)
    {
        $this->lineThree = $lineThree;
        return $this;
    }

    /**
     * Gets as cityName
     *
     * City Name
     *
     * @return string
     */
    public function getCityName()
    {
        return $this->cityName;
    }

    /**
     * Sets a new cityName
     *
     * City Name
     *
     * @param string $cityName
     * @return self
     */
    public function setCityName($cityName)
    {
        $this->cityName = $cityName;
        return $this;
    }

    /**
     * Gets as countryID
     *
     * Country Code
     *
     * @return string
     */
    public function getCountryID()
    {
        return $this->countryID;
    }

    /**
     * Sets a new countryID
     *
     * Country Code
     *
     * @param string $countryID
     * @return self
     */
    public function setCountryID($countryID)
    {
        $this->countryID = $countryID;
        return $this;
    }

    /**
     * Gets as countrySubDivisionName
     *
     * Country Sub-Division Name
     *
     * @return string
     */
    public function getCountrySubDivisionName()
    {
        return $this->countrySubDivisionName;
    }

    /**
     * Sets a new countrySubDivisionName
     *
     * Country Sub-Division Name
     *
     * @param string $countrySubDivisionName
     * @return self
     */
    public function setCountrySubDivisionName($countrySubDivisionName)
    {
        $this->countrySubDivisionName = $countrySubDivisionName;
        return $this;
    }


}

