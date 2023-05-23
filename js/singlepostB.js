
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

var defaultpage = 21;
var datipost = new FormData();


ready(function () {
    var bisa = document.getElementsByClassName("bisassociati");
    if (bisa) {
        //alert("lungh " + bisa.length);
        for (let i = 0; i < bisa.length; i++)
            bisa[i].addEventListener("mouseover", function (e) {
                e.target.style.cursor = "pointer";
                //e.preventDefault();
            });
    }
    

    var rat = document.getElementsByClassName("rating");
    if (rat.length!=0) {
       // alert("rating trovato "+rat[0]+" "+this);
        rat[0].addEventListener("click", (e) => { valGraduatoriaLU(e.target,defaultpage,104); })
    }

    var linkbis = document.getElementsByClassName("linkstylebutton");
    for (let i = 0; i < linkbis.length; i++)
        linkbis[i].addEventListener("click", function (e) {
            e.preventDefault();
            refreshSinglePost(e.target.dataset.idbis,defaultpage,104,0);
        });

    var likebtn=document.getElementById("btnlike");
    if (likebtn) { 
        likebtn.style.color = "var(--bs-primary)";
        likebtn.addEventListener("click", function (e)
        {
            e.preventDefault();
            var bis = document.getElementById("idOrigin").value;
            call_ajax_set_like(bis,'B');
            updateHearth(likebtn);
        });
}
    var bback = document.getElementById("backPage");
    bback.addEventListener("click", function () { goPersonalPost('B'); } );

    var btnpubCom = document.getElementById("pubblicaComm");
    if (btnpubCom) { 
        btnpubCom.addEventListener("click", function (e) {
            e.preventDefault();
            var savelem = e.target;
           /* alert("target "+savelem);*/
            call_ajax_set_comment("formCommento", 0, null, savelem.dataset.idbis,'B');
     });
    }
    var sig = document.querySelectorAll(".signalBtn");
    if (sig) { 
    for (let i = 0; i < sig.length; i++)
        sig[i].addEventListener("click", function () {
            var id = this.getAttribute("id");
            this.disabled = true;
            var nome = "btnSegnala";
            var idBl = id.substr(nome.length, id.length);
            //console.log(idBl);
            var bri = document.getElementById("toggleRisp" + idBl);
          /*  alert('bri ' + bri);*/
            if(bri) bri.disabled = true;
            call_ajax_segnala_commento(idBl,'B');
            var card = document.getElementById("Commento" + idBl);
           /* alert("cerco commento n " + idBl);*/
            if (!card) { 
                /*alert("cerco risposta n " + idBl);*/
                card = document.getElementById("Risposta" + idBl);
            }
            card.classList.add("signal-warning");
        });
    }
    var btnpub = document.querySelectorAll(".publicBtn");
    if (btnpub) { 
        for (let i = 0; i < btnpub.length; i++)
            btnpub[i].addEventListener("click", function (e) {
                e.preventDefault();
                var id = this.getAttribute("id");
                var nome = "rispostaCommento";
                var idBl = id.substr(nome.length, id.length);
                call_ajax_set_comment("formRisposta" + idBl, 1, idBl, e.target.dataset.idbis,'B');
            });
    }
});
