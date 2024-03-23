<?php
/*

This class is made to "summarize" curl into a single class, with most of its functionality, 
but the difference is that the cookie sessions are encapsulated in the same class and there is no need to use txt files 
(I am a php student, so surely it's not the best code)

The classes follow most of the PSR-12 standards and an easy-to-read structure.
As psr-12 says, no method affects another area other than its own, therefore everything is encapsulated
class methods communicate between static variables to save changes

Nomanclatura:
CamelCase: Variables (class and no class), callables
StudyCase: Method and Class
*/
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
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_HEADER => true
    ];


    private static object $objectCurl;
    private static string $url;
    public static float $timeTotal = 0.00;
    public static int $retrysCount = 0;


    private static array $keepProxys = [];
    private static array $keepConfig;
    private static ?string $keepMethod, $keepMsgPost;

    
    use 
    traitCookieRequest, 
    traitMethodsRequest,
    traitOthorRequest, 
    traitProxysRequest, 
    traitRetrysRequest,
    traitToolsRequest;
}