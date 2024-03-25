<?php
trait TraitMethodsRequest
{
    private static function MethodForPoly(        
        string $url, 
        string $postField = "",
        string $method,
        array $header = [], 
        array $serverProxy = [], 
        bool $cookie = true 
    ) : SexyHttps
    {
        self::builder();

        
        self::$objectOthor->ModifyUrl( $url );
        !$cookie ?: self::$objectCookie->UsedCookie( $url );
        empty( $serverProxy ) ?: self::$objectProxy->UsedProxys( $serverProxy );

        
        self::$objectOthor->LoadHeader( $header );
        self::$objectOthor->LoadMethod( $method, $postField );
        return new self( );
    }



    public static function Get( 
        string $url, 
        array $header = [], 
        array $serverProxy = [], 
        bool $cookie = true 
    ) : SexyHttps
    {
        return self::MethodForPoly( $url, "", "GET", $header, $serverProxy, $cookie );
    }



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
}