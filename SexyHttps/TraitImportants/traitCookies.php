<?php
trait TraitCookieRequest
{
    private static function ParseCookie(string $resultHttp) : void
    {
        if ( 
            !empty( preg_match_all("#(?<=set-cookie: )\S{3,}(?= )#i", $resultHttp, $matchCookie) )
        ) {
            $cookie = join( " ", $matchCookie[0] );
            self::$cookieSession[self::$url] = empty( self::$cookieSession[self::$url] ) ?
            $cookie : self::verifyAtributesCookie( $matchCookie[0] );
        }
    }



    private static function FormatArrayCookie(array $arrayKeepCookie) : array {
        $formatArrayCookie = [];
        foreach ($arrayKeepCookie as $attributeCookie) {
            $formatArrayCookie[strstr($attributeCookie, "=", true)] = 
            str_replace("=", "", strstr($attributeCookie, "=", false));
        }
    
    
        return $formatArrayCookie;
    }



    private static function VerifyAtributesCookie(array $attributesCookie) : string
    {
        $keepAttCookie = self::FormatArrayCookie( $attributesCookie );
        $cookieCopy = self::$cookieSession[self::$url];
        foreach ($keepAttCookie as $attCookie => $valueCookie) {
            if (strstr( $cookieCopy, $attCookie )) {

                $cookieCopy = preg_replace( 
                    "#(?<=$attCookie=)\S+#", 
                    $valueCookie,  
                    $cookieCopy, 
                    1 
                );

            } else {
                $cookieCopy .= " $attCookie=$valueCookie ";
            }
        }
        return $cookieCopy;      
    }



    private static function UsedCookie(string $url) : void
    {
        if (isset( self::$cookieSession[self::$url] )) {
            curl_setopt(
                self::$objectCurl,
                CURLOPT_COOKIE,
                self::$cookieSession[self::$url]
            );

            self::$keepConfig[CURLOPT_COOKIE] = self::$cookieSession[self::$url];
        }
    }
}
