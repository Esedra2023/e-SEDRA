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
      //myfunctiontest();
      //ATTENZIONE AVEVA adminfunction senza functions
      require_once ROOT_PATH.'/include/functions.php';
      require_once(ROOT_PATH . '/adminsez/admin/includes/utilfunctions.php');
	  if(!isset($_POST['page'])) forbidden();
?>

<script src="js/functions.js"></script>


<?php
$rolesP=selectPrimaryRoles();
$editU['idUs']=0;
//$rolesP=$_SESSION['primaryRoles']
if(isset($_SESSION['template']))
    unset($_SESSION['template']);
?>
<section class="container" id="userconf">
    <h2 class="page-title mt-3">Gestione Utenti</h2>
    <hr />

    <div class="row justify-content-evenly">

        <!--<div class="col-md-1"></div>-->
        <!-- Middle form - to create and edit  -->
        <div class="col-md-5 mt-3">
            <!--action sidebar-sticky position-sticky-->

            <div class="accordion" id="accordionUser">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="sezEditUser">
                        <button class="accordion-button" id="btnAccCreateUser" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <span class="bi-person-plus-fill"></span>
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="sezEditUser" data-bs-parent="#accordionUser">
                        <div class="accordion-body">

                            <form method="post" id="AUform" action="" class="row g-3 align-items-center">
                                <!--row-cols-lg-autoclass="collapse"-->

                                <!-- validation errors for the form 
                             
					-->
                                <fieldset id="allElements" class="row g-3 align-items-center">
                                    <input type="hidden" id="IDModUser" name="user_id" value="<?php if(isset($editU['idUs'])) echo $editU['idUs'] ?>" />
                                    <!-- if editing user, the id is required to identify that user -->
                                    
                                    <div class="col-12">
                                       
                                        <div class="input-group">
                                            <div class="input-group-text">@</div>
                                            <input type="email" class="form-control" id="email" name="email" value="" placeholder="* Email" required />
                                        </div>
                                        <!--<label for="email" class="form-label">Email</label>-->
                                    </div>
                                    <div class="form-floating col-md-6">
                                      
                                        <input type="text" class="form-control" id="nome" name="nome" value="" required />
                                        <label for="nome" class="form-label">* Nome</label>
                                    </div>
                                    <div class="form-floating col-md-6">
                                  
                                        <input type="text" class="form-control" id="cognome" name="cognome" value="" required />
                                        <label for="cognome" class="form-label">* Cognome</label>
                                    </div>

                                    <div class="form-floating col-md-6">
                                  
                                        <input type="tel" class="form-control" id="cell" name="cell" value=""  />
                                        <label for="cell" class="form-label">Telefono</label>
                                    </div>
                                    <div class="form-floating col-md-6">
                                    
                                        <input type="text" class="form-control hide" id="cod" name="cod" value=""  />
                                        <label for="cod" class="form-label">Codice</label>
                                    </div>
                                    <div class="form-floating col-md-6">
                                      
                                        <input type="datetime-local" class="form-control form-control-plaintext" id="dtPsw" name="dtPsw" value="<?php date_default_timezone_set('Europe/Rome');
                                    echo str_replace(" ", "T", date('Y-m-d H:i'));?>"
                                            disabled />

                                        <label for="dtPsw" class="form-label">Data creazione password</label>
                                    </div>
                                    <div class="form-floating col-md-6">
                                        <input type="text" class="form-control form-control-plaintext" id="dtscPsw" name="dtscPsw" value="" disabled />
                                        <label for="dtscPsw" class="form-label">Giorni scadenza password</label>
                                    </div>
                                    <?php
                                    $_SESSION['template']['title']='Ruoli utente (* &egrave; richiesto almeno UN ruolo primario)';
                                    //array_push($_SESSION['template']['ruoli'],'TR0');
                                    //$_SESSION['template']['numberAct']=0;
			                        include(ROOT_PATH . '/adminsez/includes/templateruoli.php');?>

                                </fieldset>
                                <!-- if editing user, display the update button instead of create button 
				-->
                                <div class="col-12 text-md-end">
                                    <input type="submit" id="saveAddUser" class="btn btn-primary" name="create_user" value="Salva Utente" />
                                    <button type="reset" id="annullAdd" class="btn btn-secondary" name="annulla">Annulla</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="sezImportUser">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <span class="bi-person-lines-fill"></span>&nbsp;Importa Utenti
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="sezImportUser" data-bs-parent="#accordionUser">
                        <div class="accordion-body">
                            
                            <!--class="collapse"-->
                            <form enctype="multipart/form-data" id="IULform" name="IULform" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <div class="input-group col-12 row align-middle">
                                    <label class="form-control-label">Ruolo primario:</label>
                                    <div id="RuoliPrim" class="col-12 mb-3">
                                        <div class="btn-group" role="group" aria-label="Elenco Ruoli Primari">
                                            <?php
                                            foreach($rolesP as $rol)
                                            {
						                        $rid=$rol['idRl'];
                                            ?>
                                                <input type="radio" class="btn-check p-2" name="Irprim" id="Irp<?php echo $rid?>" value="<?php echo $rid?>" autocomplete="off" />
                                                <label class="btn btn-outline-primary p-2" for="Irp<?php echo $rid?>">
                                                    <?php echo $rol['ruolo']?>
                                                </label>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>

                                    <div class="input-group col-md-6 mb-3">
                                        <input type="file" class="form-control" name="fileToUpload" id="fileToUpload" /><!--style="opacity:0;"-->
                                    </div>
                                    
                                    <div class="col-12 text-md-end">
                                       
                                        <!--<input type="hidden" name="hidden_field" value="1" />--><!--per progress bar-->
                                        <!--<input type="submit" id="importUserList" name="importUserList" value="Importa Utenti" class="btn btn-primary" />-->
                                        <button type="reset" id="annullIul" class="btn btn-secondary" name="annulla">Annulla</button>

                                    </div>
                                    <div id="spinner-div" class="pt-2 pb-3 d-flex align-items-center justify-content-center d-none">
                                        <div class="spinner-border text-primary" role="status"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display records from DB-->
        <div class="col-md-7 mt-3 justify-content-center">
            <div id="infoMessaggedx" class="my-callout d-none"></div>

 

            <form name="alphaform" method="POST" action="" class="mb-2 ">
                <nav class="alfa pagination btn-group justify-content-center" role="group" aria-label="Alfabeto per sottogruppi utenti">
                    <input type="radio" class="btn-check p-1" name="alfa" id="rall" value="%" autocomplete="off" <?php if(isset($_SESSION['search']) && $_SESSION['search']==='%') echo 'checked';?> />
                    <label class="btn btn-outline-primary p-1" for="rall">Tutti</label>
                    <!--<a href="#Utable" id="raz" class="btn btn-outline-primary" onclick="callAjax('[a-z]%')" name="alfa">ALL</a>-->
                    <input type="hidden" id="search" value="<?php if(isset($_SESSION['search'])) echo $_SESSION['search'];?>" />
                    <?php
                            $alf="abcdefghijklmnopqrstuvwxyz";

                            for($l=0;$l<strlen($alf);$l++)
                            {
                                $c=$alf[$l];
                                $s=$c."%";
                    ?>                                      
                    <!-- <?php if(isset($_SESSION['search']) && $_SESSION['search']===$s) echo 'checked'; ?> -->
                    <input type="radio" class="btn-check p-1" name="alfa" id="r<?php echo $c?>" value="<?php echo $s?>" autocomplete="off"/>
                    <label class="btn btn-outline-primary p-1" for="r<?php echo $c?>">
                        <?php echo strtoupper($c); ?>
                    </label>
                    <?php
                            }
                    ?>
                </nav>
            </form>
            <!-- Display notification message -->
            <div id="infoMessagge" class="my-callout d-none"></div>

            <!--commenta
			
			fine commento-->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">

                    <li class="page-item">
                        <button type="button" class="page-link" id="backP" aria-label="Previous">
                            <span aria-hidden="true" class="bi bi-arrow-left-square"></span>
                        </button>
                    </li>
                    <li class="page-item">
                        <a class="page-link disable" tabindex="-1">
                            Utenti: <span id="utenti_totali">--</span>
                            &nbsp;&nbsp;Pag. <span id="pagina_attuale">--</span> di <span id="pagine_totali">--</span>
                        </a>
                    </li>

                    <!--<li class="page-item">
            <a class="page-link" href="">1</a>
        </li>
        <li class="page-item">
            <a class="page-link" href="">2</a>
        </li>
        <li class="page-item">
            <a class="page-link" href="">3</a>
        </li>-->
                    <li class="page-item">
                        <button type="button" class="page-link" id="nextP" href="" aria-label="Next">
                            <span aria-hidden="true" class="bi bi-arrow-right-square"></span>
                        </button>
                    </li>
                </ul>
            </nav>

            <div class="table-responsive">
                <table id="Utable" class="table table-striped table-sm">
                    <!---->
                    <thead>
                        <tr>
                            <th scope="col">N</th>
                            <th scope="col">Nominativo</th>
                            <!--<th>Nome</th>-->
                            <th scope="col">Email</th>
                            <th scope="col">Ruoli</th>
                            <th scope="col" colspan="3">Azioni</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider "></tbody>
                </table>
                <!--commento fine commento-->
                <!--  -->
            </div>
            <!-- // Display records from DB -->
        </div>
    </div>
</section>
	<!-- Modal HTML -->
    <div id="myModalCancel" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Attenzione</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Premi conferma se vuoi eliminare l'utente.</p>	
                    <p class="text-secondary"><small>Operazione irreversibile!</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="closeModalCancel" class="btn btn-primary" data-bs-dismiss="modal">Annulla</button>
                    <button type="button" id="cancelUser" class="btn btn-secondary">Conferma</button>
                </div>
            </div>
        </div>
    </div>

<script src="adminsez/static/js/userscript.js"></script>

<!--<script src="../../js/jquery-3.6.1.min.js"></script>-->
<!--<script src="../../adminsez/static/js/userscript.js"></script>-->


	

