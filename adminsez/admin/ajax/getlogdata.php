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
require_once ROOT_PATH.'/include/functions.php';
require_once (ROOT_PATH . '/adminsez/admin/includes/utilfunctions.php');

//if(!isset($_POST['page'])) forbidden();
if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
$sql = "CALL getLogData()";
if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) $sql = '{'.$sql.'}';
$stmt = $conn->query($sql);
if(!$stmt) throw new Exception('Errore query getLogData');
$res = $stmt->fetchAll(PDO::FETCH_GROUP);
$result = processUserRolesString($res,'idLg');

$conn = NULL;
$stmt = NULL;
//$tot=count($result);
if(! $_POST['notajax'])
    echo json_encode($result);
