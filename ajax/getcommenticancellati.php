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


    if($_SESSION['user']['idUs']!=1)
    {
        if(!isset($_POST['notajax']))
        {
            echo json_encode(0); exit(0);
        }else return null;
    }

    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
	$sql = "SELECT 'B' as Type, b.dtIns, b.content, u.nome as anome, u.cognome as acognome,r.nome as revnome, r.cognome as revcogn, b.dtRev,b.note  FROM blogB as b, utenti as u, utenti as r  WHERE u.idUs=b.autore AND r.idUs=b.revisore AND stato = 3 ";
    $sql=$sql." UNION ";
    $sql=$sql."SELECT 'P', b.dtIns, b.content, u.nome as anome, u.cognome as acognome,r.nome as revnome, r.cognome as revcogn, b.dtRev,b.note  FROM blogP as b, utenti as u, utenti as r  WHERE u.idUs=b.autore AND r.idUs=b.revisore AND stato = 3 ;";
    $stmt = $conn->prepare($sql);
    if(!$stmt->execute()) throw new Exception('Errore query getAllCommentCanceled');
    $comcanc = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(!isset($_POST['notajax']))
    {
        echo json_encode($comcanc);
    }





