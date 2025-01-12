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
<footer class="footer mt-auto text-center text-white bg-primary">          
    <!-- Grid container  p-2 pb-0-->
    <div class="container pt-2"> 
        <!-- Section: Social media -->      
        <section class="mb-2">
            <!-- Facebook -->
            <!--<a class="btn btn-outline-light btn-floating m-1" href="#!" role="button">
                <i class="fab fa-facebook-f"></i>
            </a>-->

            <!-- Twitter -->
            <!--<a class="btn btn-outline-light btn-floating m-1" href="#!" role="button">
                <i class="fab fa-twitter"></i>
            </a>-->

            <!-- mail to -->
            <a class="btn btn-outline-light btn-floating m-1" role="button"  href='<?php echo "mailto:". $_SESSION['ini']['posta']; ?>'>
                <i class="fa fa-envelope"></i>
            </a>

            <!-- Instagram -->                                      <!-- attenzione a cosa mettere di prefisso-->
            <a class="btn btn-outline-light btn-floating m-1" href='<?php echo $_SESSION['ini']['social']; ?>' role="button">
                <i class="fab fa-instagram"></i>
            </a>

            <!-- Linkedin -->
            <!--<a class="btn btn-outline-light btn-floating m-1" href="#!" role="button">
                <i class="fab fa-linkedin-in"></i>
            </a>-->

            <!-- Github -->
          

            <!-- Instagram -->
            <a class="btn btn-outline-light btn-floating m-1" href='<?php echo "https://". $_SESSION['ini']['web']; ?>' role="button">
                <i class="fas fa-globe"></i>
            </a>
            <a class="btn btn-outline-light btn-floating m-1" href="http://itacavercelli.it/omnicrazia" role="button">
                <i class="fab fa-github"></i>
            </a>
        </section>
        <!-- Section: Social media -->
    </div>
    <!-- Grid container -->

    <!-- Copyright  -->
    <div class="footercopy text-center  p-3">
        Sviluppato da:
        Dipartimento di Informatica - IIS LOMBARDI <a class="text-white text-decoration-none" href="http://nuke.itisvc.it/"> (sede ITI Faccio) Vercelli</a>
        <br />Ultimo aggiornamento: Febbraio 2024
    </div>
    <!-- Copyright -->
</footer>

