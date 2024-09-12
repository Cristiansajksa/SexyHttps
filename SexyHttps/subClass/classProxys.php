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
        foreach ($arrayInfo as $constCurl => $valuesCurls) {
            unset( $arrayInfo[$constCurl] );
            if (!defined($constCurl)) {
                continue;
            }

            if (is_file($valuesCurls)) {
                $keepProxys = file( $valuesCurls, FILE_IGNORE_NEW_LINES );
                $valuesCurls = $keepProxys[array_rand($keepProxys)];
            }
            
            $arrayInfo[constant($constCurl)] = $valuesCurls;
        }
    }
}
