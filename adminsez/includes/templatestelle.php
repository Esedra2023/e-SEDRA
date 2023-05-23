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

?>

<fieldset id="tuttestelle" class="mb-3 border border-danger rounded">
    <legend class="col-form-label">Numero max di scelte:</legend>
    <!--<div class="col-md-1"></div>-->
    <div class="col-md-10">
        <!---->
        <table id="tableStelle" class="mb-3" name="tableStelle">
           
            <?php for($i=1;$i<=10;$i++)
                  {
            ?>
            <tr>
                <td>
                    <input type="number" class="form-control star" id="val<?php echo $i;?>" name="val<?php echo $i;?>" value="1" min="0" />
                </td>
                <td>
                    <span>&nbsp;</span><?php for($j=1;$j<=$i;$j++) echo "<span style='color:orange'>&#x2605;</span>"?>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</fieldset>

<script>


    function checkedstelle(star,campo)       
    {
       /* console.log(star);*/
        for (let j in star) {
           /* console.log("j " + j);*/
            var ind = 'val' + star[j]['idstar'];  
           /* console.log('ind '+ind);*/
            document.getElementById(ind).value = star[j][campo];
        }
    }
   
</script>