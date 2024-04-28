<?php
class CookieRequest
{
    public function ParseCookie(string $resultHttp) : void
    {
        if (preg_match_all("#(?<=set-cookie: )\S{3,}(?= )#i", $resultHttp, $matchCookie)) {
            $cookie = join( " ", $matchCookie[0] );
            sexyHttps::$cookieSession[sexyHttps::$url] = empty( sexyHttps::$cookieSession[sexyHttps::$url] ) ?
            $cookie : self::verifyAtributesCookie( $matchCookie[0] );
        }
    }



    public function UsedCookie(string $url) : void
    {
        if (isset(sexyHttps::$cookieSession[sexyHttps::$url])) {
            curl_setopt(
                sexyHttps::$objectCurl,
                CURLOPT_COOKIE,
                sexyHttps::$cookieSession[sexyHttps::$url]
            );

            sexyHttps::$keepConfig[CURLOPT_COOKIE] = sexyHttps::$cookieSession[sexyHttps::$url];
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
        $cookieCopy = sexyHttps::$cookieSession[sexyHttps::$url];
        foreach ($keepAttCookie as $attCookie => $valueCookie) {
            if (strstr($cookieCopy, $attCookie)) {

                $cookieCopy = preg_replace( 
                    "#(?<=$attCookie=)\S+#", 
                    str_replace("=", "%3D", $valueCookie),  
                    $cookieCopy, 
                    1 
                );

            } else {
                $cookieCopy .= " $attCookie=$valueCookie ";
            }
        }
        return $cookieCopy;      
    }
}
