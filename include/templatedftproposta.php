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
		<div class="col-lg-4 mb-3 mt-3">
			
   			<div id="infoMessagge" class="my-callout d-none"></div>
		<!-- Left side accordion -->
        <div class="accordion" id="accordionPro">
            <div class="accordion-item">
                <h2 class="accordion-header" id="sezEditPro">
                    <button class="accordion-button" id="btnAccPro" type="button" disabled data-bs-toggle="collapse" data-bs-target="#collapsePro" aria-expanded="true" aria-controls="collapsePro">
                        <span class="bi bi-briefcase">&nbsp;<?php if(isset($_POST['titleAcc'])) echo $_POST['titleAcc']; else echo "Vedi Proposta"?></span>
                    </button>
                </h2>
                <div id="collapsePro" class="accordion-collapse collapse" aria-labelledby="sezEditPro" data-bs-parent="#accordionPro">
                    <div class="accordion-body">
                		<form id="formInputPro" method="post" enctype="multipart/form-data" class="row g-3 align-items-center" action="<?php echo $_SERVER['PHP_SELF'];?>" >	
                           

                                <!-- validation errors for the form -->

                                <!-- if editing post, the id is required to identify that post -->

                                <?php if (isset($isEditingProposta) && ($isEditingProposta === true)) $hi = $_POST['hidden_post_id'];else $hi=0; ?>
                                <input type="hidden" id="hidden_post_id" name="hidden_post_id" value="<?php echo $hi; ?>" />
                            <fieldset id="fsForm">
                                <fieldset id="fsDatiPro">
                                    <div class="form-floating col-md-12 mb-2">
                                        <input class="form-control" type="text" id="perconto" name="perconto" value="" required />
                                        <label for="perconto" class="form-label">
                                            <span>*</span>Proponente
                                        </label>
                                    </div>
                                    <div class="row mb-2 g-2">
                                        <div class="form-floating col-6">
                                            <input class="form-control" type="email" id="propmail" name="propmail" value="" />
                                            <label for="propmail" class="form-label">Recapito mail</label>
                                        </div>

                                        <div class="form-floating col-6">
                                            <input class="form-control" type="tel" id="propcell" name="propcell" value="" />
                                            <label for="propcell" class="form-label">Recapito Telefonico</label>
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="form-floating mb-2 col-md-12">
                                    <input class="form-control" type="text" id="proTitle" name="proTitle" value="" maxlength="60" required />
                                    <label for="proTitle" class="form-label">
                                        <span>*</span>Titolo (max 60 caratteri)
                                    </label>
                                </div>

                                <!--<label style="float: left; margin: 5px auto 5px;">Featured image</label>
				<input type="file" id="featured_image" name="featured_image" >-->
                                <div class="col-md-12 mb-2">
                                    <!--form-floating-->
                                    <textarea maxlength="2048" class="form-control" name="proBody" id="proBody" cols="30" rows="10" placeholder="*Testo (max 2000 caratteri)" required></textarea>
                                    <!--<label for="proBody" class="form-label">*Testo</label>-->
                                </div>
                                <!--<div class="row mt-3">-->
                                <div class="form-floating col-md-12 mb-3">
                                    <?php

                    include(ROOT_PATH . '/include/templatebischeck.php');
                                    ?>
                                    <!--<select name="bis_id" id="bis_id" multiple >
						<option value="" selected disabled>Seleziona Bisogni correlati</option>
						<?php foreach ($bisogni as $bg): ?>
							<option value="<?php echo $bg['idBs']; ?>" class="ps-2 pe-2">
								<?php echo $bg['titleBis']; ?>
							</option>
						<?php endforeach ?>
					</select>-->
                                    <!--<label class="form-label select-label">Example label</label>-->

                                </div>
                                <div class="form-floating col-12 mb-2">
                                    <input class="form-control" type="text" id="altreinfo" name="altreinfo" value="" />
                                    <label for="altreinfo" class="form-label">Riferimento generico</label>
                                </div>
                                <!--</div>-->
                                <hr />
                                <div id="infoMessagge2" class="my-callout d-none"></div>
                                <div class="input-group col-md-12 form-floating mb-2" id="divallegaPdf">
                                    <input type="file" class="form-control col-md-6 mt-2" name="PDFToUpload" id="PDFToUpload" required /><!--style="opacity:0;"-->
                                    <label class="form-label" for="PDFToUpload" id="lblpdf">Allega Scheda Progettuale</label>
                                </div>
                                <span>
                                    Allegato:&nbsp;<label class="small" id="nomefile"></label>
                                </span>
                                <hr />
                            </fieldset>
                            <fieldset id="fsRev">
                                    <div id="revisioning" class="row g-1 <?php if(!isset( $_POST['revisionsect']) || $_POST['revisionsect']!=1) echo 'd-none';?>">

                                        <div class="form-floating mb-3 col-md-7">
                                            <input class="form-control" type="text" value="" id="NdR" name="NdR" />
                                            <label class="form-label" for="NdR">Note del revisore</label>
                                        </div>
                                        <div class="form-check mb-3 col-md-5 text-md-end">
                                            <input class="btn-check" type="checkbox" value="1" id="publish" name="publish" />
                                            <label class="btn btn-outline-primary" id="lblp" for="publish">
                                                <span id="spp" class="bi bi-display"></span>&nbsp;Pubblicato
                                            </label>
                                        </div>
                                        <!--<div class="col-12 mb-3 text-md-end inputrevisor">                 
						<input type="submit" id="revBis" class="btn btn-primary" name="revBis" value="Revisiona" />
						<button type="reset" id="annullBis" class="btn btn-secondary" name="annulla">Annulla</button>
					</div>-->
                                    </div>
                            </fieldset>
                                    <div>
                                        <!-- if editing post, display the update button instead of create button -->
                                        <div class="col-sm-12 mb-3 text-sm-end inputrevisor">
                                            <input type="reset" class="btn btn-secondary" name="annulla" value="Chiudi" />
                                            <input type="submit" id="confirmPro" class="btn btn-primary <?php if(!isset($_POST['confirmButton']) || $_POST['confirmButton']!=1) echo'd-none'; ?>" name="confirmPro" value="Conferma Dati" />
                                        </div>
                                    </div>

</form>        

                    </div>
                </div>
            </div>
        </div>

</div>

<!--le sue funzioni sono in function.js-->
<script >

    //var fieldsetBis = document.getElementById("fsFormDef");
    var collapsablePro;

    ready(function () {


    let sezB = document.getElementById("collapsePro");
    if (sezB) {
        collapsablePro = new bootstrap.Collapse(sezB, { toggle: false });
		}

	var formPro = document.getElementById("formInputPro");

    //per attivare e disattivare pubblicazione
    var pbu = document.getElementById("publish");
        if (pbu) pbu.addEventListener("click", function () { toggleBtnPub(); /*location.reload();*/ });

	 var canc = document.querySelector("input[type=reset]");        //chiude il form senza salvare
    if (canc) {
        canc.addEventListener("click", function () {
            //console.log('click su annulla');
            resetAccordion(collapsablePro, "btnAccPro", formPro,);
        });
    }
        }) //end ready

//ok per revisione - per pubblicazione 
    function showProposta(res, crud) {
       var pro = res['rec'];
        var recbis = res['bis'];
        //alert(recbis[0]['bisogno']);
        var bis = [];
        for (let i = 0; i < recbis.length; i++) {
            bis.push(recbis[i]['bisogno']);
            //alert(bis[i]);
        }
    document.getElementById("hidden_post_id").value = pro['idPr'];
    document.getElementById("proTitle").value = pro['titlePrp'];
    document.getElementById("proBody").value = pro['textPrp'];
        document.getElementById("perconto").value = pro['proponente'];
        document.getElementById("propmail").value = pro['email'];
        document.getElementById("propcell").value = pro['tel'];
        document.getElementById("altreinfo").value = pro['rifbisgenerico'];
        setBisogniCheck(bis);  
    //AGGIUNGERE CAMPI MANCANTI

    
        var lb = document.getElementById("nomefile");
        lb.classList.add("smalleprimary");
        lb.innerHTML = pro['pdforigname'];
        document.getElementById("divallegaPdf").classList.add("d-none");

        var ndr = document.getElementById("NdR");
        if (ndr)
            ndr.value = pro['rev'];
        var pb = document.getElementById("publish");
        if (pb) {
            pb.checked = pro['pubblicato'];
            toggleBtnPub();
        }
        var fsForm = document.getElementById("fsForm");
        var fsDatiPro = document.getElementById("fsDatiPro");
        var fsRev = document.getElementById("fsRev");
        let cp = document.getElementById("confirmPro");
       
        if (crud == 'U') {
            abilitaFS(fsForm, true);
            abilitaFS(fsDatiPro, true);
            vediPulsante(cp, true);
        }
        if (crud == 'D') {
            abilitaFS(fsForm, false);
            abilitaFS(fsRev, true);
            vediPulsante(cp, true);
        }
        if (crud == 'R') {
            abilitaFS(fsForm, true);
            abilitaFS(fsDatiPro,false);
            abilitaFS(fsRev, true);
            vediPulsante(cp, true);
        }
        if (crud == 'V' || pro['deleted'] == 1) {
            abilitaFS(fsForm, false);
            abilitaFS(fsRev, false);
            vediPulsante(cp, false);
        }

        //if (!mod || pro['deleted'] == 1) {
        //        fsForm.disabled = 'disabled';
        //   if(!cp.classList.contains("d-none"))
        //        cp.classList.add("d-none");
        //}

        collapsablePro.show();
}


//ok per revisione in pub rimane uguale
async function call_ajax_edit_pro(pro, crud) {
    //console.log(pro);
    var pub = 0;
    var data = new FormData;
    data.append("idPr", pro);
    data.append("pub", pub);  //pub 0 PER TUTTE LE PROPOSTE  1 SOLO QUELLI PUBBLICATI
    let promo = fetch('ajax/getsingleproposta.php', {
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
            //console.log("promessa fallita view proposta");
            return null;
        }
    );
    //console.log('aspetto che la promessa risolva');

    let result = await promo;
    if (result) {
       // alert("promessa risolta");
       // console.log(result);
        showProposta(result, crud);
        //accord.show();
    }
}


</script>

