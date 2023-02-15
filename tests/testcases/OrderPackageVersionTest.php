<?php

namespace horstoeko\orderx\tests\testcases;

use horstoeko\orderx\OrderPackageVersion;
use horstoeko\orderx\tests\TestCase;

class OrderPackageVersionTest extends TestCase
{
    /**
     * @covers \horstoeko\orderx\OrderPackageVersion
     */
    public function testAmountDecimals(): void
    {
        $this->assertNotEmpty(OrderPackageVersion::getInstalledVersion());
    }
}
