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
<section class="container mt-3" id="discutiB">
    <h2><?php echo $Dbisogni['nome']; ?></h2>
	<blockquote class="blockquote">
    <p class="alert alert-primary col-md-12 text-center" role="alert">
        <?php if($Dbisogni['blogAct']) {
                  echo ('Fase attiva dal '.date_format(date_create($Dbisogni['dtStart']),'d/m/Y H:i').' al '.date_format(date_create($Dbisogni['dtStop']),'d/m/Y H:i') . ' - Termina tra: <span id="demo">'.$Dbisogni['ggscad'].'</span></br>');
              }
       else echo('Fase non attiva');
        ?></p>
        <input type="hidden" id="scadenza" value="<?php echo $Dbisogni['dtStop']; ?>" />

  </blockquote>
    <hr />
    <div class="row justify-content-evenly">     

<!--<hr />-->
		<!-- Display records from DB-->
<?php if ($Dbisogni['blogAct']) {?>
        <!--<div class="col-md-3 mt-3">
            <?php 
                      //$_POST['grad']=$VDbisogni['altridati'];
                     // $_POST['votbp']=104;
                     // include(ROOT_PATH . '/include/votgraduatoria.php');
            ?>
        </div>-->
        <div class="col-md-12 mt-3">
            <!--colonna per table-->
            <div class="table-div mb-3">
                <div id="infoMessagge" class="my-callout d-none"></div>

                <table class="table align-middle" id="Disctable">
                    <thead>
                        <tr>
                            <th>N</th>
                            <th>Titolo</th>
                            <th>Testo</th>
                            <!--<th>Autore</th>-->
                            <th>
                                <span class="bi bi-heart-fill"></span>
                            </th>                            
                        </tr>
                    </thead>

                    <tbody>
                        <?php $tot=0;
                          if (!empty($posts)){
                              foreach ($posts as $key => $post){
                                  $tot++;
                        ?>
                        <tr>
                            <td><?php echo $key + 1; ?></td>
                            <td>
                                <input type="hidden" id="idBs<?php echo  $post['idBs']; ?>" value="<?php echo  $post['idBs']; ?>" />
                                <button type="button" class="linkstylebutton btn btn-outline-primary text-start" <?php echo"data-idbis=".$post['idBs'];?>>
                                    <?php echo $post['titleBis']; ?>
                                </button>
                                </td>
                            <td>
                                <span class="small"><?php 
                                        if (strlen($post['textBis']) > 150)
                                            echo substr($post['textBis'], 0, 150) . ' ...';
                                        else
                                            echo $post['textBis']; ?></span>
                            </td>
                            <?php
                                  //if($_SESSION['user']['idUs']==$post['utente'])
                                  //{
                                      //echo '<td>io</td>';
                                if(isset($post['nlike']))
                                    echo '<td>1</td>';
                                else  echo '<td>-</td>';
                                  //}
                                  //else if($VDbisogni['IamRev'])
                                  //{
                                  //    //echo '<td>'.$post['nome'].' '.$post['cognome'].'</td>';
                                  //    if(isset($post['nlike']))
                                  //        echo '<td>'.$post['nlike'].'</td>';
                                  //    else  echo '<td>-</td>';
                                  //}
                                  //else
                                  //      echo '<td>-</td>';
                                    //echo '<td>---</td> <td>-</td>';
                            ?>
                           
                        </tr>
                        <?php }
                          }
                       if($tot==0) {
                           echo '<tr><td colspan="4" class="alert alert-primary col-md-12 mt-3 text-center">Nessun bisogno da visualizzare</td></tr>';
                        $tot++;
                    }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
            <?php } ?>
        </div>

<script src="js/functions.js"></script>

<!--<script src="js/funzioniValLU.js"></script>-->
    <script> 
        ready(function () {
            var defaultpage = 1;

            var disctable = document.querySelector('#Disctable');
            //console.log(disctable);
            disctable.addEventListener("click", (e) => {
                let elem = e.target;
                let span = null;
                if (elem.classList.contains("linkstylebutton")) {
                    //console.log('timer = ' + timer);

                    if (timer) {
                        clearInterval(timer);
                        //console.log('timer sospeso ' + timer);
                    }
                    //console.log('+ ' + elem.nodeName + ' ' + idBisogno + 'aut ' + auth + ' rev ' + rev);
                    refreshSinglePost(elem.dataset.idbis, defaultpage, 104)
                }
            });
            var fortimer = document.getElementById("scadenza");
            var dataFine = fortimer.value;
            dataFine = dataFine.replace(" ", "T");

            avviaContoAllaRovescia(dataFine, "demo");
            //console.log('timer avviato ' + timer);
        }); //end ready
    </script>

<!--<script src="js/discubisogni.js"></script>-->
</section>	
