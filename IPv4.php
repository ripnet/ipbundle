<?php

namespace ripnet\IPBundle;

class IPv4 {
    public static function isValid($ip) {
        list($ip, $length) = self::extract($ip);
        return true;
    }

    protected static function checkIP($ip) {
        return (bool)preg_match('/^0*([1-9]?\d|1\d\d|2[0-4]\d|25[0-5])\.0*([1-9]?\d|1\d\d|2[0-4]\d|25[0-5])\.0*([1-9]?\d|1\d\d|2[0-4]\d|25[0-5])\.0*([1-9]?\d|1\d\d|2[0-4]\d|25[0-5])$/', $ip);
    }

    public static function compress($ip) {
        list($ip, $length) = self::extract($ip);

        return long2ip(ip2long($ip));
    }

    public static function extract($ipWithCIDR) {
        if (strpos($ipWithCIDR, '/')) {
            list($ip, $cidr) = explode('/', $ipWithCIDR);
            if ($cidr < 0 || $cidr > 32) {
                throw new \Exception(sprintf('Invalid subnet length: %s', $cidr));
            }
        } else {
            $ip = $ipWithCIDR;
            $cidr = 32;
        }
        if (!self::checkIP($ip)) {
            throw new \Exception(sprintf('Invalid IP: %s', $ip));
        }
        return [$ip, $cidr];
    }

    public static function getNetwork($ip) {
        list($ip, $length) = self::extract($ip);
        return long2ip(ip2long($ip) & ((-1 << (32 - $length)) & 0xFFFFFFFF));
    }

    public static function getBroadcast($ip) {
        list($ip, $length) = self::extract($ip);
        return long2ip(ip2long($ip) | (~(-1 << (32 - $length)) & 0xFFFFFFFF));
    }
}