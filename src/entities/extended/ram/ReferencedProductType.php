<?php

namespace horstoeko\orderx\entities\extended\ram;

/**
 * Class representing ReferencedProductType
 *
 * Referenced Product
 * XSD Type: ReferencedProductType
 */
class ReferencedProductType
{

    /**
     * ID
     *
     * @var \horstoeko\orderx\entities\extended\udt\IDType $iD
     */
    private $iD = null;

    /**
     * Global ID
     *
     * @var \horstoeko\orderx\entities\extended\udt\IDType[] $globalID
     */
    private $globalID = [
        
    ];

    /**
     * Seller Assigned ID
     *
     * @var \horstoeko\orderx\entities\extended\udt\IDType $sellerAssignedID
     */
    private $sellerAssignedID = null;

    /**
     * Buyer Assigned ID
     *
     * @var \horstoeko\orderx\entities\extended\udt\IDType $buyerAssignedID
     */
    private $buyerAssignedID = null;

    /**
     * Industry Assigned ID
     *
     * @var \horstoeko\orderx\entities\extended\udt\IDType $industryAssignedID
     */
    private $industryAssignedID = null;

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
     * Unit Quantity
     *
     * @var \horstoeko\orderx\entities\extended\udt\QuantityType $unitQuantity
     */
    private $unitQuantity = null;

    /**
     * Gets as iD
     *
     * ID
     *
     * @return \horstoeko\orderx\entities\extended\udt\IDType
     */
    public function getID()
    {
        return $this->iD;
    }

    /**
     * Sets a new iD
     *
     * ID
     *
     * @param \horstoeko\orderx\entities\extended\udt\IDType $iD
     * @return self
     */
    public function setID(?\horstoeko\orderx\entities\extended\udt\IDType $iD = null)
    {
        $this->iD = $iD;
        return $this;
    }

    /**
     * Adds as globalID
     *
     * Global ID
     *
     * @return self
     * @param \horstoeko\orderx\entities\extended\udt\IDType $globalID
     */
    public function addToGlobalID(\horstoeko\orderx\entities\extended\udt\IDType $globalID)
    {
        $this->globalID[] = $globalID;
        return $this;
    }

    /**
     * isset globalID
     *
     * Global ID
     *
     * @param int|string $index
     * @return bool
     */
    public function issetGlobalID($index)
    {
        return isset($this->globalID[$index]);
    }

    /**
     * unset globalID
     *
     * Global ID
     *
     * @param int|string $index
     * @return void
     */
    public function unsetGlobalID($index)
    {
        unset($this->globalID[$index]);
    }

    /**
     * Gets as globalID
     *
     * Global ID
     *
     * @return \horstoeko\orderx\entities\extended\udt\IDType[]
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
     * @param \horstoeko\orderx\entities\extended\udt\IDType[] $globalID
     * @return self
     */
    public function setGlobalID(array $globalID = null)
    {
        $this->globalID = $globalID;
        return $this;
    }

    /**
     * Gets as sellerAssignedID
     *
     * Seller Assigned ID
     *
     * @return \horstoeko\orderx\entities\extended\udt\IDType
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
     * @param \horstoeko\orderx\entities\extended\udt\IDType $sellerAssignedID
     * @return self
     */
    public function setSellerAssignedID(?\horstoeko\orderx\entities\extended\udt\IDType $sellerAssignedID = null)
    {
        $this->sellerAssignedID = $sellerAssignedID;
        return $this;
    }

    /**
     * Gets as buyerAssignedID
     *
     * Buyer Assigned ID
     *
     * @return \horstoeko\orderx\entities\extended\udt\IDType
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
     * @param \horstoeko\orderx\entities\extended\udt\IDType $buyerAssignedID
     * @return self
     */
    public function setBuyerAssignedID(?\horstoeko\orderx\entities\extended\udt\IDType $buyerAssignedID = null)
    {
        $this->buyerAssignedID = $buyerAssignedID;
        return $this;
    }

    /**
     * Gets as industryAssignedID
     *
     * Industry Assigned ID
     *
     * @return \horstoeko\orderx\entities\extended\udt\IDType
     */
    public function getIndustryAssignedID()
    {
        return $this->industryAssignedID;
    }

    /**
     * Sets a new industryAssignedID
     *
     * Industry Assigned ID
     *
     * @param \horstoeko\orderx\entities\extended\udt\IDType $industryAssignedID
     * @return self
     */
    public function setIndustryAssignedID(?\horstoeko\orderx\entities\extended\udt\IDType $industryAssignedID = null)
    {
        $this->industryAssignedID = $industryAssignedID;
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
     * @param string $name
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
     * @param string $description
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Gets as unitQuantity
     *
     * Unit Quantity
     *
     * @return \horstoeko\orderx\entities\extended\udt\QuantityType
     */
    public function getUnitQuantity()
    {
        return $this->unitQuantity;
    }

    /**
     * Sets a new unitQuantity
     *
     * Unit Quantity
     *
     * @param \horstoeko\orderx\entities\extended\udt\QuantityType $unitQuantity
     * @return self
     */
    public function setUnitQuantity(?\horstoeko\orderx\entities\extended\udt\QuantityType $unitQuantity = null)
    {
        $this->unitQuantity = $unitQuantity;
        return $this;
    }


}

