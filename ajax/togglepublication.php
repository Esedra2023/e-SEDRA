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

    $id=$_POST['id'];
    $tab=$_POST['table'];
    if($tab=='bisogni')
        $fd='idBs';
    else
        $fd='idPr';
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
    $sql="SELECT pubblicato FROM $tab WHERE $fd=$id;";
    $stmt=$conn->query($sql);
    if(!$stmt) throw new Exception('Errore query get pubblicato');
    $pub = $stmt->fetch(PDO::FETCH_ASSOC);
    if($pub['pubblicato'])  $npub=0;
    else $npub=1;
    $sql ="UPDATE $tab SET pubblicato=$npub WHERE $fd=$id;";
    $stmt=$conn->query($sql);
    if(!$stmt) throw new Exception('Errore query set pubblicato');
    $conn=null;
    $stmt=null;
    echo json_encode($npub);

    exit(0);

