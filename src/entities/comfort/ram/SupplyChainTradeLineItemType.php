<?php

namespace horstoeko\orderx\entities\comfort\ram;

/**
 * Class representing SupplyChainTradeLineItemType
 *
 * Supply Chain Trade Line Item
 * XSD Type: SupplyChainTradeLineItemType
 */
class SupplyChainTradeLineItemType
{

    /**
     * Associated Document Line
     *
     * @var \horstoeko\orderx\entities\comfort\ram\DocumentLineDocumentType $associatedDocumentLineDocument
     */
    private $associatedDocumentLineDocument = null;

    /**
     * Trade Product
     *
     * @var \horstoeko\orderx\entities\comfort\ram\TradeProductType $specifiedTradeProduct
     */
    private $specifiedTradeProduct = null;

    /**
     * Substituted Product
     *
     * @var \horstoeko\orderx\entities\comfort\ram\ReferencedProductType $substitutedReferencedProduct
     */
    private $substitutedReferencedProduct = null;

    /**
     * Line Trade Agreement
     *
     * @var \horstoeko\orderx\entities\comfort\ram\LineTradeAgreementType $specifiedLineTradeAgreement
     */
    private $specifiedLineTradeAgreement = null;

    /**
     * Line Trade Delivery
     *
     * @var \horstoeko\orderx\entities\comfort\ram\LineTradeDeliveryType $specifiedLineTradeDelivery
     */
    private $specifiedLineTradeDelivery = null;

    /**
     * Line Trade Settlement
     *
     * @var \horstoeko\orderx\entities\comfort\ram\LineTradeSettlementType $specifiedLineTradeSettlement
     */
    private $specifiedLineTradeSettlement = null;

    /**
     * Gets as associatedDocumentLineDocument
     *
     * Associated Document Line
     *
     * @return \horstoeko\orderx\entities\comfort\ram\DocumentLineDocumentType
     */
    public function getAssociatedDocumentLineDocument()
    {
        return $this->associatedDocumentLineDocument;
    }

    /**
     * Sets a new associatedDocumentLineDocument
     *
     * Associated Document Line
     *
     * @param  \horstoeko\orderx\entities\comfort\ram\DocumentLineDocumentType $associatedDocumentLineDocument
     * @return self
     */
    public function setAssociatedDocumentLineDocument(\horstoeko\orderx\entities\comfort\ram\DocumentLineDocumentType $associatedDocumentLineDocument)
    {
        $this->associatedDocumentLineDocument = $associatedDocumentLineDocument;
        return $this;
    }

    /**
     * Gets as specifiedTradeProduct
     *
     * Trade Product
     *
     * @return \horstoeko\orderx\entities\comfort\ram\TradeProductType
     */
    public function getSpecifiedTradeProduct()
    {
        return $this->specifiedTradeProduct;
    }

    /**
     * Sets a new specifiedTradeProduct
     *
     * Trade Product
     *
     * @param  \horstoeko\orderx\entities\comfort\ram\TradeProductType $specifiedTradeProduct
     * @return self
     */
    public function setSpecifiedTradeProduct(?\horstoeko\orderx\entities\comfort\ram\TradeProductType $specifiedTradeProduct = null)
    {
        $this->specifiedTradeProduct = $specifiedTradeProduct;
        return $this;
    }

    /**
     * Gets as substitutedReferencedProduct
     *
     * Substituted Product
     *
     * @return \horstoeko\orderx\entities\comfort\ram\ReferencedProductType
     */
    public function getSubstitutedReferencedProduct()
    {
        return $this->substitutedReferencedProduct;
    }

    /**
     * Sets a new substitutedReferencedProduct
     *
     * Substituted Product
     *
     * @param  \horstoeko\orderx\entities\comfort\ram\ReferencedProductType $substitutedReferencedProduct
     * @return self
     */
    public function setSubstitutedReferencedProduct(?\horstoeko\orderx\entities\comfort\ram\ReferencedProductType $substitutedReferencedProduct = null)
    {
        $this->substitutedReferencedProduct = $substitutedReferencedProduct;
        return $this;
    }

    /**
     * Gets as specifiedLineTradeAgreement
     *
     * Line Trade Agreement
     *
     * @return \horstoeko\orderx\entities\comfort\ram\LineTradeAgreementType
     */
    public function getSpecifiedLineTradeAgreement()
    {
        return $this->specifiedLineTradeAgreement;
    }

    /**
     * Sets a new specifiedLineTradeAgreement
     *
     * Line Trade Agreement
     *
     * @param  \horstoeko\orderx\entities\comfort\ram\LineTradeAgreementType $specifiedLineTradeAgreement
     * @return self
     */
    public function setSpecifiedLineTradeAgreement(\horstoeko\orderx\entities\comfort\ram\LineTradeAgreementType $specifiedLineTradeAgreement)
    {
        $this->specifiedLineTradeAgreement = $specifiedLineTradeAgreement;
        return $this;
    }

    /**
     * Gets as specifiedLineTradeDelivery
     *
     * Line Trade Delivery
     *
     * @return \horstoeko\orderx\entities\comfort\ram\LineTradeDeliveryType
     */
    public function getSpecifiedLineTradeDelivery()
    {
        return $this->specifiedLineTradeDelivery;
    }

    /**
     * Sets a new specifiedLineTradeDelivery
     *
     * Line Trade Delivery
     *
     * @param  \horstoeko\orderx\entities\comfort\ram\LineTradeDeliveryType $specifiedLineTradeDelivery
     * @return self
     */
    public function setSpecifiedLineTradeDelivery(\horstoeko\orderx\entities\comfort\ram\LineTradeDeliveryType $specifiedLineTradeDelivery)
    {
        $this->specifiedLineTradeDelivery = $specifiedLineTradeDelivery;
        return $this;
    }

    /**
     * Gets as specifiedLineTradeSettlement
     *
     * Line Trade Settlement
     *
     * @return \horstoeko\orderx\entities\comfort\ram\LineTradeSettlementType
     */
    public function getSpecifiedLineTradeSettlement()
    {
        return $this->specifiedLineTradeSettlement;
    }

    /**
     * Sets a new specifiedLineTradeSettlement
     *
     * Line Trade Settlement
     *
     * @param  \horstoeko\orderx\entities\comfort\ram\LineTradeSettlementType $specifiedLineTradeSettlement
     * @return self
     */
    public function setSpecifiedLineTradeSettlement(\horstoeko\orderx\entities\comfort\ram\LineTradeSettlementType $specifiedLineTradeSettlement)
    {
        $this->specifiedLineTradeSettlement = $specifiedLineTradeSettlement;
        return $this;
    }
}
