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


if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
    $sql='';
    if(isset($_POST['idAt']))
    {
        $anon=0;
        $ack=1;
        $ida=$_POST['idAt'];
        if($ida==103 || $ida== 203)
            $ack=0;
        if($ida==101 || $ida == 201 || $ida == 300)
            $anon=1;

        $sql =$sql."UPDATE attivita SET active=$ack, anonima=$anon, ballottaggio=0, stato=DEFAULT, giorninoti=DEFAULT,";
        $sql=$sql."dtStart=NULL, notistart=DEFAULT, dtStop=NULL, notistop=DEFAULT, revisore=NULL, altridati=NULL WHERE idAt=$ida;";
        $sql=$sql."DELETE FROM attRuoli WHERE activity = $ida;";
        if($ida==104)
            $sql=$sql."UPDATE numberOfStars SET vbis=1;";
        else if($ida==204)
            $sql=$sql."UPDATE numberOfStars SET vpro=1;";

        $stmt=$conn->query($sql);
        if(!$stmt) throw new Exception('Errore query reset attivita');

        $sql="SELECT * FROM attivita WHERE idAt = $ida;";
        $stmt=$conn->query($sql);
        if(!$stmt) throw new Exception('Errore query reset attivita');
        $riga = $stmt->fetch(PDO::FETCH_ASSOC); //result ; per una riga di attivita

        // $_SESSION['formacti']=$riga;
        //$sql ="SELECT * FROM attivita WHERE idAt=ida;";
        $conn=null;
        echo json_encode($riga);
    }
    else echo json_encode(0);
    exit(0);
