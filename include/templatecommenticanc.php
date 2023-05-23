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
if($_SESSION['user']['idUs']==1) {
          $comcanc=wr_getCommentCanceled();
          if($comcanc !=null && count($comcanc)!=0){?>
<div class="accordion mt-3 mb-3" id="accordionCIdel">
    <div class="accordion-item">
        <h2 class="accordion-header" id="sezContDel">
            <button class="accordion-button collapsed" id="bottoneAccCIdel" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCIdel" aria-expanded="true" aria-controls="collapseCIdel">
                <span class="bi bi-x-circle-fill"></span>&nbsp;Commenti Cancellati
            </button>
        </h2>
        <div id="collapseCIdel" class="accordion-collapse collapse" aria-labelledby="sezContDel" data-bs-parent="#accordionCIdel">
            <div class="accordion-body table-responsive ">
                <table class="table table-responsive table-hover table-sm align-middle" id="CInaTable">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">data Ins.</th>
                            <th scope="col">Autore</th>
                            <th scope="col">Commento</th>
                            <th scope="col">data Rev.</th>
                            <th scope="col">Revisore</th>
                            <th scope="col">Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($comcanc as $key => $ccm){
                                  $dti=substr($ccm['dtIns'],0,10);
                        ?>
                        <tr>
                            <td>
                                <input type="date" class="form-control-plaintext" value="<?php  echo $dti;?>" readonly disabled />
                            </td>
                            <td>
                                <?php echo $ccm['acognome'].' '.$ccm['anome'];?>
                            </td>
                            <td>
                                <?php echo $ccm['content'];?>
                            </td>
                            <td>
                                <input type="date" class="form-control-plaintext" value="<?php  if(isset($ccm['dtRev'])) echo $ccm['dtRev'];?>" readonly disabled />
                            </td>
                            <td>
                                <?php echo $ccm['revcogn'].' '.$ccm['revnome'];?>
                            </td>
                            <td>
                                <?php echo $ccm['note'];?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <hr />
                <div id="infoMessagge4" class="my-callout d-none"></div>
                <form method="post" id="CIdelform" action="" class="row g-3 align-items-center">

                    <div class="col-12 text-md-end">
                        <div class="form-floating col-12 mb-3">
                            <input type="text" class="form-control mb-2 signal-warning" name="mes" id="mes" readonly value="Premere il tasto Rimuovi per cancellare definitivamente tutti i commenti di questa tabella" />
                            <!--<label for="mes" class="form-label"></label>-->
                        </div>
                        <input type="submit" id="delCIdel" class="btn btn-primary" name="delCIna" value="Rimuovi" /><!--data-bs-toggle='modal' data-bs-target='#myModalCancelCIna'-->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php }
      }?>

<script>
         ready(function () {


        //var mm3 = document.getElementById('myModalCancelCIna');
        //if (mm3) {
        //// alert("prepare modal");
        //    myModalCIna = new bootstrap.Modal(mm3, {
        //    keyboard: false, backdrop: "static"
        //})
        //var canOKCIna = document.getElementById("confermCancelCIna");
        //    canOKCIna.addEventListener("click", function () { myModalCIna.hide(); call_ajax_delete_CIna(CInaToedit, itable); locatiom.reload();/*call_ajax_refresh_CIna_table();*/});

        //var anCIna = document.getElementById("closeModalCancelCIna");
        //anCIna.addEventListener("click", function () { resetAccordion(collapsableCIna, "bottoneAccCIna", formCIna, "Dettagli"); /*suNws.value = "Pubblica news";*/ });
        //}

     var deltutti = document.getElementById("delCIdel");
     if (deltutti) {
         deltutti.addEventListener("click", function () {
             call_ajax_truedelete(3);   //3 commenti impropri cancellati logicamente dai revisori
             resetAccordion(collapsableCIdel, "bottoneAccCIdel", formCIdel, "Commenti Cancellati");
         });
     }

});

      async function call_ajax_truedelete(type) {
        //alert("in true delete");
        var data = new FormData();
        data.append("type", type);
        //data.append("itable", itable);
        let promo = fetch('ajax/deleteblog.php', {
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
                //console.log("promessa fallita delete  commenti");
                return null;
            }
        );
        //console.log('aspetto che la promessa risolva');

        let result = await promo;
        //console.log('OK..promessa risolta ' + result);

        if (result) {
            //console.log(result);
        }
    }
</script>