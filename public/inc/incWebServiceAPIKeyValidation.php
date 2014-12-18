<?php
$account_id = filter_input(INPUT_GET, "account_id");
$device_id  = filter_input(INPUT_GET, "device_id");
$api_key = filter_input(INPUT_GET, "api_key");

$device = da_devices_registry::GetDevice($device_id);

$validation = $account_id==$device->account_id && $api_key == $device->api_key;

if(! $validation) {
    die();
}