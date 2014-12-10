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
require_once './DA/da_vse_data.php';

/**
 * 
 * 
 * @return \simpleResponse
 */
function execute() {
    $response = new simpleResponse();
    try {
        $account_id = 0;
        $token = 0;
        include './inc/incWebServiceSessionValidation.php';

        $entryToAdd = new be_vse_data;
        $entryToAdd->device_id = filter_input(INPUT_GET, "device_id");
        $entryToAdd->vse_label = filter_input(INPUT_GET, "label");
        $entryToAdd->vse_value = filter_input(INPUT_GET, "value");
        $entryToAdd->vse_type = filter_input(INPUT_GET, "type");
        $entryToAdd->vse_annotations = filter_input(INPUT_GET, "annotations");
        $entryToAdd->captured_datetime = filter_input(INPUT_GET, "capture_datetime");

        $capturedDateTime = date_parse($entryToAdd->captured_datetime);
        $capturedDateTimeIsValid = false;
        if ($capturedDateTime["error_count"] == 0 && checkdate($capturedDateTime["month"], $capturedDateTime["day"], $capturedDateTime["year"])) {
            $capturedDateTimeIsValid = true;
        } else {
            $capturedDateTimeIsValid = false;
        }
        if ($entryToAdd->device_id > 0 && $capturedDateTimeIsValid) {
            $registeredEntry = da_vse_data::AddEntry($entryToAdd);
            $response->status = "OK";
            $response->message = "SUCCESS";
            $response->data = $registeredEntry;
        } else {
            $response->status = "ERROR";
            $response->message = "Parámetrso Inválidos";
        }
    } catch (Exception $ex) {
        $response->status = "EXCEPTION";
        $response->message = $ex->getMessage();
    }
    return $response;
}

include './inc/incJSONHeaders.php';
echo json_encode(execute());
