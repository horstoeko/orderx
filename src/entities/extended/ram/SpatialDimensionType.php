<?php

namespace horstoeko\orderx\entities\extended\ram;

/**
 * Class representing SpatialDimensionType
 *
 * Spatial Dimensions
 * XSD Type: SpatialDimensionType
 */
class SpatialDimensionType
{

    /**
     * Width
     *
     * @var \horstoeko\orderx\entities\extended\udt\MeasureType $widthMeasure
     */
    private $widthMeasure = null;

    /**
     * Length
     *
     * @var \horstoeko\orderx\entities\extended\udt\MeasureType $lengthMeasure
     */
    private $lengthMeasure = null;

    /**
     * Height
     *
     * @var \horstoeko\orderx\entities\extended\udt\MeasureType $heightMeasure
     */
    private $heightMeasure = null;

    /**
     * Gets as widthMeasure
     *
     * Width
     *
     * @return \horstoeko\orderx\entities\extended\udt\MeasureType
     */
    public function getWidthMeasure()
    {
        return $this->widthMeasure;
    }

    /**
     * Sets a new widthMeasure
     *
     * Width
     *
     * @param  \horstoeko\orderx\entities\extended\udt\MeasureType $widthMeasure
     * @return self
     */
    public function setWidthMeasure(?\horstoeko\orderx\entities\extended\udt\MeasureType $widthMeasure = null)
    {
        $this->widthMeasure = $widthMeasure;
        return $this;
    }

    /**
     * Gets as lengthMeasure
     *
     * Length
     *
     * @return \horstoeko\orderx\entities\extended\udt\MeasureType
     */
    public function getLengthMeasure()
    {
        return $this->lengthMeasure;
    }

    /**
     * Sets a new lengthMeasure
     *
     * Length
     *
     * @param  \horstoeko\orderx\entities\extended\udt\MeasureType $lengthMeasure
     * @return self
     */
    public function setLengthMeasure(?\horstoeko\orderx\entities\extended\udt\MeasureType $lengthMeasure = null)
    {
        $this->lengthMeasure = $lengthMeasure;
        return $this;
    }

    /**
     * Gets as heightMeasure
     *
     * Height
     *
     * @return \horstoeko\orderx\entities\extended\udt\MeasureType
     */
    public function getHeightMeasure()
    {
        return $this->heightMeasure;
    }

    /**
     * Sets a new heightMeasure
     *
     * Height
     *
     * @param  \horstoeko\orderx\entities\extended\udt\MeasureType $heightMeasure
     * @return self
     */
    public function setHeightMeasure(?\horstoeko\orderx\entities\extended\udt\MeasureType $heightMeasure = null)
    {
        $this->heightMeasure = $heightMeasure;
        return $this;
    }
}
