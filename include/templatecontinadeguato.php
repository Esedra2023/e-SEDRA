<?php

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

$itable=$_POST['itable'];
?>

<!--<div class="col-md-5 mt-3 justify-content-center">-->


    <div class="accordion mt-3 mb-3" id="accordionCIna">
        <div class="accordion-item">
            <h2 class="accordion-header" id="sezContIna">
                <button class="accordion-button collapsed" id="bottoneAccCIna" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCIna" aria-expanded="true" aria-controls="collapseCIna" disabled>
                    <span class="bi bi-hand-thumbs-down-fill"></span>&nbsp;Dettagli
                </button>
            </h2>
            <div id="collapseCIna" class="accordion-collapse collapse" aria-labelledby="sezContIna" data-bs-parent="#accordionCIna">
                <div class="accordion-body" >
                    <div id="vediCI">

                    </div>                   
                    <hr />
                    <div id="infoMessagge3" class="my-callout d-none"></div>
                    <form method="post" id="CInaform" action="" class="row g-3 align-items-center">

                        <div class="col-12 text-md-end">
                            <div class="form-floating col-12 mb-3">
                                <input type="text" class="form-control mb-2" name="ndr" id="ndr" required/>
                                <label for="ndr" class="form-label">* Nota del Revisore</label>
                            </div>
                            <input type="submit" id="saveCIna" class="btn btn-primary" name="saveCIna" value="Riabilita" />
                            <input type="submit" id="delCIna" class="btn btn-secondary" name="delCIna" value="Cancella" /> <!--data-bs-toggle='modal' data-bs-target='#myModalCancelCIna'--> 
                            <button type="reset" id="annullCIna" class="btn btn-secondary" name="annulla">Annulla</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
   
<!--</div>
<div class="col-md-4 mt-3">-->
    <input type="hidden" id="itable" value="<?php echo $itable;?>" />
    <div class="table-responsive mt-3 mb-3">
        <table class="table table-hover table-responsive table-sm align-middle" id="CInaTable">
            <thead class="table-light">
                <!--<tr>
                    <th colspan="2">Segnalazione</th>
                    <th colspan="4">Commento</th>
                </tr>-->
                <tr>
                    <th class="small">
                            data Segn.
                    </th>
                    <th class="small">
                            Segn. da
                    </th>
                    <th class="small">
                            Commento
                    </th>
                    <!--<th scope="col">
                        <small>
                            Autore
                        </small>
                    </th>-->
                    <th colspan="2"class="small">
                            Contesto
                    </th>                  
                    <th colspan="1"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($allCIna)): ?>
                <tr>
                    <td colspan="6">Non ci sono segnalazioni da visionare</td>
                </tr>
                <?php else: ?>
                <?php foreach ($allCIna as $key => $cia):?>
                <tr id="rina<?php echo $cia['idSC']; ?>">
                    <td>
                        <?php  if(isset($cia['dtIns'])) echo date("d-m-Y H:i", strtotime($cia['dtIns']));?>
                    </td>
                    <td>
                        <?php echo $cia['cognome'].' '.$cia['nome'];?>
                    </td>
                    <td class="signal-warning">

                        <?php echo $cia['content'];?>
                    </td>
                   
                    <td>
                        <!--class="text-truncate"-->
                        <?php echo $cia['rife'];?>
                    </td>
                    <td>
                        <?php echo $cia['title'];?>
                    </td>
                    <td>
                        <button class="btn btn-primary gestina" data-idcina="<?php echo $cia['idSC']; ?>" id="gest<?php $cia['idSC']; ?>" value="<?php $cia['idSC'];?>" name="gest-CIna">
                            Gestisci
                        </button>

                    </td>
                </tr>
                <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>

    </div>
<!--</div>-->


<script>
    var collapsableCIna;
    var myModalCIna;  
    var formCIna;
    var CInatable;
    var CInaToedit;
    var itable;

 ready(function () {
    const sezCIna = document.getElementById("collapseCIna");
    if (sezCIna) {
            collapsableCIna = new bootstrap.Collapse(sezCIna, { toggle: false });
    }
 
    var btngest = document.querySelectorAll(".gestina")
     for (let i = 0; i < btngest.length; i++)
         btngest[i].addEventListener("click", (e) => {
             var elem = e.target;
             let param = elem.dataset.idcina;
             CInaToedit = param;
             //alert('click gestina ' + param);
             call_ajax_view_CIna(param, itable);
             collapsableCIna.show();
         });

     formCIna = document.getElementById("CInaform");

     itable = document.getElementById("itable").value;


        var mm3 = document.getElementById('myModalCancelCIna');
        if (mm3) {
        // alert("prepare modal");
            myModalCIna = new bootstrap.Modal(mm3, {
            keyboard: false, backdrop: "static"
        })
        var canOKCIna = document.getElementById("confermCancelCIna");
            canOKCIna.addEventListener("click", function () { myModalCIna.hide(); call_ajax_delete_CIna(CInaToedit, itable); window.location.reload();/*call_ajax_refresh_CIna_table();*/});

        var anCIna = document.getElementById("closeModalCancelCIna");
        anCIna.addEventListener("click", function () { resetAccordion(collapsableCIna, "bottoneAccCIna", formCIna, "Dettagli"); /*suNws.value = "Pubblica news";*/ });
        }
     var anc = document.getElementById("annullCIna");
     if (anc) { 
     anc.addEventListener("click", function () {
        /* alert('clic su annulla');*/
         resetAccordion(collapsableCIna, "bottoneAccCIna", formCIna, "Dettagli");
     });
     }

     var abiCIna = document.getElementById("saveCIna");
     if (abiCIna)
         abiCIna.addEventListener("click", function (e) { call_ajax_edit_CIna(CInaToedit,itable); /*e.preventDefault();*/ });
        //settaTitleAccordion("bottoneAccNews", "Inserisci news");
    var dCIna = document.getElementById("delCIna");
     if (dCIna)
         dCIna.addEventListener("click", (e) => { predelete(); e.preventDefault(); });
    
     var deltutti = document.getElementById("delCIdel");
     if (deltutti) {
         deltutti.addEventListener("click", function () {
             call_ajax_truedelete(3);   //3 commenti impropri cancellati logicamente dai revisori
             resetAccordion(collapsableCIdel, "bottoneAccCIdel", formCIdel, "Commenti Cancellati");
         });
     }

});

    function predelete() {
        var ndr = document.getElementById("ndr");
        if (ndr.value != "") {
                myModalCIna.show();

        } else showMessagge("Nota del revisore mancante", "my-callout-danger", "infoMessagge3");
    }

async function call_ajax_delete_CIna(idCIna,itable) {
    var data = new FormData(formCIna);
    data.append("idSC", idCIna);
    data.append("crud", 'D');
    data.append("itable", itable);
    let promo = fetch('ajax/gestiscisegnalazione.php', {
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
            //console.log("promessa fallita delete news");
            return null;
        }
    );
    //console.log('aspetto che la promessa risolva');

    let result = await promo;
    //console.log('OK..promessa risolta ' + result);

    if (result['success']) {
            
            //console.log(result['success']);
            showMessagge(result['success'], "my-callout-warning","infoMessagge3");
            resetAccordion(collapsableCIna, "bottoneAccCIna", formCIna, "Dettagli");
            document.getElementById("rina" + result['idSC']).classList.add('d-none');

    }
    else  showMessagge(result['error'], "my-callout-danger", "infoMessagge3");
}

// async function call_ajax_getCommentiCancellati() {
//    //var data = new FormData(formCIna);
//    //data.append("idSC", idCIna);
//    //data.append("crud", 'D');
//    //data.append("itable", itable);
//    let promo = fetch('ajax/getcommenticancellati.php', {
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
//            console.log("promessa fallita delete news");
//            return null;
//        }
//    );
//    console.log('aspetto che la promessa risolva');

//    let comcanc = await promo;
//    console.log('OK..promessa risolta ');

//     if (comcanc) {
//         refreshComCanc(comcanc);
//            //console.log(comcanc);
//    }
//}
 
    async function call_ajax_view_CIna(idCIna,itable) {
        var data = new FormData;
        data.append("idSC", idCIna);
        data.append("itable", itable);
        //alert(idCIna + " " + itable);
    let promo = fetch('ajax/vedicontenutosegnalato.php', {
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
            //console.log("promessa fallita view contenuto");
            return null;
        }
    );
    //console.log('aspetto che la promessa vedi contenuto segnalato risolva');

    let result = await promo;
    //console.log('OK..promessa risolta ' + result);

    if (result) {
        //console.log(result);
        vedicontenutoIna(result);
        collapsableCIna.show();
    }
}


    function vedicontenutoIna(result) {
        var divc = document.getElementById('vediCI')
        divc.innerHTML = "";
        var t = document.createElement("h5");
        t.innerHTML = result['title'];
        divc.appendChild(t);

        var card = document.createElement("div");
            card.classList.add("card");
        card.classList.add("w-100");
        if (result['mas'] != null) {
            //alert(result['mas']['dtIns']);
            var ch = creaCardHeader(result['mas']['nome'], result['mas']['cognome'], result['mas']['dtIns']);
            card.appendChild(ch);
            var cb = creaCardContent(result['mas']['content']);
            card.appendChild(cb);

            let ct = document.createElement("div");
            ct.classList.add("container");
            let car = document.createElement("div");
            car.classList.add("card");
            car.classList.add("w-100");
            car.classList.add("signal-warning");
            let cch = creaCardHeader(result['nome'], result['cognome'], result['dtIns']);
                 car.appendChild(cch);
            let ccb = creaCardContent(result['content']);
                car.appendChild(ccb);
            ct.appendChild(car);
            card.appendChild(ct);
        }
        else
        { 
            var ch, cb, cri;
            card.classList.add("signal-warning");
                //alert(result['dtIns']);
            ch = creaCardHeader(result['nome'], result['cognome'], result['dtIns']);
            card.appendChild(ch);
            cb = creaCardContent(result['content']);
            card.appendChild(cb);
            var ct = document.createElement("div");
            ct.classList.add("container");
            for (let i = 0; i < result['vrisp'].length; i++) {
                cri = document.createElement("div");
                cri.classList.add("card");
                cri.classList.add("w-100");
                ch= creaCardHeader(result['vrisp'][i]['nome'], result['vrisp'][i]['cognome'], result['vrisp'][i]['dtIns']);
                     cri.appendChild(ch);
                cb = creaCardContent(result['vrisp'][i]['content']);
                     cri.appendChild(cb);
                ct.appendChild(cri);
            }
            card.appendChild(ct);
        }

        divc.appendChild(card);
    }

    function creaCardHeader(nome, cognome, data) {
        //var dt = data.substring(0, 16);
        //var date = new Date(data);

        //var dateFormat = (date.getDate().length != 2 ?"0" + date.getDate() : date.getDate())+ "-" +((date.getMonth()+1).length != 2 ? "0" + (date.getMonth() + 1) : (date.getMonth()+1)) + "-" + date.getFullYear() ;
            var ch=document.createElement("div");
            ch.classList.add("card-header");
            ch.innerHTML="<h6 class='card-subtitle text-end'>"+nome+" "+cognome+"<span class='text-muted text-end small'> &nbsp;&nbsp;"+
        data.substring(0, 16) +" </span></h6>";
        return ch;
    }

    function creaCardContent(cont) {
    var cb = document.createElement("div");
    /* if (war) {cb.classList.add("card-body"); }*/
        cb.classList.add("card-body");
        cb.innerHTML=cont;
    return cb;
    }
//non usata
//async function call_ajax_refresh_CIna_table(itable) {
//    //var data = new FormData;
//    //data.append("idAm", idcanc);
//    var url = 'ajax/getcina.php';
//    if (itable == 'P')
//        url = 'ajax/getcinaP.php';
//    let promo = fetch(url, {
//        method: 'POST',
//       /* body: data*/
//    }).then(successResponse => {
//        if (successResponse.status != 200) {
//            return null;
//        } else {
//            return successResponse.json();
//        }
//    },
//        failResponse => {
//            console.log("promessa fallita recupera contenuto inadeguato");
//            return null;
//        }
//    );
//    console.log('aspetto che la promessa risolva');

//    let result = await promo;
//    //console.log('OK..promessa risolta recupera ambiti' + result);
//    if (result) {
//        showCInaTable(result);
//   }
//}


//function showCInaTable(righe) {

//    var tb = CInatable.getElementsByTagName("tbody");
//    for (let i = 0; i < tb.length; i++) {
//        if (tb[i] != null)
//            tb[i].remove();
//    }
//    tb = document.createElement("tbody");
//   // let righe = result;
//    let i;
//    for (i = 0; i < righe.length; i++) {
//        tr = document.createElement("tr");
//        td = document.createElement("td");
//        td.innerHTML = righe[i].title;
//        tr.appendChild(td);
//        td = document.createElement("td");
//        td.innerHTML = righe[i].dtEnd;
//        tr.appendChild(td);
//        td = document.createElement("td");
//        td.innerHTML = "<button type='button' class='btn icona' data-bs-toggle='tooltip' title='Modifica' data-idnw='" + righe[i].idNw + "' value='" + righe[i].idNw + "' name='mod-news'><span class='bi-pencil-square'></span></button>";
//        tr.appendChild(td);
//        td = document.createElement("td");
//        td.innerHTML = "<button type='button' class='btn icona' data-bs-toggle='modal' data-bs-target='#myModalCancelNews' data-idnw=" + righe[i].idNw + "  title='Elimina' value='" + righe[i].idNw + "' name='canc-news'><span class='bi-trash3'></span></button>";
//        tr.appendChild(td);
//        tb.appendChild(tr);
//    }
//        CInatable.appendChild(tb);
//}


async function call_ajax_edit_CIna(idsc,itable) {
    var data = new FormData(formCIna);
    data.append("idSC", idsc);
    data.append("crud", 'U');
    data.append("itable", itable);
    let promo = fetch('ajax/gestiscisegnalazione.php', {
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
            //console.log("promessa fallita carica bisogno segnalato");
            return null;
        }
    );
    //console.log('aspetto che termini il caricamento');

    let result = await promo;
    //console.log('OK ... promessa risolta ' + result);
    if (result) {
        //1 tutto ok aggiornamento effettuato
        //alert("ho il risultato");
        //console.log("da edit :" + result);
        //0 nota revisore mancante
        if (result['error']) {
            showMessagge(result['error'], "my-callout-danger", "infoMessagge3");
            //console.log(result['error']);    
        }
        else {
            //console.log(result['success']);
            showMessagge(result['success'], "my-callout-warning","infoMessagge3");
            resetAccordion(collapsableCIna, "bottoneAccCIna", formCIna, "Dettagli");
            document.getElementById("rina" + result['idSC']).classList.add('d-none');
        }
    }
}

function call_close_CInaForm() {
    formCIna.reset();
    collapsableCIna.hide();
    settaTitleAccordion("bottoneAccCIna", "Dettagli ...");
}

</script>