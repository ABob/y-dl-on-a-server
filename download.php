<?php
require "utils.php";

$success = false;
$error = "Request format error";
$downloadId = uniqid("id", true);
$cmd = buildCommand();

$json = processRequest();
$finalCmd = execInBackground($cmd, $downloadId);
        $success = true;
        $error = "";
        $error = $cmd;

$metaFile = buildMetaFilePath($downloadId);
saveCreationDate($json->date, $metaFile);

$tempId = $json->tempId;
$json->finalCmd = $finalCmd;
$answer = buildAnswer($success, $downloadId, $tempId, $error, $json);
sendAnswer($answer);

function buildCommand() {
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $output_dir = getAbsoluteDownloadFolderPath()."/%(title)s.%(ext)s";
        $json = processRequest();

        #program
        $cmd = "youtube-dl ";

        #as audio file?
        if($json->asMp3){
            $cmd .= "-x --audio-format mp3 ";	
        }

        #additional commands?
        $cmd .= $json->additionalArguments." ";

        #only ascii charachters for easier links
        $cmd .= "--restrict-filenames ";

        #list of links
        $links = $json->links;

        #strip newlines and double (or more) whitespaces from link input
        $links = trim(preg_replace('/\s\s+/', ' ', $links));

        #append links
        $cmd .= $links ." ";

        #output directory
        $cmd .= "-o '".$output_dir."' ";

        #in background
        #$cmd .= " &";

        return $cmd;
    }
}

function buildAnswer($success, $downloadId, $tempId, $error, $json) {
    return array("success" => $success, "downloadId" => $downloadId, "tempId" => $tempId, "error" => $error, "finalCmd" => $json->finalCmd, $json);
}

function execInBackground($cmd, $downloadId) {
    $tempDir = getAbsoluteTempFolderPath();

    if (substr(php_uname(), 0, 7) == "Windows"){
        pclose(popen("start /B ". $cmd, "r")); 
    }
    else {
        //execute command in background and write process id as well as output into separate files
        //(from https://stackoverflow.com/a/45966)
        $outputFile = buildLogFilePath($downloadId);

        //Add keyword to output after download to mark a finished process.
        //In an error case, the keyword won't be added, but youtube-dl will automatically append an 'ERROR:' to log.
        $cmd = appendKeywordToLog($cmd, getKeywordForFinished());

        //with pid:
        $finalCommand = sprintf("%s > %s 2>&1 &", $cmd, $outputFile);
        exec($finalCommand);
        return $finalCommand;
    }
} 

function saveCreationDate($date, $pathToMetaFile) {
    doLog("Saving creation date ". $date ." in file ". $pathToMetaFile);
    $file = fopen($pathToMetaFile, "w") or die("Unable to open file!");
    if($file != false) {
        fputs($file, getKeywordForCreationDate() .": ". $date. PHP_EOL);
        doLog("Done writing");
        fclose($file);
        doLog("Closed file");
    }
}

function appendKeywordToLog($cmd, $keyword) {
    return $cmd .= " --exec 'echo ". $keyword ." '";
}

?>
