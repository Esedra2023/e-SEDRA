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
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!defined('ROOT_PATH'))
    define('ROOT_PATH', $_SESSION['ini']['ROOT_PATH']);

require_once ROOT_PATH . '/include/functions.php';
$sql = "CALL getRuoliTree()";
if (stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0)
    $sql = '{' . $sql . '}';

if (!$conn = connectDB()) {
    echo errorConnectDB();
    exit();
}
try {
    $stmp = $conn->query($sql);
    $result = $stmp->fetchAll(PDO::FETCH_ASSOC); //result set per tutti i ruoli
} catch (PDOException $e) {
    echo error($e);
    exit();
}
//$listall='<select id="listAllRoles" class="form-select" size="'. count($result) . '">';
$listall = '';
$ruolo = 1;
    foreach($result as $row){
        if($ruolo != $row['idR']){
            $ruolo = $row['idR'];
            $listall=$listall. '<option class="optionGroup" value="'.$row['idR'].'">'.$row['R'].'</option>';
        }
        if($row['S']) $listall=$listall. '<option class="optionChild" value="'.$row['idS'].'">'.$row['S'].'</option>';
    }
//$listall=$listall.'</select>';
$data['allRoles'] = $listall;

include_once ROOT_PATH . '/include/getruoliall.php';

//$sql = "CALL getRuoliAll()";
//if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) $sql = '{'.$sql.'}';

//if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
//try{
//    $stmp = $conn->query( $sql );
//    $resAllRoles = $stmp->fetchAll(PDO::FETCH_ASSOC); //result set per tutti i ruoli
//} catch(PDOException $e) {echo error($e); exit();}
$listsec = '';
foreach ($resAllRoles as $row) {
    if (!$row['primario']) {
        $listsec=$listsec. '<option value="'.$row['idRl'].'">'.$row['ruolo'].'</option>';
    }
}

$data['secondaryRoles'] = $listsec;
$stmp = NULL;
$conn = NULL;
echo json_encode($data);

