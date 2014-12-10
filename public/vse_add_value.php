<?php

//TEST: http://localhost:8080/pvcloud_backend/vse_add_value.php?account_id=1&device_id=1&api_key=5eed4949398c5905578c8f17825e5316a4bec52b&label=DIRECT+TEST&value=ANY+THING&type=STRING&captured_datetime=2014-12-09+21:01

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
    $registeredEntry = new be_vse_data();
    try {
        include './inc/incWebServiceAPIKeyValidation.php'; 

        $entryToAdd = new be_vse_data;
        $entryToAdd->device_id = filter_input(INPUT_GET, "device_id");
        $entryToAdd->vse_label = filter_input(INPUT_GET, "label");
        $entryToAdd->vse_value = filter_input(INPUT_GET, "value");
        $entryToAdd->vse_type = filter_input(INPUT_GET, "type");
        $entryToAdd->vse_annotations = filter_input(INPUT_GET, "annotations");
        $entryToAdd->captured_datetime = filter_input(INPUT_GET, "captured_datetime");
       
        if(validate($entryToAdd)){
            $registeredEntry = da_vse_data::AddEntry($entryToAdd);
        } else {
            die("Parámetros Inválidos");
        }
    } catch (Exception $ex) {
        die("EXCEPTION " . $ex->getCode());
    }
    return $registeredEntry;
}

function validate($entry){
        $capturedDateTime = date_parse($entry->captured_datetime);
        $capturedDateTimeIsValid = false;
        if ($capturedDateTime["error_count"] == 0 && checkdate($capturedDateTime["month"], $capturedDateTime["day"], $capturedDateTime["year"])) {
            $capturedDateTimeIsValid = true;
        } else {
            $capturedDateTimeIsValid = false;
        }
        
        if ($entry->device_id > 0 && $capturedDateTimeIsValid==true) {
            return true;
        }
        return false;
}

include './inc/incJSONHeaders.php';
echo json_encode(execute());
