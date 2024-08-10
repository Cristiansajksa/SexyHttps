<?php
class ProxysRequest
{
    public static function keepProxys(array $serverProxyInfo) : void
    {
        self::VerifyConstValueArray( $serverProxyInfo );
        sexyHttps::$keepConfig += $serverProxyInfo;
    }


    public static function VerifyConstValueArray(array &$arrayInfo) : void
    {
        foreach ($arrayInfo as $key => $value) {
            unset( $arrayInfo[$key] );
            if (!defined($key)) {
                continue;
            }

            if (is_file($value)) {
                $keepProxys = file( $value, FILE_IGNORE_NEW_LINES );
                $value = $keepProxys[array_rand($keepProxys)];
            } else {
                $value = $value;
            }
            
            $arrayInfo[constant($key)] = $value;
        }
    }
}
