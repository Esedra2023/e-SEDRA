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
    //ATTENZIONE AVEVA adminfunction senza functions
    require_once ROOT_PATH.'/include/functions.php';
    require_once ROOT_PATH . '/adminsez/admin/includes/utilfunctions.php' ;

 if(!isset($_POST['page'])) forbidden();

 $acti=associaRuoliattivita();
?>
<?php

if(isset($_SESSION['template']))
    unset($_SESSION['template']);
if(!isset($_SESSION['allRoles']))
{
        include_once ROOT_PATH.'/include/getruoliall.php';
}

?>

<section class="container" id="activities">
    <h2 class="page-title mt-3">Configurazione attivit&agrave;</h2>
    <hr />
    <div class="row justify-content-evenly">
        <div class="col-md-4 mt-3">
            <div class="accordion" id="accordionAct">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="sezGestAct">
                        <?php if(isset($_SESSION['formacti'])) $facti=$_SESSION['formacti']; else $facti=NULL;?>
                        <button class="accordion-button" type="button" id="bottoneAccordion" aria-expanded="true" aria-controls="collapseAct">
                            <!--data-bs-toggle="collapse" data-bs-target="#collapseAct"-->
                            <span class="bi bi-gear"></span>
                        </button>
                    </h2>
                    <div id="collapseAct" class="accordion-collapse collapse" aria-labelledby="sezGestAct" data-bs-parent="#accordionAct">
                        <div class="accordion-body">

                            <!--<h4 class="mt-3" id="titoloAttivi">titolo</h4>-->
                            <form method="post" id="ACTform" action="" class="row g-3 align-items-center">
                                <fieldset id="allbuttonAct" class="row g-3 align-items-center">
                                    <input type="hidden" id="idAttCorrente" value="<?php if(isset($facti['idAt'])) echo $facti['idAt'];?>" name="idAt" />
                                    <input type="hidden" id="idTLCorrente" value="<?php if(isset($facti['dipendeda'])) echo $facti['dipendeda'];?>" name="idtl" />
                                    <div class="form-floating col-md-6">
                                        <input class="Dfrom form-control" id="dtStart" type="date" name="dtStart" <?php if(isset($facti['dtStart'])) echo "value=".$facti['dtStart']; ?> required />
                                        <label class="" for="dtStart">Dal: </label>
                                    </div>
                                    <div class="form-floating col-md-6">

                                        <input class="Dto form-control" id="dtStop" type="date" name="dtStop" value="<?php if(isset($facti['dtStop'])) echo $facti['dtStop'];?>" <?php if (!isset($facti['dtStart']) || $facti['dtStart']==0) echo "disabled"; else echo "min=".$facti['dtStart'];?> required />
                                        <label class="form-label" for="dtStop"> Al: </label>
                                    </div>
                                    <div class="form-floating col-md-12">

                                        <select class="form-select" id="revisore" name="revisore" required>
                                            <?php
                                                $resAllRoles=$_SESSION['allRoles'];
                                                echo '<option disabled selected>Non attivato</option>';
                                                //else echo '<option disabled value="select">Non attivato</option>';
                                                foreach($resAllRoles as $row){
                                                    if($row['primario']) echo "<option class='optionGroup'";
                                                    else echo "<option class='optionChild'";
                                                    if(isset($facti['revisore']) && $row['idRl'] == $facti['revisore']) echo " selected";
                                                    echo " value='{$row['idRl']}'>{$row['ruolo']}</option>";
                                                }
                                            ?>
                                        </select>
                                        <label class="form-label" for="revisore">Ruolo Revisore:</label>
                                    </div>
                                    <div class="form-floating col-6">
                                        <div id="attivablog" class="btn-group" role="group" aria-label="Attivazione Blog">
                                            <input type="checkbox" class="btn-check" name="btnattivablog" id="blogactive" autocomplete="off" value="0" <?php if(isset($facti['blog']) && $facti['blog']==1) echo "checked";?> />
                                            <label class="btn btn-outline-primary" for="blogactive"><?php if(isset($facti['blog']) && $facti['blog']==1) echo "Discussione Attivata"; else echo "Attiva Discussione";?></label>
                                        </div>
                                    </div>
                                    <div class="form-floating col-6">
                                        <div id="secondvot" class="btn-group" role="group" aria-label="Seconda Votazione">
                                            <input type="checkbox" class="btn-check" name="ballottaggio" id="ballottaggio" autocomplete="off" value="1" <?php if(isset($facti['ballottaggio']) && $facti['ballottaggio']==1) echo "checked";?> />
                                            <label class="btn btn-outline-primary" for="ballottaggio">
                                                Seconda Votazione
                                            </label>
                                        </div>
                                    </div>
                                    <div id="doposettimane" class="form-floating col-7">
                                        <input class="form-control" id="altridati" type="number" name="altridati" min="0" value="<?php if(isset($facti['altridati'])) echo $facti['altridati'];?>" />
                                        <label class="form-label" for="altridati">Elimina dopo settimane: </label>
                                    </div>
                                    <div id="giornipreavviso" class="form-floating col-12">
                                        <input class="form-control" id="giorninoti" type="number" name="giorninoti" min="0" value="<?php if(isset($facti['giorninoti'])) echo $facti['giorninoti'];?>" />
                                        <label class="form-label" for="giorninoti">Giorni di preavviso notifica: </label>
                                    </div>
                                    <div class="form-floating col-12">
                                        <div id="tipovotazione" class="btn-group" role="group" aria-label="Scelta Votazione">
                                            <input type="radio" class="btn-check" name="btntipovotazione" id="vsemplice" autocomplete="off" value="0" checked />
                                            <label class="btn btn-outline-primary" for="vsemplice">Vot. semplice</label>

                                            <input type="radio" class="btn-check" name="btntipovotazione" id="vgraduatoria" autocomplete="off" value="1" />
                                            <label class="btn btn-outline-primary" for="vgraduatoria">Graduatoria</label>
                                        </div>
                                    </div>
                                    
                                    <?php
                                         include(ROOT_PATH . '/adminsez/includes/templatestelle.php');
                                    ?>
                                    <hr class="mt-3" />
                                    <?php
                                            $_SESSION['template']['title']="Ruoli autorizzati:";

                                            include(ROOT_PATH . '/adminsez/includes/templateruoli.php');
                                    ?>
                                </fieldset>
                                <div class="col-12 text-md-end">
                                    <input type="submit" id="saveAct" class="btn btn-primary" name="save_activity" value="Salva Impostazioni" />
                                    <button type="reset" id="annullAct" class="btn btn-secondary" name="annulla">Annulla</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 mt-3">
            <div class="table-div mb-3">
                <!--<div class="col-md-12">-->

                <!-- Display notification message -->
                <div id="infoMessagge" class="my-callout d-none"></div>
                <div class="table-responsive">
                    <table class="table align-middle table-sm" id="actiTable">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Nome</th>
                                <th>Attiva</th>
                                <th>Anon.</th>
                                <th>Stato</th>
                                <th>Data inizio</th>
                                <th>Data fine</th>
                                <th>Ruolo Revisore</th>
                                <th>Ruoli Autorizzati</th>
                                <th colspan="2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($acti as $key => $ac):
                            if($ac['active']==false) {?>
                            <tr id="riga<?php echo $ac['idAt'];?>" style="background-color:var(--bs-light)">
                                                                                                             <!--display:none-->
                                <?php } else if($ac['stato']==1) {?>
                                <tr id="riga<?php echo $ac['idAt'];?>" style="background-color:var(--bs-danger)">
                                    <?php }
                                      else if($ac['stato']=='3'){ ?>
                                    <tr id="riga<?php echo $ac['idAt'];?>" style="background-color:var(--bs-warning)">
                                        <?php } else { ?>
                                        <tr id="riga<?php echo $ac['idAt'];?>">
                                            <?php } ?>
                                            <td>
                                                <input type="hidden" id="tl<?php echo $ac['idAt'];?>" name="tl<?php echo $ac['idAt'];?>" value="<?php echo $ac['dipendeda'];?>" />
                                                <button type="button" class="btn icona" data-bs-toggle="tooltip" title="Configura" data-idact="<?php echo $ac['idAt']; ?>" id="conf<?php echo $ac['idAt']; ?>" value="<?php echo $ac['idAt'];?>" name="config-act" >
                                                    <span class="bi bi-gear"></span>
                                                </button>

                                            </td>

                                            <td id="name<?php echo $ac['idAt'];?>">
                                                <?php echo $ac['nome'];?>
                                            </td>

                                            <?php
                                     if ($ac['stato']==1){
                                         //solo per vedere la riga poi da rimettere a posto con 1
                                            ?>
                                            <td colspan=9>
                                                Attivit&agrave; in corso, solo modifiche autorizzate ...
                                                <input type="hidden" id="incorso<?php echo $ac['idAt'];?>" name="incorso<?php echo $ac['idAt'];?>" value="<?php echo $ac['stato'];?>" />
                                                
                                                <input type="date" class="form-control-plaintext" id="From<?php echo $ac['idAt'];?>" value="<?php  if(isset($ac['dtStart'])) echo $ac['dtStart'];?>" readonly disabled hidden/>
                                                <input type="date" class="form-control-plaintext" id="To<?php echo $ac['idAt'];?>" value="<?php if(isset($ac['dtStop'])) echo $ac['dtStop']; ?>" readonly disabled hidden/>

                                            </td>
                                            <?php }
                                     else{
                                            ?>

                                            <td>
                                                <input type="hidden" id="incorso<?php echo $ac['idAt'];?>" name="incorso<?php echo $ac['idAt'];?>" value="<?php echo $ac['stato'];?>" />
                                                <input type="checkbox" id="actactive<?php echo $ac['idAt'];?>" class="form-check-input" name="act-active" value="<?php echo $ac['idAt'];?>" <?php echo (($ac['active'])?'checked':'')?> />
                                            </td>
                                            <td>
                                                <input type="checkbox" class="form-check-input" id="actanonim<?php echo $ac['idAt'];?>" name="act-anonim" value="<?php echo $ac['idAt'];?>" <?php echo (($ac['anonima'])?'checked':''); echo (($ac['active'])?'':'disabled');?> />
                                            </td>
                                            <td id="scad<?php echo $ac['idAt'];?>">
                                                <?php echo $ac['stato'];?>
                                            </td>
                                            <td>
                                                <input type="date" class="form-control-plaintext" id="From<?php echo $ac['idAt'];?>" value="<?php  if(isset($ac['dtStart'])) echo $ac['dtStart'];?>" readonly disabled />
                                            </td>
                                            <td>
                                                <input type="date" class="form-control-plaintext" id="To<?php echo $ac['idAt'];?>" value="<?php if(isset($ac['dtStop'])) echo $ac['dtStop']; ?>" readonly disabled />
                                            </td>
                                            <td id="rev<?php echo $ac['idAt'];?>">
                                                <?php if(isset($ac['rev'])) echo $ac['rev']; else echo '--';?>
                                            </td>
                                            <td id="raut<?php echo $ac['idAt'];?>">
                                                <?php echo $ac['raut'];?>
                                            </td>
                                            <td class="not-aval">
                                                <button type="button" class="btn icona" data-bs-toggle='modal' data-bs-target='#myModalReset' title="Reset Impostazioni" data-idact="<?php echo $ac['idAt']; ?>" id="azz<?php echo $ac['idAt']; ?>" value="<?php echo $ac['idAt'];?>" name="reset-act" <?php echo (($ac['stato']<2)?'disabled':'')?>>
                                                    <span class="bi bi-arrow-counterclockwise"></span>
                                                </button>

                                            </td>
                                            <td class="not-aval">
                                                <button type="button" class="btn icona" data-bs-toggle='modal' data-bs-target='#myModalDelete' title="Elimina Risultati precedenti" data-idact="<?php echo $ac['idAt']; ?>" id="elidata<?php echo $ac['idAt']; ?>" value="<?php echo $ac['idAt'];?>" name="deldata-act" <?php echo (($ac['stato']<2)?'disabled':'')?>>
                                                    <span class="bi bi-bag-x"></span>
                                                </button>
                                            </td>
                                            <?php } //fine else?>
                                           
                                        </tr>

                                        <?php endforeach ?>
</tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

        <!--<canvas id="myCanvas"></canvas>
        <legend for="myCanvas"></legend>
        <script type="text/javascript" src="adminsez/static/js/gantt.js"></script>-->
</section>
<!-- Modal HTML -->
<div id="myModalAttention" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Attenzione</h5>
                <!--<button type="button" class="btn-close" data-bs-dismiss="modal"></button>-->
            </div>
            <div class="modal-body">
                <p>Le attivit&agrave; in corso non dovrebbero essere modificate.<br />Procedere comunque con la configurazione? </p>
                <p class="text-secondary">
                    <small></small>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" id="closeModalAttention" class="btn btn-primary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" id="confermAttention" class="btn btn-secondary">Conferma</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal HTML -->
<div id="myModalReset" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Attenzione</h5>
                <!--<button type="button" class="btn-close" data-bs-dismiss="modal"></button>-->
            </div>
            <div class="modal-body">
                <p>L'attivit&agrave; verr&agrave; riportata alle impostazioni di default.<br />Vuoi procedere? </p>
                <p class="text-secondary">
                    <small></small>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" id="closeModalReset" class="btn btn-primary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" id="confermReset" class="btn btn-secondary">Conferma</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal HTML -->
<div id="myModalDelete" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Attenzione</h5>
                <!--<button type="button" class="btn-close" data-bs-dismiss="modal"></button>-->
            </div>
            <div class="modal-body">
                <p>
                    Tutti i dati relativi all'ultima esecuzione dell'attivit&agrave; saranno eliminati in modo definitivo.<br />Vuoi procedere?
                </p>
                <p class="text-secondary">
                    <small>Eliminazione definitiva</small>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" id="closeModaldelete" class="btn btn-primary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" id="confermDelete" class="btn btn-secondary">Conferma</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="adminsez/static/js/confactscript.js"></script>

