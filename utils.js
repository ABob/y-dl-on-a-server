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

function createSpinner() {
    var spinner = document.createElement('div');
    addClass(spinner, "spinner");
    addClass(spinner, "pull-right");
    return spinner;
}

function remove(element) {
    if(element) {
        element.parentNode.removeChild(element);
    }
}

function hide(element) {
    element.style.display = 'none';
}

function insertAfter(newElement, referenceNode) {
    referenceNode.parentNode.insertBefore(newElement, referenceNode.nextSibling);
}

function insertBefore(el, referenceNode) {
    referenceNode.parentNode.insertBefore(el, referenceNode);
}

function addClass(el, className) {
    if (el.classList) { el.classList.add(className); }
    else if (!hasClass(el, className)) { el.className += ' ' + className; }
}
    
function removeClass(el, className) {
    if (el.classList.contains(className)) {
        el.classList.remove(className);
    }
}
