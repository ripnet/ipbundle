<?php

namespace Tests\ripnet\IPBundle;

use ripnet\IPBundle\IPv6;

class IPv6Test extends \PHPUnit_Framework_TestCase {
    public function testIPv6Valid()
    {
        $this->assertTrue(IPv6::isValid('::'));
        $this->assertTrue(IPv6::isValid('::1'));
        $this->assertTrue(IPv6::isValid('2001:0000:1234:0000:0000:C1C0:ABCD:0876'));
        $this->assertTrue(IPv6::isValid('2001:0:1234::C1C0:ABCD:876'));
        $this->assertTrue(IPv6::isValid('2001:0000:1234:0000:0000:C1C0:ABCD:0876'));
        $this->assertTrue(IPv6::isValid('2001:0:1234::C1C0:ABCD:876'));
        $this->assertTrue(IPv6::isValid('3ffe:0b00:0000:0000:0001:0000:0000:000a'));
        $this->assertTrue(IPv6::isValid('3ffe:b00::1:0:0:a'));
        $this->assertTrue(IPv6::isValid('FF02:0000:0000:0000:0000:0000:0000:0001'));
        $this->assertTrue(IPv6::isValid('FF02::1'));
        $this->assertTrue(IPv6::isValid('0000:0000:0000:0000:0000:0000:0000:0001'));
        $this->assertTrue(IPv6::isValid('0000:0000:0000:0000:0000:0000:0000:0000'));
    }

    public function testIPv6Invalid()
    {
        $this->assertFalse(IPv6::isValid(''));
        $this->assertFalse(IPv6::isValid('q2001:0000:1234:0000:0000:C1C0:ABCD:0876'));
        $this->assertFalse(IPv6::isValid('2001:0000:1234:r000:0001:C1C0:ABCD:0876'));
        $this->assertFalse(IPv6::isValid('00000'));
        $this->assertFalse(IPv6::isValid('::fffff'));
        $this->assertFalse(IPv6::isValid(''));
        $this->assertFalse(IPv6::isValid(''));
        $this->assertFalse(IPv6::isValid(''));
        $this->assertFalse(IPv6::isValid(''));
    }

    public function testCompress() {
        $this->assertEquals('::2504:3:0:0:0', IPv6::compress('0000:0000:0000:2504:3:0000:0:0'));
    }

    public function testNetwork() {
        //print IPv6::getNetwork('2604:0:beef::23/126');
    }

    public function testLastIP() {
        //print IPv6::getLastIP('2604:6000:0:4::f:4016/96');
    }
}