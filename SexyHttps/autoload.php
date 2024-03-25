<?php
function AutoLoad($dirForAutoLoad = __DIR__, $extesion = "php") {
    $recursion = function() use (&$recursion, $dirForAutoLoad, $extesion) {
        foreach (scandir( $dirForAutoLoad ) as $fileName) {
            if (substr( $fileName, 0, 1 ) == ".")
                continue;

            if (
                fnmatch( "*.$extesion", $fileName )  and
                empty( preg_grep("#$fileName#", get_required_files()) )
            ) {
                require $dirForAutoLoad . "/$fileName";
            } elseif (is_dir( $fileName )) {
                $recursion( $dirForAutoLoad . "/$fileName" );
            }
        }
    };


    $recursion();
}