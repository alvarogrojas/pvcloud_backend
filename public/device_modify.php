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

        $deviceToModify = new be_device();
        $deviceToModify->account_id = filter_input(INPUT_GET, "account_id");
        $deviceToModify->device_nickname = filter_input(INPUT_GET, "device_nickname");
        $deviceToModify->device_description = filter_input(INPUT_GET, "device_description");

        if ($deviceToModify->account_id > 0 && $deviceToModify->device_nickname != "" && $deviceToModify->device_description != "") {
            $modifiedDevice = da_devices_registry::UpdateDevice($deviceToModify);
            $response->status = "OK";
            $response->message = "SUCCESS";
            $response->data = $modifiedDevice;
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
