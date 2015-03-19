<?php

/*TEST: 
 * https://localhost/pvcloud_backend/vse_get_csv.php?account_id=1&app_id=1&api_key=c55452a9bdacdc0dc15919cdfe8d8f7d4c05ac5e
 * https://localhost/pvcloud_backend/vse_get_csv.php?account_id=1&app_id=1&api_key=c55452a9bdacdc0dc15919cdfe8d8f7d4c05ac5e&value_label=TEST_01&count_limit=3
*/
error_reporting(E_ERROR);

require_once './DA/da_conf.php';
require_once './DA/da_helper.php';
require_once './DA/da_account.php';
require_once './DA/da_session.php';
require_once './DA/da_apps_registry.php';
require_once './DA/da_vse_data.php';

class beParameters {

    public $account_id = 0;
    public $app_id = 0;
    public $value_label = "";
    public $count_limit = 0;

}

class CSVWebService {

    public static function GenerateCSV() {
        try {
            $app_id = 0; //THIS WILL BE OVERRIDEN BY THE INCLUDE 
            $account_id = 0; //THIS WILL BE OVERRIDEN BY THE INCLUDE 
            include './inc/incWebServiceAPIKeyValidation.php';

            $parameters = CSVWebService::collectParameters();
            $parameters->app_id = $app_id;
            $parameters->account_id = $account_id;

            $entries = da_vse_data::GetEntries($parameters->app_id, $parameters->value_label, $parameters->count_limit);

            //$csv = CSVWebService::arrayToCsv($entries);

            $csv = CSVWebService::generateCSVContent($entries);

            return $csv;
        } catch (Exception $ex) {
            return $ex;
        }

        return null;
    }

    private static function collectParameters() {
        $parameters = new beParameters();
        $parameters->value_label = filter_input(INPUT_GET, "value_label");
        $parameters->count_limit = filter_input(INPUT_GET, "count_limit");

        if (!isset($parameters->value_label)) {
            $parameters->value_label = "";
        }

        if (!isset($parameters->count_limit)) {
            $parameters->count_limit = 0;
        }

        return $parameters;
    }

    /**
     * Converts ana array of objects into a CSV string.
     * by @janunezc
     * @param Array $entries
     * @return string
     */
    private static function generateCSVContent($entries) {
        $recordCount = 0;
        $result = "";
        $crlf = "\r\n";
        foreach ($entries as $row) {
            $csvRow = "";
            $csvHeader = "";

            foreach ($row as $fieldName => $property) {
                if ($recordCount == 0) {
                    $csvHeader.= '"' . $fieldName . '"' . ",";
                }

                $fixedProperty = str_replace("\"", "\"\"", $property);
                $csvRow.= '"' . $fixedProperty . '"' . ",";
            }

            //IMPLODE METHOD WAS NOT USED IN ORDER TO ENABLE SANITIZATION OF DOUBLEQUOTES WITHIN PROPERTY VALUES.
            //$csvRow.= "\"".implode("\",\"", get_object_vars($row))."\"".$newline;

            if ($recordCount == 0) {

                //remove last comma on header
                $lastCommaPosition = strlen($csvHeader) - 1;
                $csvHeader = substr($csvHeader, 0, $lastCommaPosition) . $crlf;
                $result.=$csvHeader;
            }

            //remove last comma on row
            $lastCommaPosition = strlen($csvRow) - 1;
            $fixedCSVRow = substr($csvRow, 0, $lastCommaPosition);
            $result .= $fixedCSVRow . $crlf;

            $recordCount ++;
        }

        return $result;
    }

    //FUNCTION NOT USED... FEEL FREE TO REMOVE ON CODE CLEANUP
    private static function arrayToCsv(array &$fields, $delimiter = ';', $enclosure = '"', $encloseAll = false, $nullToMysqlNull = false) {
        $delimiter_esc = preg_quote($delimiter, '/');
        $enclosure_esc = preg_quote($enclosure, '/');

        $output = array();
        foreach ($fields as $field) {
            if ($field === null && $nullToMysqlNull) {
                $output[] = 'NULL';
                continue;
            }

            // Enclose fields containing $delimiter, $enclosure or whitespace
            if ($encloseAll || preg_match("/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field)) {
                $output[] = $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure;
            } else {
                $output[] = $field;
            }
        }

        return implode($delimiter, $output);
    }

}

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=file.csv");
header("Pragma: no-cache");
header("Expires: 0");
echo CSVWebService::GenerateCSV();

