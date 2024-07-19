<?php
class ProxysRequest
{
    public static function UsedProxys(array $serverProxyInfo) : void
    {
        for (
            $countProxyCheck = 0; 
            ($countProxyCheck <= 7 and !self::ProxyChecker($serverProxyInfo)); 
            $countProxyCheck++
        );

        if ($countProxyCheck === 8) {
            throw new exception( "An error has occurred in the check-in process" );
        }
    }


    
    private static function ProxyChecker(array $serverProxyInfo) : bool 
    {
        self::VerifyConstValueArray( $serverProxyInfo );
        sexyHttps::$keepConfig += $serverProxyInfo;

        if (isset($serverProxyInfo)) {
            $ch = curl_init( "http://ip-api.com/json" );
            curl_setopt_array( $ch, $serverProxyInfo );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

            curl_setopt( $ch, CURLOPT_TIMEOUT, 15 );
            $resultCurl = json_decode( curl_exec($ch) );
            curl_close( $ch );

            if (empty($resultCurl->query)) {
                return false;
            } else {
                curl_setopt_array( sexyHttps::$objectCurl, $serverProxyInfo );
                return true;
            }
        }
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
