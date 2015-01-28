<?php

/* * *
 * http://localhost:8080/pvcloud_backend/account_change_password.php?account_id=1&old_password=1234pass&newpassword=1234otherpass&new_password2=1234otherpass
 * 
 * * */
error_reporting(E_ERROR);

require_once './DA/da_conf.php';
require_once './DA/da_helper.php';
require_once './DA/da_account.php';
require_once './DA/da_session.php';
include './inc/incBaseURL.php';

class be_login_parameters {

    public $Email = "";
    public $Password = "";

}

class WebServiceClass {

    /**
     * Use OK, ERROR or EXCEPTION
     * @var String 
     */
    public $status = ""; /* OK, ERROR, EXCEPTION */
    public $message = "";
    public $data = NULL;

    /**
     * Performs Login Verification and Gets Session if Authentication Succeeds.
     * @return \WebServiceClass
     */
    public static function Login() {
        $response = new WebServiceClass();

        try {
            $parameters = WebServiceClass::collectParameters();
            $account = da_account::GetAccount($parameters->Email);
            if (WebServiceClass::authenticate($account, $parameters->Password)) {
                $session = WebServiceClass::getValidSession($account);
                $response->status = "OK";
                $response->data = $session;
                $response->data->email = $account->email;
            } else {
                $response->status = "ERROR";
                $response->message = "Crenciales equivocadas";
            }
        } catch (Exception $ex) {
            $response->status = "EXCEPTION";
            $response->message = $ex->getMessage();
        }

        return $response;
    }

    private static function authenticate($account, $password) {
        $pwdHash = sha1($password);

        if ($account->account_id > 0) {
            if ($pwdHash == $account->pwd_hash) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * 
     * @param be_account $account
     * @return be_session
     * @throws Exception
     */
    private static function getValidSession($account) {
        $session = da_session::CreateSession($account->account_id);
        if (WebServiceClass::sessionIsValid($session)) {
            return $session;
        } else {
            throw new Exception("Ocurrió un error al crear su sesión");
        }
    }

    /**
     * 
     * @param be_session $session
     * @return boolean
     */
    private static function sessionIsValid($session) {
        return $session->account_id > 0 && $session->token != "" && $session != NULL;
    }

    /**
     * 
     * @return be_login_parameters
     */
    private static function collectParameters() {
        $parameters = new be_login_parameters();
        $parameters->Email = filter_input(INPUT_POST, "username");
        $parameters->Password = filter_input(INPUT_POST, "password");

        return $parameters;
    }

}

$result = WebServiceClass::Login();

switch ($result->status) {
    case "OK":
        $token = $result->data->token;
        $account_id = $result->data->account_id;
        $email = $result->data->email;
        $url = getBaseURL("pvcloud") . "#/mycloud_login/$email/$account_id/$token";
        header("Location: $url");
        break;
    case "ERROR":
        $url = getBaseURL("pvcloud") . "#/login_err";
        header("Location: $url");
    default: //EXCEPTION CASE
        $url = getBaseURL("pvcloud") . "#/err";
        header("Location: $url");        
}

