<?php

namespace horstoeko\orderx\entities\extended\ram;

/**
 * Class representing ProductClassificationType
 *
 * Product Classification
 * XSD Type: ProductClassificationType
 */
class ProductClassificationType
{

    /**
     * Class Code
     *
     * @var \horstoeko\orderx\entities\extended\udt\CodeType $classCode
     */
    private $classCode = null;

    /**
     * Class Name
     *
     * @var string $className
     */
    private $className = null;

    /**
     * Gets as classCode
     *
     * Class Code
     *
     * @return \horstoeko\orderx\entities\extended\udt\CodeType
     */
    public function getClassCode()
    {
        return $this->classCode;
    }

    /**
     * Sets a new classCode
     *
     * Class Code
     *
     * @param \horstoeko\orderx\entities\extended\udt\CodeType $classCode
     * @return self
     */
    public function setClassCode(\horstoeko\orderx\entities\extended\udt\CodeType $classCode)
    {
        $this->classCode = $classCode;
        return $this;
    }

    /**
     * Gets as className
     *
     * Class Name
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * Sets a new className
     *
     * Class Name
     *
     * @param string $className
     * @return self
     */
    public function setClassName($className)
    {
        $this->className = $className;
        return $this;
    }


}

