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
if (!isset($_SESSION['user'])) redirect($_SERVER['HTTP_HOST']);
    // redirect('index.php');

$sql = "CALL setLogLogout(:idUsr, :dtLog)";
if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) $sql = '{'.$sql.'}';

if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
try{
    $stmt = $conn->prepare($sql);
    $stmt->bindValue( ':idUsr', $_SESSION['user']['idUs']);
    $stmt->bindValue( ':dtLog', $_SESSION['user']['dtStart']);
    $stmt->execute();
} catch(PDOException $e) {echo error($e); exit();}
$conn = NULL;

ob_start();  //headers già inviato???
$_SESSION = [];
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-86400, '/');
}
session_destroy();
ob_end_flush();
redirect('../index.php');
//redirect(ROOT_PATH.'/index.php');

//$(window.location).attr('href', 'index.php');

exit();
?>