<?php

namespace horstoeko\orderx\tests\testcases;

use horstoeko\orderx\tests\TestCase;
use horstoeko\orderx\OrderDocumentValidator;
use horstoeko\orderx\codelists\OrderDocumentTypes;
use horstoeko\orderx\tests\traits\HandlesCreateTestDocument;

class OrderDocumentValidatorTest extends TestCase
{
    use HandlesCreateTestDocument;

    /**
     * @covers \horstoeko\orderx\OrderDocumentValidator
     */
    public function testInitValidator(): void
    {
        $document = $this->createTestDocument(OrderDocumentTypes::ORDER);
        $documentValidator = new OrderDocumentValidator($document);

        $property = $this->getPrivatePropertyFromObject($documentValidator, "validator");
        $propertyValue = $property->getValue($documentValidator);

        $this->assertInstanceOf('Symfony\Component\Validator\Validator\ValidatorInterface', $propertyValue);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocumentValidator
     */
    public function testValidateDocument(): void
    {
        $document = $this->createTestDocument(OrderDocumentTypes::ORDER);
        $documentValidator = new OrderDocumentValidator($document);
        $documentValidatorResult = $documentValidator->validateDocument();

        $this->assertInstanceOf('Symfony\Component\Validator\ConstraintViolationListInterface', $documentValidatorResult);
        $this->assertEquals(0, count($documentValidatorResult));
    }
}
