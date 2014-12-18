<?php

/**
 * Description of da_account
 *
 * @author janunezc
 */
class be_session {

    public $account_id = 0;
    public $token = "";
    public $expiration_datetime = NULL;
    public $created_datetime = NULL;
    public $modified_datetime = NULL;

}

class da_session {

    public static function CreateSession($account_id) {


        if ($account_id > 0) {
            $token = sha1(uniqid() . $account_id);

            $sqlCommand = "INSERT INTO sessions (token, account_id, expiration_datetime, created_datetime)"
                    . "VALUES (?,?,DATE_ADD(NOW(), INTERVAL 1 HOUR), NOW())";

            $paramTypeSpec = "si";

            $mysqli = DA_Helper::mysqli_connect();

            if ($mysqli->connect_errno) {
                $msg = "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
                throw new Exception($msg, $stmt->errno);
            }

            if (!($stmt = $mysqli->prepare($sqlCommand))) {
                $msg = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                throw new Exception($msg, $stmt->errno);
            }

            if (!$stmt->bind_param($paramTypeSpec, $token, $account_id)) {
                $msg = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
                throw new Exception($msg, $stmt->errno);
            }

            if (!$stmt->execute()) {
                $msg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                throw new Exception($msg, $stmt->errno);
            }

            $stmt->close();

            $retrievedSession = da_session::GetAndValidateSession($account_id, $token);
            return $retrievedSession;
        } else {
            throw new Exception("account_id invalid");
        }
    }

    public static function GetAndValidateSession($account_id, $token) {
        $session = da_session::getValidSession($account_id, $token);

        if ($session->account_id == $account_id && $session->token == $token) {
            $result = da_session::updateSessionExpirationDatetime($account_id, $token);
        }

        return $result;
    }

    private static function updateSessionExpirationDatetime($account_id, $token) {
        $sqlCommand = "UPDATE sessions "
                . " SET expiration_datetime = DATE_ADD(NOW(), INTERVAL 1 HOUR) "
                . " WHERE account_id = ? AND token = ?";

        $paramTypeSpec = "is";

        $mysqli = DA_Helper::mysqli_connect();

        if ($mysqli->connect_errno) {
            $msg = "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            throw new Exception($msg, $mysqli->errno);
        }

        if (!($stmt = $mysqli->prepare($sqlCommand))) {
            $msg = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            throw new Exception($msg, $stmt->errno);
        }

        if (!$stmt->bind_param($paramTypeSpec, $account_id, $token)) {
            $msg = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            throw new Exception($msg, $stmt->errno);
        }

        if (!$stmt->execute()) {
            $msg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            throw new Exception($msg, $stmt->errno);
        }

        $stmt->close();

        $retrievedSession = da_session::getValidSession($account_id, $token);

        return $retrievedSession;
    }

    private static function getValidSession($account_id, $token) {
        $sqlCommand = "SELECT s.account_id, s.token, s.expiration_datetime, s.created_datetime, s.modified_datetime "
                . " FROM sessions s "
                . " WHERE s.account_id=? AND s.token=? AND NOW() < s.expiration_datetime ";

        $mysqli = DA_Helper::mysqli_connect();
        if ($mysqli->connect_errno) {
            $msg = "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            throw new Exception($msg, $mysqli->errno);
        }

        if (!($stmt = $mysqli->prepare($sqlCommand))) {
            $msg = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            throw new Exception($msg, $stmt->errno);
        }

        if (!$stmt->bind_param("is", $account_id, $token)) {
            $msg = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            throw new Exception($msg, $stmt->errno);
        }

        if (!$stmt->execute()) {
            $msg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            throw new Exception($msg, $stmt->errno);
        }

        $result = new be_session();
        $stmt->bind_result(
                $result->account_id, $result->token, $result->expiration_datetime, $result->created_datetime, $result->modified_datetime);

        if (!$stmt->fetch()) {
            $result = NULL;
        }

        $stmt->close();
        return $result;
    }

}
