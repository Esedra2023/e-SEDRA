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
    require_once ROOT_PATH.'/include/functions.php';
    if(!isset($_POST['page'])) forbidden();
    if(isset($_SESSION['template']))
        unset($_SESSION['template']);
    //$_SESSION['template']['ruoli']=[];
    //$_SESSION['template']['title']=[];


?>
<section class="container" id="settings">
<h2 class="page-title mt-3">Configurazione generale</h2>
<hr />


<!--<section class="page" id="settings">-->
<!--<div class="spanAll flexWrap">-->
    <div class="row justify-content-evenly">
        <div class="form-floating col-md-4">           
            <select class="form-select inputcfg" id="scPsw" name='scPsw'>
                <option value="0" <?php if(isset($_SESSION['ini']['scPsw']) && $_SESSION['ini']['scPsw']==0) echo ' selected';?> >Mai</option>
                <option value="3" <?php if(isset($_SESSION['ini']['scPsw']) && $_SESSION['ini']['scPsw']==3) echo ' selected';?> >3 mesi</option>
                <option value="6" <?php if(isset($_SESSION['ini']['scPsw']) && $_SESSION['ini']['scPsw']==6) echo ' selected';?> >6 mesi</option>
                <option value="9" <?php if(isset($_SESSION['ini']['scPsw']) && $_SESSION['ini']['scPsw']==9) echo ' selected';?> >9 mesi</option>
                <option value="12" <?php if(isset($_SESSION['ini']['scPsw']) && $_SESSION['ini']['scPsw']==12) echo ' selected';?> >12 mesi</option>
                <option value="18" <?php if(isset($_SESSION['ini']['scPsw']) && $_SESSION['ini']['scPsw']==18) echo ' selected';?> >18 mesi</option>
                <option value="24" <?php if(isset($_SESSION['ini']['scPsw']) && $_SESSION['ini']['scPsw']==24) echo ' selected';?> >24 mesi</option>
            </select>
             <label class="form-label" for="scPsw">Scadenza password</label>
        </div>
   
       <div class="form-floating col-md-4">
           
            <select class="form-select inputcfg" id="ggMsgPsw" name="ggMsgPsw" <?php if($_SESSION['ini']['scPsw']==0) echo ' disabled';?>>
                <option value="0" <?php if(isset($_SESSION['ini']['ggMsgPsw']) && $_SESSION['ini']['ggMsgPsw']==0) echo ' selected';?> >Non avvisare</option>
                <option value="5" <?php if(isset($_SESSION['ini']['ggMsgPsw']) && $_SESSION['ini']['ggMsgPsw']==5) echo ' selected';?> >5 giorni</option>
                <option value="10" <?php if(isset($_SESSION['ini']['ggMsgPsw']) && $_SESSION['ini']['ggMsgPsw']==10) echo ' selected';?> >10 giorni</option>
                <option value="15" <?php if(isset($_SESSION['ini']['ggMsgPsw']) && $_SESSION['ini']['ggMsgPsw']==15) echo ' selected';?> >15 giorni</option>
                <option value="20" <?php if(isset($_SESSION['ini']['ggMsgPsw']) && $_SESSION['ini']['ggMsgPsw']==20) echo ' selected';?> >20 giorni</option>
                <option value="25" <?php if(isset($_SESSION['ini']['ggMsgPsw']) && $_SESSION['ini']['ggMsgPsw']==25) echo ' selected';?> >25 giorni</option>
                <option value="30" <?php if(isset($_SESSION['ini']['ggMsgPsw']) && $_SESSION['ini']['ggMsgPsw']==30) echo ' selected';?> >30 giorni</option>
                <option value="35" <?php if(isset($_SESSION['ini']['ggMsgPsw']) && $_SESSION['ini']['ggMsgPsw']==35) echo ' selected';?> >35 giorni</option>
            </select>
            <label class="form-label" for="ggMsgPsw">Preavviso scadenza password</label>
         </div>
    
      <div class="form-floating col-md-4">
       
        <select class="form-select inputcfg" id="scTkn" name="scTkn">
            <option value="0" <?php if(isset($_SESSION['ini']['scTkn']) && $_SESSION['ini']['scTkn']==0) echo ' selected';?> >Nessuna scadenza</option>
            <option value="1" <?php if(isset($_SESSION['ini']['scTkn']) && $_SESSION['ini']['scTkn']==1) echo ' selected';?> >1 ora</option>
            <option value="2" <?php if(isset($_SESSION['ini']['scTkn']) && $_SESSION['ini']['scTkn']==2) echo ' selected';?> >2 ore</option>
            <option value="3" <?php if(isset($_SESSION['ini']['scTkn']) && $_SESSION['ini']['scTkn']==3) echo ' selected';?> >3 ore</option>
            <option value="6" <?php if(isset($_SESSION['ini']['scTkn']) && $_SESSION['ini']['scTkn']==6) echo ' selected';?> >6 ore</option>
            <option value="12" <?php if(isset($_SESSION['ini']['scTkn']) && $_SESSION['ini']['scTkn']==12) echo ' selected';?> >12 ore</option>
            <option value="24" <?php if(isset($_SESSION['ini']['scTkn']) && $_SESSION['ini']['scTkn']==24) echo ' selected';?> >24 ore</option>
            <option value="48" <?php if(isset($_SESSION['ini']['scTkn']) && $_SESSION['ini']['scTkn']==48) echo ' selected';?> >48 ore</option>
        </select>
           <label class="form-label" for="scTkn">Validita' token</label>
    </div>
</div>
<hr />
<div class="row justify-content-evenly">
    
    <!--<div class="col-md-1"></div>-->
    <!-- Middle form - to create and edit  -->
 <!--<div class="row col-md-6 mt-3">-->

<!--<div class="spanAll flexWrap">-->
     <div class="form-floating col-md-6">      
        <input class="form-control inputcfg" id="emailNoRep" type="email" name="emailNoRep" value="<?php if(isset($_SESSION['ini']['emailNoRep'])) echo $_SESSION['ini']['emailNoRep'];?>" />
     <label class="form-label" for="emailNoRep">Email no-reply (no-reply@dominio.it)</label>
     </div>
  <div class="form-floating col-md-6">    
        <select class="form-select inputcfg" id="delLog" name='delLog'>
            <option value="0" <?php if(isset($_SESSION['ini']['delLog']) && $_SESSION['ini']['delLog']==0) echo ' selected';?> >Non mantenere</option>
            <option value="3" <?php if(isset($_SESSION['ini']['delLog']) && $_SESSION['ini']['delLog']==3) echo ' selected';?> >3 mesi</option>
            <option value="6" <?php if(isset($_SESSION['ini']['delLog']) && $_SESSION['ini']['delLog']==6) echo ' selected';?> >6 mesi</option>
            <option value="12" <?php if(isset($_SESSION['ini']['delLog']) && $_SESSION['ini']['delLog']==12) echo ' selected';?> >12 mesi</option>
            <option value="18" <?php if(isset($_SESSION['ini']['delLog']) && $_SESSION['ini']['delLog']==18) echo ' selected';?> >18 mesi</option>
            <option value="24" <?php if(isset($_SESSION['ini']['delLog']) && $_SESSION['ini']['delLog']==24) echo ' selected';?> >2 anni</option>
            <option value="36" <?php if(isset($_SESSION['ini']['delLog']) && $_SESSION['ini']['delLog']==36) echo ' selected';?> >3 anni</option>
            <option value="60" <?php if(isset($_SESSION['ini']['delLog']) && $_SESSION['ini']['delLog']==60) echo ' selected';?> >5 anni</option>
        </select>
        <label class="form-label" for="delLog">Mantieni registrazioni di log</label>
    </div>
   <!--<div class="col-md-4">
        <label class="form-label" for="swScore">Non mostrare totale valutazioni:</label>
        <input class="form-check-input checkcfg" id="swScore" type="checkbox" name="swScore" <?php echo ($_SESSION['ini']['swScore']==1?'checked':'') ?> />
    </div>-->
</div>
    <hr />
 <!-- -------- GESTIONE RUOLI  -------- -->    
<!--<hr class="spanAll"/>-->

 <div class="row mt-3 "><!--justify-content-evenly-->
    <div class="col-md-4 mb-3">
       <!--<div class="mb-3">-->    
            <?php 
                include_once ROOT_PATH.'/include/getruolitree.php'; 
            ?>
         <select id="listAllRoles" class="form-select" size="<?php echo count($result);?>">

            <?php
                foreach($result as $row){ 
                    if($ruolo != $row['idR']){
                        $ruolo = $row['idR'];
                        echo "<option class='optionGroup' value='{$row['idR']}'>{$row['R']}</option>";
                    }
                    if($row['S']) echo "<option class='optionChild' value='{$row['idS']}'>{$row['S']}</option>";
                }
            ?>
        </select>
         <!--</div>-->
    </div>
    <div class="col-md-2 mb-3">
            <button class="btn btn-primary updRuoli" id="delRole">Elimina Ruolo</button>
        </div>
    <div class="col-md-6">
       <div class="row">
        <!--<label class="form-label">Gestione Ruoli:</label>-->
        <div class="form-floating col-md-6">
            <input class="form-control" id="newRolePrim" type="text" />
             <label for="newRolePrim" class="form-label">Nuovo ruolo primario</label>
        </div>
        <div class="col-md-6">
            <button class="btn btn-primary updRuoli" id="insRolePrim">Crea Primario</button>
        </div>
        </div>
            <hr/>
        <div class="row">
        <div class="form-floating col-md-6 mb-3">
            <select id="listRolesSec" class="form-select">
            <?php 
                include_once ROOT_PATH.'/include/getruoliall.php'; 
                //echo '<option disabled selected style="color:gray;">Ruoli secondari</option>';
                foreach($resAllRoles as $row){ 
                    if(!$row['primario']){
                        echo "<option value='{$row['idRl']}'>{$row['ruolo']}</option>";
                    }
                }
            ?>
            </select>
             <label for="listRolesSec" class="form-label">Ruoli secondari</label>
        </div>
             
       <div class="col-md-4 mb-3">
             <button class="btn btn-primary updRuoli" id="addRoleSec">Assegna</button>
       </div>
       </div>
       <div class="row">
           <div class="form-floating col-md-6 mb-3">
                <input class="form-control" id="newRoleSec" type="text" />
                 <label for="newRoleSec" class="form-label">Nuovo ruolo secondario</label>
            </div>
           <div class="col-md-6 mb-3">
               <button class="btn btn-primary updRuoli" id="insRoleSec">Crea Secondario</button>
           </div>
       </div>
      
        </div>
</div>

<!--</div>-->
    </section>

<script type="text/javascript" src="adminsez/static/js/confgenscript.js"></script>
