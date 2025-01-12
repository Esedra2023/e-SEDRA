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

    $role=$_POST['role'];
    $savegrad=$_POST['savegrad'];
    $field=$_POST['field'];
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
    //if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0){
        $sql="SELECT bisogni.idBs,ambiti.idAm,ambiti.ambito,bisogni.titleBis, bisogni.utente, bisogni.pubblicato, bisogni.dtRev, bisogni.rev, bisogni.deleted, bisogni.ingrad,
        vvll.* FROM bisogni, ambiti,
    (SELECT biv, grade, votanti, nlike
        FROM (SELECT bisogni.idBs as biv, sum(grade) as grade, count(grade) as votanti
            FROM bisogni LEFT JOIN  valBis ON
            bisogni.idBs=valBis.bisogno AND valBis.dtIns >= (SELECT dtStart FROM attivita WHERE idAt=104) AND valBis.dtIns <= (SELECT dtStop FROM attivita WHERE idAt=104)
            GROUP BY bisogni.idBs) AS vv LEFT JOIN
            (SELECT bisogni.idBs as bil,count(idmi) as nlike
            FROM (bisogni LEFT JOIN  miPiaceB ON
            bisogni.idBs=miPiaceB.bisogno AND miPiaceB.dtIns >= (SELECT dtStart FROM attivita WHERE idAt=104) AND miPiaceB.dtIns <= (SELECT dtStop FROM attivita WHERE idAt=104))
            GROUP BY bisogni.idBs) as ll ON vv.biv=ll.bil) as vvll
    WHERE bisogni.idBs=vvll.biv AND ambiti.idAm=bisogni.ambito";

    if($role=="personal")
    {
        $user_id = $_SESSION['user']['idUs'];
        $sql=$sql." AND utente=$user_id ORDER BY grade DESC;";
    }else
    {
        $sql=$sql." ORDER BY grade DESC, nlike DESC, ambito ASC;";
    }
    $stmt = $conn->prepare($sql);
    if(! $stmt->execute()) throw new Exception('Errore query getAllResultPolling');
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if($savegrad){
        $gradposts=[];
        $tot=count($posts);
       foreach($posts as &$pb)
         {
             if(!$pb['deleted'] && $pb[$field]) {
                 $pb['ingrad']=1;
                 array_push($gradposts,$pb);
             }
           //if($pb['ingrad']==1 && !$pb['deleted']){array_push($gradposts,$pb); }
         }
       $bal=0;
       if($field=="ingrad")
           $bal=1;
       //array_splice($posts, 0, $tot);
       ////unset($posts);
       //foreach($gradposts as $gpb)
       //     array_push($posts,$gpb);

        //if($save)
        //{
            //$up="";
            $coll="(";
            $sql = "DELETE FROM gradBisogni WHERE ballot=$bal; INSERT INTO gradBisogni (idBs,IdAm,grade,nlike,votanti, ballot) VALUES ";
        foreach($gradposts as $gb)
            {
                if ($gb['grade'] == null)
                    $gb['grade'] = 0;
                $sql=$sql."({$gb['idBs']},{$gb['idAm']},{$gb['grade']},{$gb['nlike']},{$gb['votanti']},$bal)";
                $sql=$sql.",";
                $coll = $coll . $gb['idBs'].",";
                //$up=$up."UPDATE bisogni SET ingrad=1 WHERE idBs={$gb['idBs']};";
            }
            $rest = substr($sql, 0, -1);
            $rest=$rest.";";
            $coll = substr($coll, 0, -1);   //tolgo virgola finale
            $coll = $coll . ")";
            $rest=$rest."UPDATE bisogni SET ingrad=1 WHERE idBs IN ".$coll;
            $stmt = $conn->prepare($rest);
            if(! $stmt->execute()) throw new Exception('Errore query save graduatoria');
            $_SESSION['ini']['gradDefBisogni']=1;
            updIniFile('Temp', 'gradDefBisogni', 1);

            if($field=="ingrad")
            {
                $_SESSION['ini']['BallottaggioBis']=1;
                updIniFile('Temp', 'BallottaggioBis', 1);

            }

    }
    $conn=null;
    $stmt=null;

    if(!isset($_POST['notajax']))
    {
            echo json_encode($posts);
    }


