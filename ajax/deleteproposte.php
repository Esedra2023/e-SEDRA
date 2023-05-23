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

$output=[];
if(!$conn = connectDB()) {echo errorConnectDB(); exit();}

//recuperare il nome dell'allegato prima di cancellare
$sql="SELECT pdfalleg FROM proposte WHERE idPr={$_POST['pro_id']};";
$stmt = $conn->query($sql);
if(!$stmt) throw new Exception('Errore query get file name');
$recs = $stmt->fetch(PDO::FETCH_ASSOC);
$filename=$recs['pdfalleg'];
$logicCanc=0;
if(isset($_POST['clogic']) && $_POST['clogic'])
   $logicCanc=1;

    $sql = "CALL delProposte(:how,:logic)";
    if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) $sql = '{'.$sql.'}';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':how',$_POST['pro_id']);		// id per cancellare
    $stmt->bindValue(':logic',$logicCanc);
    if(! $stmt->execute()) throw new Exception('Errore query cancella proposta');
    $conn=null;
    $stmt=null;
    //rimuovere il file allegato se non era una cancellazione logica
    if(!$logicCanc)
    {

        $path=$_SERVER['DOCUMENT_ROOT']."\\uploadpdf\\".$filename;
        if (false === unlink($path)) {
            $output['errors']="Impossibile eliminare il file ".$filename;
        }
    }
    $output['success'] =  "Proposta cancellata";
    echo json_encode($output);
    exit(0);
