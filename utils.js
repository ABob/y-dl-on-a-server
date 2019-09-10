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
    return createDivWithClass("spinner");
}

function createTdWithClass(className) {
    return createHtmlTagWithClass('td', className);
}

function createDivWithClass(className) {
    return createHtmlTagWithClass('div', className);
}

function createHtmlTagWithClass(tagName, className) {
    var tag = document.createElement(tagName);
    addClass(tag, className);
    return tag;
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

function appendChild(childElement, referenceNode) {
    referenceNode.innerHTML += childElement.outerHTML;
}

function pullRight(htmlElement) {
    addClass(htmlElement, "pull-right");
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
