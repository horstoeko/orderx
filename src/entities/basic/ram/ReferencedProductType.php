<?php

namespace horstoeko\orderx\entities\basic\ram;

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
     * @var \horstoeko\orderx\entities\basic\udt\IDType $iD
     */
    private $iD = null;

    /**
     * Global ID
     *
     * @var \horstoeko\orderx\entities\basic\udt\IDType[] $globalID
     */
    private $globalID = [
        
    ];

    /**
     * Seller Assigned ID
     *
     * @var \horstoeko\orderx\entities\basic\udt\IDType $sellerAssignedID
     */
    private $sellerAssignedID = null;

    /**
     * Buyer Assigned ID
     *
     * @var \horstoeko\orderx\entities\basic\udt\IDType $buyerAssignedID
     */
    private $buyerAssignedID = null;

    /**
     * Name
     *
     * @var string $name
     */
    private $name = null;

    /**
     * Gets as iD
     *
     * ID
     *
     * @return \horstoeko\orderx\entities\basic\udt\IDType
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
     * @param  \horstoeko\orderx\entities\basic\udt\IDType $iD
     * @return self
     */
    public function setID(?\horstoeko\orderx\entities\basic\udt\IDType $iD = null)
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
     * @param  \horstoeko\orderx\entities\basic\udt\IDType $globalID
     */
    public function addToGlobalID(\horstoeko\orderx\entities\basic\udt\IDType $globalID)
    {
        $this->globalID[] = $globalID;
        return $this;
    }

    /**
     * isset globalID
     *
     * Global ID
     *
     * @param  int|string $index
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
     * @param  int|string $index
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
     * @return \horstoeko\orderx\entities\basic\udt\IDType[]
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
     * @param  \horstoeko\orderx\entities\basic\udt\IDType[] $globalID
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
     * @return \horstoeko\orderx\entities\basic\udt\IDType
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
     * @param  \horstoeko\orderx\entities\basic\udt\IDType $sellerAssignedID
     * @return self
     */
    public function setSellerAssignedID(?\horstoeko\orderx\entities\basic\udt\IDType $sellerAssignedID = null)
    {
        $this->sellerAssignedID = $sellerAssignedID;
        return $this;
    }

    /**
     * Gets as buyerAssignedID
     *
     * Buyer Assigned ID
     *
     * @return \horstoeko\orderx\entities\basic\udt\IDType
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
     * @param  \horstoeko\orderx\entities\basic\udt\IDType $buyerAssignedID
     * @return self
     */
    public function setBuyerAssignedID(?\horstoeko\orderx\entities\basic\udt\IDType $buyerAssignedID = null)
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
}
