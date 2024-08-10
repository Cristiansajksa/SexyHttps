<?php
trait TraitRetrysRequest
{
    private static function executeRetrys(string $msgExecute, string|array $searchCoin) : string
    {
        for (
            $countRetrys = 0;
            self::retry($msgExecute, $searchCoin) and $countRetrys < 10;
            $countRetrys++
        );

        if ($countRetrys == 10) {
            throw new exception( "retry exceeded! (10)" );
        }
        return self::$resultRetrys;
    }



    private static function retry(?string $msgExecute, string|array $searchCoin) : string
    {
        !self::$basicConfig["NewCurlRetry"] ?: self::$objectOthor->NewObjectCurl();
        self::$resultRetrys = curl_exec( self::$objectCurl );
        $primaryIp = curl_getinfo( self::$objectCurl )["primary_ip"];

        curl_close( sexyHttps::$objectCurl );

        return (
            empty($primaryIp) || 
            self::searchHtml(self::$resultRetrys, $searchCoin) || 
            self::$resultRetrys == $msgExecute
        );
    }



    private static function searchHtml(string $html, string|array $searchCoin) : bool 
    {
        $searchCoin = (array) $searchCoin;

        foreach ($searchCoin as $stringCoin) {
            $resultSearch = stristr( $html, $stringCoin );
            if ($resultSearch) {
                break;
            }
        }

        return $resultSearch;
    }
}
