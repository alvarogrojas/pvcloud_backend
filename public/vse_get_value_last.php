<?php

//TEST: http://localhost:8080/pvcloud_backend/vse_get_value_last.php?account_id=1&device_id=1&api_key=5eed4949398c5905578c8f17825e5316a4bec52b&optional_label=DIRECT+TEST
error_reporting(E_ERROR);

require_once './DA/da_conf.php';
require_once './DA/da_helper.php';
require_once './DA/da_account.php';
require_once './DA/da_session.php';
require_once './DA/da_devices_registry.php';
require_once './DA/da_vse_data.php';

/**
 * 
 * 
 * @return be_vse_data
 */
function execute() {
    $entries = new be_vse_data();
    try {
        include './inc/incWebServiceAPIKeyValidation.php';

        $parameters = collectParameters();

        if (validate($parameters)) {
            $entries = da_vse_data::GetLastEntry($parameters->device_id, $parameters->optional_vse_label);
        } else {
            die("Parámetros Inválidos");
        }
    } catch (Exception $ex) {
        die("EXCEPTION " . $ex->getCode());
    }
    return $entries;
}

function collectParameters() {
    $parameters = new stdClass();
    $parameters->device_id = filter_input(INPUT_GET, "device_id");
    $parameters->optional_vse_label = filter_input(INPUT_GET, "optional_label");

    if (!isset($parameters->optional_vse_label) || $parameters->optional_vse_label == NULL) {
        $parameters->optional_vse_label = '';
    }

    return $parameters;
}

function validate($parameters) {
    if (is_numeric($parameters->device_id) && $parameters->device_id > 0) {
        if (is_string($parameters->optional_vse_label)) {
            return true;
        }
    }

    return false;
}

include './inc/incJSONHeaders.php';
echo json_encode(execute());
