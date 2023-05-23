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

    $idBl=$_POST['idBl'];
    $itable=$_POST['itable'];
    //$cmt=$_POST['testoCommento'];
    //$rip=$_POST['risp'];
    //$us= $_SESSION['user']['idUs'];
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
    $sql="UPDATE blog$itable SET stato=1 WHERE idBl=$idBl;";
    if($_SESSION['ini']['dbms'] == 'My SQL')
        $sql=$sql."INSERT INTO segnalaComm$itable (dtIns,utente, commento) VALUES (now(),{$_SESSION['user']['idUs']},$idBl);";
   else
       $sql=$sql."INSERT INTO segnalaComm$itable (utente, commento) VALUES ({$_SESSION['user']['idUs']},$idBl);";

    $stmt=$conn->query($sql);
    if(!$stmt) throw new Exception('Errore query segnala commento');

    $conn=null;
    $stmt=null;
    echo json_encode(1);

    exit(0);

