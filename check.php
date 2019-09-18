<?php
require "utils.php";

const READ = "read";
const WRITE = "write";

$accessMatrix = array(
    getRelativeDownloadFolderPath() => WRITE,
    getRelativeDownloadFolderPath() => READ,
    getRelativeTempFolderPath() => WRITE,
    getRelativeTempFolderPath() => READ
);

echo json_encode(checkAllFiles());

function checkAllFiles() {
    global $accessMatrix;
    $result = array();

    foreach($accessMatrix as $path => $mode) {
        $msg = checkFile($path, $mode);
        if(!empty($msg)) {
            array_push($result, $msg);
        }
    }
    return $result;
}

function checkFile($filePath, $mode) {
    $success = true;
    $msg = "";
    switch($mode) {
    case READ:
        $success = is_readable($filePath);
        $verb = "readable";
        break;
    case WRITE:
        $success = is_writable($filePath);
        $verb = "writable";
        break;
    }

    if(!$success) {
        $msg = "ERROR: ". $filePath ." is not ". $verb ."! Please correct this to ensure that the application is working correctly.";
    }

    return $msg;
}
?>
