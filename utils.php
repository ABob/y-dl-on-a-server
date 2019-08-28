<?php
#$folder = dirname($_SERVER['SCRIPT_FILENAME']).'/dls';
$folder = 'dls/';

function createFileList(){
    global $folder;

	$fileList = array();
	if ($handle = opendir($folder)) {
		while (false !== ($file = readdir($handle))) {
			if (isNotFolderReference($file) && isNotHiddenFile($file)) {
                array_push($fileList, $file);
			}
		}
		closedir($handle);
	}
	return $fileList;
}

function isNotFolderReference($file) {
    return $file != "." && $file != "..";
}

function isNotHiddenFile($file){
    return $file[0] != ".";
}

function processRequest() {
    $json = file_get_contents('php://input');
    return json_decode($json);
}

function sendAnswer($result) {
    echo json_encode($result);
}

?>
