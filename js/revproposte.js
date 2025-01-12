
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
var revisorpro = {
    "ncol": 7,
    "c": [
        {
            "type": "button",
            "class": ["linkstylebutton", "btn", "btn-outline-primary", "text-start"],
            "exdata": { "idpro": [1, "idPr"], "crud": [0, "R"] },
            "value": "idPr",
            "text": [1, "titlePrp"]       //1 nome di variabile  0 dato costante
        },
        {
            "type": "link",
            "title": [1, "pdforigname"],
            "link": [1, "pdfalleg"],
        },
        {
            "type": "text",
            "text": [1, "cognome"]
        },
        {
            "type": "text",
            "text": [1, "nome"]
        },
        {
            "type": "button",
            "class": ["btn", "icona"],
            "exdata": { "idpro": [1, "idPr"], "crud": [1, "idPr"] },
            "value": "idPr",
            "other": " data-bs-toggle='tooltip' title='Pubblica/Revoca' name='publishun-post'",
            "textobj": {
                "span": {
                    "field": "pubblicato",
                    "value": 1,
                    "icon": "bi-display",
                    "deficon": "bi-eye-slash"
                }
            },
            "disable": { "field": "deleted" }
        },
        {
            "type": "button",
            "class": ["btn", "icona"],
            "exdata": { "idpro": [1, "idPr"], "crud": [0, "R"] },
            "value": "idPr",
            "other": " data-bs-toggle='tooltip' title='Revisiona' name='revision-post'",
            "textobj": {
                "span": {
                    "field": "dtRev",
                    "value": null,
                    "icon": "bi-pencil-square",
                    "deficon": "bi-pencil-fill"
                }
            },
            "disable": { "field": "deleted" }
        },
        {
            "type": "button",
            "class": ["btn", "icona"],
            "exdata": { "idpro": [1, "idPr"], "crud": [0, "D"] },
            "value": "idPr",
            "other": " data-bs-toggle='tooltip' title='Cancella' name='cancella-post'",
            "textobj": {
                "span": {
                    "field": "deleted",
                    "value": 1,
                    "icon": "bi-shield-fill-x",
                    "deficon": "bi-shield-x"
                }
            },
            "disable": { "field": "deleted" }
        }]
}


//var divmoreinfo;
var fieldsetPro;
var formPro;
var actualCrud = '';
var defaultTit;
var defaultBtn;

var collapsablePro;

ready(function () {

    formPro = document.getElementById("formInputPro");

    let sezB = document.getElementById("collapsePro");
    if (sezB) {
        collapsablePro = new bootstrap.Collapse(sezB, { toggle: false });
    }

    var lvwb = document.querySelectorAll('.linkstylebutton');
    if (lvwb) {
        for (let i = 0; i < lvwb.length; i++) {
            lvwb[i].addEventListener("mouseover", (e) => { e.target.style.cursor = 'pointer'; });
        }
    }


    var revPro = document.getElementById("confirmPro");
    if (revPro) {
        defaultTit = getTitleAccordion("btnAccPro");
        defaultBtn = revPro.value;
        revPro.addEventListener("click", (e) => {
            e.preventDefault();           
            call_ajax_rev_proposta(actualCrud);

            //call_ajax_dati_table('proposte', 'Protable', revisorpro);
            //alert("devi chiudere accordion");
            // window.location.href = window.location.href;
        });
    }

    var Protable = document.querySelector('#Protable');
    if (Protable) {
        Protable.addEventListener("click", (e) => {
            if (e.target.nodeName != 'BUTTON' && e.target.nodeName != 'SPAN') { return; }

            let elem = e.target;
            let span = null;
            if (elem.classList.contains("linkstylebutton")) {
                let idProposta = elem.dataset.idpro;
                call_ajax_edit_pro(idProposta,'R');      //false disabilita i campi
                actualCrud = elem.dataset.crud;
            }
            else {
                if (e.target.nodeName == 'SPAN') {
                    elem = e.target.parentNode;
                    span = e.target;
                }
                let param = elem.dataset.idpro;
                if (elem.name == "publishun-post") {
                    //console.log('REVOCA/PUBBLICA ' + param);
                    var pub = TogglePubblication(param,"proposte");
                    btnPubUnpub(pub, elem, span);
                    window.location.reload(); 
                    //window.location.href = window.location.href;
                }
                else if (elem.name == "revision-post") {
                    actualCrud = elem.dataset.crud;
                    //console.log('REVISION ' + param);
                    oldTit = getTitleAccordion("btnAccPro");
                    if (oldTit != defaultTit)
                        settaTitleAccordion("btnAccPro", defaultTit);
                    call_ajax_edit_pro(param, 'R' );
                    //call_ajax_revision_bis(param);
                }
                else if (elem.name == "cancella-post") {
                    actualCrud = elem.dataset.crud;
                    //console.log('CANCEL ' + param);
                    settaTitleAccordion("btnAccPro", "Cancellazione Proposta");
                    revPro.value = "Cancella";
                    call_ajax_edit_pro(param, 'D');
                   // fieldsetBis.disabled = 'disabled';    dovrebbe essere disabilitato di default
                    //call_ajax_cancella_bis(param);
                }
            }
        });
    } 
    var fortimer = document.getElementById("scadenza");
    var dataFine = fortimer.value;
    dataFine = dataFine.replace(" ", "T");
    console.log(dataFine);
    avviaContoAllaRovescia(dataFine, "demo");
});//ready


//ok per revisione in pub rimane uguale
function btnPubUnpub(pub, elem, span)
{
    if (pub) {
        elem.title = "Revoca";
        if (span != null) { 
            span.classList.remove("bi-eye-slash");
            span.classList.add("bi-display");
        }
    }
    else {
        elem.title = "Pubblica";
        if (span != null) { 
            span.classList.add("bi-eye-slash");
            span.classList.remove("bi-display");
        }
    }
}


//ok per revisione
async function call_ajax_rev_proposta(crud) { 
    //e.preventDefault();
    var selected = getBisogniFromCheck();   
    var data = new FormData(document.getElementById('formInputPro'));
    data.append('bis', selected);
    data.append('crud', crud);
    let promo1 = await fetch('ajax/revisionaproposta.php', {
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
            //console.log("promessa fallita in revisiona proposta");
            return null;
        });
    //console.log('aspetto che la promessa risolva');
    let result = await promo1;
    //console.log('OK.. ' + result);
    if (result['success']) {
        showMessagge(result['success'], "my-callout-info", "infoMessagge");
        call_ajax_dati_table('proposte', 'Protable', revisorpro, 'ajax/getallpostsdatedelimited.php');
        resetAccordion(collapsablePro, "btnAccPro", formPro, "Revisione proposta");       
    }
    else {      
        showMessagge(result['errors'], "my-callout-danger", "infoMessagge");
    }
}
