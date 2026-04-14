<?php
$urls = ['http://localhost/hh/', 'http://localhost/hh/index.php', 'http://localhost/hh/user_page/auth.html'];
foreach ($urls as $url) {
    echo "URL: $url\n";
    $content = @file_get_contents($url);
    if ($content === false) {
        echo "FAILED\n";
        var_dump($http_response_header ?? null);
        continue;
    }
    echo "HEADERS:\n";
    var_dump($http_response_header);
    echo "BODY START:\n";
    echo substr($content, 0, 400);
    echo "\n----\n";
}
