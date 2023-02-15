<?php

namespace horstoeko\orderx\entities\extended\ram;

/**
 * Class representing SupplyChainPackagingType
 *
 * Supply Chain Packaging
 * XSD Type: SupplyChainPackagingType
 */
class SupplyChainPackagingType
{

    /**
     * Type Code
     *
     * @var string $typeCode
     */
    private $typeCode = null;

    /**
     * Dimensions
     *
     * @var \horstoeko\orderx\entities\extended\ram\SpatialDimensionType $linearSpatialDimension
     */
    private $linearSpatialDimension = null;

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
     * @param  string $typeCode
     * @return self
     */
    public function setTypeCode($typeCode)
    {
        $this->typeCode = $typeCode;
        return $this;
    }

    /**
     * Gets as linearSpatialDimension
     *
     * Dimensions
     *
     * @return \horstoeko\orderx\entities\extended\ram\SpatialDimensionType
     */
    public function getLinearSpatialDimension()
    {
        return $this->linearSpatialDimension;
    }

    /**
     * Sets a new linearSpatialDimension
     *
     * Dimensions
     *
     * @param  \horstoeko\orderx\entities\extended\ram\SpatialDimensionType $linearSpatialDimension
     * @return self
     */
    public function setLinearSpatialDimension(?\horstoeko\orderx\entities\extended\ram\SpatialDimensionType $linearSpatialDimension = null)
    {
        $this->linearSpatialDimension = $linearSpatialDimension;
        return $this;
    }
}
