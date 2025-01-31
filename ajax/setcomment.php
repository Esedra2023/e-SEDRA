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

if(isset($_POST['idOrigin']))
{
    $idB=$_POST['idOrigin'];
    $cmt = esc($_POST['testoCommento']); // Assicurati che la funzione `esc` sia sufficiente per la sanificazione
    $rip = $_POST['risp'];
    $us = $_SESSION['user']['idUs'];
    $master = $_POST['master'];
    if ($master === "null")
        $master = null;
    $itable = $_POST['itable'];

    if ($itable == 'B') {
        $campo = 'bisogno';
    } else {
        $campo = 'proposta';
    }

    if (!$conn = connectDB()) {
        echo errorConnectDB();
        exit();
    }

    try {
        if ($_SESSION['ini']['dbms'] == 'My SQL') {
            // Utilizzo dei prepared statements per MySQL
            $sql = "INSERT INTO blog" . $itable . " (autore, $campo, content, risp, idMaster, dtIns) VALUES (?, ?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception('Errore nella preparazione della query');
            }
            // Sostituisce i segnaposto con i valori effettivi
            $stmt->bindParam(1, $us);
            $stmt->bindParam(2, $idB);
            $stmt->bindParam(3, $cmt);
            $stmt->bindParam(4, $rip);
            $stmt->bindParam(5, $master);
            $stmt->execute();

        } else if (stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) {
                // Qui dovresti utilizzare i prepared statements specifici per SQL Server
                // Prepara una dichiarazione SQL
                $stmt = $conn->prepare("INSERT INTO blog" . $itable . " (autore, $campo, content, risp, idMaster) VALUES (?, ?, ?, ?, ?)");

                // Sostituisce i segnaposto con i valori effettivi
                $stmt->bindParam(1, $us);
                $stmt->bindParam(2, $idB);
                $stmt->bindParam(3, $cmt);
                $stmt->bindParam(4, $rip);
                $stmt->bindParam(5, $master);

                // Esegue la dichiarazione
                $stmt->execute();

                //echo "Commento inserito con successo";
        }
    } catch (PDOException $e) {
       echo $e->getMessage();
    }
    $conn = null;
    $stmt = null;
    echo json_encode($idB);
}
else echo json_encode(0);   //non dovrebbe mai succedere






//    $cmt=esc($_POST['testoCommento']);
//    $rip=esc($_POST['risp']);
//    $us= $_SESSION['user']['idUs'];
//    $master= $_POST['master'];
//    $itable=$_POST['itable'];
//    if($itable =='B')
//        $campo='bisogno';
//    else $campo='proposta';
//    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
//    if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0)
//        $sql="INSERT INTO blog".$itable." (autore, $campo, content, risp, idMaster) VALUES ($us, $idB,'{$cmt}', $rip, $master);";       // ($us, $idB);";
//    else if($_SESSION['ini']['dbms'] == 'My SQL')
//        $sql="INSERT INTO blog".$itable." (autore, $campo, content, risp, idMaster, dtIns) VALUES ($us, $idB,'{$cmt}',$rip, $master, now());";
//    $stmt=$conn->query($sql);
//    if(!$stmt) throw new Exception('Errore query set commento');
//    $conn=null;
//    $stmt=null;
//    echo json_encode($idB);
//}
//else echo json_encode(0);   //non dovrebbe mai succedere

//exit(0);

