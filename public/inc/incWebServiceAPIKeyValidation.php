<?php

$account_id = filter_input(INPUT_GET, "account_id");
$app_id  = filter_input(INPUT_GET, "app_id");
$api_key = filter_input(INPUT_GET, "api_key");

echo $access;

if(!isset($apikey)){
	$api_key="";
}

if(!isset($app_id) || !isset($account_id)){
    die();
}

$app = da_apps_registry::GetApp($app_id);
$validation = true; 
print_r($app);

echo $validation; 
if($app->visibility_type_id == 3 && $access == "RO"){ // 3 = Public App 
	echo "22: <br>";
	$validation = $account_id==$app->account_id; 
}else{
	echo "25: <br>";
	echo $app->account_id."<br>"; 
	echo $app->api_key."<br>";
	$validation = $account_id==$app->account_id && $api_key == $app->api_key;
	print_r($validation); 
}

print_r($validation); 

if(! $validation) {
	echo "die"; 
    die();
}