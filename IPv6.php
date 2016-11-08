<?php

namespace ripnet\IPBundle;

class IPv6 {
    public static function isValid($ip) {
        try {
            list($ip, $length) = self::extract($ip);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    public static function checkIP($ip) {
        return (bool)preg_match('/^(([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9]))$/', $ip);
    }

    public static function extract($ipWithCIDR) {
        if (strpos($ipWithCIDR, '/')) {
            list($ip, $cidr) = explode('/', $ipWithCIDR);
            if ($cidr < 0 || $cidr > 128) {
                throw new \Exception(sprintf('Invalid subnet length: %s', $cidr));
            }
        } else {
            $ip = $ipWithCIDR;
            $cidr = 128;
        }
        if (!self::checkIP($ip)) {
            throw new \Exception(sprintf('Invalid IP: %s', $ip));
        }
        return [$ip, $cidr];
    }

    public static function compress($ip) {
        list($ip, $length) = self::extract($ip);

        $u = self::uncompress($ip);
        return preg_replace('/((?:(?:^|:)0+\b){2,}):?(?!\S*\b\1:0+\b)(\S*)/', '::${2}', $u);
    }

    public static function uncompress($ip) {
        list($ip, $length) = self::extract($ip);

        $parts = explode(':', $ip);
        foreach ($parts as $index => $value) {
            if ($value == '' && ($index == 0 || $index == count($parts) -1)) {
                $parts[$index] = '0';
            } elseif ($value == '') {
                $parts[$index] = substr(str_repeat('0:', 8 - substr_count($ip, ':')), 0, -1);
            } else {
                $parts[$index] = sprintf('%x', hexdec($value));
            }

        }
        return implode(':', $parts);
    }

    public static function uncompressWithPaddedZeros($ip) {
        list($ip, $length) = self::extract($ip);

        $parts = explode(':', $ip);
        foreach ($parts as $index => $value) {
            if ($value == '' && ($index == 0 || $index == count($parts) -1)) {
                $parts[$index] = '0000';
            } elseif ($value == '') {
                $parts[$index] = substr(str_repeat('0000:', 8 - substr_count($ip, ':')), 0, -1);
            } else {
                $parts[$index] = sprintf('%04x', hexdec($value));
            }

        }
        return implode(':', $parts);
    }

    public static function getNetwork($ip) {
        list($ip, $length) = self::extract($ip);

        $ipParts = explode(':', self::uncompress($ip));
        $subnetParts = [0, 0, 0, 0, 0, 0, 0, 0];
        $result = [0, 0, 0, 0, 0, 0, 0, 0];
        for ($i = 0; $i < $length; $i++) {
            $subnetParts[(int)($i / 16)] |= (1 << (15 - ($i % 16)));
        }
        foreach ($result as $i=>$v) {
            $result[$i] = sprintf('%x', hexdec($ipParts[$i]) & $subnetParts[$i]);
        }
        return self::compress(implode(':', $result));
    }


}