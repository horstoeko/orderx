<?php

declare(strict_types=1);

/**
 * This file is a part of horstoeko/orderx.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\orderx;

use horstoeko\stringmanagement\FileUtils;
use horstoeko\stringmanagement\PathUtils;
use \Symfony\Component\Validator\Validation;
use \Symfony\Component\Validator\ConstraintViolationListInterface;
use \Symfony\Component\Validator\Validator\ValidatorInterface;

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
     * @var OrderDocument
     */
    private $document;

    /**
     * The validator instance
     *
     * @var ValidatorInterface;
     */
    private $validator = null;

    /**
     * Constructor
     *
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
     * @return void
     */
    private function initValidator(): void
    {
        $validatorBuilder = Validation::createValidatorBuilder();
        $dirname = PathUtils::combinePathWithFile(PathUtils::combineAllPaths(dirname(__FILE__) , 'validation', $this->document->profileDef['name']), '*.yml');
        $files = $this->globRecursive($dirname);

        foreach ($files as $file) {
            $validatorBuilder->addYamlMapping($file);
        }

        $this->validator = $validatorBuilder->getValidator();
    }

    /**
     * Helper for find all files by pattern
     *
     * @codeCoverageIgnore
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
