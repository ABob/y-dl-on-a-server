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

    function deleteFileOfButton(button)  {
        var spinner = createSpinner();
        pullRight(spinner);
        requestDeletion(spinner, button);
        replaceButtonWithSpinner(button, spinner);
    }

    function requestDeletion(spinner, button) {
        var onLoadEventListener = function(response) { 
            handleAnswer(response.responseText, button);
            replaceSpinnerWithButton(spinner, button);
        };
        var obj = {};
        obj["file"] = getFileNameFromButton(button);
        var json = JSON.stringify(obj);
        sendAjaxJsonRequest("deleteFile.php", "POST", json, onLoadEventListener);
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

}
