<?php

namespace horstoeko\orderx\tests\testcases;

use horstoeko\orderx\OrderProfiles;
use horstoeko\orderx\tests\TestCase;
use horstoeko\orderx\OrderProfileResolver;
use horstoeko\orderx\exception\OrderUnknownProfileException;
use horstoeko\orderx\exception\OrderUnknownXmlContentException;

class OrderProfileResolverTest extends TestCase
{
    /**
     * Internal helper - returns the COMFORT-Profile Header
     *
     * @return string
     */
    private function deliverComfortHeader(): string
    {
        return <<<HDR
<?xml version="1.0" encoding="UTF-8"?>
<rsm:SCRDMCCBDACIOMessageStructure xmlns:rsm="urn:un:unece:uncefact:data:SCRDMCCBDACIOMessageStructure:100" xmlns:a="urn:un:unece:uncefact:data:standard:QualifiedDataType:100" xmlns:qdt="urn:un:unece:uncefact:data:standard:QualifiedDataType:128" xmlns:ram="urn:un:unece:uncefact:data:standard:ReusableAggregateBusinessInformationEntity:128" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:udt="urn:un:unece:uncefact:data:standard:UnqualifiedDataType:128" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
<rsm:ExchangedDocumentContext>
<ram:TestIndicator>
<udt:Indicator>false</udt:Indicator>
</ram:TestIndicator>
<ram:BusinessProcessSpecifiedDocumentContextParameter>
<ram:ID>A1</ram:ID>
</ram:BusinessProcessSpecifiedDocumentContextParameter>
<ram:GuidelineSpecifiedDocumentContextParameter>
<ram:ID>urn:order-x.eu:1p0:comfort</ram:ID>
</ram:GuidelineSpecifiedDocumentContextParameter>
</rsm:ExchangedDocumentContext>
</rsm:SCRDMCCBDACIOMessageStructure>
HDR;
    }

    /**
     * Internal helper - returns unknown profile
     *
     * @return string
     */
    private function deliverUnknownProfile(): string
    {
        return <<<HDR
<?xml version="1.0" encoding="UTF-8"?>
<rsm:SCRDMCCBDACIOMessageStructure xmlns:rsm="urn:un:unece:uncefact:data:SCRDMCCBDACIOMessageStructure:100" xmlns:a="urn:un:unece:uncefact:data:standard:QualifiedDataType:100" xmlns:qdt="urn:un:unece:uncefact:data:standard:QualifiedDataType:128" xmlns:ram="urn:un:unece:uncefact:data:standard:ReusableAggregateBusinessInformationEntity:128" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:udt="urn:un:unece:uncefact:data:standard:UnqualifiedDataType:128" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
<rsm:ExchangedDocumentContext>
<ram:TestIndicator>
<udt:Indicator>false</udt:Indicator>
</ram:TestIndicator>
<ram:BusinessProcessSpecifiedDocumentContextParameter>
<ram:ID>A1</ram:ID>
</ram:BusinessProcessSpecifiedDocumentContextParameter>
<ram:GuidelineSpecifiedDocumentContextParameter>
<ram:ID>unknown</ram:ID>
</ram:GuidelineSpecifiedDocumentContextParameter>
</rsm:ExchangedDocumentContext>
</rsm:SCRDMCCBDACIOMessageStructure>
HDR;
    }

    /**
     * Internal helper - returns unknown profile
     *
     * @return string
     */
    private function deliverInvalidXml(): string
    {
        return <<<HDR
<?xml version="1.0" encoding="UTF-8"?>
<rsm:CrossIndustryInvoice xmlns:rsm="urn:un:unece:uncefact:data:standard:CrossIndustryInvoice:100" xmlns:a="urn:un:unece:uncefact:data:standard:QualifiedDataType:100" xmlns:qdt="urn:un:unece:uncefact:data:standard:QualifiedDataType:10" xmlns:ram="urn:un:unece:uncefact:data:standard:ReusableAggregateBusinessInformationEntity:100" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:udt="urn:un:unece:uncefact:data:standard:UnqualifiedDataType:100" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
</rsm:CrossIndustryInvoice>
HDR;
    }

    public function testResolveComfort()
    {
        $resolved = OrderProfileResolver::resolve($this->deliverComfortHeader());

        $this->assertIsArray($resolved);
        $this->assertArrayHasKey(0, $resolved);
        $this->assertArrayHasKey(1, $resolved);
        $this->assertIsInt($resolved[0]);
        $this->assertIsArray($resolved[1]);
        $this->assertArrayHasKey("name", $resolved[1]);
        $this->assertArrayHasKey("altname", $resolved[1]);
        $this->assertArrayHasKey("description", $resolved[1]);
        $this->assertArrayHasKey("contextparameter", $resolved[1]);
        $this->assertArrayHasKey("attachmentfilename", $resolved[1]);
        $this->assertArrayHasKey("xmpname", $resolved[1]);
        $this->assertArrayHasKey("xsdfilename", $resolved[1]);
        $this->assertArrayHasKey("schematronfilename", $resolved[1]);

        $this->assertEquals(OrderProfiles::PROFILE_COMFORT, $resolved[0]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['name'], $resolved[1]["name"]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['altname'], $resolved[1]["altname"]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['description'], $resolved[1]["description"]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['contextparameter'], $resolved[1]["contextparameter"]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['attachmentfilename'], $resolved[1]["attachmentfilename"]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['xmpname'], $resolved[1]["xmpname"]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['xsdfilename'], $resolved[1]["xsdfilename"]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['schematronfilename'], $resolved[1]["schematronfilename"]);
    }

    public function testResolveProfileIdComfort()
    {
        $resolved = OrderProfileResolver::resolveProfileId($this->deliverComfortHeader());

        $this->assertIsInt($resolved);
        $this->assertEquals(OrderProfiles::PROFILE_COMFORT, $resolved);
    }

    public function testResolveProfileDefComfort()
    {
        $resolved = OrderProfileResolver::resolveProfileDef($this->deliverComfortHeader());

        $this->assertIsArray($resolved);
        $this->assertArrayHasKey("name", $resolved);
        $this->assertArrayHasKey("altname", $resolved);
        $this->assertArrayHasKey("description", $resolved);
        $this->assertArrayHasKey("contextparameter", $resolved);
        $this->assertArrayHasKey("attachmentfilename", $resolved);
        $this->assertArrayHasKey("xmpname", $resolved);
        $this->assertArrayHasKey("xsdfilename", $resolved);
        $this->assertArrayHasKey("schematronfilename", $resolved);

        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['name'], $resolved["name"]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['altname'], $resolved["altname"]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['description'], $resolved["description"]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['contextparameter'], $resolved["contextparameter"]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['attachmentfilename'], $resolved["attachmentfilename"]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['xmpname'], $resolved["xmpname"]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['xsdfilename'], $resolved["xsdfilename"]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['schematronfilename'], $resolved["schematronfilename"]);
    }

    public function testResolveUnknownProfile()
    {
        $this->expectException(OrderUnknownProfileException::class);
        $this->expectExceptionMessage('Cannot determain the profile by');

        OrderProfileResolver::resolveProfileId($this->deliverUnknownProfile());
    }

    public function testResolveInvalidXml()
    {
        $this->expectException(OrderUnknownXmlContentException::class);
        $this->expectExceptionMessage('The XML does not match the requirements for an XML in CII-Syntax');

        OrderProfileResolver::resolveProfileId($this->deliverInvalidXml());
    }

    public function testResolveProfileByIdComfort()
    {
        $resolved = OrderProfileResolver::resolveById(OrderProfiles::PROFILE_COMFORT);

        $this->assertIsArray($resolved);
        $this->assertArrayHasKey(0, $resolved);
        $this->assertArrayHasKey(1, $resolved);
        $this->assertIsInt($resolved[0]);
        $this->assertIsArray($resolved[1]);
        $this->assertArrayHasKey("name", $resolved[1]);
        $this->assertArrayHasKey("altname", $resolved[1]);
        $this->assertArrayHasKey("description", $resolved[1]);
        $this->assertArrayHasKey("contextparameter", $resolved[1]);
        $this->assertArrayHasKey("attachmentfilename", $resolved[1]);
        $this->assertArrayHasKey("xmpname", $resolved[1]);
        $this->assertArrayHasKey("xsdfilename", $resolved[1]);
        $this->assertArrayHasKey("schematronfilename", $resolved[1]);

        $this->assertEquals(OrderProfiles::PROFILE_COMFORT, $resolved[0]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['name'], $resolved[1]["name"]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['altname'], $resolved[1]["altname"]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['description'], $resolved[1]["description"]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['contextparameter'], $resolved[1]["contextparameter"]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['attachmentfilename'], $resolved[1]["attachmentfilename"]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['xmpname'], $resolved[1]["xmpname"]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['xsdfilename'], $resolved[1]["xsdfilename"]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['schematronfilename'], $resolved[1]["schematronfilename"]);
    }

    public function testResolveProfileDefByIdComfort()
    {
        $resolved = OrderProfileResolver::resolveProfileDefById(OrderProfiles::PROFILE_COMFORT);

        $this->assertIsArray($resolved);
        $this->assertArrayHasKey("name", $resolved);
        $this->assertArrayHasKey("altname", $resolved);
        $this->assertArrayHasKey("description", $resolved);
        $this->assertArrayHasKey("contextparameter", $resolved);
        $this->assertArrayHasKey("attachmentfilename", $resolved);
        $this->assertArrayHasKey("xmpname", $resolved);
        $this->assertArrayHasKey("xsdfilename", $resolved);
        $this->assertArrayHasKey("schematronfilename", $resolved);

        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['name'], $resolved["name"]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['altname'], $resolved["altname"]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['description'], $resolved["description"]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['contextparameter'], $resolved["contextparameter"]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['attachmentfilename'], $resolved["attachmentfilename"]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['xmpname'], $resolved["xmpname"]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['xsdfilename'], $resolved["xsdfilename"]);
        $this->assertEquals(OrderProfiles::PROFILEDEF[OrderProfiles::PROFILE_COMFORT]['schematronfilename'], $resolved["schematronfilename"]);
    }

    public function testResolveProfileDefByIdUnknown()
    {
        $this->expectException(OrderUnknownProfileException::class);
        $this->expectExceptionMessage('Could not determine the profile...');

        OrderProfileResolver::resolveProfileDefById(-1);
    }
}
