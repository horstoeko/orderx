<?php

namespace horstoeko\orderx\tests\testcases;

use horstoeko\orderx\OrderDocument;
use horstoeko\orderx\OrderDocumentBuilder;
use horstoeko\orderx\OrderProfiles;
use horstoeko\orderx\tests\TestCase;

class OrderDocumentBaseTest extends TestCase
{
    public function testDocumentCreationBasic(): void
    {
        $doc = OrderDocumentBuilder::createNew(OrderProfiles::PROFILE_BASIC);
        $this->assertNotNull($doc);
        $this->assertEquals(OrderProfiles::PROFILE_BASIC, $doc->getProfileId());
        $this->assertArrayHasKey("contextparameter", $doc->getProfileDefinition());
        $this->assertArrayHasKey("name", $doc->getProfileDefinition());
        $this->assertEquals("urn:order-x.eu:1p0:basic", $doc->getProfileDefinitionParameter("contextparameter"));
        $this->assertEquals("basic", $doc->getProfileDefinitionParameter("name"));
    }

    public function testDocumentCreationComfort(): void
    {
        $doc = OrderDocumentBuilder::createNew(OrderProfiles::PROFILE_COMFORT);
        $this->assertNotNull($doc);
        $this->assertEquals(OrderProfiles::PROFILE_COMFORT, $doc->getProfileId());
        $this->assertArrayHasKey("contextparameter", $doc->getProfileDefinition());
        $this->assertArrayHasKey("name", $doc->getProfileDefinition());
        $this->assertEquals("urn:order-x.eu:1p0:comfort", $doc->getProfileDefinitionParameter("contextparameter"));
        $this->assertEquals("comfort", $doc->getProfileDefinitionParameter("name"));
    }

    public function testDocumentCreationExtended(): void
    {
        $doc = OrderDocumentBuilder::createNew(OrderProfiles::PROFILE_EXTENDED);
        $this->assertNotNull($doc);
        $this->assertEquals(OrderProfiles::PROFILE_EXTENDED, $doc->getProfileId());
        $this->assertArrayHasKey("contextparameter", $doc->getProfileDefinition());
        $this->assertArrayHasKey("name", $doc->getProfileDefinition());
        $this->assertEquals("urn:order-x.eu:1p0:extended", $doc->getProfileDefinitionParameter("contextparameter"));
        $this->assertEquals("extended", $doc->getProfileDefinitionParameter("name"));
    }

    public function testDocumentInternals(): void
    {
        $doc = OrderDocumentBuilder::createNew(OrderProfiles::PROFILE_EXTENDED);
        $property = $this->getPrivatePropertyFromClassname('horstoeko\orderx\OrderDocument', 'serializerBuilder');
        $this->assertNotNull($property->getValue($doc));
        $property = $this->getPrivatePropertyFromClassname('horstoeko\orderx\OrderDocument', 'serializer');
        $this->assertNotNull($property->getValue($doc));
        $property = $this->getPrivatePropertyFromClassname('horstoeko\orderx\OrderDocument', 'orderObject');
        $this->assertNotNull($property->getValue($doc));
        $property = $this->getPrivatePropertyFromClassname('horstoeko\orderx\OrderDocument', 'objectHelper');
        $this->assertNotNull($property->getValue($doc));
    }
}
