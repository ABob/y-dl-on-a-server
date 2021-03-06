<?php
require "utils.php";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $json = processRequest();
    $file = $json->file;
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

function buildAnswer($success, $error) {
    return array("success" => $success, "error" => $error);
}

function isFileValidToRemove($file) {
    $removableFiles = createFileList();
    return in_array($file, $removableFiles);
}

function removeFile($file) {
    $pathToFile = realpath(getAbsoluteDownloadFolderPath() .'/'. $file);
    if(is_writable($pathToFile)) {
        return unlink($pathToFile) ? "" : "Unlink failed";
    } else {
        return "No write access on file";
    }
}

?>
