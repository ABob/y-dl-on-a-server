<?php
require 'utils.php';

$fileList = createFileToLinkMap();

//constant value changes must be applied to filesLogic.js !
define("DELETE_BUTTON_ID_PREFIX", "del-btn-");

function createFileToLinkMap(){
    global $folder;

	$fileList = array();
    foreach(createFileList() as $file) {
				$link = $folder .'/'. htmlentities(str_replace("#","%23", $file));
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
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Youtube-DL Online Interface</title>
		<!-- Bootstrap -->
		<!--link href="/css/bootstrap.min.css" rel="stylesheet"-->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<link rel="stylesheet" href="spinner.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
       <!--  <script type="module">import * as utils from './utils.js';
</script> -->
		<script src="filesLogic.js"></script>
	</head>
	<body>
	<div class="container">
		<h1>Video Downloads and Audio Conversions</h1>
		<ul class="list-group">
<?php
    printFilesInHtml();
?>
		</ul>
		<br>
		<a href="index.php" class="btn btn-info">Back</a>
	</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!--script src="/js/jquery.min.js"></script-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <!--script src="/js/bootstrap.min.js"></script-->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	</body>
</html>
<?php
}

function printFilesInHtml() {
	foreach(getFileList() as $file => $link){
        echo('<li class="list-group-item"><a href="'. $link .'">'.$file.'</a><button type="button" class="btn btn-secondary btn-sm pull-right align-middle del-btn" id="' . constant("DELETE_BUTTON_ID_PREFIX") . $file .'">x</button></li>');
	}
}
	
?>
