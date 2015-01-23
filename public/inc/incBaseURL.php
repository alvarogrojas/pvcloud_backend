<?php
function getBaseURL($resource){
    $server_https = filter_input(INPUT_SERVER,"HTTPS");
    $server_port = filter_input(INPUT_SERVER,"SERVER_PORT");
    $protocol = (!empty($server_https) && $server_https !== 'off' || $server_port == 443) ? "https://" : "http://";
    $domainName = filter_input(INPUT_SERVER,"HTTP_HOST")."/";
    return $protocol.$domainName."$resource/";
}