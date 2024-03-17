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


    /**
    method used to save the cookies received, an index/value is created for each site
    *@param string $resultHttp
    *@param string $siteCookie
    *@access private
    *@return bool
    */
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


    private static function VerifyAtributesCookie(array $attributesCookie) : string
    {
        $cookieUrlFormat = str_replace( ["[", "]"], ["tF!SDG", "tF!SDD"], join("&", $attributesCookie) );
        parse_str( $cookieUrlFormat, $keepAttCookie );
        $cookieCopy = self::$cookieSession[self::$url];


        foreach ($keepAttCookie as $attCookie => $valueCookie) {
            $attCookie = str_replace( ["tF!SDG", "tF!SDD"], ["[", "]"], $attCookie );
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


    /** 
    This method is responsible for verifying that the url has a correct format (protolo://name.extension), 
    if it goes well, the curl object is created with the site
    *@access private
    *@throws exception
    *@return void
    *@param string $url
    */
    private static function ModifyUrl(string $url) : void 
    {
        if (empty( parse_url($url)["host"] )) {
            throw new exception("Site no pass format! ");
        }
        self::$objectCurl = curl_init( $url );
        self::$keepConfig[CURLOPT_URL] = $url;
        self::$url = parse_url( $url )["host"] ?? $url;
    }


    /** 
    method to upload saved cookies from the site that is requested 
    *@param string $url
    *@return void
    *@access private
    */
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


    /** 
    method used to check if the indices are valid constant names
    *@param array $arrayInfo
    *@return void
    *@access private
    */
    private static function VerifyConstValueArray(array &$arrayInfo) : void
    {
        foreach ($arrayInfo as $key => $value) {
            unset( $arrayInfo[$key] );
            if (!defined( $key )) {
                continue;
            }

            $value = is_file( $value ) ?
            file( $value, FILE_IGNORE_NEW_LINES )[array_rand(file($value, FILE_IGNORE_NEW_LINES))] :
            $value;

            $arrayInfo[constant($key)] = $value;
        }
    }


    /** 
    method to collect the proxy methods that are brought before it and
    execute a simple request to know if the proxies are alive
    *@param array $serverProxyInfo
    *@return bool
    *@access private
    */
    private static function ProxyChecker(array $serverProxyInfo) : bool 
    {
        self::VerifyConstValueArray( $serverProxyInfo );
        self::$keepProxys = $serverProxyInfo;
        if (isset( $serverProxyInfo )) {
            $ch = curl_init( "http://ip-api.com/json" );
            curl_setopt_array( $ch, $serverProxyInfo );

            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            $resultCurl = json_decode( curl_exec($ch) );
            curl_close( $ch );

            
            if (empty( $resultCurl->query )) {
                return false;
            } else {
                curl_setopt_array( self::$objectCurl, $serverProxyInfo );
                return true;
            }
        }
    }


    /** 
    Method used for create new object curl (for rotative ip in retrys) 
    */
    private static function NewObjectCurl() : void
    {
        self::$objectCurl = curl_init(  );
        curl_setopt_array( self::$objectCurl, (self::$keepConfig + self::$configCurl) );
        curl_setopt_array( self::$objectCurl, self::$keepProxys );
        self::LoadMethod( self::$keepMethod, self::$keepMsgPost );
    }


    /** 
    methods to collect the previous method, in case the proxies fail once, 
    execute the retry and try 6 more times
    *@access private
    *@throws exception
    *@return true
    *@param array $serverProxyInfo
    */
    private static function UsedProxys(array $serverProxyInfo) : true
    {
        for (
            $countProxyCheck = 0; 
            ($countProxyCheck <= 7 and !self::ProxyChecker( $serverProxyInfo )); 
            $countProxyCheck++
        );

        if ($countProxyCheck === 8) {
            throw new exception("Proxys DD");
        }
        return true;
    }


    /** 
    method for load header in she object Curl
    *@access private
    *@throws exception
    *@return void
    *@param array $headerInfo
    */
    private static function LoadHeader(array $headerInfo) : void
    {
        !self::$basicConfig["RotativeUserAgent"] ?: self::RotativeUserAgent( $headerInfo );
        curl_setopt( self::$objectCurl, CURLOPT_HTTPHEADER, $headerInfo );
        self::$keepConfig[CURLOPT_HTTPHEADER] = $headerInfo;
    }


    /** 
    method rotative userAgent in header use json in file "userAgent" for randomize value
    *@access private
    *@return null
    *@param array $headerInfo
    */
    private static function RotativeUserAgent(array &$headerInfo) : null
    {
        $userAgentList = json_decode( 
            file_get_contents( __DIR__ . "\UserAgents.json" ), 
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


    /** 
    method for load Method and post in she object Curl
    *@access private
    *@return void
    *@param string $method
    *@param string msgPost = ""
    */
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


    /** 
    method execute simple http GET
    *@access public
    *@return SexyHttps
    */
    public static function Get( 
        string $url, 
        array $header = [], 
        array $serverProxy = [], 
        bool $cookie = true 
    ) : SexyHttps
    {
        return self::MethodForPoly( $url, "", "GET", $header, $serverProxy, $cookie );
    }


    /** 
    method execute simple http POST
    *@access public
    *@return SexyHttps
    */
    public static function Post( 
        string $url, 
        string $postField = "",
        array $header = [], 
        array $serverProxy = [], 
        bool $cookie = true 
    ) : SexyHttps
    {
        return self::MethodForPoly( $url, $postField, "POST", $header, $serverProxy, $cookie );
    }


    /** 
    method execute simple http Custom (Delete, Put, etc etc)
    *@access public
    *@return SexyHttps
    */
    public static function Custom( 
        string $url, 
        string $postField = "",
        string $method,
        array $header = [], 
        array $serverProxy = [], 
        bool $cookie = true 
    ) : SexyHttps
    {
        return self::MethodForPoly( $url, $postField, $method, $header, $serverProxy, $cookie );
    }


    /**
    method used for "polimorfismo" in methods GET, CUSTOM and Post
    *@access public
    *@return SexyHttps
    */
    private static function MethodForPoly(        
        string $url, 
        string $postField = "",
        string $method,
        array $header = [], 
        array $serverProxy = [], 
        bool $cookie = true 
    ) : SexyHttps
    {
        self::ModifyUrl( $url );
        !$cookie ?: self::UsedCookie( $url );
        empty( $serverProxy ) ?: self::UsedProxys( $serverProxy );

        
        self::LoadHeader( $header );
        self::LoadMethod( $method, $postField );
        return new self( );
    }


    /** 
    method to execute the CURL object that has been handled in the previous methods
    *@access public
    *@return object
    *@param bool $retry = true
    */
    public static function Run( 
        string $msgExecute = "",
         string $searchCoin = "isset", 
         bool $retry = false 
    ) : object | bool
    {
        if (empty( self::$objectCurl )) {
            return false;
        }
        
        
        curl_setopt_array( self::$objectCurl, self::$configCurl );
        $resp = $retry ?  
        self::executeRetrys( $msgExecute, $searchCoin ) : 
        curl_exec( self::$objectCurl );

        self::ParseCookie( $resp );
        self::$timeTotal += curl_getinfo( self::$objectCurl )["total_time"];
        curl_close( self::$objectCurl );
        return (object) [ "result" => $resp, "jsonArray" => self::JsonParse( $resp ) ];
    }


    //
    private static function ExecuteRetrys( 
        ?string $msgExecute, 
        ?string $searchCoin 
    ) : string
    {
        $countRetrys = 0;
        do {
            !self::$basicConfig["NewCurlRetry"] ?: self::NewObjectCurl(  );
            $resp = curl_exec( self::$objectCurl );
            $countRetrys++;
        } while (
            (stristr( $resp, $searchCoin ) || $msgExecute == $resp) and
            $countRetrys <= 7
        );

        if ($countRetrys > 7) {
            throw new exception( "retry exceeded! ( 7 )" );
        }
        self::$retrysCount += $countRetrys - 1;
        return $resp;
    }


    /** 
    method to chain with those responsible for establishing the request (get, post, custom) 
    to establish some extra configuration to the Curl object before using
    *@access public
    *@return void
    *@param array $configExtraCurl
    */
    public function AddConfig( array $configExtraCurl ) : void
    {
        self::VerifyConstValueArray( $configExtraCurl );
        curl_setopt_array( self::$objectCurl, $configExtraCurl );
    }


    /** 
    Method used for parse Json in result Curl 
    *@param string $string
    *@return array
    *@access public
    */
    public static function JsonParse(string $string) : array 
    {
        preg_match_all( "#\\{[\S ]{6,}\\}#", $string, $matchCoin );
        $jsonArray = [];
        foreach ($matchCoin[0] as $jsonCoin) {
            $jsonValue = json_decode( $jsonCoin, true );
            if (empty( $jsonValue ))
                continue;
    
            $jsonArray[] = $jsonCoin;
        }
        return $jsonArray;
    }


    /** 
    Method used for parse string in result Curl 
    *@param string $msgMannege
    *@param string $start
    *@param string $end
    *@return ?string
    *@access public
    */
    public static function getStr( 
        string $msgManege, 
        string $start,  
        string $end 
    ) : string | null 
    {
        $str = explode( $start, $msgManege );
        $str = explode( $end, $str[1] );  
        return empty( $str[0] ) ?  null : trim( strip_tags($str[0]) );
    }
}
