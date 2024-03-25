<?php
/*
En este codigo presenta se ta haciendo una class para facilitar curl y olvidarnos 
de tener q borrar esos archivos txt molesto ya q en esta class se va a estar encapsulando
las cookie de los sites en una array x el host (www.google.com)

Codigo:
El codigo sigue las normas de psr-12
como la nomenclantura de la class, metodos, variables etc
la forma de las linea
4 de tabulacion
entre, entre

este codigo esta hecho con el diseño builder, porque?
porque CREO YO q el diseño builder es el mejor para esta tarea, se iba a hacer con strategy
pero como son clases con responsabilidades y no comparten metodos en comun pues lo veo inutil
Decidi hacerlo con diseño build debido a su forma de no depender de q la class este definida en el momento
(extends, implements, use etc etc) ademas q de esta manera permite q el code sea escalable.
Debido a q  se puede extender las demas clases (MAS NO MODIFICAR ESTAS) para solucionar algun problema o necesidad


Los trait no tienen como tal una responsabilidad importante mas q hacer los request y las tools,
por eso se usan trait para q esta class no sean tan larga!

Propiedades q cumple el code:
1. Modularidad
2. escalable
3. extensibilidad
4. encapsula lo q varia
5. SRP 
entre otros propiedades o caracteristica

buenos sin mas iniciemos ;)
*/
require "autoload.php";
AutoLoad( __DIR__  . "/subClass");


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


    public static object $objectCurl;
    public static string $url;
    public static float $timeTotal = 0.00;
    public static int $retrysCount = 0;


    public static array $keepProxys = [];
    public static array $keepConfig;
    public static ?string $keepMethod, $keepMsgPost;


    private static object $objectCookie, $objectOthor, $objectProxys;


    use traitToolsRequest, TraitMethodsRequest, TraitRetrysRequest;


    private static function builder() 
    {
        self::$objectCookie = new CookieRequest();
        self::$objectProxys = new ProxysRequest();
        self::$objectOthor = new OthorRequest();
    }
}