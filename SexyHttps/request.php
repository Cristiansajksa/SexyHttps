<?php
class SexyHttps 
{
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


    public static array $keepProxys = [];
    public static array $keepConfig, $keepHeader;
    public static ?string $keepMethod;
    public static mixed $keepMsgPost;

    private static object $objectCookie, $objectOthor, $objectProxys;


    use TraitToolsRequest, TraitMethodsRequest, TraitRetrysRequest;


    private static function builder() 
    {
        self::$objectCookie = new CookieRequest();
        self::$objectProxys = new ProxysRequest();
        self::$objectOthor = new OthorRequest();
    }
}
