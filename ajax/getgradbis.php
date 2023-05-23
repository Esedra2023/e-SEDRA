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
    $bal=0;
    if($field=='ingrad')
        $bal=1;

    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
	$sql = "SELECT gb.*, a.ambito, b.titleBis, b.textBis, b.utente, b.dtRev, b.rev, b.pubblicato, b.ingrad FROM gradBisogni AS gb, ambiti AS a, bisogni AS b
        WHERE gb.idAm=a.idAm AND gb.idBs=b.idBs AND a.idAm=b.ambito AND gb.ballot=$bal;";
    $stmt = $conn->prepare($sql);
    if(! $stmt->execute()) throw new Exception('Errore query GetGradBis');
    $gradb = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $conn=null;
    $stmt=null;

    if(!isset($_POST['notajax']))
    {
            echo json_encode($gradb);
    }


