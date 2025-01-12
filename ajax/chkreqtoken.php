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
if(!isset($_POST['EMAIL'])) forbidden();

if(!$_SESSION['ini']['emailNoRep']){
   echo -3;
   exit();
}

$sql = "CALL reqToken(:usn, :token, :exp)";
if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) $sql = '{'.$sql.'}';

$token = genToken();
if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
try{
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':usn', $_POST['EMAIL']);
    $stmt->bindValue(':token', $token);
    if(!isset($_SESSION['ini']['scTkn']))
        $_SESSION['ini']['scTkn']=0;
  
    $stmt->bindValue(':exp', $_SESSION['ini']['scTkn']);
    $stmt->execute();
} catch(PDOException $e) {echo error($e); exit();}
$return = $stmt->fetchColumn(0);

if($return) {
    if(sendTokenMail($token,$_POST['EMAIL'])) echo $_SESSION['ini']['scTkn'];
    else echo -2;
}
else echo -1;

$stmt = NULL;
$conn = NULL;
exit();
