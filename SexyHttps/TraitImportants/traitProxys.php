<?php
trait TraitProxysRequest
{
    private static function ProxyChecker(array $serverProxyInfo) : bool 
    {
        self::VerifyConstValueArray( $serverProxyInfo );
        self::$keepProxys = $serverProxyInfo;
        if (isset( $serverProxyInfo )) {
            $ch = curl_init( "http://ip-api.com/json" );
            curl_setopt_array( $ch, $serverProxyInfo );

            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            $resultCurl = json_decode( curl_exec($ch) );
            curl_close( $ch );

            
            if (empty( $resultCurl->query )) {
                return false;
            } else {
                curl_setopt_array( self::$objectCurl, $serverProxyInfo );
                return true;
            }
        }
    }



    private static function UsedProxys(array $serverProxyInfo) : true
    {
        for (
            $countProxyCheck = 0; 
            ($countProxyCheck <= 7 and !self::ProxyChecker( $serverProxyInfo )); 
            $countProxyCheck++
        );

        if ($countProxyCheck === 8) {
            throw new exception("Proxys DD");
        }
        return true;
    }



    private static function VerifyConstValueArray(array &$arrayInfo) : void
    {
        foreach ($arrayInfo as $key => $value) {
            unset( $arrayInfo[$key] );
            if (!defined( $key )) {
                continue;
            }

            $value = is_file( $value ) ?
            file( $value, FILE_IGNORE_NEW_LINES )[array_rand(file($value, FILE_IGNORE_NEW_LINES))] :
            $value;

            $arrayInfo[constant($key)] = $value;
        }
    }
}