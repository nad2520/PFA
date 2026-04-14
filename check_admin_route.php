<?php
$urls = [
    'http://localhost/hh/admin',
    'http://localhost/hh/admin/users',
    'http://localhost/hh/admin/books'
];
foreach ($urls as $url) {
    echo "URL: $url\n";
    $ctx = stream_context_create(['http' => ['method' => 'GET', 'follow_location' => false]]);
    $content = @file_get_contents($url, false, $ctx);
    var_dump($http_response_header ?? null);
    if ($content !== false) {
        echo substr($content, 0, 500) . "\n";
    }
    echo "------\n";
}
