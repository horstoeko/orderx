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
 * Class representing the exception if a profile can't be determained
 *
 * @category Order-X
 * @package  Order-X
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/orderx
 */
class OrderCannotFindProfileString extends OrderBaseException
{
    /**
     * Constructor
     *
     * @param Throwable|null $previous
     */
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct("The string containing the profile was not found", OrderExceptionCodes::CANNOTFINDPROFILESTRING, $previous);
    }
}
