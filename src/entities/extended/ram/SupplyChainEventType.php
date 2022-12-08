<?php

namespace horstoeko\orderx\entities\extended\ram;

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
     * @var \horstoeko\orderx\entities\extended\udt\DateTimeType $occurrenceDateTime
     */
    private $occurrenceDateTime = null;

    /**
     * Unit Quantity
     *
     * @var \horstoeko\orderx\entities\extended\udt\QuantityType $unitQuantity
     */
    private $unitQuantity = null;

    /**
     * Occurrence Period
     *
     * @var \horstoeko\orderx\entities\extended\ram\SpecifiedPeriodType $occurrenceSpecifiedPeriod
     */
    private $occurrenceSpecifiedPeriod = null;

    /**
     * Gets as occurrenceDateTime
     *
     * Occurrence Date Time
     *
     * @return \horstoeko\orderx\entities\extended\udt\DateTimeType
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
     * @param \horstoeko\orderx\entities\extended\udt\DateTimeType $occurrenceDateTime
     * @return self
     */
    public function setOccurrenceDateTime(?\horstoeko\orderx\entities\extended\udt\DateTimeType $occurrenceDateTime = null)
    {
        $this->occurrenceDateTime = $occurrenceDateTime;
        return $this;
    }

    /**
     * Gets as unitQuantity
     *
     * Unit Quantity
     *
     * @return \horstoeko\orderx\entities\extended\udt\QuantityType
     */
    public function getUnitQuantity()
    {
        return $this->unitQuantity;
    }

    /**
     * Sets a new unitQuantity
     *
     * Unit Quantity
     *
     * @param \horstoeko\orderx\entities\extended\udt\QuantityType $unitQuantity
     * @return self
     */
    public function setUnitQuantity(?\horstoeko\orderx\entities\extended\udt\QuantityType $unitQuantity = null)
    {
        $this->unitQuantity = $unitQuantity;
        return $this;
    }

    /**
     * Gets as occurrenceSpecifiedPeriod
     *
     * Occurrence Period
     *
     * @return \horstoeko\orderx\entities\extended\ram\SpecifiedPeriodType
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
     * @param \horstoeko\orderx\entities\extended\ram\SpecifiedPeriodType $occurrenceSpecifiedPeriod
     * @return self
     */
    public function setOccurrenceSpecifiedPeriod(?\horstoeko\orderx\entities\extended\ram\SpecifiedPeriodType $occurrenceSpecifiedPeriod = null)
    {
        $this->occurrenceSpecifiedPeriod = $occurrenceSpecifiedPeriod;
        return $this;
    }


}

