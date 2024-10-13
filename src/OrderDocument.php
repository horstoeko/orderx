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
use horstoeko\orderx\OrderProfileResolver;
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
     * @var      integer
     */
    private $profileId = -1;

    /**
     * @internal
     * Internal profile definition (see OrderProfiles.php)
     * @var      array
     */
    private $profileDefinition = [];

    /**
     * @internal
     * Serializer builder
     * @var      SerializerBuilder
     */
    private $serializerBuilder;

    /**
     * @internal
     * Serializer
     * @var      SerializerInterface
     */
    private $serializer;

    /**
     * @internal
     * The internal invoice object
     * @var      \horstoeko\orderx\entities\basic\rsm\SCRDMCCBDACIOMessageStructure|\horstoeko\orderx\entities\comfort\rsm\SCRDMCCBDACIOMessageStructure|\horstoeko\orderx\entities\extended\rsm\SCRDMCCBDACIOMessageStructure
     */
    private $orderObject = null;

    /**
     * @internal
     * Object Helper
     * @var      OrderObjectHelper
     */
    private $objectHelper = null;

    /**
     * Constructor
     *
     * @param integer $profile
     * The ID of the profile of the document
     *
     * @codeCoverageIgnore
     */
    final protected function __construct(int $profile)
    {
        $this->initProfile($profile);
        $this->initObjectHelper();
        $this->initSerialzer();
    }

    /**
     * @internal
     *
     * Returns the internal order object (created by the
     * serializer). This is used e.g. in the validator
     *
     * @return object
     */
    public function getOrderObject()
    {
        return $this->orderObject;
    }

    /**
     * Create a new instance of the internal order object
     *
     * @return \horstoeko\orderx\entities\basic\rsm\SCRDMCCBDACIOMessageStructure|\horstoeko\orderx\entities\comfort\rsm\SCRDMCCBDACIOMessageStructure|\horstoeko\orderx\entities\extended\rsm\SCRDMCCBDACIOMessageStructure
     */
    protected function createOrderObject()
    {
        $this->orderObject = $this->getObjectHelper()->getOrderX();

        return $this->orderObject;
    }

    /**
     * Get the instance of the internal serializuer
     *
     * @return SerializerInterface
     */
    public function getSerializer()
    {
        return $this->serializer;
    }

    /**
     * Get object helper instance
     *
     * @return OrderObjectHelper
     */
    public function getObjectHelper()
    {
        return $this->objectHelper;
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
     * Get a parameter from profile definition
     *
     * @param  string $parameterName
     * @return mixed
     */
    public function getProfileDefinitionParameter(string $parameterName)
    {
        $profileDefinition = $this->getProfileDefinition();

        if (is_array($profileDefinition) && isset($profileDefinition[$parameterName])) {
            return $profileDefinition[$parameterName];
        }

        throw new \Exception(sprintf("Unknown profile definition parameter %s", $parameterName));
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
        $this->profileDefinition = OrderProfileResolver::resolveProfileDefById($profile);

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
                $this->getProfileDefinitionParameter("name"),
                'qdt'
            ),
            sprintf(
                'horstoeko\orderx\entities\%s\qdt',
                $this->getProfileDefinitionParameter("name")
            )
        );

        $this->serializerBuilder->addMetadataDir(
            PathUtils::combineAllPaths(
                OrderSettings::getYamlDirectory(),
                $this->getProfileDefinitionParameter("name"),
                'ram'
            ),
            sprintf(
                'horstoeko\orderx\entities\%s\ram',
                $this->getProfileDefinitionParameter("name")
            )
        );

        $this->serializerBuilder->addMetadataDir(
            PathUtils::combineAllPaths(
                OrderSettings::getYamlDirectory(),
                $this->getProfileDefinitionParameter("name"),
                'rsm'
            ),
            sprintf(
                'horstoeko\orderx\entities\%s\rsm',
                $this->getProfileDefinitionParameter("name")
            )
        );

        $this->serializerBuilder->addMetadataDir(
            PathUtils::combineAllPaths(
                OrderSettings::getYamlDirectory(),
                $this->getProfileDefinitionParameter("name"),
                'udt'
            ),
            sprintf(
                'horstoeko\orderx\entities\%s\udt',
                $this->getProfileDefinitionParameter("name")
            )
        );

        $this->serializerBuilder->addDefaultListeners();
        $this->serializerBuilder->addDefaultHandlers();

        $this->serializerBuilder->configureHandlers(
            function (HandlerRegistryInterface $handler) {
                $handler->registerSubscribingHandler(new BaseTypesHandler());
                $handler->registerSubscribingHandler(new XmlSchemaDateHandler());
                $handler->registerSubscribingHandler(new OrderTypesHandler());
            }
        );

        $this->serializer = $this->serializerBuilder->build();

        return $this;
    }
    /**
     * Deserialize XML content to internal invoice object
     *
     * @param  string $xmlContent
     * @return \horstoeko\orderx\entities\basic\rsm\SCRDMCCBDACIOMessageStructure|\horstoeko\orderx\entities\comfort\rsm\SCRDMCCBDACIOMessageStructure|\horstoeko\orderx\entities\extended\rsm\SCRDMCCBDACIOMessageStructure
     */
    public function deserialize($xmlContent)
    {
        $this->orderObject = $this->getSerializer()->deserialize($xmlContent, 'horstoeko\orderx\entities\\' . $this->getProfileDefinitionParameter("name") . '\rsm\SCRDMCCBDACIOMessageStructure', 'xml');

        return $this->orderObject;
    }

    /**
     * Serialize internal invoice object as XML
     *
     * @return string
     */
    public function serializeAsXml(): string
    {
        return $this->getSerializer()->serialize($this->getOrderObject(), 'xml');
    }

    /**
     * Serialize internal invoice object as JSON
     *
     * @return string
     */
    public function serializeAsJson(): string
    {
        return $this->getSerializer()->serialize($this->getOrderObject(), 'json');
    }
}
