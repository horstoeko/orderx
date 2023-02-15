<?php

namespace horstoeko\orderx\entities\comfort\ram;

/**
 * Class representing SupplyChainEventType
 *
 * Supply Chain Event
 * XSD Type: SupplyChainEventType
 */
class SupplyChainEventType
{

    /**
     * Occurrence Date Time
     *
     * @var \horstoeko\orderx\entities\comfort\udt\DateTimeType $occurrenceDateTime
     */
    private $occurrenceDateTime = null;

    /**
     * Occurrence Period
     *
     * @var \horstoeko\orderx\entities\comfort\ram\SpecifiedPeriodType $occurrenceSpecifiedPeriod
     */
    private $occurrenceSpecifiedPeriod = null;

    /**
     * Gets as occurrenceDateTime
     *
     * Occurrence Date Time
     *
     * @return \horstoeko\orderx\entities\comfort\udt\DateTimeType
     */
    public function getOccurrenceDateTime()
    {
        return $this->occurrenceDateTime;
    }

    /**
     * Sets a new occurrenceDateTime
     *
     * Occurrence Date Time
     *
     * @param  \horstoeko\orderx\entities\comfort\udt\DateTimeType $occurrenceDateTime
     * @return self
     */
    public function setOccurrenceDateTime(?\horstoeko\orderx\entities\comfort\udt\DateTimeType $occurrenceDateTime = null)
    {
        $this->occurrenceDateTime = $occurrenceDateTime;
        return $this;
    }

    /**
     * Gets as occurrenceSpecifiedPeriod
     *
     * Occurrence Period
     *
     * @return \horstoeko\orderx\entities\comfort\ram\SpecifiedPeriodType
     */
    public function getOccurrenceSpecifiedPeriod()
    {
        return $this->occurrenceSpecifiedPeriod;
    }

    /**
     * Sets a new occurrenceSpecifiedPeriod
     *
     * Occurrence Period
     *
     * @param  \horstoeko\orderx\entities\comfort\ram\SpecifiedPeriodType $occurrenceSpecifiedPeriod
     * @return self
     */
    public function setOccurrenceSpecifiedPeriod(?\horstoeko\orderx\entities\comfort\ram\SpecifiedPeriodType $occurrenceSpecifiedPeriod = null)
    {
        $this->occurrenceSpecifiedPeriod = $occurrenceSpecifiedPeriod;
        return $this;
    }
}
