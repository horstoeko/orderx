<?php

/**
 * This file is a part of horstoeko/orderx.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\orderx;

use horstoeko\stringmanagement\PathUtils;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class representing the document validator for incoming documents
 *
 * @category Order-X
 * @package  Order-X
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/orderx
 */
class OrderDocumentValidator
{
    /**
     * The invoice document reference
     *
     * @var \horstoeko\orderx\OrderDocument
     */
    private $document;

    /**
     * The validator instance
     *
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface;
     */
    private $validator = null;

    /**
     * Constructor
     *
     * @param OrderDocument $document
     */
    public function __construct(OrderDocument $document)
    {
        $this->document = $document;
        $this->initValidator();
    }

    /**
     * Perform the validation of the document
     *
     * @return ConstraintViolationListInterface
     */
    public function validateDocument(): ConstraintViolationListInterface
    {
        return $this->validator->validate($this->document->getInvoiceObject(), null, ['xsd_rules']);
    }

    /**
     * Initialize the internal validator object
     *
     * @return void
     */
    private function initValidator(): void
    {
        $validatorBuilder = Validation::createValidatorBuilder();

        $validatorYamlFiles = PathUtils::combinePathWithFile(
            PathUtils::combineAllPaths(
                OrderSettings::getValidationDirectory(),
                $this->document->getProfileDefinition()['name']
            ),
            '*.yml'
        );

        $validatorYamlFiles = $this->globRecursive($validatorYamlFiles);

        foreach ($validatorYamlFiles as $validatorYamlFile) {
            $validatorBuilder->addYamlMapping($validatorYamlFile);
        }

        $this->validator = $validatorBuilder->getValidator();
    }

    /**
     * Helper for find all files by pattern
     *
     * @param string $pattern
     * @param integer $flags
     * @return array
     */
    private function globRecursive(string $pattern, int $flags = 0): array
    {
        $files = glob($pattern, $flags);

        foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge($files, $this->globRecursive($dir . '/' . basename($pattern), $flags));
        }

        return $files;
    }
}
