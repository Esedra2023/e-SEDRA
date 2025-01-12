
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
            "type": "link",
            "title": [1, "pdforigname"],
            "link": [1, "pdfalleg"],
        },
        {
            "type": "text",
            "text": [1, "titlePrp"]
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

ready(function () {
    var collapsablePro;

    var accbt = document.getElementById("bottoneAccGrad");
    //if(accbt)
    //    accbt.addEventListener("click", function () {call_ajax_get_poll_res("revisor",0, 1);  });

    confv2 = document.getElementById("confirmVot2");
    if (confv2)
        confv2.addEventListener("click", goActivityPage);

    let sezB = document.getElementById("collapsePro");
    if (sezB) {
        collapsablePro = new bootstrap.Collapse(sezB, { toggle: false });
    }
 


    var lvwb = document.querySelectorAll('.linkstylebutton');
    if (lvwb) {
        for (let i = 0; i < lvwb.length; i++) {
            lvwb[i].addEventListener("mouseover", (e) => { e.target.style.cursor = 'pointer'; });
            lvwb[i].addEventListener("click", (e) => { let idProposta = e.target.dataset.idpro; call_ajax_edit_pro(idProposta,'V'); });
        }
    }

    //var pbu = document.getElementById("publish");
    //if (pbu) pbu.addEventListener("click", toggleBtnPub);

    var protable = document.querySelector('#Protable');
    if (protable) {
        protable.addEventListener("click", (e) => {
            if (e.target.nodeName != 'BUTTON' && e.target.nodeName != 'SPAN') { return; }

            let elem = e.target;
            let span = null;
            //if (elem.classList.contains("linkstylebutton")) {
            //    let idProposta = elem.dataset.idpro;
            //    call_ajax_edit_pro(idProposta, 'V');      //false disabilita i campi
            //}
            //else {
                if (e.target.nodeName == 'SPAN') {
                    elem = e.target.parentNode;
                    span = e.target;
                }
                let idpro = elem.dataset.idpro;
                if (elem.name == "grad-post") {
                    //console.log('AGGIUNGI/TOGLI GRAD ' + idpro);  
                    //alert(elem);
                    let posg = elem.dataset.posg;
                    stabilisciNumeroGrad('P',posg);
                }
          // }//else
        });
    }
    var fortimer = document.getElementById("scadenza");
    var dataFine = fortimer.value;
    dataFine = dataFine.replace(" ", "T");
    console.log(dataFine);
    avviaContoAllaRovescia(dataFine, "demo");
}); //end ready





