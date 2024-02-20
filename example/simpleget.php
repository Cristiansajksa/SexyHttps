<?php

require( __DIR__ . "/sexyHttps/request.php" );

SexyHttps::Get( "https://www.cual-es-mi-ip.net/" );
echo SexyHttps::Run()->result;