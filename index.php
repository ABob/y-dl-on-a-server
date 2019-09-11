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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="spinner.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
		<script src="utils.js" type="module"></script>
		<script src="indexLogic.js"></script>
	</head>
	<body>
<div class="container">
		<h1>Video Downloads and Audio Conversions</h1>

				<label for="VideoLink">
					<textarea class="form-control" rows="5" cols="50" id="links" name="links" placeholder="Links to videos, separated through commas or new lines." required></textarea>
				</label><br>
				<label for="asMp3">
					<input type="checkbox" id="asMp3" name="asMp3">
					Convert video to mp3 file?
				</label><br>
				<label for="Argumente">additional parameters for Youtube-dl:
					<input type="text" id="additionalArguments" name="additionalArguments">
				</label><br>

			<h3>Complete command:</h3>
			<div id="readyCommand"></div>
			<button  class="btn btn-primary" id="submitButton">Go!</button>

		<h3>Progress:</h3>
        <small class="text-muted">(Click on row for details)</small>
        <p>
            <div id="statusArea">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>State</th>
                            <th><!-- Leere Spalte für Spinner --></th>
                        </tr>
                    </thead>
                    <tbody id="statusAreaBody">
    <tr id="modalRow" class="hidden" data-toggle="modal" data-target="#detailModal"></tr>
                    </tbody>
                </table>
            </div>
        </p>
		<a href="files.php" class="btn btn-info">Show downloaded files...</a>
</div>

    <!-- Prototype Button to show modal -->
    <!--
    <button id="modalButton" type="button" class="btn btn-default btn-sm hidden" data-toggle="modal" data-target="#detailModal">
        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
    </button> -->

    <!-- Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Details of download <span id=modalTitleIdSection></span></h4>
        <small class="modal-title" >Started at <span id=modalTitleDateSection></span></small>
      </div>
      <div id="modalBody" class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>
    </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!--script src="/js/jquery.min.js"></script-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <!--script src="/js/bootstrap.min.js"></script-->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	</body>
</html>
