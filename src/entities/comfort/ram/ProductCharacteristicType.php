<?php

namespace horstoeko\orderx\entities\comfort\ram;

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
     * @var \horstoeko\orderx\entities\comfort\udt\CodeType $typeCode
     */
    private $typeCode = null;

    /**
     * Description
     *
     * @var string $description
     */
    private $description = null;

    /**
     * Value Text
     *
     * @var string $value
     */
    private $value = null;

    /**
     * Gets as typeCode
     *
     * Type Code
     *
     * @return \horstoeko\orderx\entities\comfort\udt\CodeType
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
     * @param \horstoeko\orderx\entities\comfort\udt\CodeType $typeCode
     * @return self
     */
    public function setTypeCode(?\horstoeko\orderx\entities\comfort\udt\CodeType $typeCode = null)
    {
        $this->typeCode = $typeCode;
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
     * @param string $description
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Gets as value
     *
     * Value Text
     *
     * @return string
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
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }


}

