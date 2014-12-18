<?php
require_once './DA/da_conf.php';
require_once './DA/da_helper.php';
require_once './DA/da_devices_registry.php';
require_once './DA/da_vse_data.php';

$account_id = 0;
$device_id  = 0;
$api_key = "";

include './inc/incWebServiceAPIKeyValidation.php';

$customLibProgram = ""
        . "function x(){"
        . "var deviceInfo = {"
        . "AccountID:$account_id,"
        . "DeviceID:$device_id,"
        . "ApiKEY:'$api_key'"
        . "};"
        . "console.log (deviceInfo);"
        . "}"
        . ""
        . "x();";

header('Content-Type: text/plain; charset=utf-8');
header('Content-Disposition: attachment; filename=pvcloud_vse_api.js');
echo ($customLibProgram);


        
        
