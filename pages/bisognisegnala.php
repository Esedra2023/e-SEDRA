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

<section class="container mt-3" id="segnalabisogni">
    <h2>Segnalazione Bisogni</h2>
	<blockquote class="blockquote">
    <p class="alert alert-primary col-md-12 text-center" role="alert">
		<?php if($Abisogni['bisAct']) echo('Fase attiva dal '.date_format(date_create($Abisogni['dtStart']),'d/m/Y H:i').' al '.date_format(date_create($Abisogni['dtStop']),'d/m/Y H:i').' - Termina tra: <span id="demo">'.$Abisogni['ggscad'].'</span><br/>');
        else echo 'Fase non attiva';
        if($Abisogni['bisAct'] && !$Abisogni['IamAuthor']) {echo 'Il tuo ruolo non consente la partecipazione in questa fase';}
        ?></p>
        <input type="hidden" id="scadenza" value="<?php echo $Abisogni['dtStop']; ?>" />


  </blockquote>
	
   <hr />
    <div class="row justify-content-evenly">  
		
        <!--<div class="col-md-5 mb-3 mt-3">-->
        <?php if(($Abisogni['IamAuthor'] && $Abisogni['bisAct'])){
                      $_POST['titleAcc']="Segnala nuovo bisogno";
                      //$_POST['fsDisable']=false;	lo abilito da javascript al ready
                      $_POST['confirmButton']=true;
                      $_POST['revisionsect']=false;
                      include(ROOT_PATH . '/include/templatedftbisogno.php');
              }?>
<!--<div class="col-md-7 mt-3">-->     <!--colonna per table-->
        <div class="table-div mb-3 col-lg-8">
            <?php if ($Abisogni['bisAct'] && $Abisogni['IamAuthor']) { ?>

            <div id="infoMessaggedx" class="my-callout d-none"></div>
            <input type="hidden" id="whatcont" value="personal" />
            <table class="table table-responsive table-hover align-middle" id="Bistable">
                <thead>
                    <tr>
                        <th>N</th>
                        <th>Titolo</th>
                        <!--<th>Autore</th>-->
                        <th colspan="1">
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
                            <button type="button" class="linkstylebutton btn btn-outline-primary text-start" data-idbis="<?php echo  $post['idBs']; ?>" value="<?php echo  $post['idBs'];?>">
                                <?php echo $post['titleBis']; ?>
                            </button>
                        </td>
                        <!--<td> io </td>-->
                        <td>
                            <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#myModalBis" title="Elimina" data-idbis="<?php echo $post['idBs']; ?>" value="<?php echo $post['idBs'];?>" name="delete-post">
                                <span class="bi-trash3"></span>
                            </button>
                        </td>
                    </tr>

                    <?php }//foreach
                    }	//if
					if($tot==0) {
						echo '<tr><td colspan="3" class="alert alert-primary col-md-12 mt-3 text-center">Nessun bisogno da visualizzare</td></tr>';
                        $tot++;
                    }
                    //for($i=$tot; $i<8;$i++)
                    //    echo '<tr><td colspan="3"><br/></td></tr>';
                    ?>
                </tbody>
            </table>

            <?php } else {}//end revBis?>
        </div>
	<!--</div>-->		
		</div>
</section>	

<div id="myModalBis" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Attenzione</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Premere conferma per eliminare il bisogno selezionato.</p>
                <p class="text-secondary">
                    <small>Operazione irreversibile!</small>
                </p>
            </div>
			<form method="dialog">
            <div class="modal-footer">
                <button type="button" id="closeModalBis" class="btn btn-primary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" id="deleteBis" value="default" class="btn btn-secondary">Conferma</button>
            </div>
			</form>
        </div>
    </div>
</div>
<script src="js/table.js"></script>
<script src="js/segnalabisogni.js"></script>
