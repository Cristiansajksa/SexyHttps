<?php
trait TraitRetrysRequest
{
    public static function Run( 
        string $msgExecute = "",
         string $searchCoin = "isset", 
         bool $retry = false 
    ) : object | bool
    {
        if (empty( self::$objectCurl )) {
            return false;
        }
        
        
        curl_setopt_array( self::$objectCurl, self::$configCurl );
        $resp = $retry ?  
        self::executeRetrys( $msgExecute, $searchCoin ) : 
        curl_exec( self::$objectCurl );

        self::ParseCookie( $resp );
        self::$timeTotal += curl_getinfo( self::$objectCurl )["total_time"];
        curl_close( self::$objectCurl );
        return (object) [ "result" => $resp, "jsonArray" => self::JsonParse( $resp ) ];
    }



    private static function ExecuteRetrys( 
        ?string $msgExecute, 
        ?string $searchCoin 
    ) : string
    {
        $countRetrys = 0;
        do {
            !self::$basicConfig["NewCurlRetry"] ?: self::NewObjectCurl(  );
            $resp = curl_exec( self::$objectCurl );

            $countRetrys++;
        } while (
            (stristr( $resp, $searchCoin ) || $msgExecute == $resp) and
            $countRetrys <= 7
        );

        if ($countRetrys > 7) {
            throw new exception( "retry exceeded! ( 7 )" );
        }
        self::$retrysCount += $countRetrys - 1;
        return $resp;
    }
}