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
if(!isset($_POST['page'])) forbidden();
if (!defined('ROOT_PATH'))
    define ('ROOT_PATH', $_SESSION['ini']['ROOT_PATH']);

require_once ROOT_PATH .'/include/functions.php';
require_once ROOT_PATH .'/include/postfunctions.php';
require_once ROOT_PATH .'/include/wrapperfunctions.php';



if(isset($_SESSION['moment']))
    unset($_SESSION['moment']);
defineMoment();		//ri definisco qui in caso di modifiche da parte dell'admin altrimenti non si aggiorna
////$nat=count($_SESSION['moment']);
$now = new DateTimeImmutable('now', new DateTimeZone('Europe/Rome')); /*new DateTime();*/
if (array_key_exists(300,$_SESSION['moment']))
{
    $news=$_SESSION['moment']['300'];
    //non c'è autore delle news
    //$IamAuthor=compareRuoli($news['author'],$_SESSION['user']['roles']);
    $news['IamAuthor']=false;
    $news['IamRev']=IamRevisor($news['revisore']);


            //$now=$now->format('Y-m-d');
            //$tomorrow= $now->add(new DateInterval('P1D'));
                //calcolaDataAfter($now,1,'D');
            //$to1W= $now->add(new DateInterval('P7D'));
            //calcolaDataAfter($now,1,'W');
    $allnews = wr_getNews(1,0,0);
    $news['periodNews']=true;
}

if (array_key_exists(103,$_SESSION['moment']))
{
    $dbis=$_SESSION['moment']['103'];

    //$IamAuthor=compareRuoli($dbis['author'],$_SESSION['user']['roles']);
    $dbis['IamAuthor']=false;
    $dbis['IamRev']=IamRevisor($dbis['revisore']);
    $dbis['periodSegnB']=true;

    $allCIna=wr_getCina(103);
}
////ancora da mettere a posto
if (array_key_exists(203,$_SESSION['moment']))
{
    $dpro=$_SESSION['moment']['203'];
    $dpro['IamAuthor']=false;
    $dpro['IamRev']=IamRevisor($dpro['revisore']);
    $dpro['periodSegnP']=true;

    $allCIna=wr_getCina(203);
}

?>
<section class="container mt-3" id="profile">
    <!--<h2 class="page-title mt-3">Profilo</h2>
    <hr />-->

    <div class="row justify-content-evenly">
        <div class="col-md-4 mt-3">
            <div id="infoMessagge" class="my-callout d-none"></div>
                    <div class="card border-primary">
                        <div class="card-header">
                            <h4>
                                <span class="bi bi-person-badge"></span>
                                <?php echo '&nbsp;'.$_SESSION['user']['nome'].' '.$_SESSION['user']['cognome']; ?>

                            </h4>
                            <input type="hidden" id="nome" value="<?php echo $_SESSION['user']['nome']; ?>" />
                            <input type="hidden" id="cognome" value="<?php echo $_SESSION['user']['cognome']; ?>" />
                            <input type="hidden" id="idUs" value="<?php echo $_SESSION['user']['idUs']; ?>" />
                        </div>

                        <div class="card-body">
                            <div class="card-title">
                                <div class="input-group">
                                    <div class="input-group-text">@</div>
                                    <input type="email" class="form-control form-control-plaintext" value="<?php echo "&nbsp;&nbsp;".$_SESSION['user']['email']; ?>" disabled />
                                </div>
                            </div>
                            <h6 class="card-subtitle mb-2 text-muted">
                                <?php
                            $ruoli=$_SESSION['user']['roles'];
                            foreach ($ruoli as $r)
                            {
                                foreach(array_keys($r) as $key=>$val)
                                {
                                    if($key=="nome")
                                        echo('<br/>'.$r[$val].'  ');
                                    else
                                        echo($r[$val].'  ');
                                }
                            }
                                ?>

                            </h6>
                            <div class="card-text">
                                <form id="formtel">
                                    <div class="form-floating mb-3">
                                        <input type="tel" class="form-control" id="mycell" name="cell" value="<?php echo $_SESSION['user']['cell']; ?>" />
                                        <label for="mycell" class="form-label">Telefono</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="mycod" name="cod" value="<?php echo $_SESSION['user']['cod']; ?>" />
                                        <label for="mycod" class="form-label">Codice</label>
                                    </div>
                                </form>
                                <div class="row">
                                    <div class="form-floating mb-3 col-md-6">
                                        <input type="text" class="form-control form-control-plaintext" id="mydateP" value="<?php echo date("d-m-Y H:i", strtotime($_SESSION['user']['dtPsw']));?>" disabled />


                                        <label for="mydateP" class="form-label">Creazione password</label>
                                    </div>
                                    <div class="form-floating mb-3 col-md-6">
                                        <input type="text" class="form-control form-control-plaintext" id="mygP" value="<?php if(isset($_SESSION['user']['ggScPsw'])) echo $_SESSION['user']['ggScPsw']; ?>" disabled />


                                        <label for="mygP" class="form-label">Giorni scadenza password</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="col-12 text-md-end">
                                <input type="submit" id="mymoddati" class="btn btn-primary" name="mymoddati" value="Modifica Dati" disabled/>
                            </div>
                            <small class="text-muted text-md-end">
                                <?php //$date = new DateTimeImmutable('now', new DateTimeZone('Europe/Rome'));
                                echo $now->format('d-m-Y H:i');  ?>
                            </small>
                        </div>

                    </div>
                </div>
        <div class="col-md-8 mt-3">
            
            <?php if(isset($dbis) && $dbis['periodSegnB'] && $dbis['IamRev'])
                  {
                      $_POST['itable']='B';
                      include(ROOT_PATH . '/include/templatecontinadeguato.php');
                  }
            ?>
            <?php if(isset($dpro) && $dpro['periodSegnP'] && $dpro['IamRev'])
                  {
                      $_POST['itable']='P';
                      include(ROOT_PATH . '/include/templatecontinadeguato.php');
                  }
                  
            ?>
            <?php if(isset($news) && $news['periodNews'] && $news['IamRev'])
                      include(ROOT_PATH . '/include/templategestiscinews.php');
            ?>
            <?php if($_SESSION['user']['idUs']==1)
                      include(ROOT_PATH . '/include/templatecommenticanc.php');
            ?>
        </div>
    </div>
</section>

<!-- Modal HTML -->
<div id="myModalCancelNews" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Attenzione</h5>
                <!--<button type="button" class="btn-close" data-bs-dismiss="modal"></button>-->
            </div>
            <div class="modal-body">
                <p>Premi conferma se vuoi eliminare la news selezionata.</p>
                <p class="text-secondary">
                    <small>Operazione irreversibile!</small>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" id="closeModalCancelNews" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" id="confermCancelNews" class="btn btn-primary">Conferma</button>
            </div>
        </div>
    </div>
</div>


<div id="myModalCancelCIna" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Attenzione</h5>
                <!--<button type="button" class="btn-close" data-bs-dismiss="modal"></button>-->
            </div>
            <div class="modal-body">
                <p>Premi conferma se vuoi eliminare il commento inadeguato.</p>
                <p class="text-secondary">
                    <small>Operazione irreversibile!</small>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" id="closeModalCancelCIna" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" id="confermCancelCIna" class="btn btn-primary">Conferma</button>
            </div>
        </div>
    </div>
</div>

    <script>

    function savetelefono() {
        var tel = document.getElementById("mycell");
        var num = tel.value;
        /* if (num != "") { */
        if (num.match(/3\d{2}[\. ]??\d{6,7}$/) || num == '') {
            data = new FormData(document.getElementById("formtel"));
            data.append("nome", document.getElementById("nome").value);
            data.append("cognome", document.getElementById("cognome").value);
            data.append("idUs", document.getElementById("idUs").value);
            var res = call_ajax_single_promise("ajax/updateuserprofile.php", data)
            if (res != 0) {
                showMessagge("Dati profilo aggiornati", "my-callout-warning");
                document.getElementById("mymoddati").setAttribute("disabled", true);
            }
        } else showMessagge("Numero cellulare non valido", "my-callout-danger");
        /*   }*/
    }

    ready(function () {

            var mybtn = document.getElementById("mymoddati");
            mybtn.addEventListener("click", savetelefono);

            var mycell = document.getElementById("mycell");
            mycell.addEventListener('change', () => {
                if (document.getElementById("mymoddati").disabled) document.getElementById("mymoddati").removeAttribute("disabled");
            });

            var mycod = document.getElementById("mycod");
            mycod.addEventListener('change', () => {
                if (document.getElementById("mymoddati").disabled) document.getElementById("mymoddati").removeAttribute("disabled");
            });
    }); 
     
    </script>

