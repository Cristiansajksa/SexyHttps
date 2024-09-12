<?php
function AutoLoad($dirForAutoLoad = __DIR__, $extesion = "php") {
    $autoLoad = function(string $dirForAutoLoad) use (&$autoLoad, $extesion) {

        foreach (scandir($dirForAutoLoad) as $fileName) {   
            $dirFile = "$dirForAutoLoad/$fileName";
            if ($fileName[0] == ".") {
                continue;
            }

            if (fnmatch("*.$extesion", $fileName)  and !stristr(__FILE__, $fileName)) {
                require $dirFile;
            } elseif (is_dir($dirFile)) {
                $autoLoad( $dirFile );
            }
        }
        
    };

    $autoLoad( $dirForAutoLoad );
}
