<?php
file_put_contents('test-static.txt', 'Hello');
$ctx = stream_context_create(['http' => ['method' => 'GET', 'follow_location' => false]]);
$c = @file_get_contents('http://localhost/hh/test-static.txt', false, $ctx);
var_dump($http_response_header);
echo $c;
