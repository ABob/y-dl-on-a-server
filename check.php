<?php
require "utils.php";

const READ = "read";
const WRITE = "write";

$accessMatrix = array(
    new Check(getRelativeDownloadFolderPath(), WRITE),
    new Check(getRelativeDownloadFolderPath(), READ),
    new Check(getRelativeTempFolderPath(), WRITE),
    new Check(getRelativeTempFolderPath(), READ)
);

echo json_encode(checkAllFiles());

function checkAllFiles() {
    global $accessMatrix;
    $result = array();

    foreach($accessMatrix as $check) {
        $msg = checkFile($check->path, $check->mode);
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

class Check {
    public $path;
    public $mode;
    function __construct($path, $mode) {
        $this->path = $path;
        $this->mode = $mode;
    }
}
?>
