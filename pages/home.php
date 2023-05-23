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

/* oppure così per testare se la sessione è già attiva
 if (!isset($_SESSION)) {
    // no session has been started yet
    session_start();
}*/
      if (!defined('ROOT_PATH'))
          define ('ROOT_PATH', $_SESSION['ini']['ROOT_PATH']);
 //myfunctiontest();
 require_once ROOT_PATH.'/include/functions.php';
 require_once ROOT_PATH.'/include/wrapperfunctions.php';

 if(!isset($_POST['page'])) forbidden();
?>
    <?php
             require ROOT_PATH. '/include/banner.php';
    ?>

<section class="container-fluid">

    <div class="content-wrapper m-3">

        <?php
    require ROOT_PATH. '/include/news.php';
        ?>


        <!-- Gallery -->
        <div class="row mb-3">
            <div id="accantologhi" class="col-md-6 d-flex align-items-center text-end">
                <h2>Partner del progetto:</h2>
            </div>
            <div id="loghi" class="col-md-6">
                <!-- Carosello con controlli Bootstrap -->
                <div id="carouseloghi" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img class="img-responsive d-block h-100" src="images/loghisponsor/logoitaca.webp" title="logoItaca" />
                        </div>
                        <div class="carousel-item">
                            <img class="img-responsive d-block h-100" src="images/loghisponsor/logovc.webp" title="logoVc" />
                        </div>
                        <div class="carousel-item">
                            <img class="img-responsive d-block h-100" src="images/loghisponsor/logoantidisc.webp" title="logoAntiDisc" />
                        </div>
                        <div class="carousel-item">
                            <img class="img-responsive d-block h-100" src="images/loghisponsor/logofcr.webp" title="logofcr" />
                        </div>
                        <div class="carousel-item">
                            <img class="img-responsive d-block h-100" src="images/loghisponsor/bancaitalia.webp" title="logoBancadItalia" />
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!--</div>-->
    <script>
      /*  $('.carousel').carousel
            ({
                interval: 5000,
                cycle:true
            });*/
    </script>
</section>


