<?php
trait TraitRetrysRequest
{
    public static function Run( 
        string $msgExecute = "",
         string $searchCoin = "isset", 
         bool $retry = false 
    ) : object | bool
    {
        if (empty( sexyHttps::$objectCurl )) {
            return false;
        }
        
        
        curl_setopt_array( sexyHttps::$objectCurl, sexyHttps::$configCurl );
        $resp = $retry ?  
        self::executeRetrys( $msgExecute, $searchCoin ) : 
        curl_exec( sexyHttps::$objectCurl );

        self::$objectCookie->ParseCookie( $resp );
        sexyHttps::$timeTotal += curl_getinfo( sexyHttps::$objectCurl )["total_time"];
        curl_close( sexyHttps::$objectCurl );
        return (object) [ "result" => $resp, "jsonArray" => self::JsonParse( $resp ) ];
    }



    private static function ExecuteRetrys( 
        ?string $msgExecute, 
        ?string $searchCoin 
    ) : string
    {
        $countRetrys = 0;
        do {
            !sexyHttps::$basicConfig["NewCurlRetry"] ?: self::$objectOthor->NewObjectCurl(  );
            $resp = curl_exec( sexyHttps::$objectCurl );

            $countRetrys++;
        } while (
            (stristr( $resp, $searchCoin ) || $msgExecute == $resp) and
            $countRetrys <= 7
        );

        if ($countRetrys > 7) {
            throw new exception( "retry exceeded! ( 7 )" );
        }
        sexyHttps::$retrysCount += $countRetrys - 1;
        return $resp;
    }
}