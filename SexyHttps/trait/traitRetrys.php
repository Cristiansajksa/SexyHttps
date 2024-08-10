<?php
trait TraitRetrysRequest
{
    private static function executeRetrys(string $msgExecute, string|array $searchCoin) : string
    {
        for (
            $countRetrys = 0;
            self::retry($msgExecute, $searchCoin) and $countRetrys < 10;
            $countRetrys++;
        );

        if ($countRetrys == 10) {
            throw new exception( "retry exceeded! (10)" );
        }
    }



    private static function retry(?string $msgExecute, string|array $searchCoin) : string
    {
        !self::$basicConfig["NewCurlRetry"] ?: self::$objectOthor->NewObjectCurl();
        $resp = curl_exec( self::$objectCurl );
        $primaryIp = curl_getinfo( self::$objectCurl )["primary_ip"];

        return empty($primaryIp) || self::searchHtml($resp, $searchCoin) || $resp == $msgExecute;
    }



    private static function searchHtml(string $html, string|array $searchCoin) : bool 
    {
        $searchCoin = (array) $searchCoin;

        foreach ($searchCoina as $stringCoin) {
            $resultSearch = stristr( $html, $searchCoin );
            if ($resultSearch) {
                break;
            }
        }

        return $resultSearch;
    }
}
