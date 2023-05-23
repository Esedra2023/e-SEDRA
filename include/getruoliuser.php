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

//    session_start(); if(!isset($_POST['page'])) forbidden();
//    require_once ROOT_PATH.'/include/functions.php';

$sql = "CALL getRuoliUser(:idUs)";
if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) $sql = '{'.$sql.'}';

if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
try{
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':idUs', $_SESSION['user']['idUs']);
    $stmt->execute();
    $resRolesUs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {echo error($e); exit();}

$stmp = NULL;
$conn = NULL;