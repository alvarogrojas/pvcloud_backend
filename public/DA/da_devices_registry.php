<?php

/**
 * Description of da_devices_registry
 *
 * @author janunezc
 */
class be_device {

    public $device_id = 0;
    public $account_id = 0;
    public $device_nickname = "";
    public $device_description = "";
    public $api_key = "";
    public $created_datetime = NULL;
    public $modified_datetime = NULL;
    public $deleted_datetime = NULL;
    public $last_connected_datetime = NULL;

}

class da_devices_registry {

    /**
     * Registers a device and returns the resultant record as be_device
     * @param be_device $device
     * @return type
     */
    public static function RegisterNewDevice($device) {
        $sqlCommand = "INSERT INTO device_registry (account_id,device_nickname,device_description,api_key)"
                . "VALUES (?,?,?,SHA1(UUID()))";

        $paramTypeSpec = "sss";

        $mysqli = DA_Helper::mysqli_connect();
        if ($mysqli->connect_errno) {
            $msg = "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            throw new Exception($msg, $mysqli->connect_errno);
        }

        if (!($stmt = $mysqli->prepare($sqlCommand))) {
            $msg = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            throw new Exception($msg, $stmt->errno);
        }

        if (!$stmt->bind_param($paramTypeSpec, $device->account_id, $device->device_nickname, $device->device_description)) {
            $msg = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            throw new Exception($msg, $stmt->errno);
        }

        if (!$stmt->execute()) {
            $msg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            throw new Exception($msg, $stmt->errno);
        }

        $stmt->close();

        $insertedDeviceID = $mysqli->insert_id;

        $retrievedDevice = da_devices_registry::GetDevice($insertedDeviceID);
        return $retrievedDevice;
    }

    /**
     * Returns a device found by its ID
     * @param int $device_id
     * @return be_device
     */
    public static function GetDevice($device_id) {
        $sqlCommand = ""
                . "SELECT   device_id,account_id,device_nickname,device_description,api_key,created_datetime,modified_datetime,deleted_datetime,last_connected_datetime"
                . " FROM device_registry WHERE device_id=? ";

        $mysqli = DA_Helper::mysqli_connect();
        if ($mysqli->connect_errno) {
            $msg = "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            throw new Exception($msg, $mysqli->connect_errno);
        }

        if (!($stmt = $mysqli->prepare($sqlCommand))) {
            $msg = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            throw new Exception($msg, $stmt->errno);
        }

        if (!$stmt->bind_param("i", $device_id)) {
            $msg = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            throw new Exception($msg, $stmt->errno);
        }

        if (!$stmt->execute()) {
            $msg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            throw new Exception($msg, $stmt->errno);
        }

        $result = new be_device();
        $stmt->bind_result(
                $result->device_id, $result->account_id, $result->device_nickname, $result->device_description, $result->api_key, $result->created_datetime, $result->modified_datetime, $result->deleted_datetime, $result->last_connected_datetime
        );

        if (!$stmt->fetch()) {
            $result = NULL;
        }

        $stmt->close();

        return $result;
    }

    /**
     * Returns a list of devices for a given user account
     * @param type $account_id
     * return Array
     */
    public static function GetListOfDevices($account_id) {
        $sqlCommand = "SELECT     device_id,account_id,device_nickname,device_description,api_key,created_datetime,modified_datetime,deleted_datetime,last_connected_datetime "
                . "FROM device_registry "
                . "WHERE account_id = ? AND deleted_datetime IS NULL";

        $mysqli = DA_Helper::mysqli_connect();
        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }

        if (!($stmt = $mysqli->prepare($sqlCommand))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("i", $account_id)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $deviceEntry = new be_device();

        $stmt->bind_result(
                $deviceEntry->device_id, $deviceEntry->account_id, $deviceEntry->device_nickname, $deviceEntry->device_description, $deviceEntry->api_key, $deviceEntry->created_datetime, $deviceEntry->modified_datetime, $deviceEntry->deleted_datetime, $deviceEntry->last_connected_datetime);

        $arrayResult = [];
        while ($stmt->fetch()) {
            $arrayResult[] = json_decode(json_encode($deviceEntry));
        }

        $stmt->close();

        return $arrayResult;
    }

    /**
     * Updates a device with the provided information and returns the resulting record as saved.
     * @param be_device $device
     * @return be_device
     */
    public static function UpdateDevice($device) {
        $sqlCommand = "UPDATE device_registry "
                . " SET  account_id = ?, "
                . "     device_nickname = ?, "
                . "     device_description = ? "
                . " WHERE device_id = ? ";

        $paramTypeSpec = "issi";

        $mysqli = DA_Helper::mysqli_connect();
        if ($mysqli->connect_errno) {
            $msg = "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            throw new Exception($msg, $mysqli->connect_errno);
        }

        if (!($stmt = $mysqli->prepare($sqlCommand))) {
            $msg = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            throw new Exception($msg, $stmt->errno);
        }

        if (!$stmt->bind_param($paramTypeSpec, $device->account_id, $device->device_nickname, $device->device_description, $device->device_id)) {
            $msg = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            throw new Exception($msg, $stmt->errno);
        }

        if (!$stmt->execute()) {
            $msg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            throw new Exception($msg, $stmt->errno);
        }

        $stmt->close();

        $retrievedDevice = da_devices_registry::GetDevice($device->device_id);
        return $retrievedDevice;
    }

    public static function RegenerateApiKey($device_id) {
        $sqlCommand = "UPDATE device_registry "
                . " SET  api_key = SHA1(UUID()) "
                . " WHERE device_id = ? ";

        $paramTypeSpec = "i";

        $mysqli = DA_Helper::mysqli_connect();
        if ($mysqli->connect_errno) {
            $msg = "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            throw new Exception($msg, $mysqli->connect_errno);
        }

        if (!($stmt = $mysqli->prepare($sqlCommand))) {
            $msg = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            throw new Exception($msg, $stmt->errno);
        }

        if (!$stmt->bind_param($paramTypeSpec, $device_id)) {
            $msg = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            throw new Exception($msg, $stmt->errno);
        }

        if (!$stmt->execute()) {
            $msg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            throw new Exception($msg, $stmt->errno);
        }

        $stmt->close();

        $retrievedDevice = da_devices_registry::GetDevice($device_id);
        return $retrievedDevice;
    }

    /**
     * Deletes a device by updating its deleted_datetime
     * @param int $device_id
     * @return be_device
     */
    public static function DeleteDevice($device_id) {
        $sqlCommand = "UPDATE device_registry "
                . " SET  deleted_datetime = NOW() "
                . " WHERE device_id = ? ";

        $paramTypeSpec = "i";

        $mysqli = DA_Helper::mysqli_connect();
        if ($mysqli->connect_errno) {
            $msg = "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            throw new Exception($msg, $mysqli->errno);
        }

        if (!($stmt = $mysqli->prepare($sqlCommand))) {
            $msg = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            throw new Exception($msg, $stmt->errno);
        }

        if (!$stmt->bind_param($paramTypeSpec, $device_id)) {
            $msg = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            throw new Exception($msg, $stmt->errno);
        }

        if (!$stmt->execute()) {
            $msg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            throw new Exception($msg, $stmt->errno);
        }

        $stmt->close();

        $retrievedDevice = da_devices_registry::GetDevice($device_id);
        return $retrievedDevice;
    }

}
