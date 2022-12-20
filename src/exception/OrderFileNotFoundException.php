<?php

declare(strict_types=1);

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
class OrderFileNotFoundException extends OrderBaseException
{
    /**
     * Constructor
     *
     * @param string $filename
     * @param Throwable|null $previous
     */
    public function __construct(string $filename, ?Throwable $previous = null)
    {
        parent::__construct(sprintf("The file %s was not found", $filename), -2000, $previous);
    }
}
