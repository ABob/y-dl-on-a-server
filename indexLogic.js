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
}

function requestDownload() {
        var onLoadEventListener = function(response) { 
            handleAnswer(response.responseText);
        };
        var obj = {};
        obj["links"] = getUrls();
        obj["asMp3"] = getAsMp3Field().checked;
        obj["additionalArguments"] = getAdditionalArgumentsField().value;
        var tempId = Date.now();
        obj["tempId"] = tempId;
        var json = JSON.stringify(obj);
        sendAjaxJsonRequest("download.php", "POST", json, onLoadEventListener);

        createStatusEntry(tempId);
}

function handleAnswer(jsonAnswer) {
    var json = JSON.parse(jsonAnswer);
    var statusEntry = document.getElementById(json.tempId);
    var status = json.downloadId + ": ";
    if(json.success) {
        status += "downloading...";
    } else {
        status += "Error on server: " + json.error;
    }
    statusEntry.id = json.downloadId;
    statusEntry.innerHTML += status;

    addStatusMonitor(statusEntry.id);
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

function getStatusArea() {
    return document.getElementById("statusArea");
}

function createStatusEntry(id) {
    var statusEntry = document.createElement('div');
    statusEntry.id = id;

    var spinner = createSpinner();
    appendChild(spinner, statusEntry);

    var statusArea = getStatusArea();
    statusArea.innerHTML = statusEntry.outerHTML + statusArea.innerHTML;
}

function removeSpinnerOf(id) {
    var statusEntry = document.getElementById(id);
    var children = statusEntry.childNodes;
    var numberOfChildren = children.length;

    for (var i = 0; i < numberOfChildren; i++) {
        if(children[i].classList.contains("spinner")) {
            statusEntry.removeChild(children[i]);
            break;
        }
    }
}

function addStatusMonitor(id) {
    var eventSource = new EventSource('monitor.php?id=' + id);
    //var eventSource = new EventSource('monitor.php');
    eventSource.onmessage=function(event){window.alert(event.data);}
}
