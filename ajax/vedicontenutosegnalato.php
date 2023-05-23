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
if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
$it=$_POST['itable'];
if($it=='B')
{
    $tab='bisogni';
    $sql="SELECT b.titleBis as title, bB.idBl,bB.content,".dateString('bB.dtIns', false)." as dtIns,  u.nome, u.cognome,bB.risp, bB.idMaster
    FROM blogB AS bB, bisogni AS b, utenti AS u
    WHERE bB.bisogno=b.idBs AND bB.autore=u.idUs AND bB.idBl=(SELECT commento FROM segnalaCommB WHERE idSC={$_POST['idSC']})";
}else{
    $tab='proposte';
    $sql="SELECT p.titlePrp as title, bP.idBl,bP.content, ".dateString('bP.dtIns', false)." as dtIns, u.nome, u.cognome,bP.risp, bP.idMaster
    FROM blogP AS bP, proposte AS p, utenti AS u
    WHERE bP.proposta=p.idPr AND bP.autore=u.idUs AND bP.idBl=(SELECT commento FROM segnalaCommP WHERE idSC={$_POST['idSC']})";
}
//$sql = $sql." bB.idBl,bB.content,bB.dtIns, u.nome, u.cognome,bB.risp, bB.idMaster
//FROM blog$it AS bB, $tab AS b, utenti AS u
//WHERE bB.bisogno=b.idBs AND bB.autore=u.idUs AND bB.idBl=(SELECT commento FROM segnalaCommB WHERE idSC={$_POST['idSC']})";
     try{
        $stmt = $conn->prepare($sql);
        if(! $stmt->execute()) throw new Exception('Errore query get seleziona commento');
        $bl = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {echo error($e); exit();}

if($bl['risp']==1)
{
    //prelevo il commento originario
    $sql = "SELECT bB.content,".dateString('bB.dtIns', false)." as dtIns, u.nome, u.cognome
    FROM blog$it AS bB, utenti AS u
    WHERE bB.autore=u.idUs AND bB.idBl={$bl['idMaster']};";
    try{
        $stmt = $conn->prepare($sql);
        if(! $stmt->execute()) throw new Exception('Errore query get seleziona commento master');
        $mas = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {echo error($e); exit();}
    $bl['mas']=$mas;
}
else {
    //vado a cercare eventuali risposte
    $sql = "SELECT bB.content,".dateString('bB.dtIns', false)." as dtIns, u.nome, u.cognome
    FROM blog$it AS bB, utenti AS u
    WHERE bB.autore=u.idUs AND bB.idMaster={$bl['idBl']};";
    try{
        $stmt = $conn->prepare($sql);
        if(! $stmt->execute()) throw new Exception('Errore query get seleziona risposte commento ');
        $risp = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {echo error($e); exit();}
    //$bl['vrisp']=[];
    $bl['vrisp']=$risp;
}
//$idb=$bl['commento'];
//if($idb!=null)
//{
//    if($_POST['crud']=='U'){

//        if($_SESSION['ini']['dbms'] == 'My SQL')
//            $sql = "UPDATE blogB SET stato=2, dtRev=now(), note='Riabilitato' WHERE idBl=$idb; DELETE FROM segnalaCommB WHERE idSC={$_POST['idSC']};";
//        else
//            $sql = "UPDATE blogB SET stato=2, dtRev=getdate(), note='Riabilitato' WHERE idBl=$idb; DELETE FROM segnalaCommB WHERE idSC={$_POST['idSC']};";
//        try{
//            $stmt = $conn->prepare($sql);
//            if(! $stmt->execute()) throw new Exception('Errore query get riabilita commento');
//            //$bl = $stmt->fetch(PDO::FETCH_ASSOC);
//        } catch(PDOException $e) {echo error($e); exit();}
//    }
//    else if($_POST['crud']=='D')
//    {
//        if($_SESSION['ini']['dbms'] == 'My SQL')
//        {
//            $sql = "UPDATE blogB SET stato=3, dtRev=now(), note='Rimosso master' WHERE idMaster=$idb;";
//            $sql=$sql."UPDATE blogB SET stato=3, dtRev=now(), note='Offensivo' WHERE idBl=$idb;";
//        }
//        else{
//            $sql = "UPDATE blogB SET stato=3, dtRev=getdate(), note='Rimosso master' WHERE idMaster=$idb;";
//            $sql=$sql."UPDATE blogB SET stato=3, dtRev=getdate(), note='Offensivo' WHERE idBl=$idb;";
//        }

//        $sql=$sql."DELETE FROM segnalaCommB WHERE idSC={$_POST['idSC']};";

//        try{
//            $stmt = $conn->prepare($sql);
//            if(! $stmt->execute()) throw new Exception('Errore query get riabilita commento');
//            //$bl = $stmt->fetch(PDO::FETCH_ASSOC);
//        } catch(PDOException $e) {echo error($e); exit();}
//    }
//}
$stmt= NULL;
$conn = NULL;
echo json_encode($bl);
exit();
