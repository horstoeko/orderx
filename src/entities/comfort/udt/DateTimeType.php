<?php

namespace horstoeko\orderx\entities\comfort\udt;

/**
 * Class representing DateTimeType
 *
 * XSD Type: DateTimeType
 */
class DateTimeType
{

    /**
     * @var \horstoeko\orderx\entities\comfort\udt\DateTimeType\DateTimeStringAType $dateTimeString
     */
    private $dateTimeString = null;

    /**
     * @var \DateTime $dateTime
     */
    private $dateTime = null;

    /**
     * Gets as dateTimeString
     *
     * @return \horstoeko\orderx\entities\comfort\udt\DateTimeType\DateTimeStringAType
     */
    public function getDateTimeString()
    {
        return $this->dateTimeString;
    }

    /**
     * Sets a new dateTimeString
     *
     * @param  \horstoeko\orderx\entities\comfort\udt\DateTimeType\DateTimeStringAType $dateTimeString
     * @return self
     */
    public function setDateTimeString(?\horstoeko\orderx\entities\comfort\udt\DateTimeType\DateTimeStringAType $dateTimeString = null)
    {
        $this->dateTimeString = $dateTimeString;
        return $this;
    }

    /**
     * Gets as dateTime
     *
     * @return \DateTime
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * Sets a new dateTime
     *
     * @param  \DateTime $dateTime
     * @return self
     */
    public function setDateTime(?\DateTime $dateTime = null)
    {
        $this->dateTime = $dateTime;
        return $this;
    }
}
