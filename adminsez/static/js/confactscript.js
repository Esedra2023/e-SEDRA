
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

//per refresh
//window.location.href = window.location.href; oppure
//location.reload();

var collapsableAct;
var myModalAtt, myModalRes, myModalDel;
var utable;
var doposetti;
var tipovot;
var conblog /*, conblogdiv*/;
//var stelle;
var grad = [];
grad['bis'] = false;
grad['prop'] = false;
var blog = [];
blog['bis'] = false;
blog['prop'] = false;

var idActReset;
var idActDelete;
//var mm;
//var updAtInCorso = false;

var formACT = document.getElementById("ACTform");
var savebtn = document.getElementById("saveAct");
var hiddenact = document.getElementById("idAttCorrente");
var hiddentlp = document.getElementById("idTLCorrente");

var activobj = [{ act:[300],
            bot:{
                revisore: 'block',
                doposettimane: 'block',
                giornipreavviso: 'none',
                tipovotazione: 'none',
                templateruoli: 'none',
                tuttestelle: 'none',
                attivablog: 'none',
                secondvot: 'none'
    }
},
    {
        act: [103, 203],
        bot: {
            revisore: 'block',
            doposettimane: 'block',
            giornipreavviso: 'block',
            tipovotazione: 'none',
            templateruoli: 'block',
            tuttestelle: 'none',
            attivablog: 'none',
            secondvot: 'none'
        }
    },
        {   act: [101,201],
            bot: {
                revisore: 'block',
                doposettimane: 'none',
                giornipreavviso: 'block',
                tipovotazione: 'none',
                templateruoli: 'block',
                tuttestelle: 'none',
                attivablog: 'none',
                secondvot: 'none'
        }},
        {   act:[104,204],
        bot: {
            revisore: 'block',
            doposettimane: 'none',
            giornipreavviso: 'block',
            tipovotazione: 'block',
            templateruoli: 'block',
            tuttestelle: 'none',
            attivablog: 'block',
            secondvot: 'block'
        }
    },
    {
        act: [102,105,202,205],
        bot: {
            revisore: 'block',
            doposettimane: 'none',
            giornipreavviso: 'block',
            tipovotazione: 'none',
            templateruoli: 'none',
            tuttestelle: 'none',
            attivablog: 'none',
            secondvot: 'none'
        }
    }
];

//const obj = {
//    demo: 'none',
//    rank: 'Captain'
//};

function searchobj(ac) {
    for (let i = 0; i < activobj.length; i++) {
        for (let j = 0; j < activobj[i].act.length;j++)
            if (activobj[i].act[j] == ac)
                return i;
    }
    return -1;
}

function addDaysToDate(dt, days) {
    var ris = new Date(dt);
    ris.setDate(ris.getDate() + days);
    return ris;
}

function getButtonLabel(me) {
    //var me = document.getElementById("mainDiv");
    var tuttifigli = me.parentNode.childNodes;
    var mieifratelli = [];
    for (i = 0; i < tuttifigli.length; i++) {
        if (tuttifigli[i] !== me) {
            //console.log(tuttifigli[i]);
            if (tuttifigli[i].nodeName =="LABEL")
                mieifratelli.push(tuttifigli[i]);
        }
    }
    //console.log("label " + mieifratelli[0].innerHTML);
    return (mieifratelli[0]);
}
ready(function () {

    var mm = document.getElementById('myModalAttention');
    if (mm) {
       // alert("prepare modal");
        myModalAtt = new bootstrap.Modal(mm, {
            keyboard: false, backdrop: "static"
        })
        var modOKAtt = document.getElementById("confermAttention");
        modOKAtt.addEventListener("click", function () { /*alert("click ok");*/ savebtn.style.visibility = 'visible';/*updAtInCorso = true;*/ myModalAtt.hide(); });
        var modNoAtt = document.getElementById("closeModalAttention");
        modNoAtt.addEventListener("click", function () {
            /*alert("click annulla");*/ /*updAtInCorso = false;*/ savebtn.style.visibility = 'hidden';
                    document.getElementById("allbuttonAct").disabled = 'disabled';});
    }

    var mm2 = document.getElementById('myModalReset');
    if (mm2) {
        // alert("prepare modal");
        myModalRes = new bootstrap.Modal(mm2, {
            keyboard: false, backdrop: "static"
        })
        var modOKres = document.getElementById("confermReset");
        modOKres.addEventListener("click", function () { call_ajax_reset_act(idActReset);  /*myModalRes.hide();*/ });
       
    }
 
    var mm3 = document.getElementById('myModalDelete');
    if (mm3) {
        // alert("prepare modal");
        myModalDel = new bootstrap.Modal(mm3, {
            keyboard: false, backdrop: "static"
        })
        var modOKdel = document.getElementById("confermDelete");
        modOKdel.addEventListener("click", function () { call_ajax_delete_data(idActDelete);  /*myModalRes.hide();*/ });
 
    }
    const sezAct = document.getElementById("collapseAct");
    if (sezAct) {
        collapsableAct = new bootstrap.Collapse(sezAct, { toggle: false });
    }

    doposetti = document.getElementById("doposettimane");
  
    conblog = document.getElementById("blogactive");
    conblog.addEventListener("click", function () {
        var lbl = getButtonLabel(this);
        if (this.checked) {
          
            lbl.innerHTML = "Discussione attivata";
            if (hiddenact.value == 104) blog['bis'] = true;
            else if (hiddenact.value == 204) blog['prop'] = true;
        }
        else {
            lbl.innerHTML = "Attiva Discussione";
            if (hiddenact.value == 104) blog['bis'] = false;
            else if (hiddenact.value == 204) blog['prop'] = false;
        }

    });
    tipovot = document.getElementById("tipovotazione");
    var graduatoria = document.getElementById("vgraduatoria");
    graduatoria.addEventListener("click", function () { gestiscistelle(hiddenact.value); });
    var semplice = document.getElementById("vsemplice");
    semplice.addEventListener("click", function () {
        document.getElementById("tuttestelle").style.display = "none"; if (hiddenact.value ==104) grad['bis'] = false;
        else if (hiddenact.value == 204) grad['prop'] = false;
    });
   
    var canc = document.getElementById("annullAct");
    canc.addEventListener("click", function () { resetAccordion(collapsableAct, "bottoneAccordion", formACT, "Impostazioni attivit&agrave") });
 
    settaTitleAccordion("bottoneAccordion", "Impostazioni attivit&agrave");

    var tempruoli = document.getElementById("templateruoli");

    savebtn.addEventListener("click", function (e) {
        //alert('save');
        var act = hiddenact.value;
       // var ruoliAuto = [];
        var ruoliAuto = serializeRuoli();
        //alert('dopo serie');
        if ((act == 101 || act == 103 || act == 104 || act == 201 || act == 203 || act == 204) && ruoliAuto.length == 0)
        {
            let elem = document.getElementById('templateruoli');//.parentNode;
           divMessage(elem, "alert alert-danger mt-2", "Scegliere almeno un ruolo primario", true);
          
            e.preventDefault();
            //console.log("ruoliAuto "+ruoliAuto);
        }     
        else
            call_ajax_save_act(act,ruoliAuto);
    });      
   

    acttable = document.getElementById('actiTable');
    /*  console.log(utable);*/
    acttable.addEventListener("click", (e) => {
        if (e.target.nodeName != 'BUTTON' && e.target.nodeName != 'SPAN' && e.target.nodeName != 'INPUT') { console.log('- ' + e.target); return; }
        let elem = e.target;
        if (e.target.nodeName == 'SPAN')
            elem = e.target.parentNode;
        /*    let param = elem.dataset.idtpc;*/
        let param = elem.value;
        if (elem.name == "config-act") {
            //console.log('CONF ' + param);
            hiddenact.value = param;
            hiddentlp.value = document.getElementById("tl" + param).value;
           // alert("hid "+hiddentlp.value);
            let ind=searchobj(param)       
            Object.entries(activobj[ind].bot).forEach(entry => {
                const [key, value] = entry;
                // alert(key + " " + value);
                document.getElementById(key).style.display = value;
            });           
            savebtn.style.visibility = 'visible';
            /* console.log('chiamo la funz edit e attendo promesse');*/
            call_ajax_edit_act(param);
        }
        else if (elem.name == "act-active") {
            //console.log('ACTIVE ' + param);
            call_ajax_update_act(param, "active", elem.checked);          
        }
        else if (elem.name == "act-anonim") {
            //console.log('ANONIM ' + param);
            call_ajax_update_act(param, "anonima", elem.checked);            
        }
        else if (elem.name == "reset-act") {
            //console.log('RESET ' + param);
            idActReset = param;
            //call_ajax_update_act(param, "anonima", elem.checked);
        }
        else if (elem.name == "deldata-act") {
            //console.log('DELETE ' + param);
            idActDelete = param;
            //call_ajax_update_act(param, "anonima", elem.checked);
        }
    });
});

//Impedite modifiche parziali delle atttività - ammessa modifiche solo con il salva Impostazioni
//24 aprile 2023

   formACT.addEventListener("change", (e) => {
       /* console.log(e.target.id);*/
       var attCorr = document.getElementById("idAttCorrente").value;
       //console.log("attività corrente :" + attCorr);
        if (e.target.nodeName == 'INPUT' || e.target.nodeName == 'SELECT' ) {
            let elem = e.target;
                switch(elem.name) {
                  case 'dtStart':                   
                        var tod = document.getElementById('dtStop');
                        tod.min = elem.value;
                    break;                 
                }
  }
});

function attivaDiscussione(id) { 
    var idf = id + 1;
    document.getElementById("riga" + id).style.backgroundColor = 'var(--bs-body-bg)';
    document.getElementById("actactive" + id).checked = 'checked';
   // document.getElementById("From" + id).setAttribute("value",document.getElementById("From" + idf).getAttribute("value"));
   // alert(document.getElementById("To" + idf).getAttribute("value"));
    //document.getElementById("To" + id).setAttribute("value", document.getElementById("To" + idf).getAttribute("value"));
   // document.getElementById("rev" + id).innerHTML=document.getElementById("rev" + idf).innerHTML;
    //document.getElementById("raut" + id).innerHTML = rig['ruoli'];
    if (idf == 104) blog['bis'] = true;
    else if (idf == 204) blog['prop'] = true;
}

function disattivaDiscussione(id) {
    var idf = id + 1;
   // document.getElementById("riga" + id).style.visibility = 'hidden';
    document.getElementById("riga" + id).style.backgroundColor = 'var(--bs-light)'; // var(--bs-secondary)';
    document.getElementById("actactive" + id).checked = '';
    if (idf == 104) blog['bis'] = false;
    else if (idf == 204) blog['prop'] = false;
}

function gestiscistelle(act) {
    if (act == 104) grad['bis'] = true;
    else if (act == 204) grad['prop'] = true;
   /* grad = true;*/
    document.getElementById("tuttestelle").style.display = "block";
    readStelleFromDb(act);
}

async function readStelleFromDb(act) {
    var data = new FormData;  
    var campo;
    if (act == 104) { campo = "vbis"; };
    if (act == 204) { campo = "vpro"; };
    data.append("campo", campo);
  /*  let tuttepromesse = [];*/
    let promo1 = await fetch('adminsez/admin/ajax/getstelle.php', {
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
            //console.log("promessa fallita in readstelle");
            return null;
        }
    );
    /*   tuttepromesse.push(promo1);*/

    //console.log('aspetto che la promessa risolva');
    let result = await promo1;
  
        //console.log('OK.. ' + result);
    if (result) { 

        checkedstelle(result,campo);
    }
}

async function call_ajax_edit_act(act) {
    
    var data = new FormData;
    data.append("select", "1");
    data.append("idAt", act);
    //console.log(act + '  ' + data);

    let tuttepromesse = [];
    let promo1 = fetch('adminsez/admin/ajax/updateactivity.php', {
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
            //console.log("promessa fallita in edit act");
            return null;
        }
    );
    tuttepromesse.push(promo1);
    //console.log('aspetto che la prima promessa risolva');

    //data.delete('select');
     var data2 = new FormData;
     data2.append("idAt", act);
    let promo2 = fetch('adminsez/admin/ajax/getroleactivity.php', {
        method: 'POST',
        body: data2
    }).then(successResponse2 => {
        if (successResponse2.status != 200) {
            return null;
        } else {
            return successResponse2.json();
        }
    },
        failResponse => {
            //console.log("promessa 2 fallita in edit act");
            return null;
        }
    );
     tuttepromesse.push(promo2);
     //console.log('aspetto che la seconda promessa risolva');

     let results = await Promise.all(tuttepromesse);
     for (let i in results) {
         //console.log('OK..promessa risolta '+results[i]);
     }

     //console.log('all promises resolved');
     
    if (results[0]) { 
        settaTitleAccordion("bottoneAccordion", results[0]['nome']);
       // let vis = true;
       // alert(results[0]['stato']);
        if (results[0]['stato'] == 1)   //attività in corso ATTENTION
        {
            myModalAtt.show();
        }
        //else
          showActivity(results[0]);
         //console.log('results ' + results[0]['nome']);        
        }
     //console.log('r1 ' + results[1]);
     if (results[1])
         checkedRuoli(results[1]);
     else
         resetRuoliPrimSec();
     collapsableAct.show(); 
/*    return results;*/
}



//function call_close_ACTform() {
//    formACT.reset();
//    /*   disAbleButton(false);*/
//    /* formElements.disabled = false;*/
//    settaTitleAccordion("bottoneAccordion","Impostazioni attivit&agrave");
//    collapsableAct.hide(); //chiude il formAU

//}

function showActivity(ac) {

  /*  console.log('showActivity' + ac['idAt'] + ' ' + ac['dtStart'] + ' ' + ac['dtStop']);*/
    if (ac) {
        var nid = ac['idAt'];
        if (nid == 104 || nid == 204) {
            if (ac['altridati'] == 0 || ac['altridati'] == null) { document.getElementById("vsemplice").checked = 'checked'; }
            else {
                document.getElementById("vgraduatoria").checked = 'checked';
                gestiscistelle(nid);
            } 
            if (ac['ballottaggio'] == 1) {
                document.getElementById("ballottaggio").checked = true;
            }
            else if (ac['ballottaggio'] == 0) {
                document.getElementById("ballottaggio").checked = false;
            }
            if (ac['stato'] != 2)
                document.getElementById("ballottaggio").disabled = 'disabled';
                //alert('blog ' + ac['blog']);
            if (ac['blog'] == 1) { 
                document.getElementById("blogactive").checked = 'checked';
                if (nid == 104)
                    blog['bis'] = true;
                else if (nid == 204)
                    blog['prop'] = true;
            } else if (ac['blog'] == 0) {
                document.getElementById("blogactive").checked = '';
                if (nid == 104)
                    blog['bis'] = false;
                else if (nid == 204)
                    blog['prop'] = false;
            } 
        }
        else
            document.getElementById("altridati").value = ac['altridati'];
        document.getElementById("giorninoti").value = ac['giorninoti'];
        defineDateStartStop(ac);
        //document.getElementById("dtStart").value = ac['dtStart'];
        //var dt = document.getElementById("dtStop");
        //dt.value = ac['dtStop'];
        //if (dt.disabled) dt.disabled = '';
        document.getElementById("revisore").value = ac['revisore'];
        //if (!vis) {
        //    savebtn.disabled = 'disabled';
        //    document.getElementById("allbuttonAct").disabled = 'disabled';

        //} else {
            savebtn.disabled = '';
            document.getElementById("allbuttonAct").disabled = '';
        //}
    } else { //console.log('show activity riceve null'); } 
}


function defineDateStartStop(act) {
    var di = document.getElementById("dtStart");
    var df = document.getElementById("dtStop");
    di.value = act['dtStart'];
    df.value = act['dtStop'];  
    if (df.disabled) df.disabled = '';
    //console.log(act['dtStart'] + " " + act['dtStop'] + " " + act['dipendeda']);
   //minimo dello start: o la data odierna per non essere retroattivo o lo stop della fase da cui dipende
   di.setAttribute("min", new Date().toISOString().split('T')[0]);
   let idp = act['dipendeda'];
   if (idp != null) {
         //console.log("dipende da "+idp);
       let dip = document.getElementById("To" + idp).getAttribute("value");      
       if (dip != "") { 
           //console.log("dip " + dip)
            let next = addDaysToDate(dip, 1);
            di.setAttribute("min", new Date(next).toISOString().split('T')[0]);
       }
   } 
        //il minimo dello stop è sempre il corrispondente start
    let diV = di.value;
    if (diV != "") {
        let next = addDaysToDate(diV, 1);
        //console.log("stop " + diV);
        df.setAttribute("min", new Date(next).toISOString().split('T')[0]);
    }
    else
        df.setAttribute("min", new Date().toISOString().split('T')[0]);
   //df.defaultValue = df.getAttribute("min"); 
}

async function call_ajax_delete_data(id) {
    var data = new FormData;
    data.append("idAt", id);

    fetch('adminsez/admin/ajax/deletedatact.php', {
        method: 'POST',
        //headers: {
        //    'Content-Type': 'application/x-www-form-urlencoded'
        //},
        body: data
    })
        .then(
            function (response) {
                if (response.status !== 200) {
                    //console.log('Looks like there was a problem. Status Code: ' +response.status);
                    return;
                }
                // Examine the text in the response
                response.text().then(function (datarisp) {

                    //console.log(datarisp);
                    myModalDel.hide();
                    if (datarisp['error'])
                        showMessagge(datarisp['error'], "my-callout-danger", "infoMessagge");
                    else
                        showMessagge(datarisp['success'], "my-callout-info", "infoMessagge");

                    /*   console.log(data);*/
                    //trasformo ilJSON in oggetto JS
                    //var rig = JSON.parse(datarisp);
                    //console.log(rig);
                    //aggiornaRigaTabella(rig, "aftereset");
                });
            })
        .catch(function (err) {
            //console.log('Fetch Error :-S', err);
        });
}


async function call_ajax_reset_act(id) {
    var data = new FormData;
    data.append("idAt", id);

    fetch('adminsez/admin/ajax/resetactivity.php', {
        method: 'POST',
        //headers: {
        //    'Content-Type': 'application/x-www-form-urlencoded'
        //},
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
                response.text().then(function (datarisp) {
                    //console.log(datarisp);
                    myModalRes.hide();
                    /*   console.log(data);*/
                    //trasformo ilJSON in oggetto JS
                    var rig = JSON.parse(datarisp);
                    //console.log(rig);
                    aggiornaRigaTabella(rig,"aftereset");
                });
            })
        .catch(function (err) {
            //console.log('Fetch Error :-S', err);
        });
}


//quando modifico active e anonima immediato tutti gli altri al salvataggio del form
async function call_ajax_update_act(id, campo, value) {
    var data = new FormData;
    data.append("idAt", id);
    data.append(campo, value);
    //console.log(id + '  ' + data);
  
    fetch('adminsez/admin/ajax/updateactivity.php', {
        method: 'POST',
        //headers: {
        //    'Content-Type': 'application/x-www-form-urlencoded'
        //},
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
                response.text().then(function (datarisp) {
                    /*   console.log(data);*/
                    //trasformo ilJSON in oggetto JS
                    var rig = JSON.parse(datarisp);
                     //console.log(campo+'  '+rig);
                    aggiornaRigaTabella(rig,campo);
                });
            })
        .catch(function (err) {
            //console.log('Fetch Error :-S', err);
        });
}

async function call_ajax_save_act(act, ruoliAuto) {
    let tuttepromesse = [];

   
    var data1 = new FormData();
    data1.append("activity", act);
    data1.append("ruoli", JSON.stringify(ruoliAuto));
    /* console.log('ruoli prima di post'+ruoliAuto+'  '+data1["ruoli"]);*/
    let promo1 = fetch('adminsez/admin/ajax/updateroleactivity.php', {
        method: 'POST',
        body: data1
    }).then(successResponse1 => {
        if (successResponse1.status != 200) {
            return null;
        } else {
            return successResponse1.json();
        }
    },
        failResponse => {
            //console.log("promessa 1 fallita in save act");
            return null;
        }
    );
    tuttepromesse.push(promo1);
    //console.log('aspetto che la prima promessa risolva');


    var data2 = new FormData(formACT);
    data2.append("idAt", act);
    //alert('save '+act+' bis ' + blog['bis'] + '  prop ' + blog['prop']);
    //console.log(data2);
    //alert(data2);
    //alert(grad['bis'] + '  ' + act);
    if ((act == 104 && grad['bis']) || (act == 204 && grad['prop'])) data2.append("grad", '1');
   // else if (act == 204 && grad['prop']) data2.append("grad", '1');
    else data2.append("grad", '0');

    if ((act == 104 && blog['bis']) || (act == 204 && blog['prop'])) {
        data2.append("blog", '1'); //alert('salvo blog 1') 
    }
    else {
        data2.append("blog", '0'); //alert('salvo blog 0') 
    }
    //if (grad)
    //    data.append("grad", '1');
    //POTREI RIMUOVERE INFO NON ESSENZIALI
    /* console.log("From form data " + data);*/


    let promo2 = fetch('adminsez/admin/ajax/updateactivity.php', {
        method: 'POST',
        body: data2
    }).then(successResponse2 => {
        if (successResponse2.status != 200) {
            return null;
        } else {
            return successResponse2.json();
        }
    },
        failResponse => {
            //console.log("promessa 2 fallita in save act");
            return null;
        }
    );
    tuttepromesse.push(promo2);
    //console.log('aspetto che la seconda promessa risolva');

    let results = await Promise.all(tuttepromesse);
    for (let i in results) {
        //console.log('OK..promessa risolta ' + results[i]);
    }

    //console.log('FINISHED 2 promises resolved');
 /*   console.log('riga ' + results[0]);*/
 
    //var altrapromessa=[];
    var data3 = new FormData;
    data3.append("idAt", act);
    let promo3 = fetch('adminsez/admin/ajax/getroleactivity.php', {
        method: 'POST',
        body: data3
    }).then(successResponse3 => {
        if (successResponse3.status != 200) {
            return null;
        } else {
            return successResponse3.json();
        }
    },
        failResponse => {
            //console.log("promessa 3 fallita in save act");
            return null;
        }
    );
    //altrapromessa.push(promo3);
    //console.log('aspetto che la terza promessa risolva '+promo3);
    let res = await promo3;
   /* for (let i in res) {*/
        //console.log('OK..promessa risolta ' + res);
  /*  }*/
    var rr = rolestring(res);
    
    results[1]['ruoli'] = rr;
    //console.log('rr ' + rr + '  results[1] ' + results[1]['ruoli']);
    //datar.push(rr);
    ////results[1].push("ruoli=" + rr);
    ////console.log("riga con check " + results[1]['ruoli']);
    aggiornaRigaTabella(results[1], "all"); 
  /* hiddenact.value = 0;*/
}

//---------------------------------
//codice di esempio con una promessa
//    async function fetchProducts() {
//        try {
//            const response = await fetch('https://mdn.github.io/learning-area/javascript/apis/fetching-data/can-store/products.json');
//            if (!response.ok) {
//                throw new Error(`HTTP error: ${response.status}`);
//            }
//            const data = await response.json();
//            return data;
//        }
//        catch (error) {
//            console.error(`Could not get products: ${error}`);
//        }
//    }

//const promise = fetchProducts();
//promise.then((data) => console.log(data[0].name));

//---------------------------------

 function rolestring(data) {
     //var raut = document.getElementById("raut" + act);
     //raut = raut.firstChild;
     //console.log('rolestring inizio' + data);
     var str = '';
     let i = 0;

   for(let i in data) {
        if (data[i]['idS'] == null)  //potrebbe essere null
        {
            str += data[i]['ruolo'] + '<br/>';
            i++;
        }
        else {
            let r = data[i]['idR'];
            str += data[i]['ruolo'] + '[' + data[i]['subruolo'];
            i++;
            while (i < data.length && r == data[i]['idR']) {
                str += ', ' + data[i]['subruolo'];
                i++;
            }
            str += ']<br/>';
        }
    }
     //console.log('Role string '+str);
     //if(str!='')
     //      raut.innerHTML = str;
    return str;
}


function aggiornaRigaTabella(rig,campo) {
        id=rig['idAt'];

  /*      incorso=document.getElementById("incorso"+id).value;*/
/*        document.getElementById("name"+id).firstChild.innerHTML=rig['nome'];*/
        //if(incorso)
        //{
        //    document.getElementById("name"+id).disabled='disabled';
        //}
        //else {
      /*          document.getElementById("name"+id).disabled='';*/
    /*   }*/
    if (campo == "aftereset") {
        document.getElementById("From" + id).value = '';
        document.getElementById("To" + id).value = '';
        document.getElementById("rev" + id).innerHTML = "---";
        document.getElementById("raut" + id).innerHTML = "---";
        document.getElementById("incorso" + id).innerHTML = rig['stato'];
        if (!rig['active']) {
            document.getElementById("riga" + id).style.backgroundColor = 'var(--bs-light)'; /*var(--bs-light);*/
            document.getElementById("actactive" + id).checked = '';
        }
        else {
                document.getElementById("riga" + id).style.backgroundColor = 'var(--bs-body-bg)';
                document.getElementById("actactive" + id).checked = 'checked';
        }
        if (rig['anonima'] == false) chk = '';
        else chk = 'checked';
        document.getElementById("actanonim" + id).checked = chk;
    }
   else if (campo == "all") {
        //console.log("update all");
        document.getElementById("From" + id).value = rig['dtStart'];
        document.getElementById("To" + id).value = rig['dtStop'];
        document.getElementById("rev" + id).innerHTML = rig['rev'];
        document.getElementById("raut" + id).innerHTML = rig['ruoli'];
        if (!rig['active']) {
            document.getElementById("riga" + id).style.backgroundColor = 'var(--bs-light)'; /*var(--bs-light);*/
            document.getElementById("actanonim" + id).disabled = 'disabled';
            document.getElementById("actanonim" + id).checked = '';
            //document.getElementById("conf" + id).style.visibility = 'hidden';
        }
        else {
            chk = 'checked';
            document.getElementById("riga" + id).style.backgroundColor = 'var(--bs-body-bg)';
            document.getElementById("actactive" + id).checked = chk;
            if (document.getElementById("actanonim" + id).disabled)
                document.getElementById("actanonim" + id).disabled = '';
            /*if (document.getElementById("conf" + id).style.visibility == 'hidden')
                document.getElementById("conf" + id).style.visibility = 'visible';*/
            //console.log('aggiorna riga - getrole activity ' + id);
            /* call_ajax_getRoleActivity(id);*/
        }
        if (rig['anonima'] == false) chk = '';
        else chk = 'checked';
        document.getElementById("actanonim" + id).checked = chk;
    }
    else
    { 
    switch (campo) {
        case 'active':
            if (!rig['active']) {
                document.getElementById("riga" + id).style.backgroundColor = 'var(--bs-light)'; /*var(--bs-light)';*/
                document.getElementById("actanonim" + id).disabled = 'disabled';
                document.getElementById("actanonim" + id).checked = '';
               // document.getElementById("conf" + id).style.visibility = 'hidden';
            }
            else {
                chk = 'checked';
                document.getElementById("riga" + id).style.backgroundColor = 'var(--bs-body-bg)';
                document.getElementById("actactive" + id).checked = chk;
                if (document.getElementById("actanonim" + id).disabled)
                    document.getElementById("actanonim" + id).disabled = '';
               /* if (document.getElementById("conf" + id).style.visibility =='hidden')
                    document.getElementById("conf" + id).style.visibility = 'visible';*/
                //console.log('aggiorna riga - getrole activity ' + id);
                /* call_ajax_getRoleActivity(id);*/
            }
            break;
        case 'anonima':
            if (rig['anonima'] == false) chk = '';
            else chk = 'checked';
            document.getElementById("actanonim" + id).checked = chk;
            break;
        case 'dtStart':
            document.getElementById("From" + id).value = rig['dtStart'];
            break;
        case 'dtStop':
            var dts = document.getElementById("To" + id);
            dts.value= rig['dtStop'];
            if (dts.disabled)
                dts.disabled = '';
            break;
        case 'revisore':
            document.getElementById("rev" + id).innerHTML = rig['rev'];
            break;
        //case 'ruoli':
        //    document.getElementById("raut" + id).innerHTML = rig['ruoli'];
        //    break;
        }
    }
   /*     document.getElementById("scad" + id).innerHTML = rig['scaduta'];*/
}

