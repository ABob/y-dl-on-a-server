<?php
require "utils.php";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $file = processRequest();
    $error = "";
    if (!isFileValidToRemove($file)){
        $error = "It's not allowed to remove files other than the previous downloaded!";
    } else {
        $error = removeFile($file);
    }
    $success = $error == "" ? true : false;
} else {
    $success = false;
    $error = "Request format error";
}
$answer = buildAnswer($success, $error);
sendAnswer($answer);

function processRequest() {
    $json = file_get_contents('php://input');
    return json_decode($json)->file;
}
function buildAnswer($success, $error) {
    return array("success" => $success, "error" => $error);
}

function isFileValidToRemove($file) {
    $removableFiles = createFileList();
    return in_array($file, $removableFiles);
}

function removeFile($file) {
    global $folder;

    $pathToFile = realpath($folder . $file);
    if(is_writable($pathToFile)) {
        return unlink($pathToFile) ? "" : "Unlink failed";
    } else {
        return "No write access on file";
    }
}

function sendAnswer($result) {
    echo json_encode($result);
}

?>
