<?php
require_once './DA/da_conf.php';
require_once './DA/da_helper.php';
require_once './DA/da_account.php';
require_once './DA/da_session.php';
require_once './DA/da_devices_registry.php';
require_once './DA/da_vse_data.php';
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
        
        //TEST_DADevice::test_da_device();

        //TEST_DAVSEValue::Test();

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

        if ($modifiedDevice->api_key != "" && $modifiedDevice->api_key != $deviceToModify->api_key) {
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
        
        ReportInfo("Loging out...");
        $loggedOffSession = da_session::Logout($session->account_id, $session->token);
        ReportInfo("Session after Logout begins here..-------------------");
        print_r($loggedOffSession);
        ReportInfo("Session after Logout ends here..-------------------");
        if($loggedOffSession->account_id == $session->account_id && $loggedOffSession->token ==$session->token && $loggedOffSession->expiration_datetime!=$session->expiration_datetime ){
            ReportSuccess("Seems to be OK after logoff.");
        } else{
            ReportError("IT seems result is NOT OK. Is that a good session value for Logout?");
        }
        
        ReportInfo("Session Tests Complete!");
    }

}

class TEST_DAVSEValue {

    public static function Test() {
        $uuid = uniqid();
        TEST_DAVSEValue::testAddEntries($uuid);

        //TEST_DAVSEValue::testGetEntries($uuid);

        TEST_DAVSEValue::testGetLastEntry();

        TEST_DAVSEValue::testClearEntries($uuid);
    }

    private static function testAddEntries($uuid) {
        ReportInfo("Testing Entries Addition");

        ReportInfo("Adding 100 entries: ");

        $successfulHits = 0;
        for ($i = 0; $i < 100; $i++) {
            $entryToAdd = new be_vse_data();
            $entryToAdd->device_id = 1;
            $entryToAdd->vse_label = "TEST_DATA_$uuid";
            $entryToAdd->vse_value = $i;
            $entryToAdd->vse_type = "NUMBER";
            $entryToAdd->vse_annotations = "This is testing data on value $i";
            $entryToAdd->captured_datetime = date("Y-m-d H:i:s");
            $addedEntry = da_vse_data::AddEntry($entryToAdd);

            if ($addedEntry->entry_id > 0 && $addedEntry->device_id == $entryToAdd->device_id && $addedEntry->vse_annotations == $entryToAdd->vse_annotations) {
                $successfulHits++;
            } else {
                ReportError("Oops! Entry addition seems failed!");
                ReportError("Requested Addition:");
                print_r($entryToAdd);

                ReportError("Result:");
                print_r($addedEntry);
            }
        } //END FOR

        ReportInfo("$successfulHits were added properly!");
    }

    private static function testGetEntries($uuid) {
        ReportInfo("Testing GET All Entries - Phase 1: Just device limit");
        $entries_test_01 = da_vse_data::GetEntries(1, '', 0);

        if (count($entries_test_01) >= 100) {
            ReportSuccess("Just retrieved " . count($entries_test_01) . " entries for device 1. no additional filters");
        } else {
            ReportError("Just retrieved " . count($entries_test_01) . " entries for device 1. no additional filters");
        }
        print_r($entries_test_01);

        ReportInfo("Testing GET All Entries - Phase 2: Device and Label limits");
        $targetLabel = "TEST_DATA_$uuid";
        $entries_test_02 = da_vse_data::GetEntries(1, $targetLabel, 0);
        if (count($entries_test_02) == 100) {
            ReportSuccess("Just retrieved " . count($entries_test_02) . " entries for device 1. Label Filter");
        } else {
            ReportError("Just retrieved " . count($entries_test_02) . " entries for device 1. Label Filter");
        }
        print_r($entries_test_02);


        ReportInfo("Testing GET All Entries - Phase 3:  Device, Label and Count Limits");
        $entries_test_03 = da_vse_data::GetEntries(1, $targetLabel, 50);

        if (count($entries_test_03) == 50) {
            ReportSuccess("Just retrieved " . count($entries_test_03) . " entries for device 1. Label Filter + last 50 filter");
        } else {
            ReportError("Just retrieved " . count($entries_test_03) . " entries for device 1. Label Filter + last 50 filter");
        }


        print_r($entries_test_03);
    }

    private static function testGetLastEntry() {
        ReportInfo("Testing to get the last entry of a device without Label Filter");
        $lastEntry01 = da_vse_data::GetLastEntry(1, '');
        print_r($lastEntry01);
        ReportInfo("Testing to get last entry with Label Filter");
        $lastEntry02 = da_vse_data::GetLastEntry(1, "TEST_DATA");
        print_r($lastEntry02);
        ReportInfo("COMPLETE! Testing to get the last entry");
    }

    private static function testClearEntries($uuid) {
        ReportInfo("Testing Clearing Entries");
        ReportInfo("Clearing All");
        $result = da_vse_data::ClearEntries(1, "");
        ReportInfo("Result:");
        print_r($result);

        for ($i = 1; $i <= 10; $i++) {
            $entry = new be_vse_data();
            $entry->device_id = 1;
            $entry->vse_label = "TEST FOR CLEARING_01";
            $entry->vse_value = $i . "OK";
            $entry->vse_type = "ANY";
            $entry->vse_annotations = "Testing for clearing methods";
            $entry->captured_datetime = date("Y-m-d H:i:s");
            da_vse_data::AddEntry($entry);
        }

        for ($i = 1; $i <= 10; $i++) {
            $entry = new be_vse_data();
            $entry->device_id = 1;
            $entry->vse_label = "TEST FOR CLEARING_02";
            $entry->vse_value = $i . "OK";
            $entry->vse_type = "ANY";
            $entry->vse_annotations = "Testing for clearing methods";
            $entry->captured_datetime = date("Y-m-d H:i:s");
            da_vse_data::AddEntry($entry);
        }
        
        ReportInfo("Getting all entries...");
        $allrecords = da_vse_data::GetEntries(1, '', 0);
        print_r($allrecords);
        
        ReportInfo("Clearing TEST_FOR_CLEARING_02");
        $check01 = da_vse_data::ClearEntries(1, 'TEST FOR CLEARING_02');
        print_r($check01);
        
        ReportInfo("Getting all entries again...");
        $allrecords = da_vse_data::GetEntries(1, '', 0);
        print_r($allrecords);   
        
        ReportInfo("Clearing TEST_FOR_CLEARING_01");
        $check02 = da_vse_data::ClearEntries(1, 'TEST FOR CLEARING_01');
        print_r($check02);
        
        ReportInfo("At this point all should be clear for device 1");
        $allrecords = da_vse_data::GetEntries(1, '', 0);
        print_r($allrecords);           
        
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
    echo("<hr><div style=\"color:darkblue;\">$moment - $message</div>");
}

function ReportSuccess($message) {
    $moment = Date("Y-m-d H:i:s");
    echo("<hr><div style=\"color:green; background-color:#ccffcc\">$moment - $message</div>");
}

function ReportError($message) {
    $moment = Date("Y-m-d H:i:s");
    echo("<hr><div style=\"color:maroon;font-weight:bold;background-color:EBABAB\">[!!!]$moment - $message</div>");
}
