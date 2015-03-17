<?php
$account_id = filter_input(INPUT_GET, "account_id");
$app_id  = filter_input(INPUT_GET, "app_id");
$api_key = filter_input(INPUT_GET, "api_key");

$app = da_apps_registry::GetApp($app_id);

$validation = $account_id==$app->account_id && $api_key == $app->api_key;

if(! $validation) {
    die();
}