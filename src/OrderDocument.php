<?php

/**
 * This file is a part of horstoeko/orderx.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\orderx;

use \GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\BaseTypesHandler;
use \GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\XmlSchemaDateHandler;
use \horstoeko\orderx\OrderObjectHelper;
use \horstoeko\orderx\entities\en16931\rsm\CrossIndustryInvoiceType;
use \horstoeko\orderx\jms\OrderTypesHandler;
use \JMS\Serializer\Handler\HandlerRegistryInterface;
use \JMS\Serializer\SerializerBuilder;
use \JMS\Serializer\SerializerInterface;

/**
 * Class representing the document basics
 *
 * @category Order-X
 * @package  Order-X
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/orderx
 */
class OrderDocument
{
    /**
     * @internal
     * Internal profile id (see OrderProfiles.php)
     * @var integer
     */
    public $profile = -1;

    /**
     * @internal
     * Internal profile definition (see OrderProfiles.php)
     * @var array
     */
    public $profiledef = [];

    /**
     * @internal
     * Serializer builder
     * @var SerializerBuilder
     */
    protected $serializerBuilder;

    /**
     * @internal
     * Serializer
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @internal
     * The internal invoice object
     * @var CrossIndustryInvoiceType
     */
    protected $invoiceObject = null;

    /**
     * @internal
     * Object Helper
     * @var OrderObjectHelper
     */
    protected $objectHelper = null;

    /**
     * Constructor
     *
     * @param integer $profile
     * The ID of the profile of the document
     *
     * @codeCoverageIgnore
     */
    public function __construct(int $profile)
    {
        $this->initProfile($profile);
        $this->initObjectHelper();
        $this->initSerialzer();
    }

    /**
     * @internal
     *
     * Returns the internal invoice object (created by the
     * serializer). This is used e.g. in the validator
     *
     * @return object
     */
    public function getInvoiceObject()
    {
        return $this->invoiceObject;
    }

    /**
     * @internal
     *
     * Sets the internal profile definitions
     *
     * @param integer $profile
     * The internal id of the profile (see OrderProfiles.php)
     *
     * @return OrderDocument
     */
    private function initProfile(int $profile): OrderDocument
    {
        $this->profile = $profile;
        $this->profiledef = OrderProfiles::PROFILEDEF[$profile];

        return $this;
    }

    /**
     * @internal
     *
     * Build the internal object helper
     * @codeCoverageIgnore
     *
     * @return OrderDocument
     */
    private function initObjectHelper(): OrderDocument
    {
        $this->objectHelper = new OrderObjectHelper($this->profile);

        return $this;
    }

    /**
     * @internal
     *
     * Build the internal serialzer
     * @codeCoverageIgnore
     *
     * @return OrderDocument
     */
    private function initSerialzer(): OrderDocument
    {
        $serializerBuilder = SerializerBuilder::create();

        $this->serializerBuilder = $serializerBuilder;
        $this->serializerBuilder->addMetadataDir(dirname(__FILE__) . '/yaml/' . $this->profiledef["name"] . '/qdt', 'horstoeko\orderx\entities\\' . $this->profiledef["name"] . '\qdt');
        $this->serializerBuilder->addMetadataDir(dirname(__FILE__) . '/yaml/' . $this->profiledef["name"] . '/ram', 'horstoeko\orderx\entities\\' . $this->profiledef["name"] . '\ram');
        $this->serializerBuilder->addMetadataDir(dirname(__FILE__) . '/yaml/' . $this->profiledef["name"] . '/rsm', 'horstoeko\orderx\entities\\' . $this->profiledef["name"] . '\rsm');
        $this->serializerBuilder->addMetadataDir(dirname(__FILE__) . '/yaml/' . $this->profiledef["name"] . '/udt', 'horstoeko\orderx\entities\\' . $this->profiledef["name"] . '\udt');
        $this->serializerBuilder->addDefaultListeners();
        $this->serializerBuilder->configureHandlers(function (HandlerRegistryInterface $handler) use ($serializerBuilder) {
            $serializerBuilder->addDefaultHandlers();
            $handler->registerSubscribingHandler(new BaseTypesHandler());
            $handler->registerSubscribingHandler(new XmlSchemaDateHandler());
            $handler->registerSubscribingHandler(new OrderTypesHandler());
        });

        $this->serializer = $this->serializerBuilder->build();

        return $this;
    }
}
