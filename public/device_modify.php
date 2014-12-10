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
        
        $deviceToModify = da_devices_registry::GetDevice($device_id);
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
            if(! $deviceToModify->account_id>0) $response->message = "Parámetros Inválidos - AccountID";
            if($deviceToModify->device_nickname == "") $response->message = "Parámetros Inválidos - Nickname";
            if($deviceToModify->device_description == "") $response->message = "Parámetros Inválidos - Description";
        }
    } catch (Exception $ex) {
        $response->status = "EXCEPTION";
        $response->message = $ex->getMessage();
    }
    return $response;
}

include './inc/incJSONHeaders.php';
echo json_encode(execute());
