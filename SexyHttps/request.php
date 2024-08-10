<?php
class SexyHttps 
{
    use TraitToolsRequest;
    use TraitMethodsRequest;
    use TraitRetrysRequest;

    public static array $cookieSession = [];
    public static array $basicConfig = 
    [
        "RotativeUserAgent" => true,
        "NewCurlRetry" => true
    ];


    public static array $configCurl = 
    [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_CONNECTTIMEOUT => 15,
        CURLOPT_HEADER => true,
        CURLOPT_COOKIEJAR => ""
    ];


    public static object $objectCurl;
    public static string $url;
    public static float $timeTotal = 0.00;
    public static int $retrysCount = 0;


    public static array $keepConfig, $keepHeader;
    public static ?string $keepMethod;
    public static mixed $keepMsgPost;

    private static object $objectCookie, $objectOthor, $objectProxys;


    private static function builder() 
    {
        self::$objectCookie = new CookieRequest();
        self::$objectProxys = new ProxysRequest();
        self::$objectOthor = new OthorRequest();
    }



    public static function Run( 
        string $msgExecute = "", string|array $searchCoin = "searchCoinSexy", bool $retry = false 
    ) : object | bool
    {
        if (empty(sexyHttps::$objectCurl)) {
            return false;
        }

        curl_setopt_array( sexyHttps::$objectCurl, sexyHttps::$configCurl );
        
        $resp = $retry ?  
        self::executeRetrys($msgExecute, $searchCoin) : 
        curl_exec(sexyHttps::$objectCurl);

        self::$objectCookie->ParseCookie( $resp );
        self::$timeTotal += curl_getinfo( self::$objectCurl )["total_time"];
        self::$keepConfig = [];
        
        return (object) ["result" => $resp, "jsonArray" => self::JsonParse($resp)];
    }
}
