<?php

/**
 * This file is a part of horstoeko/orderx.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\orderx;

use Exception;
use DOMDocument;
use LibXMLError;
use horstoeko\stringmanagement\PathUtils;
use horstoeko\orderx\OrderDocument;
use horstoeko\orderx\OrderSettings;

/**
 * Class representing the validator against XSD for documents
 *
 * @category Order-X
 * @package  Order-X
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/orderx
 */
class OrderXsdValidator
{
    /**
     * The order document reference
     *
     * @var OrderDocument
     */
    private $document;

    /**
     * Internal error bag
     *
     * @var array
     */
    private $errorBag = [];

    /**
     * Constructor
     *
     * @codeCoverageIgnore
     * @param              OrderDocument $document
     */
    public function __construct(OrderDocument $document)
    {
        $this->document = $document;
    }

    /**
     * Perform validation of document
     *
     * @return OrderXsdValidator
     */
    public function validate(): OrderXsdValidator
    {
        $this->clearErrorBag();
        $this->initLibXml();

        try {
            if (!$this->getDocumentContentAsDomDocument()->schemaValidate($this->getDocumentXsdFilename())) {
                $this->pushLibXmlErrorsToErrorBag();
            }
        } catch (Exception $exception) {
            $this->addToErrorBag($exception);
        } finally {
            $this->finalizeLibXml();
        }

        return $this;
    }

    /**
     * Returns true if validation passed otherwise false
     *
     * @return boolean
     */
    public function validationPased(): bool
    {
        return empty($this->errorBag);
    }

    /**
     * Returns true if validation failed otherwise false
     *
     * @return boolean
     */
    public function validationFailed(): bool
    {
        return !$this->validationPased();
    }

    /**
     * Returns an array of all validation errors
     *
     * @return array
     */
    public function validationErrors(): array
    {
        return $this->errorBag;
    }

    /**
     * Initialize LibXML
     *
     * @return void
     */
    private function initLibXml(): void
    {
        libxml_use_internal_errors(true);
    }

    /**
     * Finalize LibXML
     *
     * @return void
     */
    private function finalizeLibXml(): void
    {
        libxml_clear_errors();
        libxml_use_internal_errors(false);
    }

    /**
     * Get the content of the document
     *
     * @return string
     */
    private function getDocumentContent(): string
    {
        return $this->document->serializeAsXml();
    }

    /**
     * Get the content of the document as a DOMDocument
     *
     * @return DOMDocument
     */
    private function getDocumentContentAsDomDocument(): DOMDocument
    {
        $doc = new DOMDocument();
        $doc->loadXML($this->getDocumentContent());

        return $doc;
    }

    /**
     * Get the XSD file (schema definition) for the document
     *
     * @return string
     */
    private function getDocumentXsdFilename(): string
    {
        $xsdFilename = PathUtils::combineAllPaths(
            OrderSettings::getSchemaDirectory(),
            $this->document->getProfileDefinitionParameter('name'),
            $this->document->getProfileDefinitionParameter('xsdfilename')
        );

        if (!file_exists($xsdFilename)) {
            throw new Exception(sprintf("XSD file '%s' not found", $xsdFilename));
        }

        return $xsdFilename;
    }

    /**
     * Clear the internal error bag
     *
     * @return void
     */
    private function clearErrorBag(): void
    {
        $this->errorBag = [];
    }

    /**
     * Add message to error bag
     *
     * @param  string|Exception|LibXMLError $error
     * @return void
     */
    private function addToErrorBag($error): void
    {
        if (is_string($error)) {
            $this->errorBag[] = $error;
        } elseif ($error instanceof Exception) {
            $this->errorBag[] = $error->getMessage();
        } elseif ($error instanceof LibXMLError) {
            $this->errorBag[] = sprintf('[line %d] %s : %s', $error->line, $error->code, $error->message);
        }
    }

    /**
     * Pushes validation errors to error bag
     *
     * @return void
     */
    private function pushLibXmlErrorsToErrorBag(): void
    {
        foreach (libxml_get_errors() as $xmlError) {
            $this->addToErrorBag($xmlError);
        }
    }
}
