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

    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
    $ida=$_POST['activity'];
    if(isset($_POST['ruoli']))
    {
        $sql="DELETE FROM attRuoli WHERE activity=$ida;";
        $ruoli=json_decode($_POST['ruoli']);         //json_decode($_POST['ruoli']);
        $t=0;

        foreach ($ruoli as $r)
        {
            if($t==0)
            {
                $rol=$r;
                $t++;
            }
            else
            {
                if($r==0)
                    $nosub=1;
                else
                {
                 $sub=$r;
                 $nosub=0;
                }
                $t=0;
            }
            if($t==0)
            {
                if($nosub==1)
                    $sql =$sql."INSERT INTO attRuoli (activity, ruolo) VALUES($ida,$rol);";
               else
                $sql =$sql."INSERT INTO attRuoli (activity, ruolo, sottoruolo) VALUES($ida,$rol,$sub);";
            }
        }
        //$sql ="UPDATE attivita SET active=$chk WHERE idAt=$ida;";

        $stmt=$conn->query($sql);
        if(!$stmt) throw new Exception('Errore query ruoli attivita');
        //$sql="SELECT idAt,nome,active,anonima, scaduta, dtStart,dtStop,revisore,ruoli.ruolo as rev FROM attivita left join ruoli on ruoli.idRl=attivita.revisore WHERE idAt=$ida;";
        //$stmt=$conn->query($sql);
        //if(!$stmt) throw new Exception('Errore query seleziona attivita');
        //$riga = $stmt->fetch(PDO::FETCH_ASSOC); //result set per una riga di attività

        ////$sql ="SELECT * FROM attivita WHERE idAt=ida;";
        $conn=null;
        echo 0;
    }
    exit(0);
    //}
