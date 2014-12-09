<?php
require_once './DA/da_conf.php';
require_once './DA/da_helper.php';
require_once './DA/da_account.php';
require_once './DA/da_session.php';
require_once './DA/da_devices_registry.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>AUTOMATED TEST FILE</title>
    </head>
    <body style="font-family:courier">
        <?php
        ReportInfo("Initiating Tests!");

        TEST_DASession::test_da_session();

        TEST_DADevice::test_da_device();

        ReportInfo("Tests Finished!");
        ?> 
    </body>
</html>

<?php

class TEST_DADevice {

    public static function test_da_device() {
        $testName = "DA DEVICE TEST";
        ReportInfo("Initiating $testName");

        $createdDevice = TEST_DADevice::testDeviceCreation();
        
        TEST_DADevice::testDeviceListRetrieval();
        
        $modifiedDevice = TEST_DADevice::testDeviceModification($createdDevice);
        
        $apiGenerationDevice = TEST_DADevice::testAPIKeyRegeneration($modifiedDevice);
        
        $deletedDevice = TEST_DADevice::testDeviceDeletion($apiGenerationDevice);
        
        TEST_DADevice::testDeviceListRetrieval();

        ReportInfo("Complete: $testName");
    }

    private static function testDeviceCreation() {
        $newDevice = new be_device();
        $newDevice->account_id = 1;
        $newDevice->device_nickname = "JN GALILEO1";
        $newDevice->device_description = "";

        ReportInfo("Device to Create:");
        print_r($newDevice);

        $registeredDevice = da_devices_registry::RegisterNewDevice($newDevice);
        ReportInfo("Device Created:");
        print_r($registeredDevice);

        if ($registeredDevice->device_id > 0) {
            ReportSuccess("Created Device seems to be OK!");
        } else {
            ReportError("Created device seems to be WRONG! :(");
        }

        return $registeredDevice;
    }

    private static function testDeviceListRetrieval() {
        ReportInfo("Testing retrieval of a list of devices for an account...");
        $account_id = 1;
        $devices = da_devices_registry::GetListOfDevices($account_id);
        print_r($devices);
        if (count($devices) > 0) {
            ReportSuccess("Result seems to be fine.");
        } else {
            ReportSuccess("Result seems to be WRONG");
        }
    }

    /**
     * 
     * @param be_device $deviceToModify
     * @return be_device
     */
    private static function testDeviceModification($deviceToModify) {

        ReportInfo("Device to Modify:");
        print_r($deviceToModify);

        $deviceToModify->device_nickname = "JN_GALILEO_MODIFIED";
        $deviceToModify->device_description = "Modified Galielo Entry";

        $modifiedDevice = da_devices_registry::UpdateDevice($deviceToModify);

        if ($modifiedDevice->device_nickname == $deviceToModify->device_nickname && $modifiedDevice->device_description == $deviceToModify->device_description) {
            ReportSuccess("Device Seems to be properly modified");
        } else {
            ReportError("Device Modification seemed to fail!");
        }

        $deviceToModify->device_nickname = "JN GALILEO MODIFIED 2";
        ReportInfo("Second Modification:");
        print_r($deviceToModify);

        $modifiedDevice = da_devices_registry::UpdateDevice($deviceToModify);

        if ($modifiedDevice->device_nickname == $deviceToModify->device_nickname && $modifiedDevice->device_description == $deviceToModify->device_description) {
            ReportSuccess("Device Seems to be properly modified");
        } else {
            ReportError("Device Modification seemed to fail!");
        }

        return $modifiedDevice;
    }

    /**
     * 
     * @param be_device $deviceToModify
     * @return be_device
     */
    private static function testAPIKeyRegeneration($deviceToModify) {
        ReportInfo("Device to Generate API KEY for:");
        print_r($deviceToModify);

        $modifiedDevice = da_devices_registry::RegenerateApiKey($deviceToModify->device_id);

        ReportInfo("Device with new API KEY:");
        print_r($modifiedDevice);
        
        if ($modifiedDevice->api_key!="" && $modifiedDevice->api_key != $deviceToModify->api_key) {
            ReportSuccess("API KEY properly modified");
        } else {
            ReportError("API KEY GENERQATION seemed to fail!");
        }

        return $modifiedDevice;
    }

    /**
     * 
     * @param be_device $deviceToDelete
     * @return be_device
     */
    private static function testDeviceDeletion($deviceToDelete) {
        ReportInfo("Device to DELETE:");
        print_r($deviceToDelete);

        $deletedDevice = da_devices_registry::DeleteDevice($deviceToDelete->device_id);

        ReportInfo("RESULT:");
        print_r($deletedDevice);
        
        if ($deletedDevice->deleted_datetime != NULL) {
            ReportSuccess("API KEY properly modified");
        } else {
            ReportError("API KEY GENERQATION seemed to fail!");
        }

        return $deletedDevice;        
    }

}

class TEST_DASession {

    public static function test_da_session() {
        ReportInfo("Initiating Session Test");
        ReportInfo("creating session on jose.a.nunez@gmail.com");
        $session = da_session::CreateSession(1);
        print_r($session);
        if ($session->token != '') {
            ReportSuccess("Session seems to be Correct!");
        } else {
            ReportError("Session seems to be BAD");
        }
        ReportInfo("Session Tests Complete!");
    }

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

function ReportInfo($message) {
    $moment = Date("Y-m-d H:i:s");
    echo("<hr><div style=\"color:darkblue\">$moment - $message</div>");
}

function ReportSuccess($message) {
    $moment = Date("Y-m-d H:i:s");
    echo("<hr><div style=\"color:green;\">$moment - $message</div>");
}

function ReportError($message) {
    $moment = Date("Y-m-d H:i:s");
    echo("<hr><div style=\"color:red;\">[!!!]$moment - $message</div>");
}
