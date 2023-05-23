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
<section class="container mt-3" id="discutivotaB">
    <h2><?php if($VDbisogni['blogAct']) echo $VDbisogni['nome'].' con discussione'; else echo $VDbisogni['nome'];?></h2>
	<blockquote class="blockquote">
    <p class="alert alert-primary col-md-12 text-center" role="alert">
        <?php if($VDbisogni['votAct']) { 
                  echo ('Fase attiva dal '.date_format(date_create($VDbisogni['dtStart']),'d/m/Y').' al '.date_format(date_create($VDbisogni['dtStop']),'d/m/Y').' - mancano '.$VDbisogni['ggscad'].' giorni alla chiusura.');
              }
       else echo('Fase non attiva');
        ?></p>
  </blockquote>
    <hr />
    <div class="row justify-content-evenly">     

<!--<hr />-->
		<!-- Display records from DB-->
<?php if ($VDbisogni['votAct']) {?>
        <div class="col-md-3 mt-3">
            <?php 
                      $_POST['grad']=$VDbisogni['altridati'];
                      $_POST['votbp']=104;
                      include(ROOT_PATH . '/include/votgraduatoria.php');
            ?>
        </div>
        <div class="col-md-9 mt-3">
            <!--colonna per table-->
            <div class="table-div mb-3">
                <div id="infoMessagge" class="my-callout d-none"></div>

                <table class="table align-middle" id="Disctable">
                    <thead>
                        <tr>
                            <!--<th>N</th>-->
                            <th>Titolo</th>
                            <th>Autore</th>
                            <th>
                                <span class="bi bi-heart-fill"></span>
                            </th>
                            <?php if($VDbisogni['IamAuthor']) {?>
                            <th colspan="2">
                                <small>Votazione</small>
                            </th>
                            <?php } ?>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $tot=0;
                          if (!empty($posts)){
                              foreach ($posts as $key => $post){
                                  $tot++;
                        ?>
                        <tr>
                            <!--<td><?php echo $key + 1; ?></td>-->
                            <td>
                                <input type="hidden" id="idBs<?php echo  $post['idBs']; ?>" value="<?php echo  $post['idBs']; ?>" />
                                <button type="button" class="linkstylebutton btn btn-outline-primary text-start" <?php echo"data-idbis=".$post['idBs'];?>>
                                    <?php echo $post['titleBis']; ?>
                                </button>
                            </td>
                            <?php

                                  if($_SESSION['user']['idUs']==$post['utente'])
                                  {
                                      echo '<td>io</td>';
                                      if(isset($post['nlike']))
                                          echo '<td>'.$post['nlike'].'</td>';
                                      else  echo '<td>0</td>';
                                  }
                                  else if($VDbisogni['IamRev'])
                                  {
                                      echo '<td>'.$post['nome'].' '.$post['cognome'].'</td>';
                                      if(isset($post['nlike']))
                                          echo '<td>'.$post['nlike'].'</td>';
                                      else  echo '<td>0</td>';
                                  }
                                  else
                                      echo '<td>---</td> <td>-</td>';
                            ?>
                            <td>
                                <?php if($VDbisogni['IamAuthor']){ ?>
                                <input type="hidden" class="provafunz" id="idBs<?php echo  $post['idBs']; ?>" value="<?php echo  $post['idBs']; ?>" />
                                <my-rating class="rating" max-value="10" value="<?php if(isset($post['grade'])) echo $post['grade']; ?>"></my-rating>
                                <?php } ?>
                            </td>

                        </tr>
                        <?php }
                          }
                       if($tot==0) {
                           echo '<tr><td colspan="5" class="alert alert-primary col-md-12 mt-3 text-center">Nessun bisogno da visualizzare</td></tr>';
                        $tot++;
                    }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
            <?php } ?>
        </div>
</section>	

<script src="js/functions.js"></script>
<script src="js/funzioniValLU.js"></script>
<script src="js/discubisogni.js"></script>
