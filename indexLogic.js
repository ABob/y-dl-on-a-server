var STATUSENTRY_CLASSNAME_MESSAGE = "message";
var STATUSENTRY_CLASSNAME_NAME = "name";
var STATUSENTRY_CLASSNAME_DATE = "date";

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
    doAccessChecks();
    setupSubmitButtonAction();
    setupCommandBuilder();
    mirrorInput();
    showRunningDownloads();
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
        var date = new Date();
        obj["date"] = date.getTime();
        var tempId = date.getTime();
        obj["tempId"] = tempId;
        var json = JSON.stringify(obj);
        sendAjaxJsonRequest("download.php", "POST", json, onLoadEventListener);

        createStatusEntry(tempId);
        setInitialStateDescription(tempId, date);
}

function handleAnswer(jsonAnswer) {
    var json = JSON.parse(jsonAnswer);
    var statusEntry = document.getElementById(json.tempId);
    var status = json.downloadId + ": ";
    if(json.success) {
        status += "Initialized";
    } else {
        status += "Error on server: " + json.error;
    }
    statusEntry.id = json.downloadId;
    setStatusEntryMessage(json.downloadId, status);

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

    var output = "-";
    if (urls.trim() != "") {
        output = "youtube-dl ";
        var asMp3 = getAsMp3Field();
        if(asMp3.checked) {
            output = output.concat(" ", "-x --audio-format mp3");
        }
        var additionalArguments = getAdditionalArgumentsField().value;
        output = output.concat(" ", additionalArguments);
        output = output.concat(" ", urls);
    }
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

function getStatusAreaBody() {
    return document.getElementById("statusAreaBody");
}

function createStatusEntry(id) {
    //var statusEntry = document.createElement('tr');
    var statusEntry = cloneModalButtonPrototype();
    statusEntry.id = id;
    statusEntry.addEventListener('click', function() {showDetails(this.id);} );
    submitButton.addEventListener('click', requestDownload);

    var dateSection = createStatusEntryDateSection();
    appendChild(dateSection, statusEntry);

    //var nameSection = createStatusEntryNameSection();
    //appendChild(nameSection, statusEntry);

    var messageSection = createStatusEntryMessageSection();
    appendChild(messageSection, statusEntry);

    var spinner = createSpinner();
    appendChild(spinner, statusEntry);

    var statusArea = getStatusAreaBody();
    statusArea.appendChild(statusEntry, statusArea);
    //statusArea.innerHTML = statusEntry.outerHTML + statusArea.innerHTML;
}

function showDetails(id) {
    var obj = {};
    obj["id"] = id;
    var json = JSON.stringify(obj);
    sendAjaxJsonRequest("details.php", "POST", json, function(response) {
        document.getElementById("modalBody").innerHTML = response.responseText;
        document.getElementById("modalTitleIdSection").innerHTML = id;
        document.getElementById("modalTitleDateSection").innerHTML = getDateFor(id);
    });
}

function createStatusEntryMessageSection() {
    return createTdWithClass(STATUSENTRY_CLASSNAME_MESSAGE);
}

function createStatusEntryDateSection() {
    return createTdWithClass(STATUSENTRY_CLASSNAME_DATE);
}

function createStatusEntryNameSection() {
    return createTdWithClass(STATUSENTRY_CLASSNAME_NAME);
}

function setInitialStateDescription(id, date) {
    setStatusEntryDate(id, date.toLocaleString("de-DE"));

    setStatusEntryMessage(id, "Pending...");
}

function setStatusEntryMessage(id, message) {
    var messageSection = getStatusEntryElement(id, STATUSENTRY_CLASSNAME_MESSAGE);
    messageSection.innerHTML = message;
}

function getDateFor(id) {
    return getStatusEntryElement(id, STATUSENTRY_CLASSNAME_DATE).innerHTML;
}

function setStatusEntryName(id, name) {
    var nameSection = getStatusEntryElement(id, STATUSENTRY_CLASSNAME_NAME);
    nameSection.innerHTML = name;
}

function setStatusEntryDate(id, date) {
    var dateSection = getStatusEntryElement(id, STATUSENTRY_CLASSNAME_DATE);
    dateSection.innerHTML = date;
}

function fillStatusEntryDate(id, timestamp) {
    var dateSection = getStatusEntryElement(id, STATUSENTRY_CLASSNAME_DATE);
    if(dateSection.innerHTML == "") {
        var date = new Date(timestamp);
        var dateString = date.toLocaleString("de-DE");
        setStatusEntryDate(id, date.toLocaleString("de-DE"));
    }
}

function removeSpinnerOf(id) {
    var spinner = getStatusEntryElement(id, "spinner");
    getStatusEntry(id).removeChild(spinner);
}

function getStatusEntryElement(id, elementClass) {
    var statusEntry = getStatusEntry(id);
    var children = statusEntry.childNodes;
    var numberOfChildren = children.length;

    for (var i = 0; i < numberOfChildren; i++) {
        if(children[i].classList.contains(elementClass)) {
            return children[i];
        }
    }
    return null;
}

function getStatusEntry(id) {
    return document.getElementById(id);
}

function addStatusMonitor(id) {
    var eventSource = new EventSource('monitor.php?id=' + id);

    eventSource.addEventListener("ERROR", function(event){
        setStatusEntryMessage(id, JSON.parse(event.data).message);
        removeSpinnerOf(id);
    });

    eventSource.addEventListener("SUCCESS", function(event){
        setStatusEntryMessage(id, JSON.parse(event.data).message);
        removeSpinnerOf(id);
    });

    eventSource.addEventListener("STATE", function(event){
        setStatusEntryMessage(id, JSON.parse(event.data).message);
    });

    eventSource.addEventListener("CREATION", function(event){
        timestampAsString = JSON.parse(event.data).message;
        fillStatusEntryDate(id, Number(timestampAsString));
    });
}

function showRunningDownloads() {
    sendAjaxJsonRequest("runningDownloads.php", "GET", null, function(ajaxResponse) {
        var idArray = JSON.parse(ajaxResponse.responseText);
        for(var i = 0; i < idArray.length; i++) {
            var id = idArray[i];
            createStatusEntry(id);
            addStatusMonitor(id);
        }
    });
}

function cloneModalButtonPrototype() {
    var original = document.getElementById("modalRow");
    var cloned = original.cloneNode(true);
    cloned.classList.remove("hidden");
    return cloned;
}

function doAccessChecks() {
    sendAjaxJsonRequest("check.php", "GET", null, function(ajaxResponse) {
        var errors = JSON.parse(ajaxResponse.responseText);
        var joinedMessages = errors.join("<br>");
        if (joinedMessages != "") {
            var msgBox = document.getElementById("messageBox");
            msgBox.innerHTML = joinedMessages;
            msgBox.hidden = false;
        }
    });
}

