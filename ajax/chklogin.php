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

if(!isset($_POST['EMAIL'], $_POST['PSW'])) forbidden();
//$_POST['EMAIL']='a';

$sql = "CALL Login(:usn, :scPsw)";
if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) $sql = '{'.$sql.'}';

if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
try{
    $stmt = $conn->prepare($sql);
    $stmt->bindValue( ':usn', $_POST['EMAIL']);
    if(isset($_SESSION['ini']['scPsw']))
        $stmt->bindValue( ':scPsw', $_SESSION['ini']['scPsw']); //se impostata una scad x le psw è > 0
    else
        $stmt->bindValue( ':scPsw', 0);
    $stmt->execute();
    $row = $stmt->fetchAll();
} catch(PDOException $e) {echo error($e); exit();}


if(count($row)!=0 && password_verify($_POST['PSW'], $row[0]['psw'])){
    //salvo perchè ho giorni scadenza password che dopo non ho più
    $_SESSION['user'] = $row[0]; //Dati utente in $_SESSION (record 0 result set)
    // eliminazione dati $_SESSION inutili
    //unset( $_SESSION['user']['psw'],  $_SESSION['user']['X']); //X solo in resultSet MySQL (necessario alla SP)
    try{
        $stmt->nextRowset();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {echo error($e); exit();}
    //carico tutti gli altri dati utente e i relativi ruoli
    // carica ruoli in array associativo $_SESSION['user']['roles']
    // carica permessi in array associativo $_SESSION['user']['grants'] - non carico permessi
    if(count($row)>=1)
    {
        $_SESSION['user']+=$row[0];
        unset( $_SESSION['user']['psw'],$_SESSION['user']['ruolo'],$_SESSION['user']['ruoloNU'],$_SESSION['user']['subruolo'],$_SESSION['user']['subruoloNU']);
        foreach ($row as $r)
        {
            $_SESSION['user']['roles'][$r['ruoloNU']]['nome']= $r['ruolo'];
            if($r['subruoloNU']!=null)
            {
                $_SESSION['user']['roles'][$r['ruoloNU']][$r['subruoloNU']]= $r['subruolo'];
            }
        }
    }
    //foreach($row as $r){
    //    $_SESSION['user']['roles'][$r['ruoloNU']]= $r['ruolo'];
    //    //if($r['bis']) $_SESSION['user']['grants']['bis']= 1;
    //    //if($r['prp']) $_SESSION['user']['grants']['prp']= 1;
    //    //if($r['vlb']) $_SESSION['user']['grants']['vlb']= 1;
    //    //if($r['vlp']) $_SESSION['user']['grants']['vlp']= 1;
    //    //if($r['nws']) $_SESSION['user']['grants']['nws']= 1;
    //    //if($r['blg']) $_SESSION['user']['grants']['blg']= 1;
    //    // controlla se utente appartiene ruolo revisore bisogni e/o proposte
    //    if($r['idRl'] == $_SESSION['ini']['revBis']) $_SESSION['user']['grants']['rvb']= 1;
    //    if($r['idRl'] == $_SESSION['ini']['revPrp']) $_SESSION['user']['grants']['rvp']= 1;
    //}
    session_regenerate_id();
    //CONTROLLO SCADENZA PASSWORD ( x modal dialog login )
    //$gscad = $_SESSION['ini']['scPsw'] * 30;
    if( isset($_SESSION['user']['ggScPsw']) && ($_SESSION['user']['ggScPsw'] < 0)  )
        echo $_SESSION['user']['ggScPsw'];  // torna gg mancanti scadenza psw (<0 se scaduta)
    else echo 'K'; // usn e psw OK
}
else echo 'X'; //usn o psw errati

$stmt = NULL;
$conn = NULL;
exit();
