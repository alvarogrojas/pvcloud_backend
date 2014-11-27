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

function validate() {
    $email = filter_input(INPUT_GET, "email");
    $token = filter_input(INPUT_GET, "token");

    $response = new simpleResponse();

    try {
        $session = da_session::GetAndValidateSession($email, $token);

        if ($session->email == $email && $session->token == $token) {
            $response->status = "OK";
            $response->message = "Sesión válida";
        } else {
            $response->status = "ERROR";
            $response->message = "La sesión no es válida. Por favor autentíquese nuevamente";
        }
    } catch (Exception $ex) {
        $response->status = "EXCEPTION";
        $response->message = $ex->getMessage();
    }

    return $response;
}

header('Content-Type: application/json');
echo json_encode(validate());
