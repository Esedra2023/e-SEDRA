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
<section class="container mt-3" id="defaultproposte">
    <h2><?php echo $h2; ?></h2>
	<blockquote class="blockquote">
    <p class="alert alert-primary col-md-12 text-center" role="alert">
		<?php echo $titlePage;
        ?></p>
  </blockquote>
    <hr />
    <div class="row justify-content-evenly">  
        <?php
        $_POST['confirmButton']=false;
        $_POST['revisionsect']=true;
        include(ROOT_PATH . '/include/templatedftproposta.php');
        ?>

        <div class="table-div col-lg-7 mb-3 mt-3">

            <div id="infoMessaggedx" class="my-callout d-none"></div>
            <?php
            if(isset($_SESSION['ini']['BallottaggioPro']) && $_SESSION['ini']['BallottaggioPro'] == 1)
            {
                //$grad=wr_viewDefGradPro("ingrad",1);
                $_POST['field']="ingrad";
                include(ROOT_PATH . '/include/templateaccgradpro.php');
            }
            else if(isset($_SESSION['ini']['gradDefProposte']) && $_SESSION['ini']['gradDefProposte'] == 1)
            {
                $_POST['field']="pubblicato";
                //$grad=wr_viewDefGradPro("pubblicato",1);
                include(ROOT_PATH . '/include/templateaccgradpro.php');
            }

            include(ROOT_PATH . '/include/templatetablepro.php');
            ?>
        </div>
    </div>
</section>	

<script >

    ready(function () {
        var collapsablePro;

        let sezB = document.getElementById("collapsePro");
        if (sezB) {
            collapsablePro = new bootstrap.Collapse(sezB, { toggle: false });
        }

        var lsb = document.querySelectorAll('.linkstylebutton');
        if (lsb) {
            for (let i = 0; i < lsb.length; i++) {
                lsb[i].addEventListener("mouseover", (e) => { e.target.style.cursor = 'pointer'; });
                lsb[i].addEventListener("click", (e) => {let idProposta = e.target.dataset.idpro;
                                call_ajax_edit_pro(idProposta, 'V');  });
            }
        }
    }); //end ready

</script>
