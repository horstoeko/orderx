<?php

namespace horstoeko\orderx\entities\comfort\ram;

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
     * @var string $description
     */
    private $description = null;

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


}

