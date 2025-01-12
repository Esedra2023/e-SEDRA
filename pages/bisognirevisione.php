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
<section class="container mt-3" id="revisionebisogni">
    <h2>Revisione Bisogni</h2>
	<blockquote class="blockquote">
    <p class="alert alert-primary col-md-12 text-center" role="alert">
        <?php if($Rbisogni['revBis']) echo('Fase attiva dal '.date_format(date_create($Rbisogni['dtStart']),'d/m/Y H:i').' al '.date_format(date_create($Rbisogni['dtStop']), 'd/m/Y H:i') . ' - Termina tra: <span id="demo">'.$Rbisogni['ggscad'].'</span><br/>'. count($posts).' Bisogni segnalati' );
              else echo 'Fase non attiva';
              if($Rbisogni['revBis'] && !$Rbisogni['IamRev']) {echo 'Il tuo ruolo non consente la partecipazione in questa fase';}
        ?></p>
        <input type="hidden" id="scadenza" value="<?php echo $Rbisogni['dtStop']; ?>" />

  </blockquote>
    <hr />
    <div class="row justify-content-evenly">  
	<?php 
				$_POST['titleAcc']="Revisiona bisogno";
                //$_POST['fsDisable']=false;	lo abilito da javascript al ready
				$_POST['confirmButton']=true;
				$_POST['revisionsect']=true;
				include(ROOT_PATH . '/include/templatedftbisogno.php');
    ?>	

<!--<hr />-->

    <div class="table-div mb-3 col-lg-8">
        <div id="infoMessaggedx" class="my-callout d-none"></div>
        <input type="hidden" id="whatcont" value="revisor" />
        <table class="table align-middle table-responsive" id="Bistable">
            <thead>
                <tr>
                    <!--<th>N</th>-->
                    <th>Id</th>
                    <th>Titolo</th>
                    <th>Amb.</th>

                    <th>Autore</th>
                    <th>
                        <small>Rev.</small>
                    </th>
                    <th>
                        <small>Pubb.</small>
                    </th>
                    <th>
                        <small>Canc.</small>
                    </th>

                </tr>
            </thead>

            <tbody>
                <?php $tot=0;
                          if (!empty($posts)){
                              $tot=count($posts);
                              foreach ($posts as $key => $post) { ?>
                <tr>
                    <!--<td>
                        <?php //echo $key + 1; ?>
                     </td>-->
                    <td>
                        <?php echo $post['idBs'];
                          ?>
                    </td>

                    <td>
                        <button type="button" class="linkstylebutton btn btn-outline-primary text-start" data-idbis="<?php echo  $post['idBs']; ?>" data-crud="R" value="<?php echo  $post['idBs'];?>">
                            <?php echo $post['titleBis']; ?>
                                </button>
                          <br>
                                        <span class="small"><?php 
                                            echo $post['textBis']; ?></span>
                    </td>
                    <td>
                          <?php echo $post['ambito']; ?>
                    </td>

                    <td>
                        <span class="small"><?php echo $post['cognome'].' '. $post['nome']; ?></span>
                    </td>
                    <!--<td>
                        <?php //echo $post['nome']; ?>
                    </td>-->
                    <td>
                            <?php if ($post['dtRev'] != null)
                                  {
                                    //bottone disabilitato il tooltip non si vede
                                    $rev = true;
                                    //$tool="Revisionato";
                                  }
                            else
                                  {
                                    $rev = false; 
                                    //$tool="Da revisionare";
                                  }?>
                              
                            <!--<button type="button" disabled class="btn icona" data-bs-toggle="tooltip" title="<?php echo $tool;?>" data-idbis="<?php echo $post['idBs']; ?>" data-crud="R" value="<?php echo $post['idBs']; ?>" name="revision-post">-->
                                <span class="bi <?php if (!$rev)
                                    echo 'bi-pencil-square';
                                else
                                    echo 'bi-pencil-fill'; ?>"></span>
                        <!--</button>-->
                    </td>

                    <td>
                        <button type="button" class="btn icona" data-bs-toggle="tooltip" title="<?php if ($post['pubblicato'] == true) echo 'Revoca'; else echo 'Pubblica';?>" data-idbis="<?php echo  $post['idBs']; ?>" data-crud=" <?php echo  $post['idBs'];?> " name="publishun-post" <?php if($post['deleted']) echo 'disabled';?> >
                            <span class="bi <?php if ($post['pubblicato']) echo 'bi-display'; else echo 'bi-eye-slash' ?>"></span>
                        </button>
                    </td>
                   
                    <td>
                        <?php if($post['deleted']==1)	$canc=true; else $canc=false;?>
                        <button type="button" class="btn icona" data-bs-toggle="tooltip" title="Cancella" data-idbis="<?php echo $post['idBs']; ?>" data-crud="D" value="<?php echo $post['idBs'];?>" name="cancella-post" <?php if ($canc) echo 'disabled';?>>
                            <span class="bi <?php if(!$canc) echo 'bi-shield-x'; else echo 'bi-shield-fill-x';?> "></span>
                        </button>
                    </td>
                </tr>
                <?php }//foreach
                          }	//if
                          if($tot==0) {
                              echo '<tr><td colspan="7" class="alert alert-primary col-md-12 mt-3 text-center">Nessun bisogno da visualizzare</td></tr>';
                          }
                          //for($i=$tot; $i<8;$i++)
                          //    echo '<tr><td colspan="5"><br/></td></tr>';
                ?>
            </tbody>
        </table>

    </div>
	</div>
</section>	

<script src="js/table.js"></script>
<script src="js/revbisogni.js"></script>
