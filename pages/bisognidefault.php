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

<section class="container mt-3" id="defaultbisogni">
    <h2><?php echo $h2; ?></h2>
    <blockquote class="blockquote">
        <p class="alert alert-primary col-md-12 text-center" role="alert">
            <?php echo $titlePage; ?>
        </p>
    </blockquote>
    <hr />
    <div class="row justify-content-evenly">
        <?php
        $_POST['confirmButton']=false;
        $_POST['revisionsect']=true;
        include(ROOT_PATH . '/include/templatedftbisogno.php');
        ?>

        <div class="table-div col-lg-8 mb-3 mt-3">
            <div id="infoMessaggedx" class="my-callout d-none"></div>
            <?php
                if(isset($_SESSION['ini']['BallottaggioBis']) && $_SESSION['ini']['BallottaggioBis'] == 2)
                {
                    $grad=wr_viewDefGradBis("ingrad");
                    include(ROOT_PATH . '/include/templateaccgradbis.php');
                }
                else if(isset($_SESSION['ini']['gradDefBisogni']) && $_SESSION['ini']['gradDefBisogni'] == 1)
                {
                    $grad=wr_viewDefGradBis("pubblicato");
                    include(ROOT_PATH . '/include/templateaccgradbis.php');
                }
                
                include(ROOT_PATH . '/include/templatetablebis.php');
            ?>
        </div>
    </div>
</section>	

<script >

    ready(function () {
        var collapsableBis;

        let sezB = document.getElementById("collapseBis");
        if (sezB) {
            collapsableBis = new bootstrap.Collapse(sezB, { toggle: false });
        }

        var lsb = document.querySelectorAll('.linkstylebutton');
        if (lsb) {
            for (let i = 0; i < lsb.length; i++) {
                lsb[i].addEventListener("mouseover", (e) => { e.target.style.cursor = 'pointer'; });
                lsb[i].addEventListener("click", (e) => { call_ajax_edit_bis(e.target.dataset.idbis, 'V'); });     //false disabilita i campi });
            }
        }

        //var bistable = document.querySelector('#Bistable');
        //if (bistable) {
        //    bistable.addEventListener("click", (e) => {
        //        if (e.target.nodeName != 'BUTTON' && e.target.nodeName != 'SPAN') { return; }

        //        let elem = e.target;
        //        let span = null;
        //        if (elem.classList.contains("linkstylebutton")) {
        //            let idBisogno = elem.dataset.idbis;
        //            call_ajax_edit_bis(idBisogno, false, collapsableBis);      //false disabilita i campi
        //        }
        //    });
        //}
    }); //end ready

</script>
