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
<section class="container mt-3" id="revisioneproposte">
    <h2>Revisione Proposte</h2>
	<blockquote class="blockquote">
    <p class="alert alert-primary col-md-12 text-center" role="alert">
        <?php if($Rproposte['revPro']) echo('Fase attiva dal '.date_format(date_create($Rproposte['dtStart']),'d/m/Y').' al '.date_format(date_create($Rproposte['dtStop']),'d/m/Y').' - mancano '.$Rproposte['ggscad'].' giorni alla chiusura.<br/>');
              else echo 'Fase non attiva';
              if($Rproposte['revPro'] && !$Rproposte['IamRev']) {echo 'Il tuo ruolo non consente la partecipazione in questa fase';}
        ?></p>
  </blockquote>
    <hr />
    <div class="row justify-content-evenly">  
	<?php 
				$_POST['titleAcc']="Revisiona Proposta";
                //$_POST['fsDisable']=false;	lo abilito da javascript al ready
				$_POST['confirmButton']=true;
				$_POST['revisionsect']=true;
				include(ROOT_PATH . '/include/templatedftproposta.php');
    ?>	

<!--<hr />-->

    <div class="table-div mb-3 col-lg-7">
        <div id="infoMessaggedx" class="my-callout d-none"></div>
        <input type="hidden" id="whatcont" value="revisor" />
        <table class="table align-middle table-responsive" id="Protable">
            <thead>
                <tr>
                    <th>N</th>
                    <th>Titolo</th>
                    <th>Proposta</th>
                    <th colspan="2">Autore</th>
                    <th>
                        <small>Pubblica</small>
                    </th>
                    <th colspan="2">
                        <small>Azioni</small>
                    </th>
                </tr>
            </thead>

            <tbody>
                <?php $tot=0;
                          if (!empty($posts)){
                              $tot=count($posts);
                              foreach ($posts as $key => $post) { ?>
                <tr>
                    <td>
                        <?php echo $key + 1; ?>
                    </td>
                    <td>
                        <button type="button" class="linkstylebutton btn btn-outline-primary text-start" data-idpro="<?php echo  $post['idPr']; ?>" data-crud="R" value="<?php echo  $post['idPr'];?>">
                            <?php echo $post['titlePrp']; ?>
                        </button>
                    </td>
                    <td>
                        <a href="uploadpdf\<?php echo $post['pdfalleg']; ?>" target="_blank">
                            <?php echo $post['pdforigname']; ?>
                        </a>
                    </td>

                    <td>
                        <?php echo $post['cognome']; ?>
                    </td>
                    <td>
                        <?php echo $post['nome']; ?>
                    </td>
                    <td>
                        <button type="button" class="btn icona" data-bs-toggle="tooltip" title="<?php if ($post['pubblicato'] == true) echo 'Revoca'; else echo 'Pubblica';?>" data-idpro="<?php echo  $post['idPr']; ?>" data-crud=" <?php echo  $post['idPr'];?> " name="publishun-post" <?php if($post['deleted']) echo 'disabled';?> >
                            <span class="bi <?php if ($post['pubblicato']) echo 'bi-display'; else echo 'bi-eye-slash' ?>"></span>
                        </button>
                    </td>
                    <td>
                        <?php if($post['rev']!=null)	$rev=true; else $rev=false;?>
                        <button type="button" class="btn icona" data-bs-toggle="tooltip" title="Revisiona" data-idpro="<?php echo  $post['idPr']; ?>" data-crud="R" value="<?php echo  $post['idPr'];?>" name="revision-post">
                            <span class="bi <?php if(!$rev) echo 'bi-pencil-square'; else echo 'bi-pencil-fill';?>"></span>
                        </button>
                    </td>
                    <td>
                        <?php if($post['deleted']==1)	$canc=true; else $canc=false;?>
                        <button type="button" class="btn icona" data-bs-toggle="tooltip" title="Cancella" data-idpro="<?php echo $post['idPr']; ?>" data-crud="D" value="<?php echo $post['idPr'];?>" name="cancella-post" <?php if ($canc) echo 'disabled';?>>
                            <span class="bi <?php if(!$canc) echo 'bi-shield-x'; else echo 'bi-shield-fill-x';?> "></span>
                        </button>
                    </td>
                </tr>
                <?php }//foreach
                          }	//if
                          if($tot==0) {
                              echo '<tr><td colspan="5" class="alert alert-primary col-md-12 mt-3 text-center">Nessuna proposta da visualizzare</td></tr>';
                          }
                ?>
            </tbody>
        </table>

    </div>
	</div>
</section>	

<script src="js/table.js"></script>
<script src="js/revproposte.js"></script>
