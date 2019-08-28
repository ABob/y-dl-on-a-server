<?php
require "utils.php";

$success = false;
$error = "Request format error";
$requestId = null;
$cmd = buildCommand();

execInBackground($cmd);
        $success = true;
        $error = "";
        $error = $cmd;
        $requestId = uniqid("id", true);
        $json = processRequest();
$answer = buildAnswer($success, $requestId, $error, $json);
sendAnswer($answer);

function buildCommand() {
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $output_dir = dirname($_SERVER['SCRIPT_FILENAME'])."/dls/%(title)s.%(ext)s";
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

function buildAnswer($success, $requestId, $error, $json) {
    return array("success" => $success, "requestId" => $requestId, "error" => $error, $json);
}

function execInBackground($cmd) {
    if (substr(php_uname(), 0, 7) == "Windows"){
        pclose(popen("start /B ". $cmd, "r")); 
    }
    else {
        //old way...didn't work
        //exec($cmd . " > /dev/null &");  
        
        //new way from https://stackoverflow.com/a/45966
        $outputFile = "testOutput.txt";
        $pidFile = "testPid.txt";
        exec(sprintf("%s > %s 2>&1 & echo $! >> %s", $cmd, $outputFile, $pidFile));
        //$cmd .= " > /dev/null 2>/dev/null &";
        //exec($cmd);
    }
} 

?>
