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
//header('Content-Type: application/json');

//global $editU,$roleUser, $roles;
//$cognomeU, $nomeU, $emailU, $telU;
if($_POST['view'] == 0)     //salvo solo per l'edit
{
    $_SESSION['isEditingUser'] = true;
    $_SESSION['user_id'] = $_POST['user_id'];
}
if(!$conn = connectDB()) {echo errorConnectDB(); exit();}

$sql = "CALL getOneUser(:how, :pw)";
if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) {
    $sql = '{'.$sql.'}';
}
$stmt = $conn->prepare($sql);
$stmt->bindValue(':how',$_POST['user_id']);
$stmt->bindValue(':pw', $_POST['pw']);     //no password 0
if(! $stmt->execute()) throw new Exception('Errore query EditUser');
$user = $stmt->fetchAll(PDO::FETCH_GROUP);
$editu=processUserRolesNumber($user);
unset($editu['psw'], $editu['ruoloNU'],$editu['subruoloNU']);
//$_SESSION['user_id']=$editu['idUs'];
$roleUser=updatecheckroles($editu);
//$editu+=$roleUser;
//$_SESSION['roleUser']=$roleUser;
//$str=json_encode($editu).json_encode($roleUser);
//echo $str;
if(isset($_SESSION['ini']['scPsw']))
    $m=$_SESSION['ini']['scPsw'];
else
    $m=0;
if($m!=0)
{
    $dtStop=calcolaDataAfter($editu['dtPsw'],$m,'M');
    $gg=calcolaGiorniAllaScadenza($editu['dtPsw'],$dtStop);
    $editu['dtScPsw']=$dtStop;
    $editu['ggscPsw']=$gg;
}else $editu['dtScPsw']=0;
echo json_encode($editu);
$conn=null;
$stmt=null;
