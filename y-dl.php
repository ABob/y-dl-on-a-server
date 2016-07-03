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

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
		<script src="logic.js"></script>
	</head>
	<body>
<div class="container">
		<h1>Video-Downloads und Audio-Konvertierungen</h1>
			<h3>How about an app?</h3>
			Bastle eine Oberfläche, wie diese Website (vielleicht eine Web-App?). Sie nimmt Links entgegen (automatische Anbieten per "Teilen"), wandelt sie in eine youtube-dl-Kommando um und schickt es über einen Socket an den Server. Der führt den Befehl aus (und schickt eine Benachrichtigung an die App?). Per Slide-View kann man zu den Inhalten des Download-Ordners auf dem Server gelangen und sie auf das Telefon herunterladen (oder streamen?).

			<form id="formular" action="" method="post">
				<label for="VideoLink">
					<textarea class="form-control" rows="5" cols="50" id="links" name="links" placeholder="Links zu Videos, getrennt durch neue Zeile" required></textarea>
				</label><br>
				<label for="asMp3">
					<input type="checkbox" id="asMp3" name="asMp3">
					Zu MP3-Datei konvertieren?
				</label><br>
				<label for="Argumente">Weitere Argumente für Youtube-dl:
					<input type="text" id="additionalArguments" name="additionalArguments">
				</label><br>

			<h3>Fertiges Kommando:</h3>
			<div id="readyCommand"></div>

			<button  type="submit" class="btn btn-primary" id="button">Go!</button>
			</form>

		<h3>Fortschrittsanzeige</h3><br>
		<a href="files.php" class="btn btn-info">Zu den Downloads...</a>
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

if($_SERVER['REQUEST_METHOD'] == "POST"){
	$output_dir = dirname($_SERVER['SCRIPT_FILENAME'])."/dls/%(title)s.%(ext)s";

	echo ($output_dir);
	function execInBackground($cmd) {
		if (substr(php_uname(), 0, 7) == "Windows"){
			pclose(popen("start /B ". $cmd, "r")); 
		}
		else {
			exec($cmd . " > /dev/null &");  
		}
} 

#program
$cmd = "youtube-dl ";

#as audio file?
if($_POST["asMp3"]){
	$cmd .= "-x --audio-format mp3 ";	
}

#additional commands?
$cmd .= $_POST["additionalArguments"]." ";

#only ascii charachters for easier links
$cmd .= "--restrict-filenames ";

#list of links
$links = $_POST["links"];

#strip newlines and double (or more) whitespaces from link input
$links = trim(preg_replace('/\s\s+/', ' ', $links));

#append links
$cmd .= $links ." ";

#output directory
$cmd .= "-o '".$output_dir."' ";

#in background
$cmd .= " &";

#execute
execInBackground($cmd);

#redirect to downloads
header("Location:files.php");
exit();
}
?>

