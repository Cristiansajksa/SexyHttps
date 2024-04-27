<?php
trait TraitRetrysRequest
{
    public static function Run( 
        string $msgExecute = "", string|array $searchCoin = "isset", bool $retry = false 
    ) : object | bool
    {
        if (empty(sexyHttps::$objectCurl)) {
            return false;
        }
        curl_setopt_array( sexyHttps::$objectCurl, sexyHttps::$configCurl );
        
        $resp = $retry ?  
        self::executeRetrys( $msgExecute, $searchCoin ) : 
        curl_exec( sexyHttps::$objectCurl );

        self::$objectCookie->ParseCookie( $resp );
        sexyHttps::$timeTotal += curl_getinfo( sexyHttps::$objectCurl )["total_time"];
        curl_close( sexyHttps::$objectCurl );
        return (object) [ "result" => $resp, "jsonArray" => self::JsonParse($resp) ];
    }



    private static function executeRetrys(string $msgExecute, string|array $searchCoin) : string
    {
        return is_array( $searchCoin ) ? 
        self::retrysArray( $msgExecute, $searchCoin ) : 
        self::retrysString( $msgExecute, $searchCoin );
    }



    private static function checkResult(int $countRetrys) : void
    {
        if ($countRetrys > 7) {
            throw new exception( "retry exceeded! (7)" );
        }
        sexyHttps::$retrysCount += $countRetrys - 1;
    }


    
    private static function retrysArray(
        ?string $msgExecute, ?string $searchCoin 
    ) : string
    {
        for ($countRetrys = 0; $countRetrys <= 7; $countRetrys) {
            $resp = curl_exec( sexyHttps::$objectCurl );
            !sexyHttps::$basicConfig["NewCurlRetry"] ?: self::$objectOthor->NewObjectCurl();

            foreach ($searchCoin as $coinsString) {
                if (stristr($resp, $coinsString) || $resp == $msgExecute) {
                    break;
                }
                break 2;
            }
        }

        self::checkResult( $countRetrys );
        return $resp;
    }


    
    private static function retrysString( 
        ?string $msgExecute, ?string $searchCoin 
    ) : string
    {
        $countRetrys = 0;

        while (
            (stristr($resp, $searchCoin) || $msgExecute == $resp) and $countRetrys <= 7
        ) {
            $resp = curl_exec( sexyHttps::$objectCurl );
            !sexyHttps::$basicConfig["NewCurlRetry"] ?: self::$objectOthor->NewObjectCurl();
            $countRetrys++;
        } 

        self::checkResult( $countRetrys );
        return $resp;
    }
}
