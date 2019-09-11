<?php
require "utils.php";

$inputData = processRequest();
$logFilePath = buildLogFilePath($inputData->id);
if(!is_readable($logFilePath)) {
    printErrorMessage();
} else {
    printFileContent($logFilePath);
}

function printErrorMessage() {
    echo "No details available: log file not found";
}

function printFileContent($filePath) {
    $file = fopen($filePath, "r") or die("Unable to open file!");

    while(!feof($file)) {
        $line = fgets($file);
        echo $line . "<br>";
    }
    fclose($file);
}

?>
