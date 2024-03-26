<?php
function AutoLoad($dirForAutoLoad = __DIR__, $extesion = "php") {
    $recursion = function(string $dirForAutoLoad) use (&$recursion, $extesion) {
        foreach (scandir( $dirForAutoLoad ) as $fileName) {
            if ($fileName[0] == ".")
                continue;

            if (
                fnmatch( "*.$extesion", $fileName )  and
                !stristr( __FILE__, $fileName )
            ) {
                require $dirForAutoLoad . "/$fileName";
            } elseif (is_dir( $dirForAutoLoad . "/$fileName" )) {
                $recursion( $dirForAutoLoad . "/$fileName" );
            }
        }
    };


    $recursion($dirForAutoLoad);
}
