<?php

namespace Tests\ripnet\IPBundle;

use ripnet\IPBundle\IPv4;

class IPv4Test extends \PHPUnit_Framework_TestCase {
    public function testNetwork() {
        $this->assertEquals('192.168.1.0', IPv4::getNetwork('192.168.1.99/24'));
    }
    public function testBroadcast() {
        $this->assertEquals('192.168.1.255', IPv4::getBroadcast('192.168.1.99/24'));
    }
}