<?php

/* * *
 * http://localhost:8080/pvcloud_backend/device_get_list_by_account.php?account_id=1
 * 
 * * */
error_reporting(E_ERROR);

class simpleResponse {

    public $status = "";
    public $message = "";
    public $data = [];

}

require_once './DA/da_conf.php';
require_once './DA/da_helper.php';
require_once './DA/da_account.php';
require_once './DA/da_session.php';
require_once './DA/da_devices_registry.php';

/**
 * 
 * 
 * @return \simpleResponse
 */
function execute() {
    $response = new simpleResponse();
    try {

        include './inc/incWebServiceSessionValidation.php';

        $device_id = filter_input(INPUT_GET, "device_id");
        $optional_vse_label = filter_input(INPUT_GET, "optional_vse_label");
        $optional_last_limit = filter_input(INPUT_GET, "optional_last_limit");
        
        if ($device_id > 0 && isset($optional_vse_label) && isset($optional_last_limit)) {
            $result = da_vse_data::GetEntries($device_id, $optional_vse_label, $optional_last_limit);
            $response->status = "OK";
            $response->message = "SUCCESS.";
            $response->data = $result;
        } else {
            $response->status = "ERROR";
            $response->message = "Parámetros Inválidos";
        }
    } catch (Exception $ex) {
        $response->status = "EXCEPTION";
        $response->message = $ex->getMessage();
    }
    return $response;
}

include './inc/incJSONHeaders.php';
echo json_encode(execute());
