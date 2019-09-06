<?php
#$folder = dirname($_SERVER['SCRIPT_FILENAME']).'/dls';
$downloadFolder = 'dls';
$tempFolder = 'temp';

function getScriptPath() {
    return dirname($_SERVER['SCRIPT_FILENAME']);
}

function getRelativeDownloadFolderPath() {
    global $downloadFolder;
    return $downloadFolder;
}

function getAbsoluteDownloadFolderPath() {
    global $downloadFolder;
    return getScriptPath() . '/' . $downloadFolder;
}

function getRelativeTempFolderPath() {
    global $tempFolder;
    return $tempFolder;
}

function getAbsoluteTempFolderPath() {
    global $tempFolder;
    return getScriptPath() . '/' . $tempFolder;
}

function getKeywordForFinished() {
    return "Finished";
}

function getKeywordForError() {
    //defined by youtube-dl
    return "ERROR:";
}

function getKeywordForStateEvent() {
    return "STATE";
}

function getKeywordForSuccessEvent() {
    return "SUCCESS";
}

function getKeywordForErrorEvent() {
    return "ERROR";
}

function createFileList(){
    global $downloadFolder;

	$fileList = array();
	if ($handle = opendir($downloadFolder)) {
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

function buildLogFilePath($id){
    return getAbsoluteTempFolderPath() ."/". $id . ".log.txt";
}

?>
