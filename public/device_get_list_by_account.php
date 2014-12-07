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
        $account_id = filter_input(INPUT_GET, "account_id");
        if ($account_id > 0) {
            $devices = da_devices_registry::GetListOfDevices($account_id);
            $response->status="OK";
            $response->message="SUCCESS";
            $response->data = $devices;
        } else {
            $response->status = "ERROR";
        }
    } catch (Exception $ex) {
        $response->status="EXCEPTION";
        $response->message = $ex->getMessage();
    }
    return $response;
}

header('Content-Type: application/json');
echo json_encode(execute());
