<?php
require_once './DA/da_conf.php';
require_once './DA/da_helper.php';
require_once './DA/da_account.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>AUTOMATED TEST FILE</title>
    </head>
    <body>
        <?php
        $createdAccount = da_account::AddNewAccount("roberto.viquez@intel.com", "neo", sha1("sion"));
        if ($createdAccount == NULL) {
            echo ("ERROR");
        } else {
            echo ("OK<br>");
            print_r($createdAccount);
        }
        ?> 
    </body>
</html>
