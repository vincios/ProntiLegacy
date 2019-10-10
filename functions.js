const _COLORI_SELEZIONE = ['#FFF', '#FFCC33', '#ff4500', '#1e90ff', '#00fa9a'];


const patt1 = new RegExp("^[0-9]{2}$");
const patt2 = new RegExp("^[0-9]{2}[-][0-9]{2}$");
const patt3 = new RegExp("^[0-9]{2}[-]{1}[0-9]{2}[-]{1}[0-9]{4}$");

function confirmClick(e) {
    if(!confirm("Confermi?")) {
        e.preventDefault();
    }
}

function closeAllPickers() {
    let pickers = document.getElementsByClassName("colorPopup");
    for (var i = 0; i<pickers.length; i++) {
        hidePicker(pickers[i]);
    }
}

function togglePicker(c) {
    let colorPicker = c.getElementsByClassName("colorPopup")[0];
    if(colorPicker.classList.contains("show")) {
        hidePicker(colorPicker);
    } else {
        showPicker(colorPicker);
    }
}
function showPicker(colorPicker) {
    closeAllPickers();
    //let colorPicker = c.getElementsByClassName("colorPopup")[0];
    colorPicker.classList.add("show");
}

function hidePicker(colorPicker) {
    //let colorPicker = c.getElementsByClassName("colorPopup")[0];
    colorPicker.classList.remove("show");
}

function sendRowColor(page, id, color) {
    page = page.toLowerCase();

    let pageFirstLetter = page.charAt(0).toUpperCase();
    page = pageFirstLetter + page.substr(1, page.length);

    let url = "selezionaPronto" + page + ".php?id=" + id + "&color=" + color;

    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if(this.readyState === 4 && this.status === 200) {
            let responseJSON = JSON.parse(this.responseText);
            if(responseJSON.result) {
                let row = document.getElementById("datarow-" + id);
                let cells = row.getElementsByClassName("colorable");
                let cellNumber = cells.length;

                for(i = 0; i < cellNumber; i++) {
                    cells[i].setAttribute("bgcolor", _COLORI_SELEZIONE[responseJSON.color]); //double check
                }
                closeAllPickers();
            }
        } else if(this.readyState === 4 && this.status !== 200) {
            alert("Errore nella modifica della selezione");
        }
    };

    xhttp.open("GET", url, true);
    xhttp.send();
}

function selectColorCheckboxes() {
    //reset form
    document.getElementById("frmEliminaEvidenziati").reset();

    let labels = document.getElementsByClassName("material-checkbox");

    for(let i = 0; i < labels.length; i++) {
        labels[i].firstElementChild.setAttribute("checked", "checked");
    }
}

function dateAutoCompletition(txtEvent) {
    var code;

    if (txtEvent.key !== undefined) {
        code =  txtEvent.key.charCodeAt(0);
    } else if (txtEvent.keyIdentifier !== undefined) {
        code = txtEvent.keyIdentifier;
    } else if (txtEvent.keyCode !== undefined) {
        code = txtEvent.keyCode;
    }

    if(code > 47 && code < 58) {
        var currentText = txtEvent.srcElement.value;
        if (patt1.test(currentText) || patt2.test(currentText)) {
            txtEvent.srcElement.value = currentText + "-";
        } else if(patt3.test(currentText)) {
            txtEvent.preventDefault();
        }
    }
}