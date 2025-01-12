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
    $itable=$_POST['itable'];
    if($itable =='B')
        $campo='bisogno';
    else $campo='proposta';
    $id=$_POST['campo'];
    $us= $_SESSION['user']['idUs'];
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
    if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0)
        $sql="INSERT INTO miPiace$itable (utente, $campo) VALUES ($us, $id);";
    else if($_SESSION['ini']['dbms'] == 'My SQL')
        $sql="INSERT INTO miPiace$itable (dtIns, utente, $campo) VALUES (now(),$us, $id);";
    $stmt=$conn->query($sql);
    if(!$stmt) throw new Exception('Errore query set like');
    $conn=null;
    $stmt=null;
    echo '1';
    exit(0);
?>