var elements = document.getElementsByClassName("row-actions");
var archivioTag = (document.getElementById('isArchivio'));

var archivioMode = (archivioTag != null) && archivioTag.innerText === 'true';

if(archivioMode) { //in archivio mode we hide the action buttons
    for (var i = 0; i < elements.length; i++) {
        elements[i].style.visibility = 'hidden';
    }
    var toHidden = document.getElementsByClassName('archivio-hidden');
    for (var i = 0; i < toHidden.length; i++) {
        toHidden[i].style.display = 'none';
    }
} else { // so we need to add click listener to the button only if we aren't in archivio mode
    /*for (var i = 0; i < elements.length; i++) {
        var links = elements[i].getElementsByTagName("a");
        for (var j = 0; j < links.length; j++) {
            links[j].addEventListener("click", confirmClick, false);
        }
    }*/

    var elements2 = document.getElementsByClassName("evidenzia");

    Array.from(elements2).forEach(function (element) {
        element.addEventListener("click", function () {
            togglePicker(element);
        }, false);
    });
}