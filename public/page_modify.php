<?php

/* * *
 * http://localhost:8080/pvcloud_backend/page_modify.php?account_id=1&token=123x&page_id=1&title=NewTitle&description=newDescription&visibility_type_id=3
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
require_once './DA/da_apps_registry.php';

include './inc/incWebServiceSessionValidation.php';

class PageModifyWebService {

    public static function ModifyPage($page) {
        $response = new simpleResponse();
        $parameters = PageModifyWebService::collectParameters();
        $pageToModify = da_apps_registry::GetPage($parameters->$page_id);
        $pageToModify->title = $parameters->title;
        $pageToModify->description = $parameters->description;
        $pageToModify->visibility_type_id = $parameters->visibility_type_id;
               
    }

    private static function collectParameters() {
        
    }

}

/**
 * 
 * 
 * @return \simpleResponse
 */
function execute() {

    try {

        include './inc/incWebServiceSessionValidation.php';

        $app_id = filter_input(INPUT_GET, "app_id");

        $appToModify = da_apps_registry::GetApp($app_id);
        $appToModify->account_id = filter_input(INPUT_GET, "account_id");
        $appToModify->app_nickname = filter_input(INPUT_GET, "app_nickname");
        $appToModify->app_description = filter_input(INPUT_GET, "app_description");
        $appToModify->visibility_type_id = filter_input(INPUT_GET, "visibility_type_id");

        if ($appToModify->account_id > 0 && $appToModify->app_nickname != "" && $appToModify->app_description != "" && $appToModify->visibility_type_id > 0) {
            $modifiedApp = da_apps_registry::UpdateApp($appToModify);
            $response->status = "OK";
            $response->message = "SUCCESS";
            $response->data = $modifiedApp;
        } else {
            $response->status = "ERROR";
            if (!$appToModify->account_id > 0)
                $response->message = "Parámetros Inválidos - AccountID";
            if ($appToModify->app_nickname == "")
                $response->message = "Parámetros Inválidos - Nickname";
            if ($appToModify->app_description == "")
                $response->message = "Parámetros Inválidos - Description";
        }
    } catch (Exception $ex) {
        $response->status = "EXCEPTION";
        $response->message = $ex->getMessage();
    }
    return $response;
}

include './inc/incJSONHeaders.php';
echo json_encode(execute());
