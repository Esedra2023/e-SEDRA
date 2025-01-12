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

function wr_getDataLogs(){
    $_POST['notajax']=1;
    include(ROOT_PATH . '/adminsez/admin/ajax/getlogdata.php');
    return $result;
}


/* * * * * * * * * * * * * * *
 * Receives a activity id and
 * Returns roles for activity
 * * * * * * * * * * * * * * */
//function getRoleActivity($idact) {
//    // use global $conn object in function
//    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
//    $sql = "SELECT ruolo as idR, sottoruolo as idS FROM attRuoli WHERE activity=$idact;";
//    //if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) {
//    //    $sql = '{'.$sql.'}';
//    //}

//    //try{
//    $stmt = $conn->prepare($sql);
//    if(! $stmt->execute())throw new Exception('Errore query getRole Activity');
//    $dataroles = $stmt->fetchAll(PDO::FETCH_ASSOC);
//    $conn=null;
//    $stmt=null;
//    //} catch(PDOException $e) {echo 'Errore query getPublishedPosts '.$e->getMessage();}

//    //$final_posts = array();
//    //foreach ($posts as &$post) {
//    //    //unset($post['ambito']);
//    //    $post['ambitoName'] = getPostTopic($post['idBs']);
//    //    //array_push($final_posts, $post);
//    //}
//    return $dataroles;
//}


function  setAttivitaScadIncorso(&$act)     //eliminato campo scaduta, se scaduta valore di stato =2
{
    date_default_timezone_set('Europe/Rome');
    $stato=$act['stato'];
    if( $act['dtStart']!=null && $act['dtStop']!=null && $act['active'])
    {
        $today=new DateTime("now");

        $din=new DateTime($act['dtStart']);
        $dfin=new DateTime($act['dtStop']);
        //$dfin->add(new DateInterval('P1D'));

        //$dain=calcolaGiorniAllaScadenza($today,$act['dtStart']);
        //$dafin=calcolaGiorniAllaScadenza($act['dtStop'],$today);
        //$gg=calcolaGiorniAllaScadenza($act['dtStart'],$act['dtStop']);
        //$dtS = $act['dtStart'];
        //$dtS = strtotime($dtS);
        //$dtC = strtotime("now");
        //$dtE = $act['dtStop'];
        //$dtE = strtotime($dtE);
        //if ($dtS<=$dtC && $dtE>=$dtC)
        if($today>=$din && $today <= $dfin)
        {
            $act['stato']="1";       //in corso
        }
        else if($today>$din && $today > $dfin)
        {
            $act['stato']="2";      //scaduta terminata riprogrammabile
            //$act['scaduta']=true;
        }else if($today<$din && $today < $dfin)
            $act['stato']="0";       //non ancora partita
    }
    else
        $act['stato']="0";       //attiva/da attivare non ancora partita
    if($stato != $act['stato'])
        aggiornaStatoInDB($act['idAt'],$act['stato']);//,$act['scaduta']);
}

function aggiornaStatoInDB($ida,$sta)//,$scad)
{
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
    //, scaduta='$scad'
    $sql ="UPDATE attivita SET stato='$sta'WHERE idAt=$ida;";
    $stmt=$conn->query($sql);
    if(!$stmt) throw new Exception('Errore query aggiorna stato attivita');

    $stmt=null;
    $conn=null;
}
function associaRuoliattivita()
{
    $sql = "SELECT attivita.*,ruoli.ruolo as rev FROM attivita left join ruoli on ruoli.idRl=attivita.revisore;";
    //if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) $sql = '{'.$sql.'}';

    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
    //if(! ($stmt=$conn->query($sql))) throw new Exception('Errore query search ambito per nome');
    $stmt = $conn->query($sql);
	if(!$stmt) throw new Exception('Errore query getAttivita');
    $ac = $stmt->fetchAll(PDO::FETCH_ASSOC); //result set per tutte le attività


    foreach($ac as &$act)
    {
        setAttivitaScadIncorso($act);
        //$ra[] = wr_getRolesActivity($act['idAt']);
        //NON RIUTILIZZO LA FUNZIONE PERCHé RESTITUISCE UN JSON
        //include(ROOT_PATH . '/adminsez/admin/ajax/getroleactivity.php');

        $sql = "CALL getRolesActivities(:ida)";
        if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) $sql = '{'.$sql.'}';
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':ida',$act['idAt']);
        if(! $stmt->execute()) throw new Exception('Errore query get roles activity');
        $ra = $stmt->fetchAll(PDO::FETCH_ASSOC); //result set per tutte le attività
        if(count($ra)!=0)
        {
            $str=processRolesToString($ra);
            $act['raut']=$str;
        }else
        {
            $act['raut']="---";
        }
    }
    return $ac;
}



function selectPrimaryRoles()
{
    if(!isset($_SESSION['allRoles']))
        include_once ROOT_PATH.'/include/getruoliall.php';

    $resAllRoles=$_SESSION['allRoles'];     //getRuoliAll();
	$primrol=[];

	foreach($resAllRoles as $key=>$rp)
    {
        if($rp['primario'])
        {
			$primrol[$key]['idRl']=$rp['idRl'];
            $primrol[$key]['ruolo']=$rp['ruolo'];
            //array_push($primrol, $rp);
            $primrol[$key]['chk']=0;
        }
    }
    // $_SESSION['primaryRoles']=$primrol;
    return $primrol;
}

//function updatechecksubroles($sub, $pri,$ruolichk)
//{
//    //$editu=$_SESSION['editU'];

//    //foreach($editu['ruolo'] as $rU)
//    foreach($ruolichk as $rU)
//    {
//        if($rU['idR']===$pri)
//        {
//            foreach($sub as &$ss)
//            {
//                if($ss['idS']===$rU['idS'])
//                    $ss['chk']=1;
//            }
//        }
//    }
//    return $sub;
//}

function  processUserRolesNumber($users)
{
	foreach ($users as $key => $u )
    {
        $n=count($u);
        $pu=$u[0];
        $pu['email']=$key;

        //$pu['idUs']=$u[0]['idUs'];
        //$pu['email']=$key;
        //$pu['nome']=$u[0]['nome'];
        //$pu['cognome']=$u[0]['cognome'];
        //$pu['cell']=$u[0]['cell'];
        //$pu['cod']=$u[0]['cod'];
        //if(isset($u[0]['psw']))
        //    $pu['psw']=$u[0]['psw'];
        //if(isset($u[0]['dtPsw']))
        //    $pu['dtPsw']=$u[0]['dtPsw'];
        unset($pu['ruolo'],$pu['subruolo']);
		$r=[];
		for($i=0;$i<$n;$i++)
        {
                $r[$i]['idR']=$u[$i]['ruoloNU'];
                $r[$i]['idS']=$u[$i]['subruoloNU'];
        }
	    $pu['ruolo']=$r;
    }
    $editu=$pu;
    $_SESSION['editU']=$editu;
    return $editu;
}

function updatecheckroles($editu)
{
    $rl=selectPrimaryRoles();       //$_SESSION['primaryRoles'];
    foreach($editu['ruolo'] as $rU)
    {
        foreach($rl as &$rr)
        {
            if($rU['idR']===$rr['idRl'])
                $rr['chk']=1;
        }
    }
    return $rl;
}

function processRolesToString($u)
{
    $n=count($u);
    if($n == 1)
    {
        if($u[0]['subruolo']!="")
            return($u[0]['ruolo']." [".$u[0]['subruolo']."]");
        else
            return ($u[0]['ruolo']);
    }
    else
    {
        $ruolo=$u[0]['ruolo'];
        $allrol =$ruolo." [".$u[0]['subruolo'];
        for($i=1;$i<$n;$i++)
        {
            if($u[$i]['ruolo']==$ruolo)
            {
                $allrol=$allrol.", ".$u[$i]['subruolo'];
            }
            else
            {
                $ruolo=$u[$i]['ruolo'];
                $allrol=$allrol."] ".$ruolo." [".$u[$i]['subruolo'];    //tolto <br> non accettato dal json
            }
        }
        $allrol=$allrol."]";

        //$back=$allrol;
        while($pos = strrpos($allrol, "[]"))
        {
            $senza=substr($allrol, 0, $pos-1);
            $senza=$senza . substr($allrol, $pos+2);
            $allrol = $senza;
        }
        return $allrol;
    }
}

function  processUserRolesString($users, $campokey)
{
    $prou=[];
	foreach ($users as $key=> $u )
    {
        //$n=count($u);
        $pu=$u[0];
        $pu[$campokey]=$key;

        //$pu['idUs']=$u[0]['idUs'];
        //$pu['email']=$key;
        //$pu['nome']=$u[0]['nome'];
        //$pu['cognome']=$u[0]['cognome'];
        //if(isset($u[0]['cell']))
        //    $pu['cell']=$u[0]['cell'];
        //if(isset($u[0]['cod']))
        //    $pu['cod']=$u[0]['cod'];
        //if(isset($u[0]['psw']))
        //    $pu['psw']=$u[0]['psw'];
        //if(isset($u[0]['dtPsw']))
        //    $pu['dtPsw']=$u[0]['dtPsw'];

        unset($pu['ruolo'],$pu['subruolo']);
        $pu['ruolo']=processRolesToString($u);

        array_push($prou, $pu);
    }
	return $prou;
}


//19/09/2022
function set_User($vid,$dcsv,$psw=null)
{
    $up=true;
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}

    if($vid==0)
    {
        $up=false;
		$pswTemp = strToLower($dcsv['nome'][0] . $dcsv['cognome'][0]);
        $psw = password_hash($pswTemp, PASSWORD_DEFAULT);
    }

    $sql = "CALL setDataUser(:vid, :n, :c, :em, :ps, :cel, :cod)";
    if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) {
        $sql = '{'.$sql.'}';
    }
    $cell=null;
    $cod=null;

    $no=$dcsv["nome"];
    $cg=$dcsv["cognome"];
    if(!$up)
        $em=$dcsv["email"];
    else $em="non usata";
    if(key_exists("cell",$dcsv))
        $cell=$dcsv["cell"];
    if(key_exists("cod",$dcsv))
        $cod=$dcsv["cod"];
    $stmt = $conn->prepare($sql);
    //vid=0 per insert into             vid=id per update e non usa ps e email
    $arrVal = array(':vid'=>$vid, ':n'=>$no, ':c'=>$cg, ':em'=>$em, ':ps'=>$psw, ':cel'=>$cell, ':cod'=>$cod);
    if(!$stmt->execute($arrVal)) throw new Exception('Errore query SetDataUser');
    //sostituito con la select che viene dopo
    $ultimo=$conn->lastInsertId();//NON FUNZIONA SEMPRE

	if($up)
		return $vid;
	else
    {
        $sql="SELECT max(idUs) as lastid FROM utenti;";
        $stmt = $conn->query($sql);
        if(!$stmt) throw new Exception('Errore query get last user Id');
        $ultimo = $stmt->fetch(PDO::FETCH_ASSOC); //result set con ultimo id inserito

        $conn=null;
        $stmt=null;

        return $ultimo['lastid'];
    }

}

function delete_ruoli_utente($utente)
{
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
	$sql = "CALL delRolesUser(:how)";
    if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) {
        $sql = '{'.$sql.'}';
    }
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':how', $utente);

    if(! $stmt->execute()) throw new Exception('Errore query delRolesUser');
    $conn=null;
    $stmt=null;
}
//BARBARA 19/09/2022
function set_ruolo_utente($utente,$ruolo,$subr)
{
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
	$sql = "CALL setRoleUser(:how,:role, :sub)";
    if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) {
        $sql = '{'.$sql.'}';
    }
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':how', $utente);
        $stmt->bindValue(':role', $ruolo);
        $stmt->bindValue(':sub', $subr);
        if(! $stmt->execute()) throw new Exception('Errore query SetRoleUser');
        $conn=null;
        $stmt=null;
}

function verify_mail($mail)
{
    $sql = "CALL getDupMail(:how)";
    if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) {
        $sql = '{'.$sql.'}';
    }

    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':how', $mail);
    if(! $stmt->execute()) throw new Exception('Errore query search for existing mail');
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $conn=null;
    $stmt=null;
    if($res)
        return(count($res));
    else
        return(0);
}

function read_csv($file,$delim,$ruolo,&$errors)
{
    //gestione della fine riga che potrebbe essere diversa in base a dove è stato creato il csv
    ini_set('auto_detect_line_endings',TRUE);

    //Map lines of the string returned by file function to $rows array.

    $rows = array_map(function($row) use ($delim) { return str_getcsv($row, $delim); }, file($file));
    //$rows   = array_map('str_getcsv', file($file));
    //Get the first row that is the HEADER row.
    $header_row = array_shift($rows);
    //This array holds the final response.
    $data_csv= [];
    $totalRows=0;
    foreach($rows as $row) {
        if(!empty($row)){
            $totalRows++;
            $data_csv[] = array_combine($header_row, $row);
        }
    }
    //for($i=0;$i<count($data_csv);$i++)
    $riga=0;
    foreach($data_csv as $dato)
    {
        $riga++;
        if($dato["nome"]== "" || $dato["cognome"]== "" || $dato["email"]== "")
        {
              array_push($errors,'Riga '.$riga.' scartata: dati mancanti');
              $totalRows--;
        }
        else
        {
            $contmail=verify_mail($dato["email"]);
            if($contmail!=0)
            {
                array_push($errors,'('.$riga.') '.$dato['email'].' duplicata');
                $totalRows--;
            }
            else
            {
                //dovrebbero essere due operazioni indivisibili
                //$id= set_User( 0,$data['nome'],$data['cognome'],$data['email'],0,0,null); //nome, cognome, mail, ...
                $id= set_User(0,$dato,null); //nome, cognome, mail, cell, cod e password...
                set_ruolo_utente($id,$ruolo,0);     //ruolo primario e nullo il ruolo secondario
            }
        }
    }
   ini_set('auto_detect_line_endings',FALSE);
   return $totalRows;
}

//$a intestazioni di colonna
//$b valori recuperati dalla riga
//function array_combine_special($a, $b, $pad = TRUE) {
//    $acount = count($a);
//    $bcount = count($b);
//    // more elements in $a than $b but we don't want to pad either
//    if (!$pad) {
//        $size = ($acount > $bcount) ? $bcount : $acount;
//        $a = array_slice($a, 0, $size);
//        $b = array_slice($b, 0, $size);
//    } else {
//        // more headers than row fields
//        if ($acount > $bcount) {
//            $more = $acount - $bcount;
//            // how many fields are we missing at the end of the second array?
//            // Add empty strings to ensure arrays $a and $b have same number of elements
//            $more = $acount - $bcount;
//            for($i = 0; $i < $more; $i++) {
//                $b[] = "";
//            }
//            // more fields than headers
//        } else if ($acount < $bcount) {
//            $more = $bcount - $acount;
//            // fewer elements in the first array, add extra keys
//            for($i = 0; $i < $more; $i++) {
//                $key = 'extra_field_0' . $i;
//                $a[] = $key;
//            }

//        }
//    }

//    return array_combine($a, $b);
//}


?>