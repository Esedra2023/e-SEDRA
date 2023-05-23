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

header("Content-type: application/json"); //<-----  NOTARE

require_once ROOT_PATH.'/include/functions.php';
if(!isset($_POST['idPro'])) forbidden();

$sql = "CALL updValPro(:idPro, :idUs, :val)";
if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) $sql = '{'.$sql.'}';

if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
try{
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':idPro', $_POST['idPro']);
    $stmt->bindValue(':idUs', $_SESSION['user']['idUs']);
    $stmt->bindValue(':val', $_POST['val']);
    $stmt->execute();
    //$grBis = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {echo error($e); exit();}

echo json_encode($_POST['val']);

//PROVVISORIA PRIMA DI RIFARE DB
//$idPro=$_POST['idPro'];
//$idUs=$_SESSION['user']['idUs'];
//$val=$_POST['val'];
//$sql="SELECT valPrp.idVp FROM valPrp WHERE valPrp.proposta=$idPro AND valPrp.utente=$idUs AND valPrp.dtIns between (SELECT dtStart FROM attivita WHERE idAt=204) AND DATEADD(DAY, 1, (SELECT dtStop FROM attivita WHERE idAt=204));";
//$stmt = $conn->prepare($sql);
//if(! $stmt->execute()) throw new Exception('Errore query GetvalutazioneProposta');
//$id = $stmt->fetch(PDO::FETCH_ASSOC);
//if($id>0)
//    $sql="UPDATE valPrp SET valPrp.grade=$val, valPrp.dtIns=getdate() WHERE valPrp.idVp={$id['idVp']};";
//else
//    $sql="INSERT INTO valPrp (proposta, utente, grade) VALUES($idPro, $idUs, $val);";
//$stmt = $conn->prepare($sql);
//if(! $stmt->execute()) throw new Exception('Errore query SetValutazioneProposta');

//$stmt = NULL;
//$conn = NULL;
//echo json_encode($val);
exit();
