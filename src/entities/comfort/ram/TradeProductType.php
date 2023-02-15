<?php

namespace horstoeko\orderx\entities\comfort\ram;

/**
 * Class representing TradeProductType
 *
 * Trade Product
 * XSD Type: TradeProductType
 */
class TradeProductType
{

    /**
     * Global ID
     *
     * @var \horstoeko\orderx\entities\comfort\udt\IDType $globalID
     */
    private $globalID = null;

    /**
     * Seller Assigned ID
     *
     * @var \horstoeko\orderx\entities\comfort\udt\IDType $sellerAssignedID
     */
    private $sellerAssignedID = null;

    /**
     * Buyer Assigned ID
     *
     * @var \horstoeko\orderx\entities\comfort\udt\IDType $buyerAssignedID
     */
    private $buyerAssignedID = null;

    /**
     * Name
     *
     * @var string $name
     */
    private $name = null;

    /**
     * Description
     *
     * @var string $description
     */
    private $description = null;

    /**
     * Batch ID
     *
     * @var \horstoeko\orderx\entities\comfort\udt\IDType $batchID
     */
    private $batchID = null;

    /**
     * Brand Name
     *
     * @var string $brandName
     */
    private $brandName = null;

    /**
     * Characteristic
     *
     * @var \horstoeko\orderx\entities\comfort\ram\ProductCharacteristicType[] $applicableProductCharacteristic
     */
    private $applicableProductCharacteristic = [
        
    ];

    /**
     * Classification
     *
     * @var \horstoeko\orderx\entities\comfort\ram\ProductClassificationType[] $designatedProductClassification
     */
    private $designatedProductClassification = [
        
    ];

    /**
     * Individual Product Instance
     *
     * @var \horstoeko\orderx\entities\comfort\ram\TradeProductInstanceType[] $individualTradeProductInstance
     */
    private $individualTradeProductInstance = [
        
    ];

    /**
     * Packaging
     *
     * @var \horstoeko\orderx\entities\comfort\ram\SupplyChainPackagingType $applicableSupplyChainPackaging
     */
    private $applicableSupplyChainPackaging = null;

    /**
     * Origin Country
     *
     * @var \horstoeko\orderx\entities\comfort\ram\TradeCountryType $originTradeCountry
     */
    private $originTradeCountry = null;

    /**
     * Additional Document
     *
     * @var \horstoeko\orderx\entities\comfort\ram\ReferencedDocumentType[] $additionalReferenceReferencedDocument
     */
    private $additionalReferenceReferencedDocument = [
        
    ];

    /**
     * Gets as globalID
     *
     * Global ID
     *
     * @return \horstoeko\orderx\entities\comfort\udt\IDType
     */
    public function getGlobalID()
    {
        return $this->globalID;
    }

    /**
     * Sets a new globalID
     *
     * Global ID
     *
     * @param  \horstoeko\orderx\entities\comfort\udt\IDType $globalID
     * @return self
     */
    public function setGlobalID(?\horstoeko\orderx\entities\comfort\udt\IDType $globalID = null)
    {
        $this->globalID = $globalID;
        return $this;
    }

    /**
     * Gets as sellerAssignedID
     *
     * Seller Assigned ID
     *
     * @return \horstoeko\orderx\entities\comfort\udt\IDType
     */
    public function getSellerAssignedID()
    {
        return $this->sellerAssignedID;
    }

    /**
     * Sets a new sellerAssignedID
     *
     * Seller Assigned ID
     *
     * @param  \horstoeko\orderx\entities\comfort\udt\IDType $sellerAssignedID
     * @return self
     */
    public function setSellerAssignedID(?\horstoeko\orderx\entities\comfort\udt\IDType $sellerAssignedID = null)
    {
        $this->sellerAssignedID = $sellerAssignedID;
        return $this;
    }

    /**
     * Gets as buyerAssignedID
     *
     * Buyer Assigned ID
     *
     * @return \horstoeko\orderx\entities\comfort\udt\IDType
     */
    public function getBuyerAssignedID()
    {
        return $this->buyerAssignedID;
    }

    /**
     * Sets a new buyerAssignedID
     *
     * Buyer Assigned ID
     *
     * @param  \horstoeko\orderx\entities\comfort\udt\IDType $buyerAssignedID
     * @return self
     */
    public function setBuyerAssignedID(?\horstoeko\orderx\entities\comfort\udt\IDType $buyerAssignedID = null)
    {
        $this->buyerAssignedID = $buyerAssignedID;
        return $this;
    }

    /**
     * Gets as name
     *
     * Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * Name
     *
     * @param  string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets as description
     *
     * Description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets a new description
     *
     * Description
     *
     * @param  string $description
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Gets as batchID
     *
     * Batch ID
     *
     * @return \horstoeko\orderx\entities\comfort\udt\IDType
     */
    public function getBatchID()
    {
        return $this->batchID;
    }

    /**
     * Sets a new batchID
     *
     * Batch ID
     *
     * @param  \horstoeko\orderx\entities\comfort\udt\IDType $batchID
     * @return self
     */
    public function setBatchID(?\horstoeko\orderx\entities\comfort\udt\IDType $batchID = null)
    {
        $this->batchID = $batchID;
        return $this;
    }

    /**
     * Gets as brandName
     *
     * Brand Name
     *
     * @return string
     */
    public function getBrandName()
    {
        return $this->brandName;
    }

    /**
     * Sets a new brandName
     *
     * Brand Name
     *
     * @param  string $brandName
     * @return self
     */
    public function setBrandName($brandName)
    {
        $this->brandName = $brandName;
        return $this;
    }

    /**
     * Adds as applicableProductCharacteristic
     *
     * Characteristic
     *
     * @return self
     * @param  \horstoeko\orderx\entities\comfort\ram\ProductCharacteristicType $applicableProductCharacteristic
     */
    public function addToApplicableProductCharacteristic(\horstoeko\orderx\entities\comfort\ram\ProductCharacteristicType $applicableProductCharacteristic)
    {
        $this->applicableProductCharacteristic[] = $applicableProductCharacteristic;
        return $this;
    }

    /**
     * isset applicableProductCharacteristic
     *
     * Characteristic
     *
     * @param  int|string $index
     * @return bool
     */
    public function issetApplicableProductCharacteristic($index)
    {
        return isset($this->applicableProductCharacteristic[$index]);
    }

    /**
     * unset applicableProductCharacteristic
     *
     * Characteristic
     *
     * @param  int|string $index
     * @return void
     */
    public function unsetApplicableProductCharacteristic($index)
    {
        unset($this->applicableProductCharacteristic[$index]);
    }

    /**
     * Gets as applicableProductCharacteristic
     *
     * Characteristic
     *
     * @return \horstoeko\orderx\entities\comfort\ram\ProductCharacteristicType[]
     */
    public function getApplicableProductCharacteristic()
    {
        return $this->applicableProductCharacteristic;
    }

    /**
     * Sets a new applicableProductCharacteristic
     *
     * Characteristic
     *
     * @param  \horstoeko\orderx\entities\comfort\ram\ProductCharacteristicType[] $applicableProductCharacteristic
     * @return self
     */
    public function setApplicableProductCharacteristic(array $applicableProductCharacteristic = null)
    {
        $this->applicableProductCharacteristic = $applicableProductCharacteristic;
        return $this;
    }

    /**
     * Adds as designatedProductClassification
     *
     * Classification
     *
     * @return self
     * @param  \horstoeko\orderx\entities\comfort\ram\ProductClassificationType $designatedProductClassification
     */
    public function addToDesignatedProductClassification(\horstoeko\orderx\entities\comfort\ram\ProductClassificationType $designatedProductClassification)
    {
        $this->designatedProductClassification[] = $designatedProductClassification;
        return $this;
    }

    /**
     * isset designatedProductClassification
     *
     * Classification
     *
     * @param  int|string $index
     * @return bool
     */
    public function issetDesignatedProductClassification($index)
    {
        return isset($this->designatedProductClassification[$index]);
    }

    /**
     * unset designatedProductClassification
     *
     * Classification
     *
     * @param  int|string $index
     * @return void
     */
    public function unsetDesignatedProductClassification($index)
    {
        unset($this->designatedProductClassification[$index]);
    }

    /**
     * Gets as designatedProductClassification
     *
     * Classification
     *
     * @return \horstoeko\orderx\entities\comfort\ram\ProductClassificationType[]
     */
    public function getDesignatedProductClassification()
    {
        return $this->designatedProductClassification;
    }

    /**
     * Sets a new designatedProductClassification
     *
     * Classification
     *
     * @param  \horstoeko\orderx\entities\comfort\ram\ProductClassificationType[] $designatedProductClassification
     * @return self
     */
    public function setDesignatedProductClassification(array $designatedProductClassification = null)
    {
        $this->designatedProductClassification = $designatedProductClassification;
        return $this;
    }

    /**
     * Adds as individualTradeProductInstance
     *
     * Individual Product Instance
     *
     * @return self
     * @param  \horstoeko\orderx\entities\comfort\ram\TradeProductInstanceType $individualTradeProductInstance
     */
    public function addToIndividualTradeProductInstance(\horstoeko\orderx\entities\comfort\ram\TradeProductInstanceType $individualTradeProductInstance)
    {
        $this->individualTradeProductInstance[] = $individualTradeProductInstance;
        return $this;
    }

    /**
     * isset individualTradeProductInstance
     *
     * Individual Product Instance
     *
     * @param  int|string $index
     * @return bool
     */
    public function issetIndividualTradeProductInstance($index)
    {
        return isset($this->individualTradeProductInstance[$index]);
    }

    /**
     * unset individualTradeProductInstance
     *
     * Individual Product Instance
     *
     * @param  int|string $index
     * @return void
     */
    public function unsetIndividualTradeProductInstance($index)
    {
        unset($this->individualTradeProductInstance[$index]);
    }

    /**
     * Gets as individualTradeProductInstance
     *
     * Individual Product Instance
     *
     * @return \horstoeko\orderx\entities\comfort\ram\TradeProductInstanceType[]
     */
    public function getIndividualTradeProductInstance()
    {
        return $this->individualTradeProductInstance;
    }

    /**
     * Sets a new individualTradeProductInstance
     *
     * Individual Product Instance
     *
     * @param  \horstoeko\orderx\entities\comfort\ram\TradeProductInstanceType[] $individualTradeProductInstance
     * @return self
     */
    public function setIndividualTradeProductInstance(array $individualTradeProductInstance = null)
    {
        $this->individualTradeProductInstance = $individualTradeProductInstance;
        return $this;
    }

    /**
     * Gets as applicableSupplyChainPackaging
     *
     * Packaging
     *
     * @return \horstoeko\orderx\entities\comfort\ram\SupplyChainPackagingType
     */
    public function getApplicableSupplyChainPackaging()
    {
        return $this->applicableSupplyChainPackaging;
    }

    /**
     * Sets a new applicableSupplyChainPackaging
     *
     * Packaging
     *
     * @param  \horstoeko\orderx\entities\comfort\ram\SupplyChainPackagingType $applicableSupplyChainPackaging
     * @return self
     */
    public function setApplicableSupplyChainPackaging(?\horstoeko\orderx\entities\comfort\ram\SupplyChainPackagingType $applicableSupplyChainPackaging = null)
    {
        $this->applicableSupplyChainPackaging = $applicableSupplyChainPackaging;
        return $this;
    }

    /**
     * Gets as originTradeCountry
     *
     * Origin Country
     *
     * @return \horstoeko\orderx\entities\comfort\ram\TradeCountryType
     */
    public function getOriginTradeCountry()
    {
        return $this->originTradeCountry;
    }

    /**
     * Sets a new originTradeCountry
     *
     * Origin Country
     *
     * @param  \horstoeko\orderx\entities\comfort\ram\TradeCountryType $originTradeCountry
     * @return self
     */
    public function setOriginTradeCountry(?\horstoeko\orderx\entities\comfort\ram\TradeCountryType $originTradeCountry = null)
    {
        $this->originTradeCountry = $originTradeCountry;
        return $this;
    }

    /**
     * Adds as additionalReferenceReferencedDocument
     *
     * Additional Document
     *
     * @return self
     * @param  \horstoeko\orderx\entities\comfort\ram\ReferencedDocumentType $additionalReferenceReferencedDocument
     */
    public function addToAdditionalReferenceReferencedDocument(\horstoeko\orderx\entities\comfort\ram\ReferencedDocumentType $additionalReferenceReferencedDocument)
    {
        $this->additionalReferenceReferencedDocument[] = $additionalReferenceReferencedDocument;
        return $this;
    }

    /**
     * isset additionalReferenceReferencedDocument
     *
     * Additional Document
     *
     * @param  int|string $index
     * @return bool
     */
    public function issetAdditionalReferenceReferencedDocument($index)
    {
        return isset($this->additionalReferenceReferencedDocument[$index]);
    }

    /**
     * unset additionalReferenceReferencedDocument
     *
     * Additional Document
     *
     * @param  int|string $index
     * @return void
     */
    public function unsetAdditionalReferenceReferencedDocument($index)
    {
        unset($this->additionalReferenceReferencedDocument[$index]);
    }

    /**
     * Gets as additionalReferenceReferencedDocument
     *
     * Additional Document
     *
     * @return \horstoeko\orderx\entities\comfort\ram\ReferencedDocumentType[]
     */
    public function getAdditionalReferenceReferencedDocument()
    {
        return $this->additionalReferenceReferencedDocument;
    }

    /**
     * Sets a new additionalReferenceReferencedDocument
     *
     * Additional Document
     *
     * @param  \horstoeko\orderx\entities\comfort\ram\ReferencedDocumentType[] $additionalReferenceReferencedDocument
     * @return self
     */
    public function setAdditionalReferenceReferencedDocument(array $additionalReferenceReferencedDocument = null)
    {
        $this->additionalReferenceReferencedDocument = $additionalReferenceReferencedDocument;
        return $this;
    }
}
