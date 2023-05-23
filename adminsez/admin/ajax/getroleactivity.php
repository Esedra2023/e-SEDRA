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

//if(isset($_POST['idAt']) && $_POST['idAt']!=null) {

    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
    $idact=$_POST['idAt'];

    $sql = "CALL getRolesActivities(:ida)";
    if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) $sql = '{'.$sql.'}';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':ida',$idact);
    if(! $stmt->execute()) throw new Exception('Errore query get roles activity');

    $ar = $stmt->fetchAll(PDO::FETCH_ASSOC); //result set per tutte le attività
    $tot=count($ar);
    //if(!$_POST['notajax'])
    //{
        if($tot!=0)
            echo json_encode($ar);
        else echo $tot;
    //}
    $stmt = NULL;
    $conn = NULL;
?>
