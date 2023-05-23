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

$fieldb="pubblicato";
if(isset($_SESSION['ini']['BallottaggioBis']) && $_SESSION['ini']['BallottaggioBis']==1)
    $fieldb="ingrad";

$bisogni = getSummaryBis($fieldb);

?>

<fieldset id="templatebis" class="mb-3 border border-secondary rounded px-3">
    <legend class="col-form-label">Bisogni correlati alla proposta:</legend>
    <!--<div class="col-md-1"></div>-->
    <div class="col-md-10">
            <?php foreach($bisogni as $bis)
                  {                  
            ?>
                <div class="overflow-auto" style="max-width: 350px; max-height: 200px;">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input chkbis" name="b<?php echo $bis['idBs'];?>" value="<?php echo $bis['idBs']?>" <?php if(isset($bis['chk'])) echo (($bis['chk'])?' checked':'')?> />
                        <label class="form-check-label" for="b<?php echo $bis['idBs'];?>">
                            <?php echo $bis['titleBis']?>
                        </label>
                    </div>
                </div>
            <?php } //?>
        </div>
</fieldset>

<script>

    ready(function () {

    });

    function getBisogniFromCheck() {
        var bis = [];
        let k = 0;
       var allchk = document.querySelectorAll(".chkbis");
        for (let i = 0; i < allchk.length; i++) {
            if (allchk[i].checked) {
                bis[k] = allchk[i].value;
                k++;
            }
        }
        return bis;
    }

function isInArray(array, search)
{
   //alert(search);
   for(let i=0;i<array.length;i++)
        if(array[i]== search) return true;
   // return array.indexOf(search) >= 0;
return false;
}



    function setBisogniCheck(bis) {
        resetBisogniCheck();
        var allchk = document.querySelectorAll(".chkbis");
        //alert(bis[0]+" "+bis[1]);
            for (let i = 0; i < allchk.length; i++) {
                    //alert('e '+allchk[i].value);
            if (isInArray(bis, allchk[i].value)) {
                //alert('trovato '+allchk[i].value);
                allchk[i].checked = true;
            }
        }
    }

    function resetBisogniCheck() {
        var allchk = document.querySelectorAll(".chkbis");
            for (let i = 0; i < allchk.length; i++) {
            if (allchk[i].checked) {
                allchk[i].checked = false;
            }
        }
    }
</script>