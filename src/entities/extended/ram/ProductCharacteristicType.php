<?php

namespace horstoeko\orderx\entities\extended\ram;

/**
 * Class representing ProductCharacteristicType
 *
 * Product Characteristic
 * XSD Type: ProductCharacteristicType
 */
class ProductCharacteristicType
{

    /**
     * Type Code
     *
     * @var \horstoeko\orderx\entities\extended\udt\CodeType $typeCode
     */
    private $typeCode = null;

    /**
     * Description
     *
     * @var string[] $description
     */
    private $description = [
        
    ];

    /**
     * Value Measure
     *
     * @var \horstoeko\orderx\entities\extended\udt\MeasureType $valueMeasure
     */
    private $valueMeasure = null;

    /**
     * Value Text
     *
     * @var string[] $value
     */
    private $value = [
        
    ];

    /**
     * Gets as typeCode
     *
     * Type Code
     *
     * @return \horstoeko\orderx\entities\extended\udt\CodeType
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
     * @param \horstoeko\orderx\entities\extended\udt\CodeType $typeCode
     * @return self
     */
    public function setTypeCode(?\horstoeko\orderx\entities\extended\udt\CodeType $typeCode = null)
    {
        $this->typeCode = $typeCode;
        return $this;
    }

    /**
     * Adds as description
     *
     * Description
     *
     * @return self
     * @param string $description
     */
    public function addToDescription($description)
    {
        $this->description[] = $description;
        return $this;
    }

    /**
     * isset description
     *
     * Description
     *
     * @param int|string $index
     * @return bool
     */
    public function issetDescription($index)
    {
        return isset($this->description[$index]);
    }

    /**
     * unset description
     *
     * Description
     *
     * @param int|string $index
     * @return void
     */
    public function unsetDescription($index)
    {
        unset($this->description[$index]);
    }

    /**
     * Gets as description
     *
     * Description
     *
     * @return string[]
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
     * @param string $description
     * @return self
     */
    public function setDescription(array $description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Gets as valueMeasure
     *
     * Value Measure
     *
     * @return \horstoeko\orderx\entities\extended\udt\MeasureType
     */
    public function getValueMeasure()
    {
        return $this->valueMeasure;
    }

    /**
     * Sets a new valueMeasure
     *
     * Value Measure
     *
     * @param \horstoeko\orderx\entities\extended\udt\MeasureType $valueMeasure
     * @return self
     */
    public function setValueMeasure(?\horstoeko\orderx\entities\extended\udt\MeasureType $valueMeasure = null)
    {
        $this->valueMeasure = $valueMeasure;
        return $this;
    }

    /**
     * Adds as value
     *
     * Value Text
     *
     * @return self
     * @param string $value
     */
    public function addToValue($value)
    {
        $this->value[] = $value;
        return $this;
    }

    /**
     * isset value
     *
     * Value Text
     *
     * @param int|string $index
     * @return bool
     */
    public function issetValue($index)
    {
        return isset($this->value[$index]);
    }

    /**
     * unset value
     *
     * Value Text
     *
     * @param int|string $index
     * @return void
     */
    public function unsetValue($index)
    {
        unset($this->value[$index]);
    }

    /**
     * Gets as value
     *
     * Value Text
     *
     * @return string[]
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets a new value
     *
     * Value Text
     *
     * @param string $value
     * @return self
     */
    public function setValue(array $value)
    {
        $this->value = $value;
        return $this;
    }


}

