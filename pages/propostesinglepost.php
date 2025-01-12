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
if (session_status() !== PHP_SESSION_ACTIVE) {
          session_start();
      }
if (!defined('ROOT_PATH'))
          define ('ROOT_PATH', $_SESSION['ini']['ROOT_PATH']);

require_once ROOT_PATH.'/include/functions.php';
require_once ROOT_PATH . '/include/postfunctions.php'; ?>

<?php
if(!isset($_SESSION['VDproposte']) || ! isset($_POST['idPro']) )  forbidden();
$VDproposte=$_SESSION['VDproposte'];
$field="pubblicato";
if($VDproposte['ballottaggio'])
    $field="ingrad";
    //$numprop=$_POST['n'];
	$ib=$_POST['idPro'];
    $anonim=true;       //se sono un ruolo autorizzato non vedo gli autori dei bisogni
    $post =getOnePublishProWithGrade($ib,$field,$anonim);     //true per anonimo
    $lk=0;  //nessun cuore
    //if($post['utente']!=$_SESSION['user']['idUs'])    //se non è un mio post posso mettere il like
    //{
        $like=getMyLikeP($ib,$_SESSION['user']['idUs']);
        if($like!=null)
            $lk=1;  //ho già messo like
        else
            $lk=2;  //posso mettere like
    //}
   //nascondere autore se anonima
   if(!$anonim)
   {
        $autore=getAutore($post['utente']);
   }
   if($VDproposte['blogAct'])
   {
       $comments = getAllCommentsNotCanceled($ib,'blogP','proposta');
       $contCom=count($comments);
       $answers=getAllAnswersNotCanceled($ib,'blogP','proposta');
       $totalCom=count($answers)+$contCom;
       foreach($comments as &$cm)
       {
           if($cm['autore']==$_SESSION['user']['idUs'])
               $cm['nobutton']=true;
           else
               $cm['nobutton']=false;
           $cm['answ']=[];
           foreach($answers as $asn)
           {
               if($asn['idMaster']==$cm['idBl'])
               {
                   if($asn['autore']==$_SESSION['user']['idUs'])
                       $asn['nobutton']=true;
                   else
                       $asn['nobutton']=false;
                   $cm['answ'][]=$asn;
               }
           }
       }
   }
//}
?>

<!--<script defer type="module" src="nested-comments-main/script.js"></script>-->

<section class="container mt-3" id="singlepostp">
    <h2 ><?php if($VDproposte['blogAct']) echo 'Discussione Proposte'; else echo 'Discussione Proposte non attivata'; ?></h2>
    <hr />
    <div class="m-auto sticky-bottom">
        <a href="#" id="backPage" class="btn btn-primary">
            <span class="bi bi-chevron-left"></span>
        </a>
    </div>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 justify-content-evenly mb-3">
      
        <div class="col-sm-6 mb-3">
            <div class="card w-100 ">
                <!--con h-100 si allunga come quella dei commenti-->
                <div class="card-header">
                    <h4>
                        <?php echo "Proposta"?>  
                        <?php //if(isset($post['ambitoName'])) echo $post['ambitoName'] ?>
                    </h4>
                </div>
                <!-- full post div -->
                <div class="card-body">
                    <h5 class="card-title">
                        <?php if(isset($post['titlePrp'])) echo $post['titlePrp']; ?>
                    </h5>
                    <h6 class="card-subtitle mb-2 text-muted">
                        <?php if(isset($autore)) echo $autore; ?>
                    </h6>
                    <p class="card-text">
                        <?php if(isset($post['textPrp'])) echo html_entity_decode($post['textPrp']); ?>
                    </p>
                    <a href="uploadpdf\<?php echo $post['pdfalleg']; ?>" target="_blank" class="card-link">
                        <?php echo $post['pdforigname']; ?>
                    </a>
                    <hr />
                    <ul class="list-group list-group-flush">
                        <?php if (!empty($post['bisas'])){
                              foreach ($post['bisas'] as $bk){?>
                        <li class="list-group-item small"><?php echo $bk['titleBis']; ?></li>
                        <?php }
                              } ?>
                    </ul>
                </div>
                <ul class="list-group list-group-flush">
                    <?php if(isset($VDproposte['IamAuthor']) && $VDproposte['IamAuthor'] && $VDproposte['votAct'] ){
                        //zona votazione
                    ?>
                    <li class="list-group-item text-center">
                        <button type="button" class="btn icona" data-bs-toggle="tooltip" title="LIKE" id="btnlike" value="<?php echo $lk?>" name="btnlike" <?php if($lk<2) echo 'disabled';?>>
                            <span class="bi <?php if($lk<2) echo 'bi-heart-fill'; else echo 'bi-heart';?>"></span>
                        </button>
                    </li>

                    <li class="list-group-item text-center">
                        <input type="hidden" class="provafunz" id="idPr<?php echo  $post['idPr']; ?>" value="<?php echo  $post['idPr']; ?>" />
                        <my-rating class="rating" max-value="10" value="<?php if(isset($post['grade'])) echo $post['grade']; ?>"></my-rating>
                    </li>
                    <?php }  ?>
                    <li class="list-group-item text-start">
                        <form id="formCommento" action="" method="POST">
                            <input type="hidden" id="idOrigin" name="idOrigin" value="<?php echo $post['idPr']; ?>" />
                            <?php if(isset($VDproposte['IamAuthor']) && $VDproposte['IamAuthor'] && $VDproposte['blogAct']){
                                // zona discussione
                            ?>
                            <input type="checkbox" class="btn-check" id="btnCom" name="btnCom" autocomplete="off" data-bs-toggle="collapse" data-bs-target="#collapseRispondi" />
                            <label class="btn btn-outline-primary" for="btnCom">
                                <span class="bi bi-person-rolodex"></span>&nbsp;Aggiungi un commento ...
                            </label>

                            <div class="collapse mb-3 text-end" id="collapseRispondi">
                                <div class="form-floating col-12">
                                    <input type="text" class="form-control mb-2 mt-2" name="testoCommento" id="testoCommento" />
                                    <label for="testoCommento" class="form-label">Commento</label>
                                </div>
                                <input type="submit" id="pubblicaComm" class="btn btn-primary btn-sm" name="pubblicaComm" value="Pubblica" <?php echo "data-idpro=".$post['idPr']; ?> />
                            </div>
                            <?php } ?>
                        </form>
                    </li>

                </ul>
                <div class="card-footer">
                    <small class="text-muted text-end">
                        <?php if(isset($post["dtIns"])) echo date("d-m-Y H:i", strtotime($post["dtIns"])); ?>
                    </small>
                </div>
            </div>
            <div id="infoMessagge" class="my-callout d-none"></div>
            <!--sposto qui le altre proposte simili nello stesso ambito-->
            <?php

                if(isset($post['idPr']))
                    $_POST['idPr']=$post['idPr'];
                if(!isset($comments)){
                    echo "</div> <div class='col-sm-6 mb-3'>";
                }
                $_POST['field']=$field;
                include(ROOT_PATH . '/pages/propostefilteredposts.php');

            ?>


        </div>

        <!--</div>-->
        <!-- // Page wrapper -->
        <!-- post sidebar -->
        <!--<div class="col-md-6 mt-3">-->
       <?php
        if(isset($comments)):?>

        <div class="col-sm-6 mb-3">
            <div class="card w-100 h-100">
                <!--contenitore con la card dei commenti-->
                <div class="card-header">
                    <h4>
                        Commenti:&nbsp;<?php if(isset($totalCom)) echo $totalCom; ?>
                    </h4>
                </div>
                <div class="card-body scrollable h-100" id="cardCommenti">
                    <fieldset id="tutticomm" <?php if(! $VDproposte['IamAuthor']) echo 'disabled';?>>
                        <?php
                        if(isset($comments)){
                        foreach($comments as $cmn){
                            $segncom=false;
                            $riabi=false;
                            if($cmn['stato']==1) {$segncom=true;}
                            else if($cmn['stato']==2) {$riabi=true;}
                        ?>
                        <div class="card w-100 <?php if($segncom) echo 'signal-warning';else if($riabi) echo 'signal-riabi';?>" id="Commento<?php echo $cmn['idBl'];?>">
                            <!--card commento principale-->
                            <div class="card-header">
                                <h6 class="card-subtitle text-end">
                                    <?php echo $cmn['acognome'].' '.$cmn['anome'];
                                    ?>
                                    <span class="text-muted text-end small">
                                        &nbsp;&nbsp;
                                        <?php
                                                echo  date("d-m-Y H:i", strtotime($cmn["dtIns"]));
                                        ?>
                                    </span>
                                </h6>

                            </div>
                            <div class="card-body <?php if($cmn['nobutton']) echo 'my-blog';?>" id="bodycard<?php echo $cmn['idBl'];?>">
                 
                                <div class="col-sm-12 justify-content-start">
                                    <?php echo html_entity_decode($cmn['content']);
                                    ?>
                                </div>
                                <div class="col-sm-12 mt-0" id="accordionRispCom<?php echo $cmn['idBl'];?>">
                                    <div class="row  <?php if($cmn['nobutton']) echo ' d-none';?>">
                                        <div class="col-12 mb-0 text-md-end">
                                            <input type="checkbox" class="btn-check" title="Rispondi" id="toggleRisp<?php echo $cmn['idBl'];?>" autocomplete="off" data-bs-toggle="collapse" data-bs-target="#collapseInsCom<?php echo $cmn['idBl'];?>" <?php if($segncom) echo 'disabled';?> />
                                            <label class="btn btn-outline-primary" for="toggleRisp<?php echo $cmn['idBl'];?>">
                                                <span class="bi bi-reply"></span>
                                            </label>
                                            <input type="checkbox" class="btn-check signalBtn " data-bs-toggle="tooltip" title="Segnala" id="btnSegnala<?php echo $cmn['idBl'];?>" autocomplete="off" <?php if($segncom) echo 'checked disabled'; else if($riabi) echo 'disabled';?>  />
                                            <label class="btn btn-outline-primary" for="btnSegnala<?php echo $cmn['idBl'];?>">
                                                <?php if($segncom) echo "<span class='bi bi-hand-thumbs-down-fill'></span>"; else echo "<span class='bi bi-hand-thumbs-down'></span>"?><!--bi bi-hand-thumbs-down-->
                                            </label>
                                        </div>
                                    </div>

                                    <div id="collapseInsCom<?php echo $cmn['idBl'];?>" class="collapse container mt-1" aria-labelledby="">
                                        <form id="formRisposta<?php echo $cmn['idBl'];?>" action="" method="POST">
                                            <!--<input type="hidden" id="idBl<?php echo $cmn['idBl'];?> " name="idBl" value="<?php echo $cmn['idBl'];?>" />-->
                                            <input type="hidden" name="idOrigin" value="<?php echo $post['idPr']; ?>" />
                                            <div class="form-floating col-12 mb-0">
                                                <input type="text" class="form-control mb-2" name="testoCommento" id="testoRisposta<?php echo $cmn['idBl'];?>" />
                                                <label for="testoRisposta<?php echo $cmn['idBl'];?>" class="form-label">Risposta</label>
                                            </div>
                                            <div class="text-md-end">
                                                <input type="submit" id="rispostaCommento<?php echo $cmn['idBl'];?>" data-idpro="<?php echo $post['idPr']; ?>"
                                                    data-idpadre="<?php echo $cmn['proposta'];?>" class="btn btn-primary btn-sm mb-3 publicBtn" name="rispostaCommento" value="Pubblica" />
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="container">
                                <?php if(count($cmn['answ'])!=0):  foreach($cmn['answ'] as $risp) {
                                          $segnrisp=false;
                                          $riabiR=false;
                                          if($risp['stato']==1) {$segnrisp=true;}
                                          else if($risp['stato']==2){$riabiR=true;}
                                ?>

                                <div class="card w-100 h-100 <?php if($segnrisp) echo 'signal-warning';else if($riabiR) echo 'signal-riabi';?>" id="Risposta<?php echo $risp['idBl'];?>">
                                    <div class="card-header">
                                        <h6 class="card-subtitle ">
                                            <?php echo $risp['anome'].' '.$risp['acognome'];

                                            ?>
                                            <span class="text-muted text-start small">
                                                &nbsp;&nbsp;
                                                <?php
                                              echo  date("d-m-Y H:i", strtotime($risp["dtIns"]));
                                                ?>
                                            </span>
                                        </h6>

                                    </div>
                                    <div class="row card-body <?php if($risp['nobutton']) echo 'my-blog';?>" id="bodycard<?php echo $risp['idBl'];?>">

                                        <div class="col-sm-2 <?php if($risp['nobutton'] || $riabiR) echo ' d-none';?>">
                                            <input type="checkbox" class="btn-check signalBtn" data-bs-toggle="tooltip" title="Segnala" id="btnSegnala<?php echo $risp['idBl'];?>" autocomplete="off" <?php if($segnrisp) echo 'checked disabled'; else if($segncom) echo 'disabled';?> />
                                            <label class="btn btn-outline-primary" for="btnSegnala<?php echo $risp['idBl'];?>">
                                                <?php if($segnrisp) echo "<span class='bi bi-hand-thumbs-down-fill'></span>"; else echo "<span class='bi bi-hand-thumbs-down'></span>"?><!--bi bi-hand-thumbs-down-->
                                            </label>
                                        </div>
                                        <div class="col-sm-10 justify-content-start">
                                            <?php echo html_entity_decode($risp['content']);
                                            ?>
                                        </div>

                                    </div>
                                </div><!--card risposta-->
                                <?php } endif; ?>
                            </div>
                        </div>
                        <?php }
                        }//commenti principali?>
                    </fieldset>
                </div>
            </div>
        </div>
        <?php endif ?>
        <!--</div>-->
    </div>

</section>

<!--<script src="js/funzioniValLU.js"></script>-->
<script src="js/blogfunctions.js"></script>
<script src="js/functions.js"></script>
<script src="js/singlepostP.js"></script>


