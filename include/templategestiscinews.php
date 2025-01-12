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

?>
<?php
$now = new DateTimeImmutable('now', new DateTimeZone('Europe/Rome')); /*new DateTime();*/
$tomorrow = $now->add(new DateInterval('P1D'));
$to1W = $now->add(new DateInterval('P7D'));
$oneWeek = $to1W->format('Y-m-d H:i');
$oneWeek = str_replace(" ", "T", $oneWeek);
$tomorrowS = $tomorrow->format('Y-m-d H:i');
$tomorrowS = str_replace(" ", "T", $tomorrowS);

?>
<!--<div class="col-md-5 mt-3 justify-content-center">-->
<div id="infoMessagge2" class="my-callout d-none"></div>
<div class="accordion mt-3 mb-3" id="accordionNews">
    <div class="accordion-item">
        <h2 class="accordion-header" id="sezInsNews">
            <button class="accordion-button" id="bottoneAccNews" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNews" aria-expanded="true" aria-controls="collapseNews" <?php if (!$news['periodNews'])
                echo 'disabled';
            else
                echo ''; ?>>
                <span class="bi bi-newspaper"></span>
            </button>
        </h2>
        <div id="collapseNews" class="accordion-collapse collapse" aria-labelledby="sezInsNews" data-bs-parent="#accordionNews">
            <div class="accordion-body">
                <form method="post" id="Newsform" action="" class="row g-3 align-items-center">
                    <input type="hidden" id="idNw" name="idNw"
                        <?php
                        if (isset($_POST['idNw'])) {
                            echo "value=" . $_POST['idNw'];
                            if ($_POST['idNw'] != 0)
                                $editn = true;
                            else
                                $editn = false;
                        } else {
                            echo "value=0";
                            $editn = false;
                        } ?> />


                    <div class="form-floating col-md-6">
                        <input class="form-control" id="dtScadNews" type="datetime-local" name="dtEnd" <?php if (isset($_POST['dtEnd']))
                            echo "value=" . str_replace(" ", "T", $_POST['dtEnd']);
                        else if (isset($to1W))
                            echo "value=" . $oneWeek; ?> <?php echo "min=" . $tomorrowS; ?> />

                        <label class="" for="dtScadNews">Scadenza: </label>

                    </div>
                    <div class="form-floating col-md-6">
                        <input class="form-control" id="dtEliNews" type="text" name="settScad" <?php if (isset($news['altridati']))
                            echo "value=" . $news['altridati'];
                        else
                            echo "value=mai"; ?> readonly />
                        <label class="" for="dtEliNews">Eliminazione dopo (settimane): </label>

                    </div>
                    <div class="form-floating col-md-12">
                        <input type="text" class="form-control" id="titNews" name="title" placeholder="Titolo (max 60 caratteri)" <?php if (isset($_POST['title']))
                            echo "value=" . $_POST['title']; ?> maxlength="60" required />

                        <label for="titNews" class="form-label">Titolo (max 60 caratteri):</label>

                    </div>
                    <div class="col-md-12">
                        <!--form-floating-->
                        <pre>
                            <textarea maxlength="1024" class="form-control" name="text" id="newsBody" cols="30" rows="10" value="<?php if (isset($_POST['text']))
                                echo $_POST['text']; ?>" placeholder="Testo (max 1024 caratteri)" required></textarea>
                        </pre>
                        <!--<label for="bisBody" class="form-label">Testo</label>-->
                    </div>

                    <div class="col-12 text-md-end d-none" id="btset4up">

                        <input type="submit" id="aggNews" class="btn btn-primary" name="aggNews" value="Aggiorna News" />
                        <button type="button" id="pubANews" class="btn btn-secondary" name="pubANews">Pubblica News</button>
                        <button type="reset" id="annullANews" class="btn btn-secondary" name="annulla">Annulla</button>
                    </div>


                    <div class="col-12 text-md-end " id="btset4cr">

                        <input type="submit" id="pubNews" class="btn btn-primary" name="pubNews" value="Pubblica News" />
                        <button type="button" id="saveNews" class="btn btn-secondary" name="saveNews">Salva News</button>
                        <button type="reset" id="annullNews" class="btn btn-secondary" name="annulla">Annulla</button>
                    </div>



                </form>
            </div>
        </div>
    </div>
</div>
<!--</div>
<div class="col-md-4 mt-3">-->
<div class=" mt-3 mb-3">
    <table class="table table-responsive table-hover table-sm align-middle" id="newsTable">
        <thead class="table-light">
            <tr>
                <th scope="col">Titolo</th>
                <th scope="col">Scadenza</th>
                <th scope="col">P/S</th>
                <th colspan="2">Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($allnews)): ?>
                <tr>
                    <td colspan="4">Non hai ancora news pubblicate</td>
                </tr>
            <?php else: ?>
                <?php foreach ($allnews as $key => $nn): ?>
                    <tr>
                        <td>
                            <?php echo $nn['title']; ?>
                        </td>

                        <td>
                            <?php if (isset($nn['dtEnd']))
                                echo date("d-m-Y H:i", strtotime($nn['dtEnd'])); ?>
                        </td>
                        <td>
                            <span
                                <?php if ($nn['topublish'] == 1)
                                    echo 'class="bi bi-hdd-stack"';
                                else
                                    echo 'class="bi bi-display"' ?>></span>
                            </td>

                            <td>
                                <button type="button" class="btn icona" data-bs-toggle="tooltip" title="Modifica" data-idnw="<?php echo $nn['idNw']; ?>" id="mn<?php $nn['idNw']; ?>" value="<?php $nn['idNw']; ?>" name="mod-news">
                                <span class="bi-pencil-square"></span>
                            </button>

                        </td>
                        <td>
                            <button type="button" class="btn icona" data-bs-toggle='modal' data-bs-target='#myModalCancelNews' title="Elimina" data-idnw="<?php echo $nn['idNw']; ?>" id="ca<?php $nn['idNw']; ?>" value="<?php $nn['idNw']; ?>" name="canc-news">
                                <span class="bi-trash3"></span>
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
    var collapsableNews;
    var myModalCN;
    var hnw;
    var scad;
    var titolo;
    var corpo;
    var formNWS;
    var pbANws;
    //var pbNws;
    var nwtable;
    var newsToDelete;
    var b4up = document.getElementById('btset4up');
    var b4cr = document.getElementById('btset4cr');

 ready(function () {
    const sezNews = document.getElementById("collapseNews");
    if (sezNews) {
        collapsableNews = new bootstrap.Collapse(sezNews, { toggle: false });
     }

     const hacNews = document.getElementById("sezInsNews");
     if(hacNews)
           hacNews.addEventListener("click", resetEdit);

     hnw = document.getElementById("idNw");
     scad = document.getElementById("dtScadNews");
     titolo = document.getElementById("titNews");
     corpo = document.getElementById("newsBody");


  /* var mycod=document.getElementById("cod");
    mycod.addEventListener('change', () => {
        if (mybtn.disabled) mybtn.removeAttribute('disabled');
    });*/

     var cancn = document.getElementById("annullNews");
     if(cancn)
        cancn.addEventListener("click", call_close_NewsForm);

     var cancn2 = document.getElementById("annullANews");
    if (cancn2)
        cancn2.addEventListener("click", call_close_NewsForm);

     const svNws = document.getElementById("saveNews");
     if (svNws) {
        /* asNws.value = "Salva News";*/
         svNws.addEventListener("click", (e) => {
             //let nome = e.target.value; console.log("nome " + nome);
             //if (e.target.value == "Salva News")
             call_ajax_upcre_news(1);
             e.preventDefault();
             call_ajax_refresh_news_table();
         });
     }
    const agNws = document.getElementById("aggNews");
    if (agNws) {
        agNws.addEventListener("click", (e) => { /*console.log("hnw al clic" + hnw.value);*/ call_ajax_upcre_news(3); e.preventDefault(); call_ajax_refresh_news_table(); });
    }

    const pbNws = document.getElementById("pubNews");
    if (pbNws) {
        pbNws.addEventListener("click", (e) => { /*console.log("hnw al clic" + hnw.value);*/ call_ajax_upcre_news(0); e.preventDefault(); call_ajax_refresh_news_table(); });
     }

   pbANws = document.getElementById("pubANews");
    if (pbANws) {
        pbANws.addEventListener("click", (e) => { /*console.log("hnw al clic" + hnw.value);*/ call_ajax_upcre_news(0); e.preventDefault(); call_ajax_refresh_news_table(); });
    }

     formNWS = document.getElementById("Newsform");

        var mm2 = document.getElementById('myModalCancelNews');
        if (mm2) {
        // alert("prepare modal");
        myModalCN = new bootstrap.Modal(mm2, {
            keyboard: false, backdrop: "static"
        })
        var canOKnews = document.getElementById("confermCancelNews");
        canOKnews.addEventListener("click", function () { call_ajax_delete_news(newsToDelete); call_ajax_refresh_news_table();});

        var annew = document.getElementById("closeModalCancelNews");
        annew.addEventListener("click", function () { resetAccordion(collapsableNews, "bottoneAccNews", formNWS, "Crea news"); asNws.value = "Salva news"; });
        }

        settaTitleAccordion("bottoneAccNews", "Crea news");

         nwtable = document.querySelector('#newsTable');
        //console.log(nwtable);
        nwtable.addEventListener("click", (e) => {
        if (e.target.nodeName != 'BUTTON' && e.target.nodeName != 'SPAN') { /*console.log('- '+e.target);*/  return; }
        //let edt = e.target.closest('.spedit');
        //console.log(edt.dataset.idtpc);
        let elem = e.target;
        if (e.target.nodeName == 'SPAN')
            elem = e.target.parentNode;
            let param = elem.dataset.idnw;
        console.log(elem.name + '  '+param);
        if (elem.name == "mod-news") {
           /* console.log('EDIT ' + param);*/
            call_ajax_edit_news(param);
        }
        else if (elem.name == "canc-news") {
            /*console.log('DELETE ' + param);*/
            newsToDelete = param;
            //myMdT.show();
            //delOK = addEventListener("click", function () { call_ajax_delete_topic(param); });
        }
    });
});


    function resetEdit() {
        /*console.log('ho resettato');*/
        if (b4cr.classList.contains("d-none")) {
            b4cr.classList.remove("d-none");
            b4up.classList.add("d-none");
        }

        newsToDelete = 0;
        hnw.value = 0;
    }

async function call_ajax_delete_news(idnews) {
    var data = new FormData;
   /* console.log(idnews);*/
    data.append("idNw", idnews);
    let promo = fetch('ajax/deletenews.php', {
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
        myModalCN.hide();
        showMessagge(result['success'], "my-callout-warning","infoMessagge2");
    }
}

async function call_ajax_refresh_news_table() {
    var data = new FormData;
    data.append("idUs", 1);
    data.append("idNw", 0);
    let promo = fetch('ajax/getnews.php', {
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
            //console.log("promessa fallita recupera tutte le mie news");
            return null;
        }
    );
    //console.log('aspetto che la promessa risolva');

    let result = await promo;
    //console.log('OK..promessa risolta recupera ambiti' + result);
    if (result) {
        showNewsTable(result);
   }
}


function showNewsTable(righe) {
    //let n = result.length;
    //console.log(n);
    //var tabella = document.getElementById("topicTable");

    var tb = nwtable.getElementsByTagName("tbody");
    for (let i = 0; i < tb.length; i++) {
        if (tb[i] != null)
            tb[i].remove();
    }
    tb = document.createElement("tbody");
   // let righe = result;
    let i;
    for (i = 0; i < righe.length; i++) {
        tr = document.createElement("tr");
        td = document.createElement("td");
        td.innerHTML = righe[i].title;
        tr.appendChild(td);
        td = document.createElement("td");
        righe[i].dtEnd = dateForJS(righe[i].dtEnd);
        td.innerHTML = righe[i].dtEnd;
        tr.appendChild(td);
        td = document.createElement("td");
        if (righe[i].topublish == 1)
            td.innerHTML = "<span class='bi bi-hdd-stack'></span>";
        else
            td.innerHTML = "<span class='bi bi-display'></span>";
        tr.appendChild(td);
        td = document.createElement("td");
        td.innerHTML = "<button type='button' class='btn icona' data-bs-toggle='tooltip' title='Modifica' data-idnw='" + righe[i].idNw + "' value='" + righe[i].idNw + "' name='mod-news'><span class='bi-pencil-square'></span></button>";
        tr.appendChild(td);
        td = document.createElement("td");
        td.innerHTML = "<button type='button' class='btn icona' data-bs-toggle='modal' data-bs-target='#myModalCancelNews' data-idnw=" + righe[i].idNw + "  title='Elimina' value='" + righe[i].idNw + "' name='canc-news'><span class='bi-trash3'></span></button>";
        tr.appendChild(td);
        tb.appendChild(tr);
    }
    nwtable.appendChild(tb);
}

    function dateForJS(data) {
        return data.substring(8,10)+data.substring(4,7)+'-'+data.substring(0,4)+' '+data.substring(11,16);
    }

   function dateForPickerAddT(data) {
        return data.replace(" ", "T");
    }

async function call_ajax_edit_news(ided) {
    settaTitleAccordion("bottoneAccNews", "Modifica news");

    if (b4up.classList.contains("d-none")) {
            b4up.classList.remove("d-none");
            b4cr.classList.add("d-none");
        }
    var data = new FormData;
    data.append("idNw", ided);
        console.log(ided);
    data.append("idUs", 1);
    data.append("edit", 1);
    let promo = fetch('ajax/getnews.php', {
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
            //console.log("promessa fallita edit news");
            return null;
        }
    );
    //console.log('aspetto che la promessa risolva');

    let result = await promo;
    //console.log('OK ... promessa risolta ' + result);
    if (result) {
    //console.log(result[0]['idNw'] + ' ' + result[0]['title'])
        // //mettere i dati nel formresult

        hnw.value = result[0]['idNw'];
        /*console.log("salvo in hnw "+hnw.value);*/
        titolo.value = result[0]['title'];
        scad.value = result[0]['dtEnd'];
        corpo.value = result[0]['text'];
        if (ided != 0) {
        if (result[0]['topublish'] == 1)
            pbANws.removeAttribute('disabled');
            else pbANws.setAttribute('disabled', true);

        }
        /* if (tpc['moreinfo'] == 1) tcheck.checked = 'checked';*/
        //console.log('tutti :' + result[0]);
        ////cambio la label al bottone
    collapsableNews.show(); //apre il form
    }
}

    async function call_ajax_upcre_news(sp) {
        //console.log("hnw appena dentro upcre" + hnw.value);
    datanews = new FormData(formNWS);
        //console.log("hnw " + hnw.value);
    if (hnw.value != 0) {
       //console.log('update');//update
        datanews.append('crud', 'U');     //topublish dovrebbe rimanere così come era, ma non so come era
        datanews.append('topublish', sp);   // 3 da aggiorna 0 da pubblica
    }
    else {
        //console.log('create');
        datanews.append('crud', 'C');
        datanews.append('topublish', sp);   //1 arriva da salva o 0 se arriva da pubblica
        //create
        }
    //stampaFormData(datanews);

    let promo = fetch('ajax/createnews.php', {
        method: 'POST',
        body: datanews
    }).then(successResponse => {
        if (successResponse.status != 200) {
            return null;
        } else {
            return successResponse.json();
        }
    },
        failResponse => {
            //console.log("promessa fallita salva news");
            return null;
        }
    );
    //console.log('aspetto che la promessa risolva');

    let result = await promo;
    //console.log('OK ... promessa risolta ' + result);
    if (result.errors) {
        //console.log(result.errors);
        showMessagge(result['errors'], "my-callout-danger","infoMessagge2");
    } else if (result.success) {
        //console.log(result.success);
        showMessagge(result['success'], "my-callout-warning","infoMessagge2");
        resetAccordion(collapsableNews, "bottoneAccNews", formNWS, "Crea news");
        //asNws.value = "Salva news";
       /* collapsableTopic.hide();*/
    }
}


function call_close_NewsForm() {

    formNWS.reset();
    //asNws.value = "Salva news";
    collapsableNews.hide();
    settaTitleAccordion("bottoneAccNews", "Crea News");

}

</script>
