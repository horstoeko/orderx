<?php

/**
 * This file is a part of horstoeko/orderx.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\orderx\jms;

use \DOMText;
use \DOMElement;
use horstoeko\orderx\OrderSettings;
use JMS\Serializer\XmlSerializationVisitor;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Context;

/**
 * Class representing a collection of serialization handlers for amount formatting and so on
 *
 * @category Order-X
 * @package  Order-X
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/orderx
 */
class OrderTypesHandler implements SubscribingHandlerInterface
{
    /**
     * Return format:
     *
     *      array(
     *          array(
     *              'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
     *              'format' => 'json',
     *              'type' => 'DateTime',
     *              'method' => 'serializeDateTimeToJson',
     *          ),
     *      )
     *
     * @return array
     */
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'xml',
                'type' => 'horstoeko\orderx\entities\basic\udt\AmountType',
                'method' => 'serializeAmountType'
            ],
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'xml',
                'type' => 'horstoeko\orderx\entities\comfort\udt\AmountType',
                'method' => 'serializeAmountType'
            ],
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'xml',
                'type' => 'horstoeko\orderx\entities\extended\udt\AmountType',
                'method' => 'serializeAmountType'
            ],
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'xml',
                'type' => 'horstoeko\orderx\entities\basic\udt\QuantityType',
                'method' => 'serializeQuantityType'
            ],
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'xml',
                'type' => 'horstoeko\orderx\entities\comfort\udt\QuantityType',
                'method' => 'serializeQuantityType'
            ],
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'xml',
                'type' => 'horstoeko\orderx\entities\extended\udt\QuantityType',
                'method' => 'serializeQuantityType'
            ],
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'xml',
                'type' => 'horstoeko\orderx\entities\basic\udt\PercentType',
                'method' => 'serializePercentType'
            ],
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'xml',
                'type' => 'horstoeko\orderx\entities\comfort\udt\PercentType',
                'method' => 'serializePercentType'
            ],
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'xml',
                'type' => 'horstoeko\orderx\entities\extended\udt\PercentType',
                'method' => 'serializePercentType'
            ],
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'xml',
                'type' => 'horstoeko\orderx\entities\basic\udt\IndicatorType',
                'method' => 'serializeIndicatorType'
            ],
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'xml',
                'type' => 'horstoeko\orderx\entities\comfort\udt\IndicatorType',
                'method' => 'serializeIndicatorType'
            ],
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'xml',
                'type' => 'horstoeko\orderx\entities\extended\udt\IndicatorType',
                'method' => 'serializeIndicatorType'
            ],
        ];
    }

    /**
     * Serialize Anount type
     * The amounts will be serialized with a precission of 2 digits
     *
     * @param XmlSerializationVisitor $visitor
     * @param mixed $data
     * @param array $type
     * @param Context $context
     * @return DOMText|false
     */
    public function serializeAmountType(XmlSerializationVisitor $visitor, $data, array $type, Context $context)
    {
        $node = $visitor->getDocument()->createTextNode(number_format($data->value(), OrderSettings::getAmountDecimals(), ".", ""));

        if ($data->getCurrencyID() != null) {
            $attr = $visitor->getDocument()->createAttribute("currencyID");
            $attr->value = $data->getCurrencyID();
            $visitor->getCurrentNode()->appendChild($attr);
        }

        return $node;
    }

    /**
     * Serialize quantity type
     * The quantity will be serialized with a precission of 4 digits
     *
     * @param XmlSerializationVisitor $visitor
     * @param mixed $data
     * @param array $type
     * @param Context $context
     * @return DOMText|false
     */
    public function serializeQuantityType(XmlSerializationVisitor $visitor, $data, array $type, Context $context)
    {
        $node = $visitor->getDocument()->createTextNode(number_format($data->value(), OrderSettings::getQuantityDecimals(), ".", ""));

        if ($data->getUnitCode() != null) {
            $attr = $visitor->getDocument()->createAttribute("unitCode");
            $attr->value = $data->getUnitCode();
            $visitor->getCurrentNode()->appendChild($attr);
        }

        return $node;
    }

    /**
     * Serialize a percantage value
     * The valze will be serialized with a precission of 2 digits
     *
     * @param XmlSerializationVisitor $visitor
     * @param mixed $data
     * @param array $type
     * @param Context $context
     * @return DOMText|false
     */
    public function serializePercentType(XmlSerializationVisitor $visitor, $data, array $type, Context $context)
    {
        $node = $visitor->getDocument()->createTextNode(number_format($data->value(), OrderSettings::getPercentDecimals(), ".", ""));
        return $node;
    }

    /**
     * Serialize a inditcator
     * False and true values will be serialized correctly (false won't be serialized
     * in the default implementation)
     *
     * @param XmlSerializationVisitor $visitor
     * @param mixed $data
     * @param array $type
     * @param Context $context
     * @return DOMElement|false
     */
    public function serializeIndicatorType(XmlSerializationVisitor $visitor, $data, array $type, Context $context)
    {
        $node = $visitor->getDocument()->createElement('udt:Indicator', $data->getIndicator() == false ? 'false' : 'true');
        return $node;
    }
}
