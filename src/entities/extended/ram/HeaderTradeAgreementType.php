<?php

namespace horstoeko\orderx\entities\extended\ram;

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
     * @var \horstoeko\orderx\entities\extended\ram\TradePartyType $sellerTradeParty
     */
    private $sellerTradeParty = null;

    /**
     * Buyer
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradePartyType $buyerTradeParty
     */
    private $buyerTradeParty = null;

    /**
     * Buyer Requisitioner
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradePartyType $buyerRequisitionerTradeParty
     */
    private $buyerRequisitionerTradeParty = null;

    /**
     * End User
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradePartyType $productEndUserTradeParty
     */
    private $productEndUserTradeParty = null;

    /**
     * Trade Delivery Terms
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradeDeliveryTermsType $applicableTradeDeliveryTerms
     */
    private $applicableTradeDeliveryTerms = null;

    /**
     * Seller Order Document
     *
     * @var \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $sellerOrderReferencedDocument
     */
    private $sellerOrderReferencedDocument = null;

    /**
     * Buyer Order Document
     *
     * @var \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $buyerOrderReferencedDocument
     */
    private $buyerOrderReferencedDocument = null;

    /**
     * Quotation Document
     *
     * @var \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $quotationReferencedDocument
     */
    private $quotationReferencedDocument = null;

    /**
     * Contract Document
     *
     * @var \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $contractReferencedDocument
     */
    private $contractReferencedDocument = null;

    /**
     * Requisition Document
     *
     * @var \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $requisitionReferencedDocument
     */
    private $requisitionReferencedDocument = null;

    /**
     * Additional Document
     *
     * @var \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType[] $additionalReferencedDocument
     */
    private $additionalReferencedDocument = [
        
    ];

    /**
     * Buyer Agent
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradePartyType $buyerAgentTradeParty
     */
    private $buyerAgentTradeParty = null;

    /**
     * Catalogue Document
     *
     * @var \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $catalogueReferencedDocument
     */
    private $catalogueReferencedDocument = null;

    /**
     * Blanket Order Document
     *
     * @var \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $blanketOrderReferencedDocument
     */
    private $blanketOrderReferencedDocument = null;

    /**
     * Previous Order Document
     *
     * @var \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $previousOrderReferencedDocument
     */
    private $previousOrderReferencedDocument = null;

    /**
     * Previous Order Change Document
     *
     * @var \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $previousOrderChangeReferencedDocument
     */
    private $previousOrderChangeReferencedDocument = null;

    /**
     * Previous Order Response Document
     *
     * @var \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $previousOrderResponseReferencedDocument
     */
    private $previousOrderResponseReferencedDocument = null;

    /**
     * Procuring Project
     *
     * @var \horstoeko\orderx\entities\extended\ram\ProcuringProjectType $specifiedProcuringProject
     */
    private $specifiedProcuringProject = null;

    /**
     * Ultimate Customer Order Document
     *
     * @var \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType[] $ultimateCustomerOrderReferencedDocument
     */
    private $ultimateCustomerOrderReferencedDocument = [
        
    ];

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
     * @param  string $buyerReference
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
     * @return \horstoeko\orderx\entities\extended\ram\TradePartyType
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
     * @param  \horstoeko\orderx\entities\extended\ram\TradePartyType $sellerTradeParty
     * @return self
     */
    public function setSellerTradeParty(\horstoeko\orderx\entities\extended\ram\TradePartyType $sellerTradeParty)
    {
        $this->sellerTradeParty = $sellerTradeParty;
        return $this;
    }

    /**
     * Gets as buyerTradeParty
     *
     * Buyer
     *
     * @return \horstoeko\orderx\entities\extended\ram\TradePartyType
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
     * @param  \horstoeko\orderx\entities\extended\ram\TradePartyType $buyerTradeParty
     * @return self
     */
    public function setBuyerTradeParty(\horstoeko\orderx\entities\extended\ram\TradePartyType $buyerTradeParty)
    {
        $this->buyerTradeParty = $buyerTradeParty;
        return $this;
    }

    /**
     * Gets as buyerRequisitionerTradeParty
     *
     * Buyer Requisitioner
     *
     * @return \horstoeko\orderx\entities\extended\ram\TradePartyType
     */
    public function getBuyerRequisitionerTradeParty()
    {
        return $this->buyerRequisitionerTradeParty;
    }

    /**
     * Sets a new buyerRequisitionerTradeParty
     *
     * Buyer Requisitioner
     *
     * @param  \horstoeko\orderx\entities\extended\ram\TradePartyType $buyerRequisitionerTradeParty
     * @return self
     */
    public function setBuyerRequisitionerTradeParty(?\horstoeko\orderx\entities\extended\ram\TradePartyType $buyerRequisitionerTradeParty = null)
    {
        $this->buyerRequisitionerTradeParty = $buyerRequisitionerTradeParty;
        return $this;
    }

    /**
     * Gets as productEndUserTradeParty
     *
     * End User
     *
     * @return \horstoeko\orderx\entities\extended\ram\TradePartyType
     */
    public function getProductEndUserTradeParty()
    {
        return $this->productEndUserTradeParty;
    }

    /**
     * Sets a new productEndUserTradeParty
     *
     * End User
     *
     * @param  \horstoeko\orderx\entities\extended\ram\TradePartyType $productEndUserTradeParty
     * @return self
     */
    public function setProductEndUserTradeParty(?\horstoeko\orderx\entities\extended\ram\TradePartyType $productEndUserTradeParty = null)
    {
        $this->productEndUserTradeParty = $productEndUserTradeParty;
        return $this;
    }

    /**
     * Gets as applicableTradeDeliveryTerms
     *
     * Trade Delivery Terms
     *
     * @return \horstoeko\orderx\entities\extended\ram\TradeDeliveryTermsType
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
     * @param  \horstoeko\orderx\entities\extended\ram\TradeDeliveryTermsType $applicableTradeDeliveryTerms
     * @return self
     */
    public function setApplicableTradeDeliveryTerms(?\horstoeko\orderx\entities\extended\ram\TradeDeliveryTermsType $applicableTradeDeliveryTerms = null)
    {
        $this->applicableTradeDeliveryTerms = $applicableTradeDeliveryTerms;
        return $this;
    }

    /**
     * Gets as sellerOrderReferencedDocument
     *
     * Seller Order Document
     *
     * @return \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType
     */
    public function getSellerOrderReferencedDocument()
    {
        return $this->sellerOrderReferencedDocument;
    }

    /**
     * Sets a new sellerOrderReferencedDocument
     *
     * Seller Order Document
     *
     * @param  \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $sellerOrderReferencedDocument
     * @return self
     */
    public function setSellerOrderReferencedDocument(?\horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $sellerOrderReferencedDocument = null)
    {
        $this->sellerOrderReferencedDocument = $sellerOrderReferencedDocument;
        return $this;
    }

    /**
     * Gets as buyerOrderReferencedDocument
     *
     * Buyer Order Document
     *
     * @return \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType
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
     * @param  \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $buyerOrderReferencedDocument
     * @return self
     */
    public function setBuyerOrderReferencedDocument(?\horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $buyerOrderReferencedDocument = null)
    {
        $this->buyerOrderReferencedDocument = $buyerOrderReferencedDocument;
        return $this;
    }

    /**
     * Gets as quotationReferencedDocument
     *
     * Quotation Document
     *
     * @return \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType
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
     * @param  \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $quotationReferencedDocument
     * @return self
     */
    public function setQuotationReferencedDocument(?\horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $quotationReferencedDocument = null)
    {
        $this->quotationReferencedDocument = $quotationReferencedDocument;
        return $this;
    }

    /**
     * Gets as contractReferencedDocument
     *
     * Contract Document
     *
     * @return \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType
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
     * @param  \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $contractReferencedDocument
     * @return self
     */
    public function setContractReferencedDocument(?\horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $contractReferencedDocument = null)
    {
        $this->contractReferencedDocument = $contractReferencedDocument;
        return $this;
    }

    /**
     * Gets as requisitionReferencedDocument
     *
     * Requisition Document
     *
     * @return \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType
     */
    public function getRequisitionReferencedDocument()
    {
        return $this->requisitionReferencedDocument;
    }

    /**
     * Sets a new requisitionReferencedDocument
     *
     * Requisition Document
     *
     * @param  \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $requisitionReferencedDocument
     * @return self
     */
    public function setRequisitionReferencedDocument(?\horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $requisitionReferencedDocument = null)
    {
        $this->requisitionReferencedDocument = $requisitionReferencedDocument;
        return $this;
    }

    /**
     * Adds as additionalReferencedDocument
     *
     * Additional Document
     *
     * @return self
     * @param  \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $additionalReferencedDocument
     */
    public function addToAdditionalReferencedDocument(\horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $additionalReferencedDocument)
    {
        $this->additionalReferencedDocument[] = $additionalReferencedDocument;
        return $this;
    }

    /**
     * isset additionalReferencedDocument
     *
     * Additional Document
     *
     * @param  int|string $index
     * @return bool
     */
    public function issetAdditionalReferencedDocument($index)
    {
        return isset($this->additionalReferencedDocument[$index]);
    }

    /**
     * unset additionalReferencedDocument
     *
     * Additional Document
     *
     * @param  int|string $index
     * @return void
     */
    public function unsetAdditionalReferencedDocument($index)
    {
        unset($this->additionalReferencedDocument[$index]);
    }

    /**
     * Gets as additionalReferencedDocument
     *
     * Additional Document
     *
     * @return \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType[]
     */
    public function getAdditionalReferencedDocument()
    {
        return $this->additionalReferencedDocument;
    }

    /**
     * Sets a new additionalReferencedDocument
     *
     * Additional Document
     *
     * @param  \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType[] $additionalReferencedDocument
     * @return self
     */
    public function setAdditionalReferencedDocument(array $additionalReferencedDocument = null)
    {
        $this->additionalReferencedDocument = $additionalReferencedDocument;
        return $this;
    }

    /**
     * Gets as buyerAgentTradeParty
     *
     * Buyer Agent
     *
     * @return \horstoeko\orderx\entities\extended\ram\TradePartyType
     */
    public function getBuyerAgentTradeParty()
    {
        return $this->buyerAgentTradeParty;
    }

    /**
     * Sets a new buyerAgentTradeParty
     *
     * Buyer Agent
     *
     * @param  \horstoeko\orderx\entities\extended\ram\TradePartyType $buyerAgentTradeParty
     * @return self
     */
    public function setBuyerAgentTradeParty(?\horstoeko\orderx\entities\extended\ram\TradePartyType $buyerAgentTradeParty = null)
    {
        $this->buyerAgentTradeParty = $buyerAgentTradeParty;
        return $this;
    }

    /**
     * Gets as catalogueReferencedDocument
     *
     * Catalogue Document
     *
     * @return \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType
     */
    public function getCatalogueReferencedDocument()
    {
        return $this->catalogueReferencedDocument;
    }

    /**
     * Sets a new catalogueReferencedDocument
     *
     * Catalogue Document
     *
     * @param  \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $catalogueReferencedDocument
     * @return self
     */
    public function setCatalogueReferencedDocument(?\horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $catalogueReferencedDocument = null)
    {
        $this->catalogueReferencedDocument = $catalogueReferencedDocument;
        return $this;
    }

    /**
     * Gets as blanketOrderReferencedDocument
     *
     * Blanket Order Document
     *
     * @return \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType
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
     * @param  \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $blanketOrderReferencedDocument
     * @return self
     */
    public function setBlanketOrderReferencedDocument(?\horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $blanketOrderReferencedDocument = null)
    {
        $this->blanketOrderReferencedDocument = $blanketOrderReferencedDocument;
        return $this;
    }

    /**
     * Gets as previousOrderReferencedDocument
     *
     * Previous Order Document
     *
     * @return \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType
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
     * @param  \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $previousOrderReferencedDocument
     * @return self
     */
    public function setPreviousOrderReferencedDocument(?\horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $previousOrderReferencedDocument = null)
    {
        $this->previousOrderReferencedDocument = $previousOrderReferencedDocument;
        return $this;
    }

    /**
     * Gets as previousOrderChangeReferencedDocument
     *
     * Previous Order Change Document
     *
     * @return \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType
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
     * @param  \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $previousOrderChangeReferencedDocument
     * @return self
     */
    public function setPreviousOrderChangeReferencedDocument(?\horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $previousOrderChangeReferencedDocument = null)
    {
        $this->previousOrderChangeReferencedDocument = $previousOrderChangeReferencedDocument;
        return $this;
    }

    /**
     * Gets as previousOrderResponseReferencedDocument
     *
     * Previous Order Response Document
     *
     * @return \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType
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
     * @param  \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $previousOrderResponseReferencedDocument
     * @return self
     */
    public function setPreviousOrderResponseReferencedDocument(?\horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $previousOrderResponseReferencedDocument = null)
    {
        $this->previousOrderResponseReferencedDocument = $previousOrderResponseReferencedDocument;
        return $this;
    }

    /**
     * Gets as specifiedProcuringProject
     *
     * Procuring Project
     *
     * @return \horstoeko\orderx\entities\extended\ram\ProcuringProjectType
     */
    public function getSpecifiedProcuringProject()
    {
        return $this->specifiedProcuringProject;
    }

    /**
     * Sets a new specifiedProcuringProject
     *
     * Procuring Project
     *
     * @param  \horstoeko\orderx\entities\extended\ram\ProcuringProjectType $specifiedProcuringProject
     * @return self
     */
    public function setSpecifiedProcuringProject(?\horstoeko\orderx\entities\extended\ram\ProcuringProjectType $specifiedProcuringProject = null)
    {
        $this->specifiedProcuringProject = $specifiedProcuringProject;
        return $this;
    }

    /**
     * Adds as ultimateCustomerOrderReferencedDocument
     *
     * Ultimate Customer Order Document
     *
     * @return self
     * @param  \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $ultimateCustomerOrderReferencedDocument
     */
    public function addToUltimateCustomerOrderReferencedDocument(\horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $ultimateCustomerOrderReferencedDocument)
    {
        $this->ultimateCustomerOrderReferencedDocument[] = $ultimateCustomerOrderReferencedDocument;
        return $this;
    }

    /**
     * isset ultimateCustomerOrderReferencedDocument
     *
     * Ultimate Customer Order Document
     *
     * @param  int|string $index
     * @return bool
     */
    public function issetUltimateCustomerOrderReferencedDocument($index)
    {
        return isset($this->ultimateCustomerOrderReferencedDocument[$index]);
    }

    /**
     * unset ultimateCustomerOrderReferencedDocument
     *
     * Ultimate Customer Order Document
     *
     * @param  int|string $index
     * @return void
     */
    public function unsetUltimateCustomerOrderReferencedDocument($index)
    {
        unset($this->ultimateCustomerOrderReferencedDocument[$index]);
    }

    /**
     * Gets as ultimateCustomerOrderReferencedDocument
     *
     * Ultimate Customer Order Document
     *
     * @return \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType[]
     */
    public function getUltimateCustomerOrderReferencedDocument()
    {
        return $this->ultimateCustomerOrderReferencedDocument;
    }

    /**
     * Sets a new ultimateCustomerOrderReferencedDocument
     *
     * Ultimate Customer Order Document
     *
     * @param  \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType[] $ultimateCustomerOrderReferencedDocument
     * @return self
     */
    public function setUltimateCustomerOrderReferencedDocument(array $ultimateCustomerOrderReferencedDocument = null)
    {
        $this->ultimateCustomerOrderReferencedDocument = $ultimateCustomerOrderReferencedDocument;
        return $this;
    }
}
