<?php

/**
 * This file is a part of horstoeko/order-x.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\orderx;

use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\BaseTypesHandler;
use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\XmlSchemaDateHandler;
use horstoeko\orderx\jms\OrderTypesHandler;
use horstoeko\stringmanagement\PathUtils;
use JMS\Serializer\Exception\RuntimeException as ExceptionRuntimeException;
use JMS\Serializer\Handler\HandlerRegistryInterface;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use stdClass;

/**
 * Class representing the export of a order-x document
 * in JSON format
 *
 * @category Order-X
 * @package  Order-X
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/orderx
 */
class OrderDocumentJsonExporter
{
    /**
     * The instance to the order-x document
     *
     * @var OrderDocument
     */
    private $document = null;

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
     * Constructor
     *
     * @param OrderDocument $document
     *
     * @codeCoverageIgnore
     */
    public function __construct(OrderDocument $document)
    {
        $this->document = $document;
        $this->initSerialzer();
    }

    /**
     * Returns the order object as a json string
     *
     * @return string
     */
    public function toJsonString(): string
    {
        return $this->serializer->serialize($this->document->getOrderObject(), 'json');
    }

    /**
     * Returns the order object as a json object
     *
     * @return null|stdClass
     * @throws ExceptionRuntimeException
     */
    public function toJsonObject(): ?\stdClass
    {
        $jsonObject = json_decode($this->toJsonString());

        return $jsonObject;
    }

    /**
     * Returns the order object as a pretty printed json string
     *
     * @return string|boolean
     */
    public function toPrettyJsonString()
    {
        return json_encode($this->toJsonObject(), JSON_PRETTY_PRINT);
    }

    /**
     * @internal
     *
     * Build the internal serialzer
     *
     * @return OrderDocumentJsonExporter
     *
     * @codeCoverageIgnore
     */
    private function initSerialzer(): OrderDocumentJsonExporter
    {
        $this->serializerBuilder = SerializerBuilder::create();

        $this->serializerBuilder->addMetadataDir(
            PathUtils::combineAllPaths(
                OrderSettings::getYamlDirectory(),
                $this->document->getProfileDefinition()["name"],
                'qdt'
            ),
            sprintf(
                'horstoeko\orderx\entities\%s\qdt',
                $this->document->getProfileDefinition()["name"]
            )
        );
        $this->serializerBuilder->addMetadataDir(
            PathUtils::combineAllPaths(
                OrderSettings::getYamlDirectory(),
                $this->document->getProfileDefinition()["name"],
                'ram'
            ),
            sprintf(
                'horstoeko\orderx\entities\%s\ram',
                $this->document->getProfileDefinition()["name"]
            )
        );
        $this->serializerBuilder->addMetadataDir(
            PathUtils::combineAllPaths(
                OrderSettings::getYamlDirectory(),
                $this->document->getProfileDefinition()["name"],
                'rsm'
            ),
            sprintf(
                'horstoeko\orderx\entities\%s\rsm',
                $this->document->getProfileDefinition()["name"]
            )
        );
        $this->serializerBuilder->addMetadataDir(
            PathUtils::combineAllPaths(
                OrderSettings::getYamlDirectory(),
                $this->document->getProfileDefinition()["name"],
                'udt'
            ),
            sprintf(
                'horstoeko\orderx\entities\%s\udt',
                $this->document->getProfileDefinition()["name"]
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
}
