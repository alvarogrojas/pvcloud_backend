<?php

$account_id = filter_input(INPUT_GET, "account_id");
$app_id  = filter_input(INPUT_GET, "app_id");
$api_key = filter_input(INPUT_GET, "api_key");

if(!isset($apikey)){
	$api_key="";
}

if(!isset($app_id) || !isset($account_id)){
    die();
}

$app = da_apps_registry::GetApp($app_id);

if($app->visibility_type_id == 3){ // 3 = Public App 
	$validation = $account_id==$app->account_id; 
}else{
	$validation = $account_id==$app->account_id && $api_key == $app->api_key;
}

if(! $validation) {
    die();
}