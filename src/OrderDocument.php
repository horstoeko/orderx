<?php

/**
 * This file is a part of horstoeko/orderx.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\orderx;

use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\BaseTypesHandler;
use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\XmlSchemaDateHandler;
use horstoeko\orderx\jms\OrderTypesHandler;
use horstoeko\orderx\OrderObjectHelper;
use horstoeko\stringmanagement\PathUtils;
use JMS\Serializer\Handler\HandlerRegistryInterface;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;

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
    public $profileId = -1;

    /**
     * @internal
     * Internal profile definition (see OrderProfiles.php)
     * @var array
     */
    public $profileDefinition = [];

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
     * @var \horstoeko\orderx\entities\basic\rsm\SCRDMCCBDACIOMessageStructure|\horstoeko\orderx\entities\comfort\rsm\SCRDMCCBDACIOMessageStructure|\horstoeko\orderx\entities\extended\rsm\SCRDMCCBDACIOMessageStructure
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
     * Returns the selected profile id
     *
     * @return integer
     */
    public function getProfileId(): int
    {
        return $this->profileId;
    }

    /**
     * Returns the profile definition
     *
     * @return array
     */
    public function getProfileDefinition(): array
    {
        return $this->profileDefinition;
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
        $this->profileId = $profile;
        $this->profileDefinition = OrderProfiles::PROFILEDEF[$profile];

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
        $this->objectHelper = new OrderObjectHelper($this->profileId);

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
        $this->serializerBuilder = SerializerBuilder::create();

        $this->serializerBuilder->addMetadataDir(
            PathUtils::combineAllPaths(
                OrderSettings::getYamlDirectory(),
                $this->getProfileDefinition()["name"],
                'qdt'
            ),
            sprintf(
                'horstoeko\orderx\entities\%s\qdt',
                $this->getProfileDefinition()["name"]
            )
        );

        $this->serializerBuilder->addMetadataDir(
            PathUtils::combineAllPaths(
                OrderSettings::getYamlDirectory(),
                $this->getProfileDefinition()["name"],
                'ram'
            ),
            sprintf(
                'horstoeko\orderx\entities\%s\ram',
                $this->getProfileDefinition()["name"]
            )
        );

        $this->serializerBuilder->addMetadataDir(
            PathUtils::combineAllPaths(
                OrderSettings::getYamlDirectory(),
                $this->getProfileDefinition()["name"],
                'rsm'
            ),
            sprintf(
                'horstoeko\orderx\entities\%s\rsm',
                $this->getProfileDefinition()["name"]
            )
        );

        $this->serializerBuilder->addMetadataDir(
            PathUtils::combineAllPaths(
                OrderSettings::getYamlDirectory(),
                $this->getProfileDefinition()["name"],
                'udt'
            ),
            sprintf(
                'horstoeko\orderx\entities\%s\udt',
                $this->getProfileDefinition()["name"]
            )
        );

        $this->serializerBuilder->addDefaultListeners();
        $this->serializerBuilder->addDefaultHandlers();

        $this->serializerBuilder->configureHandlers(function (HandlerRegistryInterface $handler) {
            $handler->registerSubscribingHandler(new BaseTypesHandler());
            $handler->registerSubscribingHandler(new XmlSchemaDateHandler());
            $handler->registerSubscribingHandler(new OrderTypesHandler());
        });

        $this->serializer = $this->serializerBuilder->build();

        return $this;
    }
}
