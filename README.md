this class is based and inspired by curlX (d3vbl4ck)

the value of the GET, POST AND CUSTOM proxysInfo methods, the order of the array must be the following:

"CURLOPT_PROXY" => "value"

being that the constants are taken in a chain to verify if it exists

The location of a txt with proxies can be placed as an argument, 
for this constant being that it takes one of the txt automatically in a random way

In some cases, some sites do not return their content if a user-agent is placed, 
you can prevent them from placing a user-agent or change the one they have in the header by changing the boolean value of the index of the static variable "basicConfig"
