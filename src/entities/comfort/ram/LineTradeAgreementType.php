<?php

namespace horstoeko\orderx\entities\comfort\ram;

/**
 * Class representing LineTradeAgreementType
 *
 * Line Trade Agreement
 * XSD Type: LineTradeAgreementType
 */
class LineTradeAgreementType
{

    /**
     * Buyer Order Document
     *
     * @var \horstoeko\orderx\entities\comfort\ram\ReferencedDocumentType $buyerOrderReferencedDocument
     */
    private $buyerOrderReferencedDocument = null;

    /**
     * Quotation Document
     *
     * @var \horstoeko\orderx\entities\comfort\ram\ReferencedDocumentType $quotationReferencedDocument
     */
    private $quotationReferencedDocument = null;

    /**
     * Additional Document
     *
     * @var \horstoeko\orderx\entities\comfort\ram\ReferencedDocumentType[] $additionalReferencedDocument
     */
    private $additionalReferencedDocument = [
        
    ];

    /**
     * Product Gross Price
     *
     * @var \horstoeko\orderx\entities\comfort\ram\TradePriceType $grossPriceProductTradePrice
     */
    private $grossPriceProductTradePrice = null;

    /**
     * Product Net Price
     *
     * @var \horstoeko\orderx\entities\comfort\ram\TradePriceType $netPriceProductTradePrice
     */
    private $netPriceProductTradePrice = null;

    /**
     * Catalogue Document
     *
     * @var \horstoeko\orderx\entities\comfort\ram\ReferencedDocumentType $catalogueReferencedDocument
     */
    private $catalogueReferencedDocument = null;

    /**
     * Blanket Order Document
     *
     * @var \horstoeko\orderx\entities\comfort\ram\ReferencedDocumentType $blanketOrderReferencedDocument
     */
    private $blanketOrderReferencedDocument = null;

    /**
     * Gets as buyerOrderReferencedDocument
     *
     * Buyer Order Document
     *
     * @return \horstoeko\orderx\entities\comfort\ram\ReferencedDocumentType
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
     * @param \horstoeko\orderx\entities\comfort\ram\ReferencedDocumentType $buyerOrderReferencedDocument
     * @return self
     */
    public function setBuyerOrderReferencedDocument(?\horstoeko\orderx\entities\comfort\ram\ReferencedDocumentType $buyerOrderReferencedDocument = null)
    {
        $this->buyerOrderReferencedDocument = $buyerOrderReferencedDocument;
        return $this;
    }

    /**
     * Gets as quotationReferencedDocument
     *
     * Quotation Document
     *
     * @return \horstoeko\orderx\entities\comfort\ram\ReferencedDocumentType
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
     * @param \horstoeko\orderx\entities\comfort\ram\ReferencedDocumentType $quotationReferencedDocument
     * @return self
     */
    public function setQuotationReferencedDocument(?\horstoeko\orderx\entities\comfort\ram\ReferencedDocumentType $quotationReferencedDocument = null)
    {
        $this->quotationReferencedDocument = $quotationReferencedDocument;
        return $this;
    }

    /**
     * Adds as additionalReferencedDocument
     *
     * Additional Document
     *
     * @return self
     * @param \horstoeko\orderx\entities\comfort\ram\ReferencedDocumentType $additionalReferencedDocument
     */
    public function addToAdditionalReferencedDocument(\horstoeko\orderx\entities\comfort\ram\ReferencedDocumentType $additionalReferencedDocument)
    {
        $this->additionalReferencedDocument[] = $additionalReferencedDocument;
        return $this;
    }

    /**
     * isset additionalReferencedDocument
     *
     * Additional Document
     *
     * @param int|string $index
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
     * @param int|string $index
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
     * @return \horstoeko\orderx\entities\comfort\ram\ReferencedDocumentType[]
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
     * @param \horstoeko\orderx\entities\comfort\ram\ReferencedDocumentType[] $additionalReferencedDocument
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
     * @return \horstoeko\orderx\entities\comfort\ram\TradePriceType
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
     * @param \horstoeko\orderx\entities\comfort\ram\TradePriceType $grossPriceProductTradePrice
     * @return self
     */
    public function setGrossPriceProductTradePrice(?\horstoeko\orderx\entities\comfort\ram\TradePriceType $grossPriceProductTradePrice = null)
    {
        $this->grossPriceProductTradePrice = $grossPriceProductTradePrice;
        return $this;
    }

    /**
     * Gets as netPriceProductTradePrice
     *
     * Product Net Price
     *
     * @return \horstoeko\orderx\entities\comfort\ram\TradePriceType
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
     * @param \horstoeko\orderx\entities\comfort\ram\TradePriceType $netPriceProductTradePrice
     * @return self
     */
    public function setNetPriceProductTradePrice(\horstoeko\orderx\entities\comfort\ram\TradePriceType $netPriceProductTradePrice)
    {
        $this->netPriceProductTradePrice = $netPriceProductTradePrice;
        return $this;
    }

    /**
     * Gets as catalogueReferencedDocument
     *
     * Catalogue Document
     *
     * @return \horstoeko\orderx\entities\comfort\ram\ReferencedDocumentType
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
     * @param \horstoeko\orderx\entities\comfort\ram\ReferencedDocumentType $catalogueReferencedDocument
     * @return self
     */
    public function setCatalogueReferencedDocument(?\horstoeko\orderx\entities\comfort\ram\ReferencedDocumentType $catalogueReferencedDocument = null)
    {
        $this->catalogueReferencedDocument = $catalogueReferencedDocument;
        return $this;
    }

    /**
     * Gets as blanketOrderReferencedDocument
     *
     * Blanket Order Document
     *
     * @return \horstoeko\orderx\entities\comfort\ram\ReferencedDocumentType
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
     * @param \horstoeko\orderx\entities\comfort\ram\ReferencedDocumentType $blanketOrderReferencedDocument
     * @return self
     */
    public function setBlanketOrderReferencedDocument(?\horstoeko\orderx\entities\comfort\ram\ReferencedDocumentType $blanketOrderReferencedDocument = null)
    {
        $this->blanketOrderReferencedDocument = $blanketOrderReferencedDocument;
        return $this;
    }


}

