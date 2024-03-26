<?php
trait TraitToolsRequest
{
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



    public static function JsonParse(string $string) : array 
    {
        preg_match_all( "#\\{[\S ]{6,}\\}#", $string, $matchCoin );
        return array_filter( 
            $matchCoin[0], 
            fn($value) => is_object(json_decode($value))
        );
    }



    public function AddConfig(array $configExtraCurl) : void
    {
        self::$objectProxys->VerifyConstValueArray( $configExtraCurl );
        curl_setopt_array( self::$objectCurl, $configExtraCurl );
    }
}
