<?php

namespace horstoeko\orderx\entities\basic\ram;

/**
 * Class representing ExchangedDocumentContextType
 *
 * Exchanged Document Context
 * XSD Type: ExchangedDocumentContextType
 */
class ExchangedDocumentContextType
{

    /**
     * Test Indicator
     *
     * @var \horstoeko\orderx\entities\basic\udt\IndicatorType $testIndicator
     */
    private $testIndicator = null;

    /**
     * Business Process
     *
     * @var \horstoeko\orderx\entities\basic\ram\DocumentContextParameterType $businessProcessSpecifiedDocumentContextParameter
     */
    private $businessProcessSpecifiedDocumentContextParameter = null;

    /**
     * Guideline
     *
     * @var \horstoeko\orderx\entities\basic\ram\DocumentContextParameterType $guidelineSpecifiedDocumentContextParameter
     */
    private $guidelineSpecifiedDocumentContextParameter = null;

    /**
     * Gets as testIndicator
     *
     * Test Indicator
     *
     * @return \horstoeko\orderx\entities\basic\udt\IndicatorType
     */
    public function getTestIndicator()
    {
        return $this->testIndicator;
    }

    /**
     * Sets a new testIndicator
     *
     * Test Indicator
     *
     * @param  \horstoeko\orderx\entities\basic\udt\IndicatorType $testIndicator
     * @return self
     */
    public function setTestIndicator(?\horstoeko\orderx\entities\basic\udt\IndicatorType $testIndicator = null)
    {
        $this->testIndicator = $testIndicator;
        return $this;
    }

    /**
     * Gets as businessProcessSpecifiedDocumentContextParameter
     *
     * Business Process
     *
     * @return \horstoeko\orderx\entities\basic\ram\DocumentContextParameterType
     */
    public function getBusinessProcessSpecifiedDocumentContextParameter()
    {
        return $this->businessProcessSpecifiedDocumentContextParameter;
    }

    /**
     * Sets a new businessProcessSpecifiedDocumentContextParameter
     *
     * Business Process
     *
     * @param  \horstoeko\orderx\entities\basic\ram\DocumentContextParameterType $businessProcessSpecifiedDocumentContextParameter
     * @return self
     */
    public function setBusinessProcessSpecifiedDocumentContextParameter(?\horstoeko\orderx\entities\basic\ram\DocumentContextParameterType $businessProcessSpecifiedDocumentContextParameter = null)
    {
        $this->businessProcessSpecifiedDocumentContextParameter = $businessProcessSpecifiedDocumentContextParameter;
        return $this;
    }

    /**
     * Gets as guidelineSpecifiedDocumentContextParameter
     *
     * Guideline
     *
     * @return \horstoeko\orderx\entities\basic\ram\DocumentContextParameterType
     */
    public function getGuidelineSpecifiedDocumentContextParameter()
    {
        return $this->guidelineSpecifiedDocumentContextParameter;
    }

    /**
     * Sets a new guidelineSpecifiedDocumentContextParameter
     *
     * Guideline
     *
     * @param  \horstoeko\orderx\entities\basic\ram\DocumentContextParameterType $guidelineSpecifiedDocumentContextParameter
     * @return self
     */
    public function setGuidelineSpecifiedDocumentContextParameter(\horstoeko\orderx\entities\basic\ram\DocumentContextParameterType $guidelineSpecifiedDocumentContextParameter)
    {
        $this->guidelineSpecifiedDocumentContextParameter = $guidelineSpecifiedDocumentContextParameter;
        return $this;
    }
}
