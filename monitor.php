<?php
require "utils.php";

header("Cache-Control: no-cache");
header("Content-Type: text/event-stream\n\n");

$logPath = "log.monitor.txt";
$log = fopen($logPath, "w");

doLog("Starting monitor"); 
//send("DEBUG", "DEBUG", print_r($_GET));
$state = new State();
doLog("GET: ". print_r($_GET, true));
$id = getIdFromRequest();
if(is_null($id)){
    doLog("EXIT: ID is null");
    exit();
}


$pathToLogFile = buildLogFilePath($id);
doLog("Monitoring file ". $pathToLogFile);
sendStateUntilFinish($id, $pathToLogFile);
closeStream($pathToLogFile);

function sendStateUntilFinish($id, $pathToLogFile){
    $currentState = new State();
    $fileStats = null;
    while (!isEndingState($currentState)) {
        $newFileStats = getFileStats($pathToLogFile);
        if(fileHasChanged($fileStats, $newFileStats)){
            $currentState = extractState($pathToLogFile);
            if(isEndingState($currentState)){
                doLog("Ending state detected...");
                if(isFinished($currentState)){
                doLog("Finished!");
                    sendSuccess($id, $currentState);
                } else {
                doLog("Error!");
                    sendError($id, $currentState);
                }
            } else {
                sendState($id, $currentState);
            }
        }
        sleep(3);
    }
    doLog("Finished sending states. Stream can get closed now");
}

function getIdFromRequest() {
    if(isset($_GET["id"])){
        return $_GET["id"];
    }
    return null;
}

function getFileStats($filepath){
    return is_readable($filepath) ? stat($filepath) : false;
}

function fileHasChanged($oldStats, $newStats){
    if (empty($oldStats)){
        return !empty($newStats);
    } else {
        if(empty($newState)) {
            return true;
        } else {
            return $newStats["size"] != $oldStats["size"] || $newStats["mtime"] != $oldStats["mtime"];
        }
    }
}

function extractState($pathToLogFile, $lastState){
    $state = new State();
    if(!is_readable($pathToLogFile) && !$lastState->hasState(State::INIT)) {
        $state->transitTo(State::ERROR);
    } 
    else {
        $state->transitTo(State::GET_METADATA);
        $logFile = fopen($pathToLogFile, "r") or die("Unable to open file!");
        // Output one line until end-of-file
        while(!feof($logFile)) {
            $line = fgets($logFile);
            $state = parseState($line, $state);
        }
        //Besser: Merke letzten Status und letzte Handler-Position und starte von dort
        fclose($logFile);
    }
    return $state;
}

function parseState($line, $oldState) {
    if (startsWith($line, "[download]")) {
        $oldState->transitTo(State::DOWNLOAD);
    } elseif (startsWith($line, "[ffmpeg]")) {
        $oldState->transitTo(State::FFMPEG);
    } elseif (startsWith($line, "[exec]")) {
        $oldState->transitTo(State::EXEC);
    } elseif (contains($line,getKeywordForFinished())) {
        $oldState->transitTo(State::FINISH);
    } elseif (startsWith($line, "ERROR")) {
        $oldState->transitTo(State::ERROR);
    }
    return $oldState;
}

//from https://stackoverflow.com/a/834355
function startsWith($haystack, $needle) {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

function contains($haystack, $needle) {
    return strpos($haystack, $needle) !== false;
}

function isEndingState($state) {
    doLog("Is Ending state? $state is ". $state);
    return $state->hasState(State::ERROR) || $state->hasState(State::FINISH);
}

function isFinished($state) {
    return $state->hasState(State::FINISH);
}

function sendState($id, $state){
    send(getKeywordForStateEvent(), $id, $state);
}

function sendError($id, $state){
    send(getKeywordForErrorEvent(), $id, $state);
}

function sendSuccess($id, $state){
    send(getKeywordForSuccessEvent(), $id, $state);
}

function send($eventType, $id, $state){
    buildMessage($eventType, $id, (string)$state);
    sendEvent();
}

function buildMessage($eventType, $id, $message) {
    echo 'event: '. $eventType .'\n';
    echo 'data: {"'. $id .'": "' . $message . '"}';
    echo "\n\n";
    doLog("Built Message. EventType: ". $eventType .", Message: ". $message);
}

function doLog($message) {
    global $log;
    fputs($log,$message."\n"); 
}

function sendEvent() {
    doLog("Try to send event");
    while (ob_get_level() > 0) {
        ob_end_flush();
    }
    flush();
    doLog("Flush! AHAAAA ");
}

function closeStream($logFilePath) {
    for($i = 0; $i < 10; $i++) {
        if(is_writable($logFilePath)) {
            unlink($logFilePath);
            doLog("Removed log file ". $logFilePath);
        } else {
            sleep(60);
        }
    }
    doLog("Could not remove log file ". $logFilePath .": Not writable");
}
class State {
    const INIT = 0;
    const GET_METADATA = 1;
    const DOWNLOAD = 2;
    const FFMPEG = 3;
    const EXEC = 4;
    const FINISH = 5;
    const ERROR = 6;

    private $currentState;

    public function __construct() {
    $this->currentState = State::INIT;
    doLog("Constructed State. currentState is ". $this->currentState);
    }

    public function transitTo($newState) {
        doLog("Wanna transition from currentState = ". $this->currentState ." to newState ". $newState);
        if ($newState != $this->currentState && $newState >= $this->currentState){
            $this->currentState = $newState;
            return true;
        } else {
            return false;
        }
    }

    public function __toString() {
        switch ($this->currentState) {
        case State::INIT : return "Initializing: Looking for log file";
        case State::GET_METADATA : return "Receive meta data";
        case State::DOWNLOAD : return "Downloading";
        case State::EXEC : return "Executing post-download commands";
        case State::FFMPEG : return "Converting wirh ffmpeg";
        case State::FINISH : return "Finished";
        case State::ERROR : return "Error occured";
        default : return "UNDEFINED STATE";
        }
    }

    public function hasState($state) {
        return $state == $this->currentState;
    }
}
?>
