<?php

require( __DIR__ . "/request.php" );

$jsonString = '{"name": "John", "age": 25}{"name": "Alice", "age": 30}{"name": "Bob", "age": 28}';
var_dump( httpSexy::JsonParse($jsonString) );
