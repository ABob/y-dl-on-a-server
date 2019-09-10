<?php
//Sucht nach Downloads, die gerade laufen, und schickt ihre IDs.
require "utils.php";

$logFileList = collectDirectoryFiles(getRelativeTempFolderPath());
$idList = array();
foreach($logFileList as $file) {
    if(contains($file, getLogFileSuffix())) {
        $id = explode($logFileSuffix, $file);
        if(!empty($id)) {
            array_push($idList, $id[0]);
        }
    }
}
echo json_encode($idList);
?>
