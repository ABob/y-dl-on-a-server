<?php
#$folder = dirname($_SERVER['SCRIPT_FILENAME']).'/dls';
$downloadFolder = 'dls';
$tempFolder = 'temp';
$logFileSuffix = ".log.txt";
$metaFileSuffix = ".meta.txt";
$rssFeedFile = $tempFolder."/genrss.xml";

$logPath = $tempFolder."/log.monitor.txt";

# uncomment this line to turn on log
#$log = initLog($logPath);

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

function getRelativeRssFilePath() {
    global $rssFeedFile;
    return $rssFeedFile;
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

function getKeywordForCreateEvent() {
    return "CREATION";
}

function getKeywordForTitleEvent() {
    return "TITLE";
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

function getKeywordForCreationDate() {
    return "CREATED";
}

function getKeywordForTitle() {
    return "TITLE";
}

function createFileList(){
    global $downloadFolder;
    return collectDirectoryFiles($downloadFolder);
}

function collectDirectoryFiles($folderPath) {
	$fileList = array();
	if ($handle = opendir($folderPath)) {
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
    global $logFileSuffix;
    return getAbsoluteTempFolderPath() ."/". $id .$logFileSuffix;
}

function buildMetaFilePath($id){
    global $metaFileSuffix;
    return getAbsoluteTempFolderPath() ."/". $id .$metaFileSuffix;
}

function appendToMetaFile($text) {
    appendToFile(buildMetaFilePath($id, $text));
}

function appendToFile($filePath, $text) {
    doLog("Appending: '". $text ."' to file ". $filePath);
    $file = fopen($filePath, "a") or die("Unable to open file!");
    if(flock($file, LOCK_EX)) {
        fputs($file, $text);
        doLog("Done writing");
    }
    fclose($file);
    doLog("Closed file");
}

function getLogFileSuffix() {
    global $logFileSuffix;
    return $logFileSuffix;
}

function initLog($logFilePath) {
    if(is_writable($logFilePath)) {
        return fopen($logFilePath, "a");
    } else {
        return false;
    }
}

function doLog($message) {
    global $log;
    if ($log != false) {
        fputs($log,$message."\n"); 
    }
}

//from https://stackoverflow.com/a/834355
function startsWith($haystack, $needle) {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

function contains($haystack, $needle) {
    return strpos($haystack, $needle) !== false;
}

function getProtocol() {
    return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
}

function getOwnUrl() {
    return getProtocol()."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}

function getScriptDirectoryUrl($fileConstant) {
    $scriptFileName = basename($fileConstant);
    $fullUrl = getOwnUrl();
    $fileNamePosition = strpos($fullUrl, $scriptFileName);
    return substr($fullUrl, 0, $fileNamePosition);
}

?>
