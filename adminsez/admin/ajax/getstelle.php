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


    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
    $campo=$_POST['campo'];
    $sql = "SELECT idstar,[$campo] FROM numberOfStars;";
    if(! $stmt=$conn->query($sql)) throw new Exception('Errore query getstelle');

    $stelle = $stmt->fetchAll(PDO::FETCH_ASSOC); //result set per tutte le attività
    echo json_encode($stelle);

    $stmt = NULL;
    $conn = NULL;
?>
