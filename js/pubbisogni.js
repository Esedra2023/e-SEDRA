
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

var gradtab = {
    "ncol": 5,
    "c": [
        {
            "type": "text",
            "text": [1, "ambito"]
        },
        {
            "type": "text",
            "text": [1, "titleBis"]
        },
        {
            "type": "text",
            "text": [1, "totalGrade"]
        },
        {
            "type": "text",
            "text": [1, "nlike"]
        },
        {
            "type": "text",
            "text": [1, "votanti"]
        }]
}


var localgrad = [];
var confGrad;
var confv2;
ready(function () {
    var collapsableBis;

    //var accbt = document.getElementById("bottoneAccGrad");
    //if(accbt)
    //    accbt.addEventListener("click", function () {call_ajax_get_poll_res("revisor",0, 1);  });

    confv2 = document.getElementById("confirmVot2");
    if (confv2) { 
        confv2.addEventListener("click", (e) =>{
            //alert(e.target.parentNode);
            //alert(e.target.parentNode.value);
            componiAutoNews("Bisogni", 'B', e.target.parentNode.value);
        });
       /* confv2.addEventListener("click", goActivityPage);*/
    }
    let sezB = document.getElementById("collapseBis");
    if (sezB) {
        collapsableBis = new bootstrap.Collapse(sezB, { toggle: false });
    }
 
    //var goa = document.getElementById("gopageact");
    //if (goa) {     
    //        goa.addEventListener("click", goActivityPage);
    //}

    var lvwb = document.querySelectorAll('.linkstylebutton');
    if (lvwb) {
        for (let i = 0; i < lvwb.length; i++) {
            lvwb[i].addEventListener("mouseover", (e) => { e.target.style.cursor = 'pointer'; });
            lvwb[i].addEventListener("click", (e) => { let idBisogno = e.target.dataset.idbis; call_ajax_edit_bis(idBisogno, 'V'); });
        }
    }

    //var pbu = document.getElementById("publish");
    //if (pbu) pbu.addEventListener("click", toggleBtnPub);

    var bistable = document.querySelector('#Bistable');
    if (bistable) {
        bistable.addEventListener("click", (e) => {
            if (e.target.nodeName != 'BUTTON' && e.target.nodeName != 'SPAN') { return; }

            let elem = e.target;
            let span = null;
            if (elem.classList.contains("linkstylebutton")) {
                let idBisogno = elem.dataset.idbis;
                call_ajax_edit_bis(idBisogno, 'V');      //false disabilita i campi
            }
            else {
                if (e.target.nodeName == 'SPAN') {
                    elem = e.target.parentNode;
                    span = e.target;
                }
                let idbis = elem.dataset.idbis;
                if (elem.name == "grad-post") {
                    //console.log('AGGIUNGI/TOGLI GRAD ' + idbis);  
                    //alert(elem);
                    let posg = elem.dataset.posg;
                    stabilisciNumeroGrad('B',posg);
                    //var gg = ToggleGradDef(idbis, "bisogni");
                    //var colgrad = document.getElementById("collapseGrad");
                 
                    //if(colgrad.classList.contains('show'))
                    //{
                    //    //alert('sono show');
                    //    call_ajax_get_poll_res("revisor",0,1);
                    //    //refreshTable("Gradtable", postgrad, gradtab);

                    //}
/*                    else alert('sono chiuso');*/
                    //refreshRowTable(param);
                    /*location.reload();*/
                    //window.location.href = window.location.href;
                }

            }//else
        });
    }  
}); //end ready


async function componiAutoNews(what,itable,field) {
    var data = new FormData;
    data.append("title", "Pubblicata graduatoria " + what);
    data.append("field", field);
    data.append("itable", itable);
    let promo = fetch('ajax/automaticNews.php', {
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
            //console.log("promessa fallita con updatebis2vot");
            return null;
        }
    );
    //console.log('aspetto che la promessa risolva');

    let result = await promo;
    confv2.disabled = true;
    goHomePage();
    console.log('OK ... promessa risolta ' + result);
    return (result);
}

//sono in function.js

function goHomePage() {
    //var data = new FormData;
    //data.append("page", 1);
    //call_ajax_viewPage('adminsez/admin/admconfact.php', data);
    menu = document.getElementById("0");
    simulateClick(menu);
}

//function stabilisciNumeroGrad(elem, span,idbis, posg) {
//    let totr = document.getElementById("nrigh").value;
//    //alert(posg + " " + totr);
//    var i;
//    for (i = 0; i <= posg; i++) {
//        let rx = document.getElementById("gd" + i);
//        if (rx) {
//            var btaf = rx.children;
//            if (btaf) { 
//                if (btaf[0].classList.contains("bi-list")) {
//                    btaf[0].classList.remove("bi-list");
//                    btaf[0].classList.add("bi-check2-square")
//                }
//                call_ajax_set_II_vot(rx.value,1);
//            }
//        }
//    }
//    var j;
//    for (j = i; j < totr; j++) {
//        let rx = document.getElementById("gd" + j);
//        if (rx) {
//            var btaf = rx.children;
//            if (btaf) {
//                if (btaf[0].classList.contains("bi-check2-square")) {
//                    btaf[0].classList.remove("bi-check2-square");
//                    btaf[0].classList.add("bi-list")
//                }
//                call_ajax_set_II_vot(rx.value,0);
//            }
//        }
//    }
//}

//function consolidaGrad() {
//   //call_ajax_get_poll_res("revisor", 1, 1); 
//   // confGrad.disabled = true;
//   // location.reload();
//    //creapdf();
//    //alert(globalgrad[0].titleBis);
       
//}

//function creapdf() {
//    var doc = new jsPDF();
//    doc.setFont("Courier");
//    doc.setFontType("normal");
//    doc.setFontSize(12);
//    doc.setTextColor(0, 255, 0);
//    doc.text(20, 20, 'Graduatoria definitiva bisogni');
//    doc.setTextColor(0, 0, 0);
//    doc.text(20, 30, 'Ciao a tutti son un file PDF creato in automatico');
//    doc.text(20, 40, globalgrad[0].ambito + "\t" + globalgrad[0].titleBis + " " + globalgrad[0].nlike);
//    /*  doc.addPage();*/
//    var d = new Date();
//    var st = 'Pubblicata il ' + d;
//    doc.text(20, 80, st);

//    // Save the PDF
//    doc.save('gradBisogni.pdf');
//}

//async function call_ajax_set_II_vot(idbis,val) {
//    var data = new FormData;
//    data.append("idBs", idbis);
//    data.append("val", val);
//    let promo = fetch('ajax/updatebis2vot.php', {
//        method: 'POST',
//        body: data
//    }).then(successResponse => {
//        if (successResponse.status != 200) {
//            return null;
//        } else {
//            return successResponse.json();
//        }
//    },
//        failResponse => {
//            //console.log("promessa fallita con updatebis2vot");
//            return null;
//        }
//    );
//    //console.log('aspetto che la promessa risolva');

//    let result = await promo;
//    //console.log('OK ... promessa risolta ' + result);   
//    //if (!save) {
//    //    refreshTable("Gradtable", result, gradtab);
//    //   /* return 0;*/
//    //}
//    //else localgrad = result;
////    else return (result.length);
////   return grad;
//}


