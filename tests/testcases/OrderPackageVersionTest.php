<?php

namespace horstoeko\orderx\tests\testcases;

use horstoeko\orderx\OrderPackageVersion;
use horstoeko\orderx\tests\TestCase;

class OrderPackageVersionTest extends TestCase
{
    public function testAmountDecimals(): void
    {
        $this->assertNotEmpty(OrderPackageVersion::getInstalledVersion());
    }
}
