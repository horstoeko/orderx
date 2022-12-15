<?php

namespace horstoeko\orderx\tests;

use horstoeko\orderx\tests\TestCase;
use horstoeko\orderx\OrderDocument;
use horstoeko\orderx\OrderProfiles;

class OrderDocumentTest extends TestCase
{
    /**
     * @covers \horstoeko\orderx\OrderDocument
     */
    public function testDocumentCreationBasic(): void
    {
        $doc = new OrderDocument(OrderProfiles::PROFILE_BASIC);
        $this->assertNotNull($doc);
        $this->assertEquals(OrderProfiles::PROFILE_BASIC, $doc->getProfileId());
        $this->assertArrayHasKey("contextparameter", $doc->getProfileDefinition());
        $this->assertArrayHasKey("name", $doc->getProfileDefinition());
        $this->assertEquals("urn:order-x.eu:1p0:basic", $doc->getProfileDefinition()["contextparameter"]);
        $this->assertEquals("basic", $doc->getProfileDefinition()["name"]);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocument
     */
    public function testDocumentCreationComfort(): void
    {
        $doc = new OrderDocument(OrderProfiles::PROFILE_COMFORT);
        $this->assertNotNull($doc);
        $this->assertEquals(OrderProfiles::PROFILE_COMFORT, $doc->getProfileId());
        $this->assertArrayHasKey("contextparameter", $doc->getProfileDefinition());
        $this->assertArrayHasKey("name", $doc->getProfileDefinition());
        $this->assertEquals("urn:order-x.eu:1p0:comfort", $doc->getProfileDefinition()["contextparameter"]);
        $this->assertEquals("comfort", $doc->getProfileDefinition()["name"]);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocument
     */
    public function testDocumentCreationExtended(): void
    {
        $doc = new OrderDocument(OrderProfiles::PROFILE_EXTENDED);
        $this->assertNotNull($doc);
        $this->assertEquals(OrderProfiles::PROFILE_EXTENDED, $doc->getProfileId());
        $this->assertArrayHasKey("contextparameter", $doc->getProfileDefinition());
        $this->assertArrayHasKey("name", $doc->getProfileDefinition());
        $this->assertEquals("urn:order-x.eu:1p0:basicurn:order-x.eu:1p0:extended", $doc->getProfileDefinition()["contextparameter"]);
        $this->assertEquals("extended", $doc->getProfileDefinition()["name"]);
    }

    /**
     * @covers \horstoeko\orderx\OrderDocument
     */
    public function testDocumentInternals(): void
    {
        $doc = new OrderDocument(OrderProfiles::PROFILE_EXTENDED);
        $property = $this->getPrivateProperty('horstoeko\orderx\OrderDocument', 'serializerBuilder');
        $this->assertNotNull($property->getValue($doc));
        $property = $this->getPrivateProperty('horstoeko\orderx\OrderDocument', 'serializer');
        $this->assertNotNull($property->getValue($doc));
        $property = $this->getPrivateProperty('horstoeko\orderx\OrderDocument', 'invoiceObject');
        $this->assertNull($property->getValue($doc));
        $property = $this->getPrivateProperty('horstoeko\orderx\OrderDocument', 'objectHelper');
        $this->assertNotNull($property->getValue($doc));
        $this->assertNull($doc->getInvoiceObject());
    }
}