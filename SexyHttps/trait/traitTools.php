<?php
trait TraitToolsRequest
{
    public static function getStr( 
        string $msgManege, string $start, string $end, bool $format = false
    ) : string | null 
    {
        $partExtract = explode( $start, $msgManege );
        $partExtract = @explode( $end, $partExtract[1] );  
        $extractString = $partExtract[0];

        if (empty($extractString)) {
            return null;
        }
        return $format ? urlencode($extractString) : $extractString;
    }



    public static function JsonParse(string $string) : array 
    {
        preg_match_all( "#\\{[\S ]{6,}\\}#", $string, $matchCoin );
        return array_filter( 
            $matchCoin[0], fn($value) => is_object(json_decode($value))
        );
    }



    public function AddConfig(array $configExtraCurl) : void
    {
        self::$objectProxys->VerifyConstValueArray( $configExtraCurl );
        curl_setopt_array( self::$objectCurl, $configExtraCurl );
    }
}
