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

$grad=$_POST['grad'];
$votbp=$_POST['votbp'];
$chkVal=wr_chkvalut($votbp,$grad);
?>

    <div id="infoMessagge" class="my-callout d-none"></div>

    <div class="card w-100 ">
    <!--con h-100 si allunga come quella dei commenti-->
    <div class="card-header">
        <h4>
            Graduatoria
        </h4>
    </div>
    <div class="card-body">

        <table id="tableSt" class="table table-responsive mb-3" name="tableStelle">
            <tr>
                <th><small>Stelle</small></th>
                <th><small>N</small></th>
                <th><small>di</small></th>
                <th><small>Max</small></th>
            </tr>
            <?php
            for($i=1;$i<=10;$i++)
                  {
                $cla="";
                if($chkVal[$i][0] == 0) $cla=$cla." not-aval";
                    else if($chkVal[$i][0] == $chkVal[$i][1]) $cla=$cla." completed";
            ?>
            <tr id="<?php echo 'r'.$i;?>" class="<?php echo $cla; ?>">
                <!--<td>
                    <input type="number" class="form-control star" id="val<?php echo $i;?>" name="val<?php echo $i;?>" value="1" min="0" />
                </td>-->
                <td>
                    <span>&nbsp;</span><?php for($j=1;$j<=$i;$j++) echo "<span style='color:orange'>&#x2605;</span>"?>
                </td>
                <td id="<?php echo 's'.$i;?>">
                    <?php echo $chkVal[$i][1]; ?>
                </td>
                <td>di</td>
                <td>
                    <?php if($grad) echo $chkVal[$i][0]; else echo '&#8734;' ?>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
    <div class="card-footer">      
        <small class="text-muted text-end">           
        </small>
    </div>
    </div>



<script>

    function refreshstelle(nri,nval,ori,oval) {
        var nrig = document.getElementById("s" + nri);
        nrig.innerHTML = nval;
        if (ori != 0) { 
            var orig = document.getElementById("s" + ori);
            orig.innerHTML = oval;
        }
        var tb = document.getElementById("tableSt");
        var c1n = tb.rows[nri].cells[1].innerHTML.trim();
        var c2n = tb.rows[nri].cells[3].innerHTML.trim();
       
        if (c1n == c2n && !tb.rows[nri].classList.contains('completed')) {
           /* alert("metto completed a 1 "+c1n+" "+c2n);*/
            tb.rows[nri].classList.add('completed');
        }
        if (c1n != c2n &&  tb.rows[nri].classList.contains('completed')) {
               /* alert("tolgo completed a 1"+c1n+" "+c2n);*/
            tb.rows[nri].classList.remove('completed');
        }
        if (ori != 0) { 
            var c1o = tb.rows[ori].cells[1].innerHTML.trim();
            var c2o = tb.rows[ori].cells[3].innerHTML.trim();
                /* alert(c1n + " "+ c2n +" "+ c1o+" "+c2o);*/
            if (c1o == c2o && !tb.rows[ori].classList.contains('completed')) {
                   /* alert("metto completed a 1"+c1o+" "+c2o);*/
                tb.rows[ori].classList.add('completed');
            }
            if (c1o != c2o && tb.rows[ori].classList.contains('completed')) {
              /*  alert("tolgo completed a 1"+c1o+" "+c2o);*/
                tb.rows[ori].classList.remove('completed');
                }
        }
    }
</script>