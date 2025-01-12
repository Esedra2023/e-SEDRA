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
require_once ROOT_PATH .'/include/functions.php';


    $field=$_POST['field'];
    $ruolo=$_POST['ruolo'];
    $bal=0;
    if($field=='ingrad')
        $bal=1;

    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
	$sql = "SELECT gp.*, p.titlePrp, p.textPrp, p.pdfalleg, p.pdforigname,  p.utente, p.dtRev, p.rev, p.pubblicato, p.ingrad FROM gradProposte AS gp, proposte AS p
        WHERE gp.idPr=p.idPr AND gp.idRl=$ruolo AND gp.ballot=$bal ORDER BY gb.grade DESC, gb.nlike DESC, gb.votanti DESC;";
    $stmt = $conn->prepare($sql);
    if(! $stmt->execute()) throw new Exception('Errore query GetGradPro');
    $gradb = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $conn=null;
    $stmt=null;

    if(!isset($_POST['notajax']))
    {
            echo json_encode($gradb);
    }


