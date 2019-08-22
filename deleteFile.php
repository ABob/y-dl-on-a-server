<?php
require "utils.php";

//for debugging:
//print_r($_POST);
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['file'])){
    $file = $_POST['file'];
    $error = "";
    if (!isFileValidToRemove($file)){
        $error = "It's not allowed to remove files other than the previous downloaded!";
    } else {
        $error = removeFile($file);
    }
    $success = $error == "" ? true : false;
    $answer = array("success" => $success, "error" => $error);
    sendAnswer($answer);
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
