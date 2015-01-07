<?php
//error_reporting(E_ERROR);
require_once './DA/da_conf.php';
require_once './DA/da_helper.php';
require_once './DA/da_account.php';
require_once './DA/da_session.php';
require_once './DA/da_devices_registry.php';

function collectParameters() {
    $parameters = new stdClass();
    $parameters->device_id = filter_input(INPUT_GET, "device_id");
    $parameters->account_id = filter_input(INPUT_GET, "account_id");
    $parameters->api_key = filter_input(INPUT_GET, "api_key");
    $parameters->baseURL = "http://costaricamakers.com/pvcloud_backend/";

    return $parameters;
}

function validate($parameters) {
    if (is_numeric($parameters->device_id) && $parameters->device_id > 0) {
        if (is_numeric($parameters->account_id) && $parameters->account_id > 0) {
            if (is_string($parameters->api_key)) {
                return true;
            }
        }
    }
    return false;
}

function setDownloadableJSHeaders() {
    header('Content-Type: application/js');
    header('Content-Disposition: attachment; filename="pvcloud_api.js"');
}

function execute() {
    try {
        include './inc/incWebServiceAPIKeyValidation.php';

        $parameters = collectParameters();

        if (validate($parameters)) {
            setDownloadableJSHeaders();
        } else {
            die("Parámetros Inválidos");
        }
    } catch (Exception $ex) {
        die("EXCEPTION " . $ex->getCode());
    }
    return $parameters;
}

$parameters = execute();
$script = file_get_contents("inc/pvcloud_template.js");
echo($script);
echo (" pvCloudModule($parameters->device_id, '$parameters->api_key',$parameters->account_id, '$parameters->baseURL' )");