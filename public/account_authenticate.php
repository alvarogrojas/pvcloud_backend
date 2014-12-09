<?php

/* * *
 * http://localhost:8080/pvcloud_backend/account_authenticate.php?email=jose.a.nunez@gmail.com&pwd=1234pass
 * 
 * * */
error_reporting(E_ERROR);

class simpleResponse {

    public $status = ""; /*OK, ERROR, EXCEPTION*/
    public $message = "";
    public $data = NULL;

}

require_once './DA/da_conf.php';
require_once './DA/da_helper.php';
require_once './DA/da_account.php';
require_once './DA/da_session.php';

function authenticate() {
    $email = filter_input(INPUT_GET, "email");
    $pwd = filter_input(INPUT_GET, "pwd");

    $response = new simpleResponse();
    try {
        $activatedAccount = da_account::GetAccount($email);
        $pwdHash = sha1($pwd);

        if ($pwdHash == $activatedAccount->pwd_hash) {
            $newSession = da_session::CreateSession($activatedAccount->account_id);
            if ($newSession->account_id > 0 && $newSession->token != "" && $newSession != NULL) {
                $response->status = "OK";
                $response->data = $newSession;
            }
        } else {
            $response->status = "ERROR";
            $response->message = "Las credenciales suministradas no son válidas";
        }
    } catch (Exception $ex) {
        $response->status = "EXCEPTION";
        $response->message = $ex->getMessage();
    }

    return $response;
}
header("Access-Control-Allow-Origin: http://localhost:9000");
header('Content-Type: application/json');
echo json_encode(authenticate());
