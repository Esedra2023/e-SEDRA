
/** This file is part of e-Sedra.
 *
 *   e-Sedra is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   e-Sedra is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *   along with e-Sedra.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright Copyright 2023 e-Sedra. All Rights Reserved.
 *
 */

//var divmoreinfo;
var personalbis = {
    "ncol": 2,
    "c": [
        {
            "type": "button",
            "class": ["linkstylebutton", "btn", "btn-outline-primary", "text-start"],
            "exdata": { "idbis": [1, "idBs"] },
            "value": "idBs",
            "text": [1, "titleBis"]       //1 nome di variabile  0 dato costante
        },
        {
            "type": "button",
            "class": ["btn", "icona"],
            "exdata": { "idbis": [1, "idBs"] },
            "value": "idBs",
            "text": [0, "<span class='bi-trash3'></span>"],
            "other": " data-bs-toggle='modal' data-bs-target='#myModalBis' title='Elimina' name='delete-post'"
        }]
}

var formBis;
var collapsableBis;

ready(function () {
    /*   var MyMdT;*/


    var id_bisogno_selezionato;
    /* var infomes;*/
    var defaultpage = 1;

    formBis = document.getElementById("formInputBis");

    //Riabilito i campi disabilitati di default dal template
    var btnacc = document.getElementById("btnAccBis");
    if (btnacc) {
        btnacc.disabled = false;
        btnacc.addEventListener("click", resetHidden());
    }

    var fieldsetBis = document.getElementById("fsForm");
    if (fieldsetBis) {
        fieldsetBis.disabled = false;
    }

    var mmt = document.getElementById('myModalBis');
    if (mmt) {
        /* alert("prepare modal");*/
        myMdT = new bootstrap.Modal(mmt, {
            keyboard: false
        })
    }
    let sezB = document.getElementById("collapseBis");
    if (sezB) {
        collapsableBis = new bootstrap.Collapse(sezB, { toggle: false });
    }

    var delBisOK = document.getElementById("deleteBis");
    delBisOK.addEventListener("click", function () {
        call_ajax_delete_bis(id_bisogno_selezionato, 0);    //0 false fa eliminazione fisica => è un mio bisogno
        call_ajax_dati_table('bisogni', 'Bistable', personalbis);
    });


    var canc = document.getElementById("annullBis");        //chiude il form senza salvare
    if (canc) {
        canc.addEventListener("click", function () {
            //console.log('click su annulla');
            resetAccordion(collapsableBis, "btnAccBis", formBis, " Segnala nuovo bisogno");
        });
    }


    var suBis = document.getElementById("confirmBis");
    if (suBis) {
        suBis.addEventListener("click", (e) => {
            e.preventDefault();
            call_ajax_upcre_bisogni();
        });
    } /*else alert("suBis nullo");*/

    var link = document.querySelectorAll('.linkstylebutton');
    for (let i = 0; i < link.length; i++) {
        link[i].addEventListener("mouseover", (e) => { e.target.style.cursor = 'pointer'; });
    }
    var bistable = document.querySelector('#Bistable');
    if (bistable) {
        bistable.addEventListener("click", (e) => {
            if (e.target.nodeName != 'BUTTON' && e.target.nodeName != 'SPAN') { /*console.log('- '+e.target);*/  return; }
            let elem = e.target;
            let span = null;
            if (elem.classList.contains("linkstylebutton")) {
                //let idBisogno = elem.dataset.idbis;
                //console.log('+ ' + elem.nodeName + ' ' + idBisogno);
                call_ajax_edit_bis(elem.dataset.idbis, 'U');      //false disabilita i campi
                actualCrud = elem.dataset.crud;
            }
            else {
                if (e.target.nodeName == 'SPAN') {
                    elem = e.target.parentNode;
                    span = e.target;
                }
            /*    let param = elem.dataset.idbis;*/

                if (elem.name == "delete-post") {
                    //console.log('DELETE ' + param);
                    id_bisogno_selezionato = elem.dataset.idbis;
                }
            }
        });
    }
}); //end ready

function resetHidden() {
    document.getElementById("hidden_post_id").value = 0;
}

function setHidden(bis) {
    document.getElementById("hidden_post_id").value = bis;
}

function getHidden() {
    return(document.getElementById("hidden_post_id").value);
}

//ok per cancellazione
async function call_ajax_delete_bis(idcanc,clogic) {
    var data = new FormData;
    data.append("bis_id", idcanc);
    data.append("clogic",clogic);
    let promo = fetch('ajax/deletebisogni.php', {
        method: 'POST',
        body: data
    }).then(successResponse => {
        if (successResponse.status != 200) {
            return null;
        } else {
            return successResponse.json();
        }
    },
        failResponse => {
            //console.log("promessa fallita delete bisogni");
            return null;
        }
    );
    //console.log('aspetto che la promessa risolva');

    let result = await promo;
        //console.log('OK..promessa risolta ' + result);  
    if (result['success']) {
        //console.log(result['success']);
        myMdT.hide();
        showMessagge(result['success'], "my-callout-warning");
        call_ajax_dati_table('bisogni', 'Bistable', personalbis);
        //window.location.href = window.location.href;
    }
}


async function call_ajax_upcre_bisogni() {
   // e.preventDefault();
    //alert("sono nel salva");
    var data = new FormData(formBis);
    //vedere cosa carica e poi aggiungere o togliere
    let promo1 = await fetch('ajax/createbisogni.php', {
        method: 'POST',
        body: data
    }).then(successResponse => {
        if (successResponse.status != 200) {
            return null;
        } else {
            return successResponse.json();
        }
    },
        failResponse => {
            //console.log("promessa fallita in update create bisogni");
            return null;
        });
    //console.log('aspetto che la promessa risolva');
    let result = await promo1;
        //console.log('OK.. ' + result);
    if (result['success']) {
        resetAccordion(collapsableBis, "btnAccBis", formBis, "Segnala nuovo bisogno");
        formBis.reset();
        showMessagge(result['success'], "my-callout-info", "infoMessagge");      
        call_ajax_dati_table('bisogni', 'Bistable', personalbis);
    }
    else showMessagge(result['errors'], "my-callout-danger","infoMessagge");   
}
