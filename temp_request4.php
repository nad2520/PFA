<?php
$urls = ['http://localhost/hh/index.html', 'http://localhost/hh/unknown.html'];
foreach ($urls as $url) {
    echo "URL: $url\n";
    $ctx = stream_context_create(['http' => ['method' => 'GET', 'follow_location' => false]]);
    $c = @file_get_contents($url, false, $ctx);
    var_dump($http_response_header);
    echo substr($c ?: 'NO BODY', 0, 200) . "\n-----\n";
}
