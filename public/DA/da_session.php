<?php

/**
 * Description of da_account
 *
 * @author janunezc
 */
class be_session {

    public $token = "";
    public $email = "";
    public $expiration_datetime = NULL;
    public $created_datetime = NULL;
    public $modified_datetime = NULL;

}

class da_session {

    public static function CreateSession($email) {

        $token = sha1(uniqid() . $email);

        $sqlCommand = "INSERT INTO sessions (token, email,expiration_datetime)"
                . "VALUES (?,?,DATE_ADD(NOW(), INTERVAL 1 HOUR))";

        $paramTypeSpec = "ss";

        $mysqli = DA_Helper::mysqli_connect();

        if ($mysqli->connect_errno) {
            $msg = "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            throw new Exception($msg, $stmt->errno);
        }

        if (!($stmt = $mysqli->prepare($sqlCommand))) {
            $msg = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            throw new Exception($msg, $stmt->errno);
        }

        if (!$stmt->bind_param($paramTypeSpec, $token, $email)) {
            $msg = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            throw new Exception($msg, $stmt->errno);
        }

        if (!$stmt->execute()) {
            $msg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            throw new Exception($msg, $stmt->errno);
        }

        $stmt->close();

        $retrievedSession = da_session::getValidSession($email, $token);
        return $retrievedSession;
    }

    public static function GetAndValidateSession($email, $token) {
        $session = da_session::getValidSession($email, $token);

        if ($session->email == $email && $session->token == $token) {
            $result = da_session::updateSessionExpirationDatetime($email, $token);
        }
        
        return $result;
    }

    private static function updateSessionExpirationDatetime($email, $token) {
        $sqlCommand = "UPDATE sessions "
                . " SET expiration_datetime = DATE_ADD(NOW(), INTERVAL 1 HOUR) "
                . " WHERE email = ? AND token = ?";

        $paramTypeSpec = "ss";

        $mysqli = DA_Helper::mysqli_connect();

        if ($mysqli->connect_errno) {
            $msg = "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            throw new Exception($msg, $stmt->errno);
        }

        if (!($stmt = $mysqli->prepare($sqlCommand))) {
            $msg = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            throw new Exception($msg, $stmt->errno);
        }

        if (!$stmt->bind_param($paramTypeSpec, $email, $token)) {
            $msg = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            throw new Exception($msg, $stmt->errno);
        }

        if (!$stmt->execute()) {
            $msg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            throw new Exception($msg, $stmt->errno);
        }

        $stmt->close();

        $retrievedSession = da_session::getValidSession($email, $token);

        return $retrievedSession;
    }

    private static function getValidSession($email, $token) {
        $sqlCommand = "SELECT email, token, expiration_datetime, created_datetime, modified_datetime "
                . " FROM sessions "
                . " WHERE email=? AND token=? AND NOW() < expiration_datetime ";

        $mysqli = DA_Helper::mysqli_connect();
        if ($mysqli->connect_errno) {
            $msg = "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            throw new Exception($msg, $stmt->errno);
        }

        if (!($stmt = $mysqli->prepare($sqlCommand))) {
            $msg = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            throw new Exception($msg, $stmt->errno);
        }

        if (!$stmt->bind_param("ss", $email, $token)) {
            $msg = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            throw new Exception($msg, $stmt->errno);
        }

        if (!$stmt->execute()) {
            $msg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            throw new Exception($msg, $stmt->errno);
        }

        $result = new be_session();
        $stmt->bind_result(
                $result->email, $result->token, $result->expiration_datetime, $result->created_datetime, $result->modified_datetime);

        if (!$stmt->fetch()) {
            $result = NULL;
        }

        $stmt->close();
        return $result;
    }

}
