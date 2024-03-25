<?php
class OthorRequest
{
    public function ModifyUrl(string $url) : void 
    {
        if (empty( parse_url($url)["host"] )) {
            throw new exception("Site no pass format! ");
        }
        sexyHttps::$objectCurl = curl_init( $url );
        sexyHttps::$keepConfig[CURLOPT_URL] = $url;
        
        sexyHttps::$url = parse_url( $url )["host"] ?? $url;
    }



    public function NewObjectCurl() : void
    {
        sexyHttps::$objectCurl = curl_init(  );
        curl_setopt_array( sexyHttps::$objectCurl, (sexyHttps::$keepConfig + sexyHttps::$configCurl) );
        curl_setopt_array( sexyHttps::$objectCurl, sexyHttps::$keepProxys );
        sexyHttps::LoadMethod( sexyHttps::$keepMethod, sexyHttps::$keepMsgPost );
    }



    public function LoadHeader(array $headerInfo) : void
    {
        !sexyHttps::$basicConfig["RotativeUserAgent"] ?: self::RotativeUserAgent( $headerInfo );
        curl_setopt( sexyHttps::$objectCurl, CURLOPT_HTTPHEADER, $headerInfo );
        sexyHttps::$keepConfig[CURLOPT_HTTPHEADER] = $headerInfo;
    }



    public function LoadMethod(string $method, string $msgPost = "") : void
    {
        $method = strtoupper( $method );
        sexyHttps::$keepMethod = $method;
        sexyHttps::$keepMsgPost = $msgPost;


        if ($method == "GET") {
            curl_setopt( sexyHttps::$objectCurl, CURLOPT_HTTPGET, true );
        } else {
            $method == "POST" ?
            curl_setopt( sexyHttps::$objectCurl, CURLOPT_POST, true ) :
            curl_setopt( sexyHttps::$objectCurl, CURLOPT_CUSTOMREQUEST, $method );

            curl_setopt( sexyHttps::$objectCurl, CURLOPT_POSTFIELDS, $msgPost );
        }
    }



    private static function RotativeUserAgent(array &$headerInfo) : null
    {
        $userAgentList = json_decode( 
            file_get_contents( __DIR__ . "\UserAgent.json" ), 
            true 
        )["UserAgent"];


        foreach ($headerInfo as &$header) {
            if (stristr( $header, "user-agent" )) {
                $header = "User-Agent: " . $userAgentList[array_rand($userAgentList)];
                return null; 
            }
        }
        $headerInfo[] = "User-Agent: " . $userAgentList[array_rand($userAgentList)];
        return null;
    }
}