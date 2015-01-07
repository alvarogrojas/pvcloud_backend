/**
 * node pvcloud_api.js action=add_value value="abc 123" value_label="pvCloud_TEST" value_type="ALPHA OR WATEVER" captured_datetime="2014-12-19+12:27"
 */

var request = require('request');

var pvCloudModule = function (device_id, api_key, account_id, baseURL ){
    var DEBUG = false;

    log("started");
    log("process.argv: ");
    log(process.argv);

    device_id = device_id || 2;
    api_key = api_key || "32c50daa34760183d9ec217ed775c60d155ac81c";
    account_id = account_id || 1;
    baseURL = baseURL || "http://costaricamakers.com/pvcloud_backend/";

    var parameters = captureParameters();

    if (validateParameters()) {
        switch (parameters.action) {
            case "add_value":
                pvCloud_AddValue(device_id, parameters.value_label, parameters.value, parameters.value_type, parameters.captured_datetime);
                break;
            case "get_last_value":
                pvCloud_GetLastValue(device_id, parameters.value_label);
                break;
            case "get_values":
                pvCloud_GetValues(device_id, parameters.value_label, parameters.last_limit);
                break;
            case "clear_values":
                pvCloud_ClearValues(device_id, parameters.value_label);
                break;
        }
    }


    /* PRIVATE FUNCTIONS */
    function captureParameters() {
        log("captureParameters()");
        var parameters = {};
        process.argv.forEach(function (val, index /*, array*/) {
            /*  
             *  index 0: a reference to the running node program
             *  index 1: a reference to the JS program being executed
             *  index 2 and beyond: parameters passed to the program
             */

            if (index >= 2) {
                var param = val.split("=");
                parameters[param[0]] = param[1];
            }//END IF
        }); //END FOREACH

        log("Parameters Captured:");
        log(parameters);
        return parameters;
    }

    function validateParameters() {
        var action = parameters.action;
        switch (action) {
            case "add_value":
                if (parameters.value === undefined)
                    throw("Invalid or missing parameter for action add_value: value");
                if (parameters.value_label === undefined)
                    throw("Invalid or missing parameter for action add_value: value_label");
                if (parameters.value_type === undefined)
                    throw("Invalid or missing parameter for action add_value: value_type");
                if (parameters.captured_datetime === undefined)
                    throw("Invalid or missing parameter for action add_value: captured_datetime");
                else {
                    var pattern01 = /(\d{4})-(\d{2})-(\d{2})\+(\d{2}):(\d{2})/;
                    var pattern02 = /(\d{4})-(\d{2})-(\d{2})\+(\d{2}):(\d{2}):(\d)/;
                    if (!parameters.captured_datetime.match(pattern01) && !parameters.captured_datetime.match(pattern02)) {
                        throw("Invalid or missing parameter for action add_value: captured_datetime. Wrong Pattern");
                    }
                }
                break;
            case "get_last_value":
                if (parameters.value_label === undefined)
                    parameters.value_label = "";
                break;
            case "get_values":
                if (parameters.value_label === undefined)
                    parameters.value_label = "";
                break;
            case "clear_values":
                if (parameters.value_label === undefined)
                    parameters.value_label = "";
                break;
            default:
                throw ("Invalid Action");
        }

        return true;
    }

    function log(message) {
        if (DEBUG) {
            console.log(message);
        }
    }

    function pvCloud_AddValue(device_id, value_label, value, value_type, captured_datetime) {
        log("AddValue()");
        var wsURL = baseURL;
        wsURL += "vse_add_value.php";
        wsURL += '?device_id=' + device_id;
        wsURL += '&api_key=' + api_key;
        wsURL += '&account_id=' + account_id;

        wsURL += '&label=' + value_label;
        wsURL += '&value=' + value;
        wsURL += '&type=' + value_type;
        wsURL += '&captured_datetime=' + captured_datetime;
        
        log(wsURL);

        request(wsURL, function (error, response, body) {

            if (!error && response.statusCode === 200) {
                console.log(body);
            } else {
                console.log(response);
                console.log(error);
            }
        });
    }

    function pvCloud_ClearValues(device_id, value_label) {
        var wsURL = baseURL;
        wsURL += "vse_clear_values.php";
        wsURL += '?device_id=' + device_id;
        wsURL += '&api_key=' + api_key;
        wsURL += '&account_id=' + account_id;

        wsURL += '&label=' + value_label;

        request(wsURL, function (error, response, body) {

            if (!error && response.statusCode === 200) {
                console.log(body);
            } else {
                console.log(response);
                console.log(error);
            }
        });
    }

    function pvCloud_GetLastValue(device_id, value_label) {
        var wsURL = baseURL;
        wsURL += "vse_get_value_last.php";
        wsURL += '?device_id=' + device_id;
        wsURL += '&api_key=' + api_key;
        wsURL += '&account_id=' + account_id;
        
        wsURL += '&label=' + value_label;

        request(wsURL, function (error, response, body) {

            if (!error && response.statusCode === 200) {
                console.log(body);
            } else {
                console.log(response);
                console.log(error);
            }
        });
    }

    function pvCloud_GetValues(device_id, value_label, last_limit) {
        var wsURL = baseURL;
        wsURL += "vse_get_values.php";
        wsURL += '?device_id=' + device_id;
        wsURL += '&api_key=' + api_key;
        wsURL += '&account_id=' + account_id;        
        
        wsURL += '&label=' + value_label;

        request(wsURL, function (error, response, body) {
            if (!error && response.statusCode === 200) {
                console.log(body);
            } else {
                console.log(response);
                console.log(error);
            }
        });
    }
};