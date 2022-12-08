<?php

namespace horstoeko\orderx\entities\basic\ram;

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
     * @var \horstoeko\orderx\entities\basic\ram\ReferencedDocumentType $buyerOrderReferencedDocument
     */
    private $buyerOrderReferencedDocument = null;

    /**
     * Product Net Price
     *
     * @var \horstoeko\orderx\entities\basic\ram\TradePriceType $netPriceProductTradePrice
     */
    private $netPriceProductTradePrice = null;

    /**
     * Blanket Order Document
     *
     * @var \horstoeko\orderx\entities\basic\ram\ReferencedDocumentType $blanketOrderReferencedDocument
     */
    private $blanketOrderReferencedDocument = null;

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
     * Gets as netPriceProductTradePrice
     *
     * Product Net Price
     *
     * @return \horstoeko\orderx\entities\basic\ram\TradePriceType
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
     * @param \horstoeko\orderx\entities\basic\ram\TradePriceType $netPriceProductTradePrice
     * @return self
     */
    public function setNetPriceProductTradePrice(\horstoeko\orderx\entities\basic\ram\TradePriceType $netPriceProductTradePrice)
    {
        $this->netPriceProductTradePrice = $netPriceProductTradePrice;
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


}

