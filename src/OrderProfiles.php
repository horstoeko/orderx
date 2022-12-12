<?php

/**
 * This file is a part of horstoeko/orderx.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\orderx;

/**
 * Class representing the document profiles
 *
 * @category Order-X
 * @package  Order-X
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/orderx
 */
class OrderProfiles
{
    /**
     * Internal constant that identifies the BASIC profile
     */
    const PROFILE_BASIC = 0;

    /**
     * Internal constant that identifies the EN16931 profile
     */
    const PROFILE_COMFORT = 2;

    /**
     * Internal constant that identifies the EXTENDED profile
     */
    const PROFILE_EXTENDED = 3;

    /**
     * The definitions of the several profiles
     */
    const PROFILEDEF = [
        self::PROFILE_BASIC => [
            'name' => 'basic',
            'altname' => 'BASIC',
            'description' => 'The Order-X BASIC profile has been built to addres a “standard” purchase order process, with no VAT information, except optionally on Totals, allowing single delivery or pick up mode in a single location',
            'contextparameter' => 'urn:order-x.eu:1p0:basic',
            'attachmentfilename' => 'order-x.xml',
            'xmpname' => 'BASIC',
            'xsdfilename' => 'SCRDMCCBDACIOMessageStructure_100pD20B.xsd',
            'schematronfilename' => 'SCRDMCCBDACIOMessageStructure_100pD20B_BASIC.sch',
        ],
        self::PROFILE_COMFORT => [
            'name' => 'comfort',
            'altname' => 'COMFORT',
            'description' => 'The COMFORT Profile is the BASIC profile with more information available optionnally, allowing multiple delivery, Buyer Requisitioner Party, Invoicee Party, VAT information except VAT breakdown, Allowances and Charges on line and document levels and more details',
            'contextparameter' => 'urn:order-x.eu:1p0:comfort',
            'attachmentfilename' => 'order-x.xml',
            'xmpname' => 'COMFORT',
            'xsdfilename' => 'SCRDMCCBDACIOMessageStructure_100pD20B.xsd',
            'schematronfilename' => 'SCRDMCCBDACIOMessageStructure_100pD20B_COMFORT.sch',
        ],
        self::PROFILE_EXTENDED => [
            'name' => 'extended',
            'altname' => 'EXTENDED',
            'description' => 'The EXTENDED Profile is the COMFORT profile, with more information on line level, allowing for instance multiple delivery location, Buyer Requisitioner on line level, minimum and maximum requested quantities, Seller and Buyer legal Address (if different of Address), VAT Breakdown, … and all information on EXTENDED profile for Factur-X that can be present at the Order step of the global supply chain.',
            'contextparameter' => 'urn:order-x.eu:1p0:basicurn:order-x.eu:1p0:extended',
            'attachmentfilename' => 'order-x.xml',
            'xmpname' => 'EXTENDED',
            'xsdfilename' => 'SCRDMCCBDACIOMessageStructure_100pD20B.xsd',
            'schematronfilename' => 'SCRDMCCBDACIOMessageStructure_100pD20B_EXTENDED.sch',
        ],
    ];
}
