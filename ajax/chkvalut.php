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
      $chkVoti = [];

      require_once ROOT_PATH.'/include/functions.php';
      //if(!isset($_POST['idBis'])) forbidden();
      $tipoVal=0; //tipo valutazione 0=valutazione normale 1=valutazione con graduatoria
      //$act=104;
      $act=$_POST['Act'];
      //Impostazione tipo attività B = Bisogno = 104, altro = Proposta = 204
      //if ($_POST['Act'] = 'B') {
      //    $act = 104;
      //}
      //else {
      //    $act = 204;
      //}

      /* Chiamata a store procedure per estrazione parametri di configurazione attività */
      $sql = "CALL getActConf(:idAct)";
      if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) $sql = '{'.$sql.'}';

      if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
      try{
          $stmt = $conn->prepare($sql);
          $err=$stmt->bindValue(':idAct', $act);
          $stmt->execute();
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          $dtIni = $row["dtStart"];
          $dtFin = $row["dtStop"];
          $tipoVal = $row["altridati"];
      } catch(PDOException $e) {echo error($e); exit();}

      //Se impostata votazione con graduatoria
      if ($tipoVal == 1) {
          /* Chiamata a store procedure per estrazione numero max di stelle.
          Se $idAct = 104 si tratta di bisogni, se $idAct = 204 si tratta di proposte */

          $sql = "CALL getMaxStar(:idAct)";
          if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) $sql = '{'.$sql.'}';

          //$sql="SELECT numberOfStars.idStar, numberOfStars.vbis AS maxStar FROM numberOfStars;";
          if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
          try{
              $stmt = $conn->prepare($sql);
              $stmt->bindValue(':idAct', $act);
              $stmt->execute();
              $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
              //$chkVal=[];
              for($i=0;$i<count($rows);$i++)
              //foreach($rows as $ro)
              {
                  $idStar = $rows[$i]['idStar'];
                  $max=$rows[$i]['maxstar'];
                  $chkVal[$idStar]=array($max,0);
              }
              //    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
              //    $idStar = $row['idStar'];
              //    $chkVal["$idStar"] = array($row["maxStar"], 0);
              //}
          } catch(PDOException $e) {echo error($e); exit();}
      }else{
          for($i=1;$i<=10;$i++)
              $chkVal[$i] = array(1024, 0);
      }
          $sql = "CALL getNroVoti(:idAct, :idUs, :dtIni, :dtFin)";
          if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) $sql = '{'.$sql.'}';

          if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
          try{
              $stmt = $conn->prepare($sql);
              $stmt->bindValue(':idUs', $_SESSION['user']['idUs']);
              $stmt->bindValue(':dtIni', $dtIni);
              $stmt->bindValue(':dtFin', $dtFin);
              $stmt->bindValue(':idAct', $act);
              $stmt->execute();
              while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                  $i = $row["grade"];
                  $chkVal[$i][1]=$row["tot"];
              }
          } catch(PDOException $e) {echo error($e); exit();}

          $stmt = NULL;
          $conn = NULL;
          if(!isset($_POST['notajax']))
             echo json_encode($chkVal);

          //exit();
      //}
      //else
      //{

      //    //$chkVal=$chkVoti;
      //    if(!isset($_POST['notajax']))
      //        echo json_encode($chkVoti);
      //}
?>