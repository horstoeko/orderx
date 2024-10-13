<?php

/**
 * This file is a part of horstoeko/orderx.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\orderx;

use Throwable;
use SimpleXMLElement;
use horstoeko\orderx\OrderProfiles;
use horstoeko\orderx\exception\OrderUnknownProfileException;
use horstoeko\orderx\exception\OrderUnknownProfileIdException;
use horstoeko\orderx\exception\OrderUnknownXmlContentException;

/**
 * Class representing the profile resolver
 *
 * @category Order-X
 * @package  Order-X
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/orderx
 */
class OrderProfileResolver
{
    /**
     * Resolve profile id and profile definition by the content of $xmlContent
     *
     * @param  string $xmlContent
     * @return array
     * @throws OrderUnknownProfileException
     */
    public static function resolve(string $xmlContent): array
    {
        $prevUseInternalErrors = \libxml_use_internal_errors(true);

        try {
            libxml_clear_errors();
            $xmldocument = new SimpleXMLElement($xmlContent);
            $typeelement = $xmldocument->xpath('/rsm:SCRDMCCBDACIOMessageStructure/rsm:ExchangedDocumentContext/ram:GuidelineSpecifiedDocumentContextParameter/ram:ID');
            if (libxml_get_last_error()) {
                throw new OrderUnknownXmlContentException();
            }
        } catch (Throwable $e) {
            throw new OrderUnknownXmlContentException();
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($prevUseInternalErrors);
        }

        if (!is_array($typeelement) || !isset($typeelement[0])) {
            throw new OrderUnknownXmlContentException();
        }

        /**
         * @var int $profile
         * @var array $profiledef
         */
        foreach (OrderProfiles::PROFILEDEF as $profile => $profiledef) {
            if ($typeelement[0] == $profiledef["contextparameter"]) {
                return [$profile, $profiledef];
            }
        }

        throw new OrderUnknownProfileException((string)$typeelement[0]);
    }

    /**
     * Resolve profile id by the content of $xmlContent
     *
     * @param  string $xmlContent
     * @return int
     * @throws OrderUnknownXmlContentException
     * @throws OrderUnknownProfileException
     */
    public static function resolveProfileId(string $xmlContent): int
    {
        return static::resolve($xmlContent)[0];
    }

    /**
     * Resolve profile definition by the content of $xmlContent
     *
     * @param  string $xmlContent
     * @return array
     * @throws OrderUnknownXmlContentException
     * @throws OrderUnknownProfileException
     */
    public static function resolveProfileDef(string $xmlContent): array
    {
        return static::resolve($xmlContent)[1];
    }

    /**
     * Resolve profile id and profile definition by it's id
     *
     * @param  int $profileId
     * @return array
     * @throws OrderUnknownProfileException
     */
    public static function resolveById(int $profileId): array
    {
        if (!isset(OrderProfiles::PROFILEDEF[$profileId])) {
            throw new OrderUnknownProfileIdException($profileId);
        }

        return [$profileId, OrderProfiles::PROFILEDEF[$profileId]];
    }

    /**
     * Resolve profile profile definition by it's id
     *
     * @param  int $profileId
     * @return array
     * @throws OrderUnknownProfileException
     */
    public static function resolveProfileDefById(int $profileId): array
    {
        $resolved = static::resolveById($profileId);

        return $resolved[1];
    }
}
