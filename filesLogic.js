window.onload = function() {
    const DELETE_BUTTON_ID_PREFIX = "del-btn-";

    var deleteButtons = document.getElementsByClassName("del-btn");
    Array.from(deleteButtons).forEach((button) => {
        var file = button.id.replace(DELETE_BUTTON_ID_PREFIX, "");
        button.addEventListener('click', function () { 
            var spinner = createSpinner();
            sendAjaxRequest(spinner, button);
            replaceButtonWithSpinner(button, spinner);
        });
    });

    function replaceButtonWithSpinner(button, spinner) {
            button.style.display = 'none';
            showBefore(button.id, spinner);
    }

    function deleteFile (filename)  {
        window.alert("Delete "+filename+"?");
    }

    function sendAjaxRequest(spinner, button) {
        var oReq = new XMLHttpRequest();
        oReq.addEventListener("load", function() { 
            window.alert(oReq.responseText);
            replaceSpinnerWithButton(spinner, button);
        });
        oReq.open("POST", "deleteFile.php");
        oReq.send();
    }

    function replaceSpinnerWithButton(spinner, button) {
        remove(spinner);
        button.style.display = '';
    }

    function showBefore(elementId, newElement) {
        var element = document.getElementById(elementId);
        if (element) {
            insertBefore(newElement, element);
        }
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

    function addClass(el, className) {
        if (el.classList) { el.classList.add(className); }
        else if (!hasClass(el, className)) { el.className += ' ' + className; }
    }
    
    function insertAfter(newElement, referenceNode) {
        referenceNode.parentNode.insertBefore(newElement, referenceNode.nextSibling);
    }

    function insertBefore(el, referenceNode) {
        referenceNode.parentNode.insertBefore(el, referenceNode);
    }
}
