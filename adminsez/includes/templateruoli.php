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

require_once ROOT_PATH.'/include/functions.php';
require_once(ROOT_PATH . '/adminsez/admin/includes/utilfunctions.php');

$roles=selectPrimaryRoles();
//$num=$_SESSION['template']['numberAct'];
//if($num!=0) $index=$num-1;
//else $index=$num;
//$idtemp=$_SESSION['template']['ruoli'][$index];
$title=$_SESSION['template']['title'];
?>

<fieldset id="templateruoli" class="mb-3 border border-primary rounded px-3">
    <legend class="col-form-label"><?php echo $title?></legend>
    <!--<div class="col-md-1"></div>-->
    <div class="col-md-10">
        <!---->
        <div id="TR" class="mb-3" name="TR">
            <!--TR#-->
            <!--col-12-->
            <?php foreach($roles as $role)
                  {
                      $na='_'.$role['idRl'];
            ?>
            <!--</br>-->
            <div class="form-check">                                                                    
                <input type="checkbox" class="form-check-input ruoliprimari" data-bs-toggle="collapse" data-bs-target="#dv<?php echo $na;?>" data-lui="<?php echo $na;?>" name="rp<?php echo $na;?>" id="rp<?php echo $na;?>" value="<?php echo $role['idRl'];?>" <?php echo (($role['chk'])?'checked':'')?> />

                <label class="form-check-label" for="rp<?php echo $na;?>">
                    <?php echo $role['ruolo']?>
                </label>
            </div>

            <div class="collapse container col-md-9 mb-3 " id="dv<?php echo $na;?>" name="dv<?php echo $na;?>">
                <?php
                      $_POST['idRp']=$role['idRl'];
                      include(ROOT_PATH . '/adminsez/admin/ajax/getruolisec.php');
                      //if ($_SESSION['isEditingUser'] === true){
                      //if($opensb==true){
                      //    $sub=updatechecksubroles($sub,$role['idRl'],$data);
                      //}
                      foreach($sub as $s)
                      {
                          if($s['idS']!=0)
                          {
                ?>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input ruolisec" id="rsec<?php echo $na.'_'.$s['idS'];?>" name="rsec<?php echo $na.'_'.$s['idS'];?>" value="<?php echo $s['idS']?>" <?php if(isset($s['chk'])) echo (($s['chk'])?' checked':'')?> />
                                <label class="form-check-label" for="rsec<?php echo $na .'_'. $s['idS'];?>">
                                    <?php echo $s['S']?>
                                </label>
                            </div>
                            <?php 
                          } 
                      } //sottoruoli?>
            </div>
            <?php } //ruoli?>
        </div>
    </div>
</fieldset>

<script src="adminsez/static/js/ruoliscript.js"></script>
