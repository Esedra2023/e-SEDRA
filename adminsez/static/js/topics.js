
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
var MyMdT;
var collapsableTopic;
var delOK;
var tid;
var tname;
var tval;
var tcheck;
var htip;
var suTpc;
var tptable;
var formTOPIC;
var dataTopic;
var topicToDelete;

function call_close_form() {
    collapsableTopic.hide(); //chiude il formAU
    settaTitleAccordion("btnAccTopic", "Crea Nuovo Ambito");
}

ready(function () {
    formTOPIC = document.getElementById('formTOPIC');
        var mmt = document.getElementById('myModalTopic');
        if (mmt) {
            /* alert("prepare modal");*/
            myMdT = new bootstrap.Modal(mmt, {
                keyboard: false
            })
        }
    let sezTopic = document.getElementById("collapseTopic");
    if (sezTopic) {
        /*alert("si sezcreate");*/
        collapsableTopic = new bootstrap.Collapse(sezTopic, { toggle: false });
    }

    htip = document.getElementById("idAm");

    delOK = document.getElementById("cancelTpc");
    delOK.addEventListener("click", function () { call_ajax_delete_topic(topicToDelete); call_ajax_refresh_topic_table(); });

    var canc = document.getElementById("annullTpc");
    canc.addEventListener("click", function () { resetAccordion(collapsableTopic, "btnAccTopic", formTOPIC, "Crea nuovo ambito"); suTpc.value = "Salva Ambito"; });

    settaTitleAccordion("btnAccTopic", "Crea nuovo ambito");
    suTpc = document.getElementById("saveUpdTpc");
    suTpc.value = "Salva Ambito";
    suTpc.addEventListener("click", (e) => { call_ajax_upcre_topic(); e.preventDefault(); call_ajax_refresh_topic_table(); });

    /*  tid=document.getElementById('topic_id');*/
   tname=document.getElementById('ambito');
    tval = document.getElementById('valenza');
    tcheck = document.getElementById('moreinfo');

    tptable = document.querySelector('#topicTable');
    //console.log(tptable);
    tptable.addEventListener("click", (e) => { 
        if (e.target.nodeName != 'BUTTON' && e.target.nodeName != 'SPAN') { /*//console.log('- '+e.target);*/  return; }
        //let edt = e.target.closest('.spedit');
        //console.log(edt.dataset.idtpc);
        let elem = e.target;
        if (e.target.nodeName == 'SPAN')
            elem = e.target.parentNode;
        let param = elem.dataset.idtpc;
        if (elem.name == "edit-topic") {
            //console.log('EDIT ' + param);
            call_ajax_edit_topic(param);
        }
        else if (elem.name == "delete-topic") {                     
            //console.log('DELETE ' + param);
            topicToDelete = param;
            //myMdT.show();
            //delOK = addEventListener("click", function () { call_ajax_delete_topic(param); });
        }
    });
});

async function call_ajax_delete_topic(idcanc) {
    var data = new FormData;
    data.append("idAm", idcanc);
    let promo = fetch('adminsez/admin/ajax/deletetopic.php', {
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
            //console.log("promessa fallita delete ambiti");
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
    }
}
  
async function call_ajax_refresh_topic_table() {
    //var data = new FormData;
    //data.append("idAm", idcanc);
    let promo = fetch('adminsez/admin/ajax/getambiti.php', {
        method: 'POST',
       /* body: data*/
    }).then(successResponse => {
        if (successResponse.status != 200) {
            return null;
        } else {
            return successResponse.json();
        }
    },
        failResponse => {
            //console.log("promessa fallita recupera ambiti tutti");
            return null;
        }
    );
    //console.log('aspetto che la promessa risolva');

    let result = await promo;
    //console.log('OK..promessa risolta recupera ambiti' + result);
    if (result) { 
        showTopicsTable(result);
   // if (result['success']) {
        //console.log(result[0]);
        //myMdT.hide();
        //showMessagge(result['success'], "my-callout-warning");
        //refresh table??
   }
}


function showTopicsTable(righe) {
    //let n = result.length;
    //console.log(n);
    //var tabella = document.getElementById("topicTable");

    var tb = tptable.getElementsByTagName("tbody");
    for (let i = 0; i < tb.length; i++) {
        if (tb[i] != null)
            tb[i].remove();
    }
    tb = document.createElement("tbody");
   // let righe = result;
    let i;
    for (i = 0; i < righe.length; i++) {
        tr = document.createElement("tr");
       /* td = document.createElement("td");
        td.innerHTML = (i + 1) + outPag.perPagina * (outPag.paginaCorrente - 1);
        tr.appendChild(td);
        td = document.createElement("td");
        td.innerHTML = "<button type='button' class='btn text-start' data-bs-toggle='tooltip' title='Vedi' value='" + righe[i].idUs + "' name='view-user'>" + righe[i].cognome + ' ' + righe[i].nome + "</button>";
       */ /*   td.innerHTML = righe[i].cognome + ' ' + righe[i].nome;*/
        //tr.appendChild(td);
        td = document.createElement("td");
        td.innerHTML = righe[i].ambito;
        tr.appendChild(td);
        td = document.createElement("td");
        td.innerHTML = righe[i].valenza;
        tr.appendChild(td);
        td = document.createElement("td");
        td.innerHTML = "<button type='button' class='btn' data-bs-toggle='tooltip' title='Modifica' data-idtpc='" + righe[i].idAm + "' value='" + righe[i].idAm + "' name='edit-topic'><span class='bi-pencil-square spedit'></span></button>";
        tr.appendChild(td);
        td = document.createElement("td");
        td.innerHTML = "<button type='button' class='btn' data-bs-toggle='modal' data-bs-target='#myModalTopic' data-idtpc=" + righe[i].idAm + "  title='Elimina' value='" + righe[i].idAm + "' name='delete-topic'><span class='bi-trash3'></span></button>";
        tr.appendChild(td);
        //td = document.createElement("td");
        //td.innerHTML = "<button type='button' class='btn vedi' data-bs-toggle='tooltip' title='Vedi' value='" + righe[i].idUs + "' name='view-user'><span class='bi-eye'></span></button>";
        ////    "<a name='view-user' href='users.php?view-user=" + righe[i].idUs + "'><i class='bi bi-eye'></i></a>";
        //tr.appendChild(td);
        tb.appendChild(tr);
    }
    tptable.appendChild(tb);
}


async function call_ajax_edit_topic(ided) {
    settaTitleAccordion("btnAccTopic", "Modifica Ambito");
    var data = new FormData;
    data.append("idAm", ided);
    let promo = fetch('adminsez/admin/ajax/searchtopic.php', {
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
            //console.log("promessa fallita edit topic");
            return null;
        }
    );
    //console.log('aspetto che la promessa risolva');

    let tpc = await promo;
    //console.log('OK ... promessa risolta ' + tpc);
    if (tpc) { 
    //console.log(tpc['idAm'] + ' ' + tpc['ambito'])
    // //mettere i dati nel form
    htip.value = tpc['idAm'];
    tname.value = tpc['ambito'];
    tval.value = tpc['valenza'];
        if (tpc['moreinfo'] == 1) tcheck.checked = 'checked';
    ////cambio la label al bottone
    suTpc.value = "Aggiorna Ambito";
    collapsableTopic.show(); //apre il form
    }
}

async function call_ajax_upcre_topic() {
    dataTopic = new FormData(formTOPIC);
    if (htip.value != 0) {
        //console.log('update');//update
       dataTopic.append('crud','U');       
    }
    else {
        //console.log('create');
        dataTopic.append('crud', 'C');    
        //create 
    }
    if (tcheck.checked) dataTopic.set('moreinfo', '1');     //set sostituisce l'eventuale valore già presente
    else dataTopic.set('moreinfo', '0');

    let promo = fetch('adminsez/admin/ajax/createtopic.php', {
        method: 'POST',
        body: dataTopic
    }).then(successResponse => {
        if (successResponse.status != 200) {
            return null;
        } else {
            return successResponse.json();
        }
    },
        failResponse => {
            //console.log("promessa fallita edit topic");
            return null;
        }
    );
    //console.log('aspetto che la promessa risolva');

    let result = await promo;
    //console.log('OK ... promessa risolta ' + result);
    if (result.errors) {
        //console.log(result.errors);
        showMessagge(result['errors'], "my-callout-danger");
    } else if (result.success) {
        //console.log(result.success);
        showMessagge(result['success'], "my-callout-warning");
        resetAccordion(collapsableTopic, "btnAccTopic", formTOPIC, "Crea nuovo ambito");
        suTpc.value = "Salva Ambito";
       /* collapsableTopic.hide();*/
    }   
}

