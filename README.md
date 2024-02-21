# SexyHttps

This Project is based and inspired by [CurlX](https://github.com/devblack/curlx)

## ProxysInfo Method

The value of the GET, POST, and CUSTOM `proxysInfo` methods must follow this array order:

```php
"CURLOPT_PROXY" => "value"
```

These constants are taken in a chain to verify their existence.

You can place the location of a text file with proxies as an argument. For this, use the constant which automatically selects one of the text files in a random manner.

In some cases, some sites do not return their content if a user-agent is specified. You can prevent this by either not specifying a user-agent or by changing the one in the header by modifying the boolean value of the index of the static variable `basicConfig`.

## About the Project

This project is designed to encapsulate most of the functionality of curl into a single class, including cookie sessions. There is no need to use text files for cookie sessions.

The class follows most of the PSR-12 standards and has an easy-to-read structure. As per PSR-12, no method affects another area other than its own, so everything is encapsulated.

## Usage

```php
<?php

// Example usage of the SexyHttps Project

// Need Include the Project file
include 'SexyHttps.php';

// Make a request to page https://www.cual-es-mi-ip.net/
SexyHttps::Get( "https://www.cual-es-mi-ip.net/" );

// Show the result
echo SexyHttps::Run()->result;
?>
```

## Methods

- `Get`: Execute a simple HTTP GET request.
- `Post`: Execute a simple HTTP POST request.
- `Custom`: Execute a simple HTTP Custom request (e.g., DELETE, PUT).
- `Run`: Execute the CURL object that has been handled in the previous methods.