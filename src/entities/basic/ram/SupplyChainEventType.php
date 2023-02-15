<?php

namespace horstoeko\orderx\entities\basic\ram;

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
     * @var \horstoeko\orderx\entities\basic\udt\DateTimeType $occurrenceDateTime
     */
    private $occurrenceDateTime = null;

    /**
     * Occurrence Period
     *
     * @var \horstoeko\orderx\entities\basic\ram\SpecifiedPeriodType $occurrenceSpecifiedPeriod
     */
    private $occurrenceSpecifiedPeriod = null;

    /**
     * Gets as occurrenceDateTime
     *
     * Occurrence Date Time
     *
     * @return \horstoeko\orderx\entities\basic\udt\DateTimeType
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
     * @param  \horstoeko\orderx\entities\basic\udt\DateTimeType $occurrenceDateTime
     * @return self
     */
    public function setOccurrenceDateTime(?\horstoeko\orderx\entities\basic\udt\DateTimeType $occurrenceDateTime = null)
    {
        $this->occurrenceDateTime = $occurrenceDateTime;
        return $this;
    }

    /**
     * Gets as occurrenceSpecifiedPeriod
     *
     * Occurrence Period
     *
     * @return \horstoeko\orderx\entities\basic\ram\SpecifiedPeriodType
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
     * @param  \horstoeko\orderx\entities\basic\ram\SpecifiedPeriodType $occurrenceSpecifiedPeriod
     * @return self
     */
    public function setOccurrenceSpecifiedPeriod(?\horstoeko\orderx\entities\basic\ram\SpecifiedPeriodType $occurrenceSpecifiedPeriod = null)
    {
        $this->occurrenceSpecifiedPeriod = $occurrenceSpecifiedPeriod;
        return $this;
    }
}
