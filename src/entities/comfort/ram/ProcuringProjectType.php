<?php

namespace horstoeko\orderx\entities\comfort\ram;

/**
 * Class representing ProcuringProjectType
 *
 * Procuring Project
 * XSD Type: ProcuringProjectType
 */
class ProcuringProjectType
{

    /**
     * ID
     *
     * @var \horstoeko\orderx\entities\comfort\udt\IDType $iD
     */
    private $iD = null;

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
     * @return \horstoeko\orderx\entities\comfort\udt\IDType
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
     * @param  \horstoeko\orderx\entities\comfort\udt\IDType $iD
     * @return self
     */
    public function setID(\horstoeko\orderx\entities\comfort\udt\IDType $iD)
    {
        $this->iD = $iD;
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
