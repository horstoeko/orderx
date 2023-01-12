<?php

/**
 * This file is a part of horstoeko/orderx.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\orderx\exception;

use Throwable;

/**
 * Class representing the exception if an invalid date format
 * is presented to the system
 *
 * @category Order-X
 * @package  Order-X
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/orderx
 */
class OrderUnknownDateFormatException extends OrderBaseException
{
    /**
     * Constructor
     *
     * @param Throwable|null $previous
     */
    public function __construct(string $dateFormatCode, ?Throwable $previous = null)
    {
        parent::__construct(sprintf("Invalid date format %s", $dateFormatCode), OrderExceptionCodes::UNKNOWNDATEFORMAT, $previous);
    }
}
