window.onload = function() {
    importScript("utils.js", main);

    //dynamic script loading to import utils.js
    //see: https://stackoverflow.com/a/950146
    function importScript(url, callback) {
         // Adding a script tag that loads the wanted javascript file to the html document head 
         var head = document.head;
         var script = document.createElement('script');
         script.type = 'text/javascript';
         script.src = url;
        
         // Then bind the event to the callback function.
         // There are several events for cross browser compatibility.
         script.onreadystatechange = callback;
         script.onload = callback;

         // Fire the loading
         head.appendChild(script);
    }
}

function main() {
    setupSubmitButtonAction();
    setupCommandBuilder();
}

function setupSubmitButtonAction() {
	var submitButton = document.getElementById('submitButton');
    submitButton.addEventListener('click', requestDownload);
    //submitButton.addEventListener('click', requestTime);
}

function requestTime() {
if(typeof(EventSource) !== "undefined") {
      // Yes! Server-sent events support!
       // Some code.....
     var source = new EventSource("test.php");
     // source.onmessage = function(event) {
//        document.getElementById("result").innerHTML += event.data + "<br>";
  //      }; 

    source.addEventListener("ping", function(event) {
          const eventList = document.getElementById("result");
          const newElement = document.createElement("li");
          const time = JSON.parse(event.data).time;
          newElement.innerHTML = "ping at " + time;
          eventList.appendChild(newElement);
    });
       } else {
         // Sorry! No server-sent events support..
           window.alert("Kann keine Updates empfangen!");
         } 
}

function requestDownload() {
        var onLoadEventListener = function(response) { 
            handleAnswer(response.responseText);
        };
        var obj = {};
        obj["links"] = getUrls();
        obj["asMp3"] = getAsMp3Field().checked;
        obj["additionalArguments"] = getAdditionalArgumentsField().value;
        var json = JSON.stringify(obj);
        sendAjaxJsonRequest("download.php", "POST", json, onLoadEventListener);

        var resultArea = document.getElementById('result');
        addClass(resultArea, "spinner");
}

function handleAnswer(jsonAnswer) {
    var json = JSON.parse(jsonAnswer);
    if(json.success) {
        window.alert("Downloading!");
    } else {
        window.alert("Error on server: " + json.error);
    }
    var resultArea = document.getElementById('result');
    removeClass(resultArea, "spinner");
}

function setupCommandBuilder() {
	var readyCommand = document.getElementById('readyCommand');
	var linksField = getLinksField();
	var asMp3Field = getAsMp3Field();
	var additionalArgumentsField = getAdditionalArgumentsField();
	linksField.oninput = mirrorInput;
	asMp3Field.onchange = mirrorInput;
	additionalArgumentsField.oninput = mirrorInput;
}

function mirrorInput() {
    var urls = getUrls();
    urls = urls.replace(/\n/g, " ");
    urls = urls.replace(/,/g, " ");
    var output = "youtube-dl ";
    var asMp3 = getAsMp3Field();
    if(asMp3.checked) {
        output = output.concat(" ", "-x --audio-format mp3");
    }
    var additionalArguments = getAdditionalArgumentsField().value;
    output = output.concat(" ", additionalArguments);
    output = output.concat(" ", urls);
    readyCommand.innerHTML = output;
}

function getLinksField() {
    return document.getElementById("links");
}

function getUrls() {
    return getLinksField().value;
}

function getAdditionalArgumentsField(){
    return document.getElementById("additionalArguments");
}

function getAsMp3Field() {
    return document.getElementById("asMp3");
}

