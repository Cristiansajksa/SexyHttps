<?php
trait TraitRetrysRequest
{
    private static function executeRetrys(string $msgExecute, string|array $searchCoin) : string
    {
        return is_array($searchCoin) ? 
        self::retrysArray($msgExecute, $searchCoin) : 
        self::retrysString($msgExecute, $searchCoin);
    }



    private static function checkResult(int $countRetrys) : void
    {
        if ($countRetrys >= 7) {
            throw new exception( "retry exceeded! (7)" );
        }
        sexyHttps::$retrysCount += $countRetrys;
    }


    
    private static function retrysArray(?string $msgExecute, array $searchCoin) : string
    {
        $countRetrys = 0;
        
        while ($countRetrys <= 7) {
            !sexyHttps::$basicConfig["NewCurlRetry"] ?: self::$objectOthor->NewObjectCurl();
            $resp = curl_exec( sexyHttps::$objectCurl );

            foreach ($searchCoin as $coinsString) {
                if (stristr($resp, $coinsString) || $resp == $msgExecute) {
                    $countRetrys++;
                    continue 2;
                }
            }

            break;
        }
        
        self::checkResult( $countRetrys );
        return $resp;
    }


    
    private static function retrysString(?string $msgExecute, string $searchCoin) : string
    {
        $countRetrys = 0;
        
        do {
            !sexyHttps::$basicConfig["NewCurlRetry"] ?: self::$objectOthor->NewObjectCurl();
            $resp = curl_exec( sexyHttps::$objectCurl );
            $countRetrys++;
        } while ((stristr($resp, $searchCoin) || $msgExecute == $resp) and $countRetrys <= 7); 

        $countRetrys--;
        self::checkResult( $countRetrys );
        return $resp;
    }
}
