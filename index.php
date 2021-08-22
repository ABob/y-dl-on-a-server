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

		<script src="utils.js" type="module"></script>
		<script src="indexLogic.js"></script>
	</head>
	<body>
        <div class="container">
            <div id="messageBox" class="alert alert-danger" role="alert" hidden></div>
            <h1>Video Downloads and Audio Conversions</h1>

            <label for="VideoLink">
                <textarea class="form-control" rows="5" cols="50" id="links" name="links" placeholder="Links to videos, separated through commas or new lines." required></textarea>
            </label>
            <p>
                <label for="asMp3">
                    <input type="checkbox" id="asMp3" name="asMp3">
                    Convert video to mp3 file?
                </label><br>
                <label for="Argumente">Additional parameters for Youtube-dl:
                    <input type="text" id="additionalArguments" name="additionalArguments">
                </label><br>
                <small class="text-muted">(see <a href="https://github.com/ytdl-org/youtube-dl/blob/master/README.md#options">the youtube-dl documentation</a> for options)</small>
            </p>

            <h3>Complete command:</h3>
            <p>
                <div id="readyCommand"></div>
            </p>
            <p>
                <button  class="btn btn-primary" id="submitButton">Execute!</button>
            </p>

            <h3>Progress:</h3>
            <small class="text-muted">(Click on row for details)</small>
            <p>
                <div id="statusArea">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Name</th>
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
            <a href="files.php" class="btn btn-info">Show downloaded files</a>
        </div>

        <!-- Prototype Button to show modal -->
        <!--
        <button id="modalButton" type="button" class="btn btn-default btn-sm hidden" data-toggle="modal" data-target="#detailModal">
            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
        </button> -->

        <!-- Modal -->
        <div class="modal fade" id="detailModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <div class="modal-title">
                        <h5>Details of download <span id=modalTitleSection></span></h5>
                        <small class="modal-title" >Started at <span id=modalDateSection></span></small><br>
                        <small class="modal-title" >ID: <span id=modalIdSection></span></small>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div id="modalBody" class="modal-body"></div>
                  <div class="modal-footer">
                    <button type="button" class="close" data-dismiss="modal">Close</button>
                  </div>
                </div>
            </div>
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="lib/jquery/3.3.1/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"></script>
        <script src="lib/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"></script>
	</body>
</html>
