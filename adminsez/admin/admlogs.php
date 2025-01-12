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
//require_once ROOT_PATH.'/include/functions.php';
require_once (ROOT_PATH . '/adminsez/admin/includes/utilfunctions.php');

$result=wr_getDataLogs();
$tot=count($result);
?>

<section class="container" id="logs">
    <h2 class="page-title mt-3">Registro accessi</h2>
    <hr />

    <div class="row justify-content-evenly">
 
     <!--<div class="col-md-6 mt-3">-->
        
         <!--</div>-->
           
            <div class="col-3 text-start">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item">
                            <button type="button" class="page-link" id="backP" aria-label="Previous">
                                <span aria-hidden="true" class="bi bi-arrow-left-square"></span>
                            </button>
                        </li>
                        <li class="page-item">
                            <a class="page-link disable" tabindex="-1">
                                Accessi: <span id="utenti_totali">
                                    <?php echo $tot;?>
                                </span>
                            </a>
                        </li>
                        <li class="page-item">
                            <button type="button" class="page-link" id="nextP" href="" aria-label="Next">
                                <span aria-hidden="true" class="bi bi-arrow-right-square"></span>
                            </button>
                        </li>
                    </ul>
                </nav>
            </div>
        <div class="col-4"></div>
        <form method='POST' class="row g-3 text-end col-5" action='adminsez/admin/writelogs.php'>
            <!--<input type='submit' value='Export' name='Export' />-->
            <div class="col-md-6">
                <input type="submit" class="btn btn-primary" value="Esporta registrazioni" />
            </div>
            <div class="col-md-6">
                <button class="btn btn-primary" id="delLog">Elimina registrazioni</button>
            </div>
        </form>
</div>
    <hr />
            <div class="table-responsive mt-3 mb-3">
                <table id="Utable" class="table table-striped table-sm">
                    <!---->
                    <thead>
                        <tr>
                            <!--<th scope="col">id</th>-->
                            <th scope="col">Utente</th>
                            <th scope="col">Ruolo</th>
                            <th scope="col">e-mail</th>
                            <th scope="col">Data Login</th>
                            <th scope="col">Data Logout</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider ">
                        <?php
                        foreach($result as $row) {
                            $dfi = $row["dtE"];
                            if ($row["dtE"] == "")
                                $dfi = "...";
                            echo "<tr><td>" . $row["cognome"] . " " . $row["nome"] . "</td><td>" . $row["ruolo"] . "</td><td>" . $row["email"] . "</td><td>". $row["dtS"] . "</td><td>" . $dfi . "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
        </div>
       
</section>

<script>
    // var btex = document.getElementById("exportLog");
    //btex.addEventListener("click", function () {
    //    call_ajax_write_logs();
        
    //});

    //function call_ajax_crea_pdf()
    //{
        
    //}
     var btdel = document.getElementById("delLog");
     btdel.addEventListener("click", function () { call_ajax_delete_logs(); });

async function call_ajax_delete_logs() {

    let promo = fetch('adminsez/admin/ajax/deletelogs.php', {
        method: 'POST'
       // body: data
    }).then(successResponse => {
        if (successResponse.status != 200) {
            return null;
        } else {
            return successResponse.json();
        }
    },
        failResponse => {
            //console.log("promessa fallita in delete logs");
            return null;
        }
    );
    //console.log('aspetto che la promessa risolva');
    let result = await promo;
   // console.log('OK..promessa risolta ' + result);
        window.location.reload(); 
}

//async function call_ajax_write_logs() {

//    let promo = fetch('adminsez/admin/writelogs.php', {
//        method: 'POST'
//       // body: data
//    }).then(successResponse => {
//        if (successResponse.status != 200) {
//            return null;
//        } else {
//            return successResponse.json();
//        }
//    },
//        failResponse => {
//            console.log("promessa fallita in delete logs");
//            return null;
//        }
//    );
//    console.log('aspetto che la promessa risolva');
//    let result = await promo;
//    console.log('OK..promessa risolta ' + result);

//    //link = document.getElementById("linkfile");
//    //    if (link.classList.contains("d-none")) {
//    //        link.classList.remove("d-none)");
//    //        link.setAttribute("href", result);
//    //    }
//}

</script>