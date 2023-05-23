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
//require_once ('../../config.php');
if (!defined('ROOT_PATH'))
    define ('ROOT_PATH', $_SESSION['ini']['ROOT_PATH']);
require_once ROOT_PATH .'/include/functions.php';

//attenzione ai numeri delle attività

$output=[];
if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
    $sql='';
    if(isset($_POST['idAt']))
    {
        $ida=$_POST['idAt'];
        $sql="SELECT stato FROM attivita WHERE idAt = $ida;";
        $stmt=$conn->query($sql);
        if(!$stmt) throw new Exception('Errore query select attivita');
        $rec = $stmt->fetch(PDO::FETCH_ASSOC); //result set per una riga di attivita
        if($rec['stato']==2)
        {
            $sql="";
            switch($ida)
            {
                case 101:
                    $sql="DELETE FROM bisogni;";
                    $wor="Bisogni";
                    break;
                case 102:
                    break;
                case 103:
                    $sql="DELETE FROM segnalaCommB; DELETE FROM blogB;";
                    $wor="Commenti sui Bisogni";
                    break;
                case 104:
                    $sql="DELETE FROM valBis; DELETE FROM miPiaceB;";
                    $wor="Voti e like sui Bisogni";
                    break;
                case 105:
                    $sql="DELETE FROM gradBisogni;";
                    $wor="Graduatoria Bisogni";
                    break;
                case 201:
                    $sql="DELETE FROM propBis; DELETE FROM proposte;";
                    $wor="Proposte";
                    break;
                case 202:
                    break;
                case 203:
                    $sql="DELETE FROM segnalaCommP;DELETE FROM blogP;";
                    $wor="Commenti sulle Proposte";
                    break;
                case 204:
                    $sql="DELETE FROM valPrp;DELETE FROM miPiaceP; ";
                    $wor="Voti e like delle Proposte ";
                    break;
                case 205:
                    $sql="DELETE FROM gradProposte";
                    $wor="Graduatoria Proposte";
                    break;
            }
            if($sql!="")
            {
                $stmt=$conn->query($sql);
                if(!$stmt) throw new Exception('Errore query delete data from '.$ida);
                $stmt=null;
                $conn=null;
                $output['success']=$wor." eliminati definitivamente";
            }
            else $output['error']="L'attivit&agrave; non ha dati da cancellare";

        } else $output['error']="Attivit&agrave; in corso dati non cancellabili";
    }
    else $output['error']="Attivit&agrave; non specificata";
    echo json_encode($output);
    exit(0);


