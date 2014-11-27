<?php
require_once './DA/da_conf.php';
require_once './DA/da_helper.php';
require_once './DA/da_account.php';
require_once './DA/da_session.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>AUTOMATED TEST FILE</title>
    </head>
    <body>
        <?php
        test_da_session();
        ?> 
    </body>
</html>

<?php

function test_da_session() {
    $session = da_session::CreateSession("jose.a.nunez@gmail.com");
    print_r($session);
}

function test_da_account() {
    $createdAccount = da_account::AddNewAccount("roberto.viquez@intel.com", "neo", sha1("sion"));
    if ($createdAccount == NULL) {
        echo ("ERROR");
    } else {
        echo ("OK<br>");
        print_r($createdAccount);
    }
}
?>
