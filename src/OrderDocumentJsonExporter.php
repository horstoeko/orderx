<?php

/**
 * This file is a part of horstoeko/order-x.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\orderx;

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
     * Constructor
     *
     * @param OrderDocument $document
     *
     * @codeCoverageIgnore
     */
    public function __construct(OrderDocument $document)
    {
        $this->document = $document;
    }

    /**
     * Returns the order object as a json string
     *
     * @return string
     */
    public function toJsonString(): string
    {
        return $this->document->serializeAsJson();
    }

    /**
     * Returns the order object as a json object
     *
     * @return null|stdClass
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
}
