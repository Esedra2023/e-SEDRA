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
if(!isset($_POST['idRl'])) forbidden();

$sql = "CALL updRuoliUser(:idUs, :idRl, :val)";
if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) $sql = '{'.$sql.'}';

if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
try{
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':idUs', $_SESSION['user']['idUs']);
    $stmt->bindValue(':idRl', $_POST['idRl']);
    $stmt->bindValue(':val', $_POST['val']);
    $stmt->execute();
} catch(PDOException $e) {echo error($e); exit();}

///////////////////AGGIORNARE SESSION----------------------------!!!!!
//	Se eliminato ruolo revisore (bisogni e/o proposte) si aggiorna file INI
/*
if($_POST['val']=='' && $_POST['idRl'] == $_SESSION['ini']['revBis']){
    $_SESSION['ini']['revBis'] = 0;
    updIniFile('Settings', 'revBis', 0);
}
if($_POST['val']=='' && $_POST['idRl'] == $_SESSION['ini']['revPrp']){
    $_SESSION['ini']['revPrp'] = 0;
    updIniFile('Settings', 'revPrp', 0);
}
*/
$stmt = NULL;
$conn = NULL;
exit();
