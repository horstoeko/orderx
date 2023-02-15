<?php

namespace horstoeko\orderx\entities\extended\ram;

/**
 * Class representing LineTradeAgreementType
 *
 * Line Trade Agreement
 * XSD Type: LineTradeAgreementType
 */
class LineTradeAgreementType
{

    /**
     * Minimum Product Orderable Quantity
     *
     * @var \horstoeko\orderx\entities\extended\udt\QuantityType $minimumProductOrderableQuantity
     */
    private $minimumProductOrderableQuantity = null;

    /**
     * Maximum Product Orderable Quantity
     *
     * @var \horstoeko\orderx\entities\extended\udt\QuantityType $maximumProductOrderableQuantity
     */
    private $maximumProductOrderableQuantity = null;

    /**
     * Buyer Requisitioner
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradePartyType $buyerRequisitionerTradeParty
     */
    private $buyerRequisitionerTradeParty = null;

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
     * Additional Document
     *
     * @var \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType[] $additionalReferencedDocument
     */
    private $additionalReferencedDocument = [
        
    ];

    /**
     * Product Gross Price
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradePriceType $grossPriceProductTradePrice
     */
    private $grossPriceProductTradePrice = null;

    /**
     * Product Net Price
     *
     * @var \horstoeko\orderx\entities\extended\ram\TradePriceType $netPriceProductTradePrice
     */
    private $netPriceProductTradePrice = null;

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
     * Ultimate Customer Order Document
     *
     * @var \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $ultimateCustomerOrderReferencedDocument
     */
    private $ultimateCustomerOrderReferencedDocument = null;

    /**
     * Gets as minimumProductOrderableQuantity
     *
     * Minimum Product Orderable Quantity
     *
     * @return \horstoeko\orderx\entities\extended\udt\QuantityType
     */
    public function getMinimumProductOrderableQuantity()
    {
        return $this->minimumProductOrderableQuantity;
    }

    /**
     * Sets a new minimumProductOrderableQuantity
     *
     * Minimum Product Orderable Quantity
     *
     * @param  \horstoeko\orderx\entities\extended\udt\QuantityType $minimumProductOrderableQuantity
     * @return self
     */
    public function setMinimumProductOrderableQuantity(?\horstoeko\orderx\entities\extended\udt\QuantityType $minimumProductOrderableQuantity = null)
    {
        $this->minimumProductOrderableQuantity = $minimumProductOrderableQuantity;
        return $this;
    }

    /**
     * Gets as maximumProductOrderableQuantity
     *
     * Maximum Product Orderable Quantity
     *
     * @return \horstoeko\orderx\entities\extended\udt\QuantityType
     */
    public function getMaximumProductOrderableQuantity()
    {
        return $this->maximumProductOrderableQuantity;
    }

    /**
     * Sets a new maximumProductOrderableQuantity
     *
     * Maximum Product Orderable Quantity
     *
     * @param  \horstoeko\orderx\entities\extended\udt\QuantityType $maximumProductOrderableQuantity
     * @return self
     */
    public function setMaximumProductOrderableQuantity(?\horstoeko\orderx\entities\extended\udt\QuantityType $maximumProductOrderableQuantity = null)
    {
        $this->maximumProductOrderableQuantity = $maximumProductOrderableQuantity;
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
     * Gets as grossPriceProductTradePrice
     *
     * Product Gross Price
     *
     * @return \horstoeko\orderx\entities\extended\ram\TradePriceType
     */
    public function getGrossPriceProductTradePrice()
    {
        return $this->grossPriceProductTradePrice;
    }

    /**
     * Sets a new grossPriceProductTradePrice
     *
     * Product Gross Price
     *
     * @param  \horstoeko\orderx\entities\extended\ram\TradePriceType $grossPriceProductTradePrice
     * @return self
     */
    public function setGrossPriceProductTradePrice(?\horstoeko\orderx\entities\extended\ram\TradePriceType $grossPriceProductTradePrice = null)
    {
        $this->grossPriceProductTradePrice = $grossPriceProductTradePrice;
        return $this;
    }

    /**
     * Gets as netPriceProductTradePrice
     *
     * Product Net Price
     *
     * @return \horstoeko\orderx\entities\extended\ram\TradePriceType
     */
    public function getNetPriceProductTradePrice()
    {
        return $this->netPriceProductTradePrice;
    }

    /**
     * Sets a new netPriceProductTradePrice
     *
     * Product Net Price
     *
     * @param  \horstoeko\orderx\entities\extended\ram\TradePriceType $netPriceProductTradePrice
     * @return self
     */
    public function setNetPriceProductTradePrice(\horstoeko\orderx\entities\extended\ram\TradePriceType $netPriceProductTradePrice)
    {
        $this->netPriceProductTradePrice = $netPriceProductTradePrice;
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
     * Gets as ultimateCustomerOrderReferencedDocument
     *
     * Ultimate Customer Order Document
     *
     * @return \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType
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
     * @param  \horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $ultimateCustomerOrderReferencedDocument
     * @return self
     */
    public function setUltimateCustomerOrderReferencedDocument(?\horstoeko\orderx\entities\extended\ram\ReferencedDocumentType $ultimateCustomerOrderReferencedDocument = null)
    {
        $this->ultimateCustomerOrderReferencedDocument = $ultimateCustomerOrderReferencedDocument;
        return $this;
    }
}
