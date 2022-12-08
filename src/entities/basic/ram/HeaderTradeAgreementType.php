<?php

namespace horstoeko\orderx\entities\basic\ram;

/**
 * Class representing HeaderTradeAgreementType
 *
 * Header Trade Agreement
 * XSD Type: HeaderTradeAgreementType
 */
class HeaderTradeAgreementType
{

    /**
     * Buyer Reference Text
     *
     * @var string $buyerReference
     */
    private $buyerReference = null;

    /**
     * Seller
     *
     * @var \horstoeko\orderx\entities\basic\ram\TradePartyType $sellerTradeParty
     */
    private $sellerTradeParty = null;

    /**
     * Buyer
     *
     * @var \horstoeko\orderx\entities\basic\ram\TradePartyType $buyerTradeParty
     */
    private $buyerTradeParty = null;

    /**
     * Trade Delivery Terms
     *
     * @var \horstoeko\orderx\entities\basic\ram\TradeDeliveryTermsType $applicableTradeDeliveryTerms
     */
    private $applicableTradeDeliveryTerms = null;

    /**
     * Buyer Order Document
     *
     * @var \horstoeko\orderx\entities\basic\ram\ReferencedDocumentType $buyerOrderReferencedDocument
     */
    private $buyerOrderReferencedDocument = null;

    /**
     * Quotation Document
     *
     * @var \horstoeko\orderx\entities\basic\ram\ReferencedDocumentType $quotationReferencedDocument
     */
    private $quotationReferencedDocument = null;

    /**
     * Contract Document
     *
     * @var \horstoeko\orderx\entities\basic\ram\ReferencedDocumentType $contractReferencedDocument
     */
    private $contractReferencedDocument = null;

    /**
     * Blanket Order Document
     *
     * @var \horstoeko\orderx\entities\basic\ram\ReferencedDocumentType $blanketOrderReferencedDocument
     */
    private $blanketOrderReferencedDocument = null;

    /**
     * Previous Order Document
     *
     * @var \horstoeko\orderx\entities\basic\ram\ReferencedDocumentType $previousOrderReferencedDocument
     */
    private $previousOrderReferencedDocument = null;

    /**
     * Previous Order Change Document
     *
     * @var \horstoeko\orderx\entities\basic\ram\ReferencedDocumentType $previousOrderChangeReferencedDocument
     */
    private $previousOrderChangeReferencedDocument = null;

    /**
     * Previous Order Response Document
     *
     * @var \horstoeko\orderx\entities\basic\ram\ReferencedDocumentType $previousOrderResponseReferencedDocument
     */
    private $previousOrderResponseReferencedDocument = null;

    /**
     * Gets as buyerReference
     *
     * Buyer Reference Text
     *
     * @return string
     */
    public function getBuyerReference()
    {
        return $this->buyerReference;
    }

    /**
     * Sets a new buyerReference
     *
     * Buyer Reference Text
     *
     * @param string $buyerReference
     * @return self
     */
    public function setBuyerReference($buyerReference)
    {
        $this->buyerReference = $buyerReference;
        return $this;
    }

    /**
     * Gets as sellerTradeParty
     *
     * Seller
     *
     * @return \horstoeko\orderx\entities\basic\ram\TradePartyType
     */
    public function getSellerTradeParty()
    {
        return $this->sellerTradeParty;
    }

    /**
     * Sets a new sellerTradeParty
     *
     * Seller
     *
     * @param \horstoeko\orderx\entities\basic\ram\TradePartyType $sellerTradeParty
     * @return self
     */
    public function setSellerTradeParty(\horstoeko\orderx\entities\basic\ram\TradePartyType $sellerTradeParty)
    {
        $this->sellerTradeParty = $sellerTradeParty;
        return $this;
    }

    /**
     * Gets as buyerTradeParty
     *
     * Buyer
     *
     * @return \horstoeko\orderx\entities\basic\ram\TradePartyType
     */
    public function getBuyerTradeParty()
    {
        return $this->buyerTradeParty;
    }

    /**
     * Sets a new buyerTradeParty
     *
     * Buyer
     *
     * @param \horstoeko\orderx\entities\basic\ram\TradePartyType $buyerTradeParty
     * @return self
     */
    public function setBuyerTradeParty(\horstoeko\orderx\entities\basic\ram\TradePartyType $buyerTradeParty)
    {
        $this->buyerTradeParty = $buyerTradeParty;
        return $this;
    }

    /**
     * Gets as applicableTradeDeliveryTerms
     *
     * Trade Delivery Terms
     *
     * @return \horstoeko\orderx\entities\basic\ram\TradeDeliveryTermsType
     */
    public function getApplicableTradeDeliveryTerms()
    {
        return $this->applicableTradeDeliveryTerms;
    }

    /**
     * Sets a new applicableTradeDeliveryTerms
     *
     * Trade Delivery Terms
     *
     * @param \horstoeko\orderx\entities\basic\ram\TradeDeliveryTermsType $applicableTradeDeliveryTerms
     * @return self
     */
    public function setApplicableTradeDeliveryTerms(?\horstoeko\orderx\entities\basic\ram\TradeDeliveryTermsType $applicableTradeDeliveryTerms = null)
    {
        $this->applicableTradeDeliveryTerms = $applicableTradeDeliveryTerms;
        return $this;
    }

    /**
     * Gets as buyerOrderReferencedDocument
     *
     * Buyer Order Document
     *
     * @return \horstoeko\orderx\entities\basic\ram\ReferencedDocumentType
     */
    public function getBuyerOrderReferencedDocument()
    {
        return $this->buyerOrderReferencedDocument;
    }

    /**
     * Sets a new buyerOrderReferencedDocument
     *
     * Buyer Order Document
     *
     * @param \horstoeko\orderx\entities\basic\ram\ReferencedDocumentType $buyerOrderReferencedDocument
     * @return self
     */
    public function setBuyerOrderReferencedDocument(?\horstoeko\orderx\entities\basic\ram\ReferencedDocumentType $buyerOrderReferencedDocument = null)
    {
        $this->buyerOrderReferencedDocument = $buyerOrderReferencedDocument;
        return $this;
    }

    /**
     * Gets as quotationReferencedDocument
     *
     * Quotation Document
     *
     * @return \horstoeko\orderx\entities\basic\ram\ReferencedDocumentType
     */
    public function getQuotationReferencedDocument()
    {
        return $this->quotationReferencedDocument;
    }

    /**
     * Sets a new quotationReferencedDocument
     *
     * Quotation Document
     *
     * @param \horstoeko\orderx\entities\basic\ram\ReferencedDocumentType $quotationReferencedDocument
     * @return self
     */
    public function setQuotationReferencedDocument(?\horstoeko\orderx\entities\basic\ram\ReferencedDocumentType $quotationReferencedDocument = null)
    {
        $this->quotationReferencedDocument = $quotationReferencedDocument;
        return $this;
    }

    /**
     * Gets as contractReferencedDocument
     *
     * Contract Document
     *
     * @return \horstoeko\orderx\entities\basic\ram\ReferencedDocumentType
     */
    public function getContractReferencedDocument()
    {
        return $this->contractReferencedDocument;
    }

    /**
     * Sets a new contractReferencedDocument
     *
     * Contract Document
     *
     * @param \horstoeko\orderx\entities\basic\ram\ReferencedDocumentType $contractReferencedDocument
     * @return self
     */
    public function setContractReferencedDocument(?\horstoeko\orderx\entities\basic\ram\ReferencedDocumentType $contractReferencedDocument = null)
    {
        $this->contractReferencedDocument = $contractReferencedDocument;
        return $this;
    }

    /**
     * Gets as blanketOrderReferencedDocument
     *
     * Blanket Order Document
     *
     * @return \horstoeko\orderx\entities\basic\ram\ReferencedDocumentType
     */
    public function getBlanketOrderReferencedDocument()
    {
        return $this->blanketOrderReferencedDocument;
    }

    /**
     * Sets a new blanketOrderReferencedDocument
     *
     * Blanket Order Document
     *
     * @param \horstoeko\orderx\entities\basic\ram\ReferencedDocumentType $blanketOrderReferencedDocument
     * @return self
     */
    public function setBlanketOrderReferencedDocument(?\horstoeko\orderx\entities\basic\ram\ReferencedDocumentType $blanketOrderReferencedDocument = null)
    {
        $this->blanketOrderReferencedDocument = $blanketOrderReferencedDocument;
        return $this;
    }

    /**
     * Gets as previousOrderReferencedDocument
     *
     * Previous Order Document
     *
     * @return \horstoeko\orderx\entities\basic\ram\ReferencedDocumentType
     */
    public function getPreviousOrderReferencedDocument()
    {
        return $this->previousOrderReferencedDocument;
    }

    /**
     * Sets a new previousOrderReferencedDocument
     *
     * Previous Order Document
     *
     * @param \horstoeko\orderx\entities\basic\ram\ReferencedDocumentType $previousOrderReferencedDocument
     * @return self
     */
    public function setPreviousOrderReferencedDocument(?\horstoeko\orderx\entities\basic\ram\ReferencedDocumentType $previousOrderReferencedDocument = null)
    {
        $this->previousOrderReferencedDocument = $previousOrderReferencedDocument;
        return $this;
    }

    /**
     * Gets as previousOrderChangeReferencedDocument
     *
     * Previous Order Change Document
     *
     * @return \horstoeko\orderx\entities\basic\ram\ReferencedDocumentType
     */
    public function getPreviousOrderChangeReferencedDocument()
    {
        return $this->previousOrderChangeReferencedDocument;
    }

    /**
     * Sets a new previousOrderChangeReferencedDocument
     *
     * Previous Order Change Document
     *
     * @param \horstoeko\orderx\entities\basic\ram\ReferencedDocumentType $previousOrderChangeReferencedDocument
     * @return self
     */
    public function setPreviousOrderChangeReferencedDocument(?\horstoeko\orderx\entities\basic\ram\ReferencedDocumentType $previousOrderChangeReferencedDocument = null)
    {
        $this->previousOrderChangeReferencedDocument = $previousOrderChangeReferencedDocument;
        return $this;
    }

    /**
     * Gets as previousOrderResponseReferencedDocument
     *
     * Previous Order Response Document
     *
     * @return \horstoeko\orderx\entities\basic\ram\ReferencedDocumentType
     */
    public function getPreviousOrderResponseReferencedDocument()
    {
        return $this->previousOrderResponseReferencedDocument;
    }

    /**
     * Sets a new previousOrderResponseReferencedDocument
     *
     * Previous Order Response Document
     *
     * @param \horstoeko\orderx\entities\basic\ram\ReferencedDocumentType $previousOrderResponseReferencedDocument
     * @return self
     */
    public function setPreviousOrderResponseReferencedDocument(?\horstoeko\orderx\entities\basic\ram\ReferencedDocumentType $previousOrderResponseReferencedDocument = null)
    {
        $this->previousOrderResponseReferencedDocument = $previousOrderResponseReferencedDocument;
        return $this;
    }


}

