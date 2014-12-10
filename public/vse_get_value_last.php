<?php

/* * *
 * http://localhost:8080/pvcloud_backend/account_authenticate.php?email=jose.a.nunez@gmail.com&pwd=1234pass
 * 
 * * */
error_reporting(E_ERROR);

class simpleResponse {

    public $status = "";
    public $message = "";

}

require_once './DA/da_conf.php';
require_once './DA/da_helper.php';
require_once './DA/da_account.php';
require_once './DA/da_session.php';

function execute() {
    $response = new simpleResponse();
    try {

        include './inc/incWebServiceSessionValidation.php';

        $device_id = filter_input(INPUT_GET, "device_id");
        $optional_vse_label = filter_input(INPUT_GET, "optional_vse_label");

        if ($device_id > 0 && isset($optional_vse_label)) {
            $result = da_vse_data::GetLastEntry($device_id, $optional_vse_label);
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
