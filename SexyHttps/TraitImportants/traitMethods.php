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
        self::ModifyUrl( $url );
        !$cookie ?: self::UsedCookie( $url );
        empty( $serverProxy ) ?: self::UsedProxys( $serverProxy );

        
        self::LoadHeader( $header );
        self::LoadMethod( $method, $postField );
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