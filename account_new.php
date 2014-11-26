<?php

/* * *
 * http://localhost:8080/pvcloud_backend/new_account.php?email=jose.a.nunez@gmail.com&nickname=jose&pwdHash=1234pass
 * 
 * * */
error_reporting(E_ERROR);

class newAccountResponse {

    public $status = "";
    public $message = "";

}

require_once './DA/da_conf.php';
require_once './DA/da_helper.php';
require_once './DA/da_account.php';

$email = filter_input(INPUT_GET, "email");
$nickname = filter_input(INPUT_GET, "nickname");
$pwdHash = filter_input(INPUT_GET, "pwdHash");



$response = new newAccountResponse();
try {
    $newAccount = da_account::AddNewAccount($email, $nickname, $pwdHash);

    if ($newAccount != NULL && $newAccount->email == $email) {
        $response->status = "OK";
        $response->message = "Account for $newAccount->email was created successfully.";
    } else {
        $response->status = "ERROR";
        $response->message = "Adding new account failed";
    }
} catch (Exception $ex) {
    $response->status = "ERROR";
    $response->message = $ex->getMessage();
}

try {
    sendNewAccountEmail($newAccount->email, $newAccount->confirmation_guid);
} catch (Exception $ex) {
    
}
header('Content-Type: application/json');
echo json_encode($response);

function sendNewAccountEmail($email, $guid) {
    $message = "Gracias por registrarse en pvCloud. Por favor haga clic en el siguiente enlace para confirmar su cuenta.";
    $message .= "http://localhost:8080/pvcloud_backend/account_activate.php?email=$email&guid=$guid";
    $to = $email;
    $subject = "Confirmación de cuenta PV Cloud";
    $headers = 'From: donotreply@costaricamakers.com' . "\r\n";
    
    
    $result = mail($to, $subject, $message, $headers);
        print_r($result);
}
