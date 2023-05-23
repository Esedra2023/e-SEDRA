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
    $sql="SELECT p.idPr,p.titlePrp, p.utente, p.pubblicato, p.dtRev, p.rev, p.deleted, p.ingrad,
        vvll.* FROM proposte AS p,
    (SELECT piv, grade, votanti, nlike
        FROM (SELECT proposte.idPr as piv, sum(grade) as grade, count(grade) as votanti
            FROM proposte LEFT JOIN  valPrp ON
            proposte.idPr=valPrp.proposta AND valPrp.dtIns >= (SELECT dtStart FROM attivita WHERE idAt=204) AND valPrp.dtIns <= (SELECT dtStop FROM attivita WHERE idAt=204)
            GROUP BY proposte.idPr) AS vv LEFT JOIN
            (SELECT proposte.idPr as pil,count(idmi) as nlike
            FROM (proposte LEFT JOIN  miPiaceP ON
            proposte.idPr=miPiaceP.proposta AND miPiaceP.dtIns >= (SELECT dtStart FROM attivita WHERE idAt=204) AND miPiaceP.dtIns <= (SELECT dtStop FROM attivita WHERE idAt=204))
            GROUP BY proposte.idPr) as ll ON vv.piv=ll.pil) as vvll
    WHERE p.idPr=vvll.piv";


    //$sql="SELECT bisogni.idBs,ambiti.idAm,ambiti.ambito,bisogni.titleBis, bisogni.utente, bisogni.pubblicato, bisogni.dtRev, bisogni.rev, bisogni.deleted, bisogni.ingrad,
    //    vvll.* FROM bisogni, ambiti,
    //(SELECT biv, grade, votanti, nlike
    //    FROM (SELECT bisogni.idBs as biv, sum(grade) as grade, count(grade) as votanti
    //        FROM bisogni LEFT JOIN  valBis ON
    //        bisogni.idBs=valbis.bisogno AND valBis.dtIns between (SELECT dtStart FROM attivita WHERE idAt=104) AND DATEADD(DAY, 1, (SELECT dtStop FROM attivita WHERE idAt=104))
    //        GROUP BY bisogni.idBs) AS vv LEFT JOIN
    //        (SELECT bisogni.idBs as bil,count(idmi) as nlike
    //        FROM (bisogni LEFT JOIN  miPiaceB ON
    //        bisogni.idBs=miPiaceB.bisogno AND miPiaceB.dtIns between (SELECT dtStart FROM attivita WHERE idAt=104) AND DATEADD(DAY, 1, (SELECT dtStop FROM attivita WHERE idAt=104)))
    //        GROUP BY bisogni.idBs) as ll ON vv.biv=ll.bil) as vvll
    //WHERE bisogni.idBs=vvll.biv AND ambiti.idAm=bisogni.ambito";
    if($role=="personal")
    {
        $user_id = $_SESSION['user']['idUs'];
        $sql=$sql." AND utente=$user_id ORDER BY grade DESC;";
    }else
    {
        $sql=$sql." ORDER BY grade DESC, nlike DESC;";
    }
    $stmt = $conn->prepare($sql);
    if(! $stmt->execute()) throw new Exception('Errore query getAllResultPolling Proposte');
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
       //non faccio conteggio dei voti dell'amministratore da solo come ruolo 1 per conflitto con la graduatoria principale
       $sqlp="SELECT idPr,titlePrp, pubblicato, deleted, ingrad, m.* FROM(SELECT proposta,t.ruolo, SUM(t.grade) AS grade, votanti FROM
(SELECT DISTINCT valPrp.*,ruolo, count(*) AS votanti FROM valPrp LEFT JOIN ruoliUtenti on valPrp.utente=ruoliUtenti.utente WHERE dtins>= (SELECT dtStart FROM attivita WHERE idAt=204) AND
dtIns <= (SELECT dtStop FROM attivita WHERE idAt=204) AND ruolo<> 1 GROUP BY ruolo, idVp,dtIns,valPrp.utente, proposta, grade ) as t
GROUP BY ruolo, proposta,votanti ) as m, proposte WHERE proposte.idPr=m.proposta ORDER BY ruolo, Voti DESC;
";
       $stmt = $conn->prepare($sqlp);
       if(! $stmt->execute()) throw new Exception('Errore query getAllResultPolling Proposte');
       $gradruoli = $stmt->fetchAll(PDO::FETCH_ASSOC);

       $gradruolidef=[];
       //$totr=count($posts);
       foreach($gradruoli as &$gb)
       {
           if(!$gb['deleted'] && $gb[$field]) {
               $gb['ingrad']=1;
               array_push($gradruolidef,$gb);
           }
       }

       $bal=0;
       if($field=="ingrad")
           $bal=1;

            $up="";
            $sql="INSERT INTO gradProposte (idPr,idRl,grade,nlike,votanti, ballot) VALUES ";
            foreach($gradposts as $gb)      //idRl graduatoria per ruolo 1 tutti ruoli - devo per forza mettere un ruolo assegnabile è FK
            {
                $sql=$sql."({$gb['idPr']},1,{$gb['grade']},{$gb['nlike']},{$gb['votanti']},$bal)";
                $sql=$sql.",";
                $up=$up."UPDATE proposte SET ingrad=1 WHERE idPr={$gb['idPr']};";
            }
            foreach($gradruolidef as $gdf)
            {
                $sql=$sql."({$gdf['idPr']},{$gdf['ruolo']},{$gdf['grade']},0,{$gdf['votanti']},$bal)";
                $sql=$sql.",";
            }
            $rest = substr($sql, 0, -1);
            $rest=$rest.";";
            $rest=$rest.$up;
            $stmt = $conn->prepare($rest);
            if(! $stmt->execute()) throw new Exception('Errore query save graduatoria proposte');
            $_SESSION['ini']['gradDefProposte']=true;
            updIniFile('Temp', 'gradDefProposte', true);

            if($field=="ingrad")
            {
                $_SESSION['ini']['BallottaggioPro']=true;
                updIniFile('Temp', 'BallottaggioPro', true);

            }

        //}
    }
    $conn=null;
    $stmt=null;

    if(!isset($_POST['notajax']))
    {
            echo json_encode($posts);
    }


