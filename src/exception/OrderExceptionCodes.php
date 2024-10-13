<?php

/**
 * This file is a part of horstoeko/orderx.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\orderx\exception;

/**
 * Class representing the internal coes for Order-X-Exceptions
 *
 * @category Order-X
 * @package  Order-X
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/orderx
 */
class OrderExceptionCodes
{
    public const CANNOTFINDPROFILESTRING = -1101;
    public const UNKNOWNPROFILE = -1102;
    public const MIMETYPENOTSUPPORTED = -1103;
    public const UNKNOWNDATEFORMAT = -1104;
    public const NOVALIDATTACHMENTFOUNDINPDF = -1105;
    public const UNKNOWNPROFILEPARAMETER = -1106;
    public const UNKNOWNSYNTAX = -1107;
    public const FILENOTFOUND = -2000;
    public const FILENOTREADABLE = -2001;
}
