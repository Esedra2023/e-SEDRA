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
<section class="container mt-3" id="discutivotaP">
    <h2>
        <?php if($VDproposte['blogAct']) echo $VDproposte['nome'].' con discussione'; else echo $VDproposte['nome'];?>
    </h2>
    <blockquote class="blockquote">
        <p class="alert alert-primary col-md-12 text-center" role="alert">
            <?php if($VDproposte['votAct']) {
                  echo ('Fase attiva dal '.date_format(date_create($VDproposte['dtStart']),'d/m/Y H:i').' al '.date_format(date_create($VDproposte['dtStop']),'d/m/Y H:i').' - Termina tra: <span id="demo">'.$VDproposte['ggscad']. '</span></br>');
            }
       else echo('Fase non attiva');
            ?>
        </p>
        <input type="hidden" id="scadenza" value="<?php echo $VDproposte['dtStop']; ?>" />

    </blockquote>
    <hr />
    <div class="row justify-content-evenly">

        <!--<hr />-->
        <!-- Display records from DB-->
        <?php if ($VDproposte['votAct']) {?>
        <div class="col-md-3 mt-3">
            <?php
                      $_POST['grad']=$VDproposte['altridati'];
                      $_POST['votbp']=204;
                      include(ROOT_PATH . '/include/votgraduatoria.php');
            ?>
        </div>
        <div class="mt-3">
            <!--colonna per table-->
            <div class="table-div col-lg-8 mb-3">
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
                            <?php if($VDproposte['IamAuthor']) {?>
                            <th colspan="2">
                                <small>Votazione</small>
                            </th>
                            <th></th>
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
                                <input type="hidden" id="key<?php echo $key; ?>" value="<?php echo $key; ?>" />
                                <button type="button" class="linkstylebutton btn btn-outline-primary text-start" <?php echo"data-idpro=".$post['idPr'];?>>
                                    <?php echo $post['titlePrp']; ?>
                                </button>
                            </td>
                            <?php

                                  //if($_SESSION['user']['idUs']==$post['utente'])
                                  //{
                                  //    echo '<td>io</td>';
                                      if(isset($post['nlike']))
                                          echo '<td>'.$post['nlike'].'</td>';
                                      else  echo '<td>-</td>';
                                  //}
                                  //if($VDproposte['IamRev'])
                                  //{
                                  //    echo '<td>'.$post['nome'].' '.$post['cognome'].'</td>';
                                  //    if(isset($post['nlike']))
                                  //        echo '<td>'.$post['nlike'].'</td>';
                                  //    else  echo '<td>0</td>';
                                  //}
                                  //else
                                  //    echo '<td>---</td> <td>-</td>';
                            ?>
                            <td>
                                <?php if($VDproposte['IamAuthor']){ ?>
                                <input type="hidden" class="provafunz" id="idPr<?php echo  $post['idPr']; ?>" value="<?php echo  $post['idPr']; ?>" />
                                <my-rating class="rating" max-value="10" value="<?php if(isset($post['grade'])) echo $post['grade']; ?>"></my-rating>
                                <?php } ?>
                            </td>
                            <td>
                                <button type="button" class="btn icona" data-bs-toggle="tooltip" title="Cancella Voto" data-idbis="<?php echo $post['idPr']; ?>" data-crud="D" value="<?php echo $post['idPr']; ?>" name="cancella-voto" <?php //if (!isset($post['grade'])) echo 'disabled'; ?>>
                                    <span class="bi <?php
                                                echo 'bi-trash'; ?> "></span>
                                </button>

                            </td>

                        </tr>
                        <?php }
                          }
                       if($tot==0) {
                           echo '<tr><td colspan="6" class="alert alert-primary col-md-12 mt-3 text-center">Nessuna proposta da visualizzare</td></tr>';
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
    <script src="js/funzioniValLU.js"></script>
    <script src="js/discuproposte.js"></script>
</section>
