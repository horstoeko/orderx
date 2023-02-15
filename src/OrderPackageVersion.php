<?php

/**
 * This file is a part of horstoeko/orderx.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\orderx;

use \Composer\InstalledVersions as ComposerInstalledVersions;

/**
 * Class representing some tools for getting the package version
 * of this package
 *
 * @category Order-X
 * @package  Order-X
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/orderx
 */
class OrderPackageVersion
{
    public static function getInstalledVersion(): string
    {
        return ComposerInstalledVersions::getVersion('horstoeko/orderx');
    }
}
