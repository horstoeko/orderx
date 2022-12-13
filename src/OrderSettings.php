<?php

/**
 * This file is a part of horstoeko/orderx.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\orderx;

/**
 * Class representing the general settings
 *
 * @category Order-X
 * @package  Order-X
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/orderx
 */
class OrderSettings
{
    /**
     * The number of decimals for amount values
     *
     * @var integer
     */
    protected static $amountDecimals = 2;

    /**
     * The number of decimals for quantity values
     *
     * @var integer
     */
    protected static $quantityDecimals = 2;

    /**
     * The number of decimals for percent values
     *
     * @var integer
     */
    protected static $percentDecimals = 2;

    /**
     * Get the number of decimals to use for amount values
     *
     * @return integer
     */
    public static function getAmountDecimals(): int
    {
        return self::$amountDecimals;
    }

    /**
     * Set the number of decimals to use for amount values
     *
     * @param  integer $amountDecimals
     * @return void
     */
    public static function setAmountDecimals(int $amountDecimals): void
    {
        self::$amountDecimals = $amountDecimals;
    }

    /**
     * Get the number of decimals to use for amount values
     *
     * @return integer
     */
    public static function getQuantityDecimals(): int
    {
        return self::$quantityDecimals;
    }

    /**
     * Set the number of decimals to use for quantity values
     *
     * @param  integer $quantityDecimals
     * @return void
     */
    public static function setQuantityDecimals(int $quantityDecimals): void
    {
        self::$quantityDecimals = $quantityDecimals;
    }

    /**
     * Get the number of decimals to use for percent values
     *
     * @return integer
     */
    public static function getPercentDecimals(): int
    {
        return self::$percentDecimals;
    }

    /**
     * Set the number of decimals to use for percent values
     *
     * @param  integer $percentDecimals
     * @return void
     */
    public static function setPercentDecimals(int $percentDecimals): void
    {
        self::$percentDecimals = $percentDecimals;
    }
}
