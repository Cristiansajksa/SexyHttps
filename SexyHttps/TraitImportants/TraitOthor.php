<?php
trait TraitOthorRequest
{
    private static function ModifyUrl(string $url) : void 
    {
        if (empty( parse_url($url)["host"] )) {
            throw new exception("Site no pass format! ");
        }
        self::$objectCurl = curl_init( $url );
        self::$keepConfig[CURLOPT_URL] = $url;
        
        self::$url = parse_url( $url )["host"] ?? $url;
    }



    private static function NewObjectCurl() : void
    {
        self::$objectCurl = curl_init(  );
        curl_setopt_array( self::$objectCurl, (self::$keepConfig + self::$configCurl) );
        curl_setopt_array( self::$objectCurl, self::$keepProxys );
        self::LoadMethod( self::$keepMethod, self::$keepMsgPost );
    }



    private static function LoadHeader(array $headerInfo) : void
    {
        !self::$basicConfig["RotativeUserAgent"] ?: self::RotativeUserAgent( $headerInfo );
        curl_setopt( self::$objectCurl, CURLOPT_HTTPHEADER, $headerInfo );
        self::$keepConfig[CURLOPT_HTTPHEADER] = $headerInfo;
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



    private static function LoadMethod(string $method, string $msgPost = "") : void
    {
        $method = strtoupper( $method );
        self::$keepMethod = $method;
        self::$keepMsgPost = $msgPost;


        if ($method == "GET") {
            curl_setopt( self::$objectCurl, CURLOPT_HTTPGET, true );
        } else {
            $method == "POST" ?
            curl_setopt( self::$objectCurl, CURLOPT_POST, true ) :
            curl_setopt( self::$objectCurl, CURLOPT_CUSTOMREQUEST, $method );

            curl_setopt( self::$objectCurl, CURLOPT_POSTFIELDS, $msgPost );
        }
    }
}