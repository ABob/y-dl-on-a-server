function sendAjaxJsonRequest(url, type, jsonData, loadEventHandler) {
    var request = new XMLHttpRequest();
    request.addEventListener("load", function() {
        loadEventHandler(request);
    });
    request.open(type, url);
    request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    request.setRequestHeader('Content-Type', 'application/json');
    request.send(jsonData);
}
