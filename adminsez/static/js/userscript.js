﻿
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

var record;
var outPag;

//const maxrighepagina = 15;
var collapsableCreate;
var collapsableImport;
var myModal;
var utable;

//const rprim = document.getElementsByName("rprim");
var formAU = document.getElementById("AUform");
var formElements = document.getElementById("allElements");

var formIUL = document.getElementById("IULform");
var createUpdatebtn = document.getElementById("saveAddUser");
var idModUser = document.getElementById("IDModUser");
var fakesearch = document.getElementById("search");
var id_user_selezionato;
var defaultsearch = 'a%';
var lastbtnsearch = document.getElementById("ra");
var samplefile = document.getElementsByName("fileToUpload")[0];

samplefile.addEventListener('change', () => {
   uploadFile(samplefile.files[0]);
});

var ptot = document.getElementById("pagine_totali");
var patt = document.getElementById("pagina_attuale");
var utentiperlettera = document.getElementById("utenti_totali");

ready(function () {
    //alert("sono in ready");

    settaTitleAccordion("btnAccCreateUser", "Aggiungi Utente");

    const sezCreate = document.getElementById("collapseOne");
    if (sezCreate) {
        collapsableCreate = new bootstrap.Collapse(sezCreate, { toggle: false });
    }
    const sezImport = document.getElementById("collapseTwo");
    if (sezImport) {
        collapsableImport = new bootstrap.Collapse(sezImport, { toggle: false });
    }

    var mm = document.getElementById('myModalCancel');
    if (mm) {
        //alert("prepare modal");
        myModal = new bootstrap.Modal(mm, {
            keyboard: false
        })
    }
    var deleteOK = document.getElementById("cancelUser");
    deleteOK.addEventListener("click", function () { call_ajax_delete_user(id_user_selezionato); topFunction(); });

    var em = document.getElementById("email");
    em.addEventListener("change", call_ajax_gestisci_mail_duplicate);

    var canc = document.getElementById("annullAdd");
    canc.addEventListener("click", function () { call_close_AUform("Aggiungi Utente"); idModUser.value = 0; });
    var canc2 = document.getElementById("annullIul");
    canc2.addEventListener("click", call_close_IULform);


    let alfall = document.querySelector(".alfa");
    alfall.addEventListener("click", (e) => {
        //console.log('- ' + e.target.nodeName);
        //e.target.nodeName != 'LABEL' && 
        if (e.target.nodeName != 'INPUT') { /*console.log('- '+e.target);*/  return; }
        else {
            let elem = e.target;
            if (elem.getAttribute("id") == "rall") {
                if (confirm("Gli utenti da visualizzare potrebbero essere molti. Continuare?")) {
                    //alert("click su alfaall ");
                    call_ajax_refresh_table_user(elem.value, 1);
                    lastbtnsearch = elem;
                    if (!lastbtnsearch.checked) lastbtnsearch.checked = true;
                    //alert("value "+elem.value);
                    fakesearch.value = elem.value;
                    e.stopPropagation();
                }
                else
                    lastbtnsearch.checked = true;
            }
            else {
               // alert("click su "+elem.value);
                call_ajax_refresh_table_user(elem.value, 1);   
                checkSearch(elem.value);
                //lastbtnsearch = elem;
                //lastbtnsearch.checked=true;
               /* alert("value " + elem.value);*/
               /* fakesearch.value = elem.value;*/
                e.stopPropagation();
            }
        }
    });


    createUpdatebtn.addEventListener("click", (e) => { call_ajax_create_user(); e.preventDefault(); });

    utable = document.querySelector('#Utable');
    /*  console.log(utable);*/
    utable.addEventListener("click", (e) => {
        if (e.target.nodeName != 'BUTTON' && e.target.nodeName != 'SPAN') { /*console.log('- '+e.target);*/  return; }
        //let edt = e.target.closest('.spedit');
        //console.log(edt.dataset.idtpc);
        let elem = e.target;
        if (e.target.nodeName == 'SPAN')
            elem = e.target.parentNode;
        let param = elem.value;
        if (elem.name == "edit-user") {
            //console.log('EDIT ' + param);
            topFunction();
            settaTitleAccordion("btnAccCreateUser", "Modifica Utente");

            //disabilito campo mail in edit_user
            call_ajax_edit_user(param, false, true);
        }
        else if (elem.name == "delete-user") {
            //console.log('DELETE ' + param);
            topFunction();
            //apre la finestra modale che ho chiamato eliminaModal
            id_user_selezionato = param;
        }
        else if (elem.name == "view-user") {
            //console.log('VIEW ' + param);
            topFunction();
            settaTitleAccordion("btnAccCreateUser", "Visualizza Utente");
            //disabilito tutti i campi in edit_user
            call_ajax_edit_user(param, true, true);
        }
    });

    //campo nascosto che memorizza il criterio di visualizzazione della tabella 
    fakesearch.addEventListener("click", function (e) {
        if (outPag) {
            if (outPag.paginaCorrente) {
                //alert("fakesearch out" + outPag.paginaCorrente + " " + this.value);
                call_ajax_refresh_table_user(this.value, outPag.paginaCorrente);
                checkSearch(this.value);
            }
            else {
                //alert("out ma else " + this.value);
                call_ajax_refresh_table_user(this.value, 1);
                checkSearch(this.value);
            }
            e.stopPropagation(); 
        }
    });

    simulateClick(fakesearch);
});

function checkSearch(ser) {
    var btn;
    if (ser[0] == '%') {
        btn = document.getElementById("rall");
    }
    else {
        btn = document.getElementById("r" + ser[0]);       
    }
    if (btn) {   
        //alert("check su " + btn.value);
        btn.checked = true;
        lastbtnsearch = btn;
        fakesearch.value = ser;
    }
}

var btnAv = document.getElementById("nextP");

btnAv.addEventListener('click', (e) => {

    //console.log('clic su avanti');
    if (outPag.nextpage) {
       /* console.log(outPag.nextpage)*/;
        outPag = Upagination(record, outPag.nextpage);
        //console.log(outPag);
        aggiornaPaginanew(/*outPag*/);
        setnavigation(/*outPag*/);
        e.stopPropagation();
    }
});

var btnInd = document.getElementById("backP");

btnInd.addEventListener('click', (e) => {
    if (outPag.prevpage) {
        outPag = Upagination(record, outPag.prevpage);
        aggiornaPaginanew(/*outPag*/);
        setnavigation(/*outPag*/);
        e.stopPropagation();
    }
});


var setnavigation = (/*outPag*/) => {
    ptot.innerText = outPag.pagineTotali;
    patt.innerText = outPag.paginaCorrente;
    utentiperlettera.innerText = outPag.totali;
    if (!outPag.nextpage)
        btnAv.disabled = true;
    else btnAv.disabled = false;
    if (!outPag.prevpage)
        btnInd.disabled = true;
    else
        btnInd.disabled = false;
};

function setDati(dati) {
    record = dati;
}

var Upagination = (record, paginaCorrente, elementiPerPagina) => {
    //console.log("entro in pagination con pagina corrente "+paginaCorrente);
    //se non specificato considero di essere alla pagina numero 1
    let pagina = paginaCorrente;
    //se non specificato dico che voglio 10 elmenti per pagina
    let perPagina = elementiPerPagina || 10;
    //calcolo l'offset di spostamento della "finestra" di elementi che voglio restituire
    let offset = (pagina - 1) * perPagina;
    //estraggo la porzione di oggetti che mi interessa
    let contPag = record.slice(offset).slice(0, perPagina);
    //Calcolo il numero totale di pagine
    let tot = record.length;
    let pagineTotali = Math.ceil(tot / perPagina);
    //console.log(pagina+'/'+pagineTotali);

    //console.log(record);
    /*
      Restituisco un oggetto contenente la porzione paginata e tutte le informazioni necessarie alla navigazione
    */
    return {
        paginaCorrente: pagina,
        perPagina: perPagina,
        prevpage: pagina - 1 ? pagina - 1 : null,
        nextpage: pagineTotali > pagina ? pagina + 1 : null,
        totali: tot,
        pagineTotali: pagineTotali,
        contenutopag: contPag
    };
};

var uploadFile = (file) => {

    const data = new FormData();
    if (!['text/csv', 'text/txt'].includes(file.type)) {
        divMessage(samplefile, "col-12 alert alert-danger mt-2", "Formato file non compatibile", true);
        samplefile.value = '';
        return;
    }
    if (file.size > 2*1024 * 1024)  //2MB  max per file upload
    {
        divMessage(samplefile, "col-12 alert alert-danger mt-2", "Dimensione massima del file 2MB", true);
        samplefile.value = '';
        return;
    }
    var ruol = 0;
    var ruoli = document.getElementsByName("Irprim");
    for (let i = 0; i < ruoli.length; i++) {
        if (ruoli[i].checked) {
            ruol = ruoli[i].value;
            data.append("Irprim", ruol); 
            break;
        }
    }
    //console.log('Import file con Irprim = ' + ruol);
    data.append('fileup', file);
    let wic = document.getElementById("spinner-div");
    wic.classList.remove('d-none');
    fetch('adminsez/admin/ajax/impu.php', {
        method: "POST",
        body: data
    }).then(function (risp) {
        return risp.json();
    }).then(function (rispdata) {
        samplefile.value = '';
        let wic = document.getElementById("spinner-div");
        wic.classList.add('d-none');
        if (rispdata.success) {     
            //non c'è verso di visualizzarlo!!!'
            /*showMessagge(rispdata.success, "my-callout-warning");  */
            showMessagge(rispdata.success, "my-callout-info", "infoMessaggedx");
            //alert(rispdata.success);
            call_close_IULform();
        }
        if (rispdata.Ruolo) {
            divMessage(samplefile, "col-12 alert alert-danger mt-2", "Scegliere un ruolo!", true);
            return;
        }
        if (rispdata.errors){
            var mes = '';
            for (let j = 0; j < rispdata.errors.length; j++)
                mes = mes + rispdata.errors[j] + '\n';
            /* showMessagge(mes, "my-callout-danger");*/
            call_close_IULform();
            showMessagge(mes, "my-callout-danger", "infoMessaggedx");
            //alert(mes);
        } 
        simulateClick(fakesearch);
    });
}


//la fetch sostituisce la $.post di JQuery
//$.post("../admin/ajax/searchmail.php", { emsearch: this.value }, function (res) {

function call_ajax_gestisci_mail_duplicate() {
    //alert(this.value);
    // dati da mandare alla richiesta POST 
    var datai = new FormData;
    datai.append('emsearch', this.value);
    fetch('adminsez/admin/ajax/searchmail.php', {
        method: 'POST',
        //headers: {
        //    'Content-Type': 'application/x-www-form-urlencoded'
        //},
        body: datai
    })
        .then(
            function (response) {
                if (response.status !== 200) {
                    console.log('Looks like there was a problem. Status Code: ' +
                        response.status);
                    return;
                }
                // Examine the text in the response
                response.json().then(function (data) {
                    if (data != 0) {
                        elem = document.getElementById("IDModUser");
                        divMessage(elem, "alert alert-danger mt-2", "Mail gi&agrave; presente nella base di dati!", false);
                        
                      /*  document.getElementById("email").after(el);*/
                        createUpdatebtn.disabled = 'disabled';
                    }
                    else {
                        al = document.querySelectorAll(".alert");
                        for (const el of al) {
                            el.remove();
                        }
                       /* document.getElementById("AUform").querySelector(".alert").remove();*/
                        var x = document.getElementById("saveAddUser").disabled;
                        if (x)
                            document.getElementById("saveAddUser").disabled = !x;
                    }
                    //alert(data);
                });
            }
        )
        .catch(function (err) {
            //console.log('Fetch Error :-S', err);
 });

}

function call_close_AUform(title) {
    /*formAU.reset();*/
    /*   disAbleButton(false);*/
    resetRuoliPrimSec();
    formAU.classList.remove("alert");
    formElements.disabled = false;
    document.getElementById("email").disabled = false;
    createUpdatebtn.style.visibility = "visible";
    createUpdatebtn.value = "Salva Utente";
    resetAccordion(collapsableCreate, "btnAccCreateUser", formAU, title)
   /* collapsableCreate.hide();*/ //chiude il formAU

}

function call_close_IULform() {
    formIUL.reset();
    /* disAbleButton(false);*/
    collapsableImport.hide();
}

function call_ajax_refresh_table_user(param, pagCorr) {
   
    //console.log("call_ajax-refresh_table " + param + '  ' + pagCorr);
    var usedefault=false;
    if (param == '') { 
        param = defaultsearch;
        usedefault = true;
        }
   
    var datai = new FormData;
    datai.append("search",param);
    fetch('adminsez/admin/ajax/getusers.php', {
        method: 'POST',
        //headers: {
        //    'Content-Type': 'application/x-www-form-urlencoded'
        //},
        body: datai
    })
        .then(
            function (response) {
                if (response.status !== 200) {
                    console.log('Looks like there was a problem. Status Code: ' +
                        response.status);
                    return;
                }
                // Examine the text in the response
                response.text().then(function (data) {
                 /*   console.log(data);*/
                     //trasformo ilJSON in oggetto JS
                    var ut = JSON.parse(data);
                    setDati(ut);
                    outPag = Upagination(ut, pagCorr);
                    setnavigation(/*outPag*/);                
                    aggiornaPaginanew(/*outPag*/);
                   
                    if (usedefault)
                        document.getElementById('ra').checked = true;
                });
            })
        .catch(function (err) {
            //console.log('Fetch Error :-S', err);
        });
}

function aggiornaPaginanew(/*porz*/) {
    //console.log("sono in aggiornaPagina " + righe.length);
    /*alert(righe[0].email);*/

   // var tabella = document.getElementById("Utable");

    var tb = utable.getElementsByTagName("tbody");
    for (let i = 0; i < tb.length; i++) {
        if (tb[i] != null)
            tb[i].remove();
    }
    tb = document.createElement("tbody");
    let righe = outPag.contenutopag;
    let i;
    for (i = 0; i < righe.length; i++) {
        tr = document.createElement("tr");
        td = document.createElement("td");
        td.innerHTML = (i + 1)+outPag.perPagina*(outPag.paginaCorrente-1);
        tr.appendChild(td);
        td = document.createElement("td");
        td.innerHTML = "<button type='button' class='btn text-start' data-bs-toggle='tooltip' title='Vedi' value='" + righe[i].idUs + "' name='view-user'>"+righe[i].cognome + ' ' + righe[i].nome+"</button>";
     /*   td.innerHTML = righe[i].cognome + ' ' + righe[i].nome;*/
        tr.appendChild(td);
        td = document.createElement("td");
        td.innerHTML = righe[i].email;
        tr.appendChild(td);
        td = document.createElement("td");
        td.innerHTML = righe[i].ruolo;
        tr.appendChild(td);
        td = document.createElement("td");
        td.innerHTML = "<button type='button' class='btn edita icona' data-bs-toggle='tooltip' title='Modifica' value='" + righe[i].idUs + "' name='edit-user'><span class='bi-pencil-square'></span></button>";
        tr.appendChild(td);
        td = document.createElement("td"); 
        if (righe[i].idUs!=1)
            td.innerHTML = "<button type='button' class='btn elimina icona' data-bs-toggle='modal' data-bs-target='#myModalCancel' data-id=" + righe[i].idUs + "  title='Elimina' value='" + righe[i].idUs + "' name='delete-user'><span class='bi-trash3'></span></button>";
        tr.appendChild(td);
        td = document.createElement("td");
        td.innerHTML = "<button type='button' class='btn vedi icona' data-bs-toggle='tooltip' title='Vedi' value='" + righe[i].idUs + "' name='view-user'><span class='bi-eye'></span></button>";
        //    "<a name='view-user' href='users.php?view-user=" + righe[i].idUs + "'><i class='bi bi-eye'></i></a>";
        tr.appendChild(td);
        tb.appendChild(tr);
    }
    //righe vuote per completare le pagine corte
    if (i != outPag.perPagina) { 
        if (i == 0) {
            tr = document.createElement("tr");
            td = document.createElement("td");
            td.colSpan = "7";
            td.innerHTML = "Nessun utente presente con l'iniziale del cognome selezionata!";
            tr.appendChild(td);
            tb.appendChild(tr);
        }
        for (let j = i; j < outPag.perPagina; j++) {
            tr = document.createElement("tr");
            td = document.createElement("td");
            td.colSpan = "7";
            td.innerHTML = "</br>";
            tr.appendChild(td);
            tb.appendChild(tr);
            }
        }
    utable.appendChild(tb);
}

async function call_ajax_create_user() {
    var elem;
    var ruoli = serializeRuoli();
    //alert(data);
    if (ruoli.length == 0) {
        //alert("Ruolo primario mancante");
        elem = document.getElementById('templateruoli').parentNode;
        divMessage(elem, "alert alert-danger mt-2", "Scegliere almeno un ruolo primario", true);
    }
    else { 
        /*  formAU.classList.remove("alert");*/
        var data = new FormData(formAU);
        data.append('ruoli', JSON.stringify(ruoli));
        /* console.log(data);*/
        let promo = fetch('adminsez/admin/ajax/addu.php', {
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
                //console.log("promessa fallita in create/update user");
                return null;
            }
        );
        //console.log('aspetto che la promessa risolva');
        let risp = await promo;
        //console.log('OK..promessa risolta ' + risp);
        //alert(risp);
        if (risp.errors) {
            var l = risp.errors.length;
            for (i = 0; i < l; i++) {
                elem = document.getElementById(risp.errors[i].param);
                divMessage(elem, "alert alert-danger mt-2", risp.errors[i].msg, false);
            }
        } else if (risp.success) {
             //già presenti nel close_AUform
            //createUpdatebtn.style.visibility = "visible";
            //createUpdatebtn.value = "Salva Utente";          
            call_close_AUform("Aggiungi Utente");
            showMessagge(risp.success[0], "my-callout-warning");
           // console.log(risp.success[0][0]);
            if (risp.success[0][0] == 'C') {
                var newsearch = risp.success[2] + '%';
                //console.log("newsearch " + newsearch);
                if (newsearch != fakesearch.value) {  
                    //alert("ok new");
                    fakesearch.value = newsearch;
                    outPag.paginaCorrente = 0; 
                    simulateClick(fakesearch);
                }
            }
            else
                simulateClick(fakesearch);
            
        }
    }
}

function call_ajax_edit_user(id, readonly, showpd) {
    var mostrapassword = 0;
    if (showpd) mostrapassword = 1;
    var ro = 0;
    if (readonly) {
        ro = 1;       //usata per la view
    }
    var data = new FormData;
    data.append('user_id', id);
    data.append('pw', mostrapassword);
    data.append('view', ro);

    fetch('adminsez/admin/ajax/edituser.php', {
        method: 'POST',
        body: data
    })
        .then(
            function (response) {
                if (response.status !== 200) {
                    console.log('Looks like there was a problem. Status Code: ' +
                        response.status);
                    return;
                }
                // Examine the text in the response
                response.text().then(function (risp) {
                    //console.log(risp);
                    //trasformo ilJSON in oggetto JS
                    var ut = JSON.parse(risp);
                    //console.log('UT=' + ut);
                    //mettere i dati nel form
                    vediUtente(ut, readonly);
                    //cambio la label al bottone
                    if (readonly)
                        createUpdatebtn.style.visibility = "hidden";
                    else {
                        createUpdatebtn.style.visibility = "visible";
                        createUpdatebtn.value = "Aggiorna Dati Utente";
                    }
                    collapsableCreate.show(); //apre il formAU

                });
            })
        .catch(function (err) {
            //console.log('Fetch Error :-S', err);
        });
}

function vediUtente(ut, readonly) {

    if (!readonly) { formElements.disabled = false; }
    idModUser.value = ut['idUs'];
    document.getElementById("nome").value = ut['nome'];
    document.getElementById("cognome").value = ut['cognome'];
    document.getElementById("email").value = ut['email'];
    document.getElementById("cell").value = ut['cell'];
    document.getElementById("cod").value = ut['cod'];
    document.getElementById("dtPsw").value = ut['dtPsw'];
    if (ut['dtScPsw'] != 0) { 
        //console.log('giorni: '+ut['ggscPsw']);
        document.getElementById("dtscPsw").value = ut['ggscPsw'];   //vedo i giorni mancanti se calcolati
        document.getElementById("dtscPsw").style.visibility = "visible";
    }
    else
        document.getElementById("dtscPsw").style.visibility ="hidden";

    //resetRuoliPrimSec();
    //check ruoli primari e secondari
    checkedRuoli(ut['ruolo']);
    if (ut['idUs'] == 1) {
        document.getElementById("nome").disabled = true;
        document.getElementById("cognome").disabled = true;
    }
    else {
        document.getElementById("nome").disabled = false;
        document.getElementById("cognome").disabled = false;
    }
    if (readonly) {
        formElements.disabled = true;
    }
    else
        document.getElementById("email").disabled = true;
    
}

async function call_ajax_delete_user(idcanc) {

    var data = new FormData;
    data.append("user_id", idcanc);
    let promo = fetch('adminsez/admin/ajax/deleteuser.php', {
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
            //console.log("promessa fallita in delete user");
            return null;
        }
    );
    //console.log('aspetto che la promessa risolva');
    let result = await promo;
    //console.log('OK..promessa risolta ' + result);
    myModal.hide();
    showMessagge(result, "my-callout-warning");
    //alert("fakeclick da delete user");
    simulateClick(fakesearch);  //per aggiornare la tabella
}