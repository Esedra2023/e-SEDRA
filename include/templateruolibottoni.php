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

require_once(ROOT_PATH . '/adminsez/admin/includes/utilfunctions.php');
require_once ROOT_PATH . '/include/wrapperfunctions.php';

$field=$_POST['field'];
$rolesP=selectPrimaryRoles();

?>

<div id="RuoliPrim" class="col-12 mb-3">
    <div class="btn-group" role="group" aria-label="Elenco Ruoli Primari">
        <input type="radio" class="btn-check p-2 ruolibot" name="Irprim" id="Irp1" value="1" data-field="<?php echo $field;?>" autocomplete="off" checked/>
        <label class="btn btn-outline-primary p-2" for="Irp1">
            <?php echo "Tutti i ruoli" ?>
        </label>
        <?php
        foreach($rolesP as $rol)
        {
			    $rid=$rol['idRl'];
        ?>
            <input type="radio" class="btn-check p-2 ruolibot" name="Irprim" id="Irp<?php echo $rid?>" value="<?php echo $rid?>" data-field="<?php echo $field;?>" autocomplete="off" />
            <label class="btn btn-outline-primary p-2" for="Irp<?php echo $rid?>">
                <?php echo $rol['ruolo']?>
            </label>
        <?php
          }
        ?>
    </div>
</div>

<script src="js/table.js"></script>
<script>
            var jobG = {
        "ncol": 4,
        "c": [
            {
                "type": "text",
                "text": [1, "titlePrp"]
            },
            {
                "type": "text",
                "text": [1, "grade"]
            },
            {
                "type": "text",
                "text": [1, "nlike"]
            },
            {
                "type": "text",
                "text": [1, "votanti"]
            }]
    }
    var rck=document.querySelectorAll(".ruolibot");
    if (rck) {
        for (let i = 0; i < rck.length; i++)
            rck[i].addEventListener("click", (e) => {
                field = e.target.dataset.field;
                call_ajax_viewDefGradPro(field, e.target.value);
            });
    }


    async function call_ajax_viewDefGradPro(field, ruolo) {
        var data = new FormData;
        data.append("field", field);
        data.append("ruolo", ruolo);
        //alert(field+" chiamo getgrad");
        let promo = fetch('ajax/getgradpro.php', {
        method: 'POST',
        body: data
    }).then(successResponse => {
        if (successResponse.status != 200) {
            return null;
        } else {
            return successResponse.json();
        }
    },
        failResponse => {
            //console.log("promessa fallita in aggiorna graduatoria");
            return null;
        }
    );
    //console.log('aspetto che la promessa risolva');
    let result = await promo;
        //console.log('OK..promessa risolta ' + result);
        refreshTable("Gradtable", result, jobG)
//    simulateClick(fakesearch);  //per aggiornare la tabella
}

</script>