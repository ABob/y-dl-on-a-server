window.onload = function() {
    const DELETE_BUTTON_ID_PREFIX = "del-btn-";

    var deleteButtons = document.getElementsByClassName("del-btn");
    Array.from(deleteButtons).forEach((button) => {
        var file = getFileNameFromButton(button);
        button.addEventListener('click', function () { 
            var confirmation = askForConfirmation(file);
            if(confirmation) {
                deleteFileOfButton(button);
            }
        });
    });

    function askForConfirmation(filename) {
        return confirm("Delete " + filename + "?");
    }

    function getFileNameFromButton(button) {
        return button.id.replace(DELETE_BUTTON_ID_PREFIX, "");
    }

    function replaceButtonWithSpinner(button, spinner) {
        hide(button);
        showBefore(button.id, spinner);
    }

    function hide(element) {
        element.style.display = 'none';
    }

    function deleteFileOfButton(button)  {
        var spinner = createSpinner();
        sendAjaxRequest(spinner, button);
        replaceButtonWithSpinner(button, spinner);
    }

    function sendAjaxRequest(spinner, button) {
        var request = new XMLHttpRequest();
        request.addEventListener("load", function() { 
            handleAnswer(request.responseText, button);
            replaceSpinnerWithButton(spinner, button);
        });
        request.open("POST", "deleteFile.php");
        request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        request.send("file=" + getFileNameFromButton(button));
    }

    function handleAnswer(jsonAnswer, button) {
        var json = JSON.parse(jsonAnswer);
        if(json.success) {
            removeRowOf(button);
        } else {
            window.alert("Error on server: " + json.error);
        }
    }

    function removeRowOf(button) {
        var row = button.parentNode;
        hide(row);
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
