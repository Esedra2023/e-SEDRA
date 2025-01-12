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

$check=$_POST['check'];
$news = $_POST['news'];
$field = $_POST['field'];

?>
<section class="container mt-3" id="pubblicaPGrad">
    <h2>
        <?php echo $Pproposte['nome'];?>
    </h2>
	<blockquote class="blockquote">
    <p class="alert alert-primary col-md-12 text-center" role="alert">
        <?php if($Pproposte['pubPro']) echo('Fase attiva dal '.date_format(date_create($Pproposte['dtStart']),'d/m/Y H:i').' al '.date_format(date_create($Pproposte['dtStop']),'d/m/Y H:i') . ' - Termina tra: <span id="demo">'.$Pproposte['ggscad'].'</span><br/>');
              else echo 'Fase non attiva';
              if($Pproposte['pubPro'] && !$Pproposte['IamRev']) {echo 'Il tuo ruolo non consente la partecipazione in questa fase';}
        ?></p>
        <input type="hidden" id="scadenza" value="<?php echo $Pbisogni['dtStop']; ?>" />
  </blockquote>
    <hr />
    <div class="row justify-content-evenly">  
	<?php 
    $_POST['titleAcc']="Dettagli Proposta";
    $_POST['confirmButton']=false;
    $_POST['revisionsect']=false;
    include(ROOT_PATH . '/include/templatedftproposta.php');
    ?>		

        <div class="table-div mb-3 col-lg-8">
            <div id="infoMessaggedx" class="my-callout d-none"></div>
            <?php 
        if ($Pproposte['pubPro']  && $Pproposte['IamRev']) {
            ?>
            <!--sezione del revisore-->
            <table class="table table-responsive align-middle" id="Protable">
                <caption>Graduatoria definitiva Proposte <?php if($check == 0) echo 'dopo ballottaggio';?></caption>
                <thead>
                    <tr>
                        <th>N</th>
                        <th>Titolo</th>
                        <th>Proposta</th>
                        <th>Votanti</th>
                        <th>Voti</th>
                        <th>
                            <i class="bi bi-heart-fill"></i>
                        </th>
                        <?php if($check == 1){?>
                            <th>Pubblica</th>
                        <?php }?>
                    </tr>
                </thead>

                <tbody>
                    <?php $tot=0;
                if (!empty($posts)){
                $tot=count($posts);
                foreach ($posts as $key => $post) {
                    ?>                  
                        <tr>
                             
                        <td>
                            <?php echo $key + 1; ?>
                        </td>
                        <td>
                            <button type="button" class="linkstylebutton btn btn-outline-primary text-start" data-idpro="<?php echo  $post['idPr']; ?>" value="<?php echo  $post['idPr'];?>">
                                <?php echo $post['titlePrp']; ?>
                            </button>
                        </td>
                        <td>
                            <a href="uploadpdf\<?php echo $post['pdfalleg']; ?>" target="_blank">
                                <?php echo $post['pdforigname']; ?>
                            </a>
                        </td>
                        <td>
                            <?php echo $post['votanti']; ?>
                        </td>
                        <td>
                            <?php echo $post['grade'];  ?>
                        </td>
                        <td>
                            <?php echo $post['nlike'];?>
                        </td>
                       
                            <?php if($check == 1):?>
                            <td>
                            <button type="button" id="gd<?php echo  $key; ?>" class="btn icona" data-bs-toggle="tooltip" data-posg="<?php echo  $key; ?>" title="Pubblica fino a qui" data-idpro="<?php echo  $post['idPr']; ?>"
                                value="<?php echo  $post['idPr'];?>" name="grad-post">
                                <span class="bi <?php if ($post['ingrad']==1) echo 'bi bi-check2-square'; else if($post['ingrad']==0) echo 'bi bi-list' ?>"></span>
                            </button>
                        </td>
                            <?php endif?>
                    </tr>
                    <?php
                              }//end for
                          }//if
                          if($tot==0) {
                              echo '<tr><td colspan="7"class="alert alert-primary col-md-12 mt-3 text-center">Nessuna proposta da visualizzare</td></tr>';
                          }
                    ?>
                </tbody>
              
            </table>
            <input type="hidden" id="nrigh" name="nrigh" value="<?php echo $tot?>" />
            <?php 
            if($news == 1)   {  ?>
            <div class="col-sm-12 mb-3 text-sm-end inputrevisor">
                <button id="confirmVot2" class="btn btn-primary" value="<?php  echo $field;?> ">
                    <span class='bi bi-box-seam-fill'>&nbsp;Conferma graduatoria</span>
                </button>
            </div>
            <?php
            }
            ?>

            <!--<div id="infoMessagged" class="my-callout my-callout-info">
                Dopo aver scelto quanti bisogni pubblicare, contatta l'amministratore<br />
                per la configurazione di una eventuale seconda fase di votazione
            </div>-->
            <?php } else if ($Pproposte['pubPro']  && ! $Pproposte['IamRev']) {
                //include(ROOT_PATH . '/include/templatetablebis.php');
            ?>
                    <table class="table align-middle" id="ProtableU">
                        <caption>Graduatoria definitiva Proposte</caption>
                        <thead>
                            <tr>
                                <th>N</th>
                                <th>Titolo</th>
                                <th>Proposta</th>
                                <th>Voti</th>
                                <th>
                                    <i class="bi bi-heart-fill"></i>
                                </th>
                                <th>Data Revisione</th>
                                <th>Note Revisore</th>
                                <th>Stato</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $tot=0;
                if (!empty($posts)){
                $tot=count($posts);
                foreach ($posts as $key => $post) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo $key + 1; ?>
                                </td>
                                <td>
                                    <button type="button" class="linkstylebutton btn btn-outline-primary text-start" data-idpro="<?php echo  $post['idPr']; ?>" value="<?php echo  $post['idPr'];?>">
                                        <?php echo $post['titlePrp']; ?>
                                    </button>
                                </td>
                                <td>
                                    <a href="uploadpdf\<?php echo $post['pdfalleg']; ?>" target="_blank">
                                        <?php echo $post['pdforigname']; ?>
                                    </a>
                                </td>
                                <td>
                                    <?php echo $post['grade'];  ?>
                                </td>
                                <td>
                                    <?php echo $post['nlike'];?>
                                </td>
                                <?php if($post['utente']==$_SESSION['user']['idUs']) {?>
                                <td>
                                    <?php if($post['dtRev']!=null) echo date("d-m-Y H:i", strtotime($post['dtRev']));else echo ''?>
                                </td>
                                <td>
                                    <?php echo $post['rev']; ?>
                                </td>
    
                                <td>
                                    <?php if($post['pubblicato']) echo "<i class='bi bi-check-square'></i>"; else if($post['deleted']==1) echo "<i class='bi bi-trash3'></i>"; else echo "<i class='bi bi-x-circle-fill'></i>"; ?>
                                </td>
                                <?php } ?>
                            </tr>
                            <?php
                    }//end for
                 }//if
                if($tot==0) {
                    echo '<tr><td colspan="8"class="alert alert-primary col-md-12 mt-3 text-center">Nessuna proposta da visualizzare</td></tr>';
                }
                            ?>
                        </tbody>

                    </table>
                    <?php 
        }//end pubPro ?>
     <?php if (!empty($esclusib)) { ?>
            <table class="table table-responsive align-middle mt-3" id="Esctable">
                <caption>Proposte escluse</caption>
                <thead>
                    <tr>
                        <th>N</th>
                        <th>Titolo</th>
                        <th>Proposta</th>
                        <th>Note</th>
                        <th>Data Rev.</th>
                        <th>Stato</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $tot2=0;
                              $tot2=count($esclusib);
                              foreach ($esclusib as $key => $eb) {
                    ?>
                    <tr>
                        <td>
                            <?php echo $key + 1; ?>
                        </td>
                        <td>
                            <button type="button" class="linkstylebutton btn btn-outline-primary text-start" data-idpro="<?php echo  $eb['idPr']; ?>" value="<?php echo  $eb['idPr'];?>">
                                <?php echo $eb['titlePrp']; ?>
                            </button>
                        </td>
                        <td>
                            <a href="uploadpdf\<?php echo $post['pdfalleg']; ?>" target="_blank">
                                <?php echo $post['pdforigname']; ?>
                            </a>
                        </td>
                        <td>
                            <?php echo $eb['rev']; ?>
                        </td>
                        <td>
                            <?php if($eb['dtRev']!=null) echo date("d-m-Y H:i", strtotime($eb['dtRev']));else echo '';?>
                        </td>
                        <td>
                            <?php if(isset($eb['deleted']) && $eb['deleted']) echo "<i class='bi bi-trash3'></i>"; else if(!$eb['pubblicato']) echo "<i class='bi bi-x-circle-fill'></i>"; else echo "<i class='bi bi-display'></i>"; ?>

                        </td>
                    </tr>
                    <?php
                              }//end for
                          if($tot2==0) {
                              echo '<tr><td colspan="6"class="alert alert-primary col-md-12 mt-3">Non ci sono proposte escluse</td></tr>';
                          }
                    ?>
                </tbody>
            </table>
            <?php } ?>
        </div>

	</div>
</section>	

<script src="js/pubproposte.js"></script>
