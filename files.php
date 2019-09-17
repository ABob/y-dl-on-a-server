<?php
require 'utils.php';

$fileList = createFileToLinkMap();

//constant value changes must be applied to filesLogic.js !
define("DELETE_BUTTON_ID_PREFIX", "del-btn-");

function createFileToLinkMap(){
	$fileList = array();
    foreach(createFileList() as $file) {
				$link = getRelativeDownloadFolderPath() .'/'. htmlentities(str_replace("#","%23", $file));
				$fileList[$file] = $link;
	}
	return $fileList;
}

function getFileList(){
    global $fileList;
    return $fileList;
}

if(isset($_GET["mode"])
		&& $_GET["mode"] == "json"){
	asJson();
} else {
	asHtml();
}

function asJson(){ 
	$data = getFileList();
	header('Content-Type: application/json');
	echo json_encode($data);
}

function asHtml(){
?>
<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Youtube-DL Online Interface</title>
		<!-- Bootstrap -->
        <link rel="stylesheet" href="lib/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T">
		<link rel="stylesheet" href="spinner.css">

		<script src="filesLogic.js"></script>
	</head>
	<body>
        <div class="container">
            <small>
                <button id="rssButton" class="btn float-right" ><img id="rssIcon" title="RSS feed" src="img/RSS.png" alt="RSS" style="heigth: 1px">
                </button><br>
            </small>
            <h1 id="title">Video Downloads and Audio Conversions</h1>
            <p hidden>
                <div id="rssLinkArea" class="input-group" hidden>
                    <input id="rssLink" type="text" class="form-control">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-secondary" id="rssLinkCopyButton">Copy</button>
                    </div>
                </div>
            </p>

            <ul class="list-group">
<?php
    printFilesInHtml();
?>
            </ul>
            <br>
            <a href="index.php" class="btn btn-info">Back</a>
        </div>
	</body>
</html>
<?php
}

function printFilesInHtml() {
	foreach(getFileList() as $file => $link){
        echo('<li class="list-group-item"><a href="'. $link .'">'.$file.'</a><button type="button" class="close btn-xs float-right align-middle del-btn" id="' . constant("DELETE_BUTTON_ID_PREFIX") . $file .'">x</button></li>');
	}
}
	
?>
