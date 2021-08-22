<?php
require "utils.php";

// Uses youtube-dl to get the title information of an url.
if(isset($_GET["url"])) {
    $url = $_GET["url"];
    doLog("using Oembed for url ".$url);
    withYoutubeOembed($url);
} else {
    echo("No url given");
}

# //Not working
# function isYoutubeUrl($url) {
#     $pattern = "/^(https?:\/\/)?(www.)?youtube.(\w\d)+/i";
#     echo preg_match($pattern, $str); // Outputs 1
# }

function withYoutubeDl($url) {
    $finalCommand = sprintf("youtube-dl --get-title %s", $url);
    $finalCommand = escapeshellcmd($finalCommand);
    $title = (exec($finalCommand));
    echo wrapInJson($title);
    //TODO: Prüfen, ob befehl ohne Fehler ausgeführt wurde
}

function wrapInJson($title) {
    $result =  '{"title":"'.$title.'"}';
    doLog("Meta information wrapped in json:\n". $result);
    return $result;
}

function withYoutubeOembed($url) {
    $oembedUrl = "https://www.youtube.com/oembed?format=json&url=".$url;
    $httpResponseCode = get_http_response_code($oembedUrl);
    if($httpResponseCode != 200) {
        doLog("Error when downloading metadata with oembed, (HTTP ". $httpResponseCode ." / URL: ". $oembedUrl);
        //TODO: wieder entfernen
        echo("Error when downloading metadata with oembed, (HTTP ". $httpResponseCode ." / URL: ". $oembedUrl);
        withYoutubeDl($url);
    } else {
        $data =  file_get_contents($oembedUrl);
        echo($data);
    }
}

//taken from:
//https://stackoverflow.com/a/4358138
function get_http_response_code($url) {
        $headers = get_headers($url);
            return substr($headers[0], 9, 3);
}

?>
