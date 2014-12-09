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
        $deviceToRegister = new be_device();
        $deviceToRegister->account_id = filter_input(INPUT_GET, "account_id");
        $deviceToRegister->device_nickname = filter_input(INPUT_GET, "device_nickname");
        $deviceToRegister->device_description = filter_input(INPUT_GET, "device_description");
        $token = filter_input(INPUT_GET, "token");

        if ($deviceToRegister->account_id > 0) {
            if ($deviceToRegister->device_nickname != "") {
                $device = da_devices_registry::RegisterNewDevice($deviceToRegister);
                $response->status = "OK";
                $response->message = "SUCCESS";
                $response->data = $device;
            } else {
                $response->status = "ERROR";
                $response->message = "Parámetro Inválido: Nombre de Dispositivo";
            }
        } else {
            $response->status = "ERROR";
            $response->message = "Parámetro Inválido: Cuenta de Usuario";
        }
    } catch (Exception $ex) {
        $response->status = "EXCEPTION";
        $response->message = $ex->getMessage();
    }
    return $response;
}

include './inc/incJSONHeaders.php';
echo json_encode(execute());
