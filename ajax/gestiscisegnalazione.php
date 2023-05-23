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
if(!isset($_POST['idSC'])) forbidden();
$output=[];
$output['idSC']=$_POST['idSC'];
$itable=$_POST['itable'];

if(!isset($_POST['ndr']) || $_POST['ndr']=="")
{
    $output['error']="Nota del revisore mancante";
    echo json_encode($output);
    exit();
}
if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
$revisor=$_SESSION['user']['idUs'];
$sql = "SELECT commento FROM segnalaComm$itable WHERE idSC={$_POST['idSC']};";
     try{
        $stmt = $conn->prepare($sql);
        if(! $stmt->execute()) throw new Exception('Errore query get seleziona commento');
        $bl = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {echo error($e); exit();}
$idb=$bl['commento'];


if($idb!=null)
{
    if($_POST['crud']=='U'){
        if($_SESSION['ini']['dbms'] == 'My SQL')
            $sql = "UPDATE blog$itable SET stato=2, dtRev=now(), revisore=$revisor, note='{$_POST['ndr']}' WHERE idBl=$idb; DELETE FROM segnalaComm$itable WHERE idSC={$_POST['idSC']};";
        else
            $sql = "UPDATE blog$itable SET stato=2, dtRev=getdate(), revisore=$revisor, note='{$_POST['ndr']}' WHERE idBl=$idb; DELETE FROM segnalaComm$itable WHERE idSC={$_POST['idSC']};";
        try{
            $stmt = $conn->prepare($sql);
            if(! $stmt->execute()) throw new Exception('Errore query get riabilita commento');
            //$bl = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {echo error($e); exit();}
        $output['success']="Commento riabilitato";
    }
    else if($_POST['crud']=='D')
    {
        if($_SESSION['ini']['dbms'] == 'My SQL')
        {
            $sql = "UPDATE blog$itable SET stato=3, dtRev=now(), revisore=$revisor, note='{$_POST['ndr']}' WHERE idMaster=$idb;";
            $sql=$sql."UPDATE blog$itable SET stato=3, dtRev=now(), revisore=$revisor, note='{$_POST['ndr']}' WHERE idBl=$idb;";
        }
        else{
            $sql = "UPDATE blog$itable SET stato=3, dtRev=getdate(), revisore=$revisor, note='{$_POST['ndr']}' WHERE idMaster=$idb;";
            $sql=$sql."UPDATE blog$itable SET stato=3, dtRev=getdate(), revisore=$revisor, note='{$_POST['ndr']}' WHERE idBl=$idb;";
        }

        $sql=$sql."DELETE FROM segnalaComm$itable WHERE idSC={$_POST['idSC']};";

        try{
            $stmt = $conn->prepare($sql);
            if(! $stmt->execute()) throw new Exception('Errore query get riabilita commento');
            //$bl = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {echo error($e); exit();}
        $output['success']="Commento cancellato con le sue eventuali risposte";
    }
} else $output['error']="Segnalazone riferita ad un commento non più esistente";
$stmt= NULL;
$conn = NULL;
echo json_encode($output);
exit();
