<?php

namespace horstoeko\orderx\entities\extended\ram;

/**
 * Class representing TradePaymentTermsType
 *
 * Trade Payment Terms
 * XSD Type: TradePaymentTermsType
 */
class TradePaymentTermsType
{

    /**
     * Description
     *
     * @var string[] $description
     */
    private $description = [
        
    ];

    /**
     * Adds as description
     *
     * Description
     *
     * @return self
     * @param  string $description
     */
    public function addToDescription($description)
    {
        $this->description[] = $description;
        return $this;
    }

    /**
     * isset description
     *
     * Description
     *
     * @param  int|string $index
     * @return bool
     */
    public function issetDescription($index)
    {
        return isset($this->description[$index]);
    }

    /**
     * unset description
     *
     * Description
     *
     * @param  int|string $index
     * @return void
     */
    public function unsetDescription($index)
    {
        unset($this->description[$index]);
    }

    /**
     * Gets as description
     *
     * Description
     *
     * @return string[]
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
    public function setDescription(array $description)
    {
        $this->description = $description;
        return $this;
    }
}
