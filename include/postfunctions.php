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


function getBisogniEsclusi($who,$field)
{
    $bal=0;
    if($field=='ingrad')
        $bal=1;
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
	$sql = "SELECT b.idBs,a.ambito, b.titleBis, b.textBis, b.dtRev, b.rev, b.pubblicato, b.deleted FROM bisogni AS b,ambiti as a
        WHERE a.idAm=b.ambito AND idBs NOT IN (SELECT idBs FROM gradBisogni WHERE ballot=$bal)";
    if($who == 'personal')
        $sql=$sql." AND utente = {$_SESSION['user']['idUs']};";
        else $sql=$sql.';';
    $stmt = $conn->prepare($sql);
    if(! $stmt->execute()) throw new Exception('Errore query GetPostAuthorById');
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $conn=null;
    $stmt=null;
	if ($result) {
		return $result;
	} else return null;
}

function getProposteEscluse($who,$field)
{
    $bal=0;
    if($field=='ingrad')
        $bal=1;
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
	$sql = "SELECT p.idPr,p.titlePrp, p.textPrp, p.dtRev, p.rev, p.pubblicato, p.deleted FROM proposte AS p
        WHERE idPr NOT IN (SELECT idPr FROM gradProposte WHERE ballot=$bal)";
    if($who == 'personal')
        $sql=$sql." AND utente = {$_SESSION['user']['idUs']};";
    else $sql=$sql.';';
    $stmt = $conn->prepare($sql);
    if(! $stmt->execute()) throw new Exception('Errore query GetProposteEscluse');
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $conn=null;
    $stmt=null;
	if ($result) {
		return $result;
	} else return null;
}

function getDefGradBisogni()
{
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
	$sql = "SELECT gb.*, a.ambito, b.titleBis, b.textBis, b.dtRev, b.rev, b.pubblicato, b.deleted FROM gradBisogni AS gb, ambiti AS a, bisogni AS b
        WHERE gb.idAm=a.idAm AND gb.idBs=b.idBs AND a.idAm=b.ambito;";
    $stmt = $conn->prepare($sql);
    if(! $stmt->execute()) throw new Exception('Errore query GetPostAuthorById');
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $conn=null;
    $stmt=null;
	if ($result) {
		return $result;
	}
}

function getAutore($idUs){
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
    $sql = "SELECT nome, cognome FROM utenti WHERE idUS=$idUs;";
    $stmt = $conn->prepare($sql);
    if(! $stmt->execute()) throw new Exception('Errore query GetAutore');
    $ut = $stmt->fetch(PDO::FETCH_ASSOC);
    $conn=null;
    $stmt=null;
    return $ut['nome'].' '.$ut['cognome'];
}

function getPostAuthorById($user_id)
{
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
	$sql = "SELECT nome, cognome FROM utenti WHERE idUs=$user_id";
    $stmt = $conn->prepare($sql);
    if(! $stmt->execute()) throw new Exception('Errore query GetPostAuthorById');
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $conn=null;
    $stmt=null;
	if ($result) {
		$username=array_values($result);
		return $username;
	} else {
		return null;
	}
}

function getAllPublishBisWithGrade($anonim,$field)
{
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}

    if(!$anonim)
       $sql="SELECT t.* , idVb, grade FROM (SELECT b.*,nome, cognome FROM bisogni as b, utenti as u WHERE b.utente=u.idUs AND $field=1 AND deleted <> 1) AS t LEFT JOIN valBis ON idBs=bisogno AND valBis.utente=".$_SESSION['user']['idUs'];
    else
        $sql="SELECT t.* , idVb, grade FROM (SELECT * FROM bisogni WHERE $field=1 AND deleted <> 1) AS t LEFT JOIN valBis ON idBs=bisogno AND valBis.utente=".$_SESSION['user']['idUs'];
    if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0){
        $sqd= " AND valBis.dtIns between (SELECT dtStart FROM attivita WHERE idAt=104) AND DATEADD(DAY, 1, (SELECT dtStop FROM attivita WHERE idAt=104));";
    }
    else if($_SESSION['ini']['dbms'] == 'My SQL'){
        $sqd= " AND valBis.dtIns between (SELECT dtStart FROM attivita WHERE idAt=104) AND ((SELECT dtStop FROM attivita WHERE idAt=104) + INTERVAL 1 DAY);";
    }
    $sql=$sql.$sqd;
    $stmt = $conn->prepare($sql);
    if(! $stmt->execute()) throw new Exception('Errore query GetAllPublishBisogniWithGrade');
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $conn=null;
    $stmt=null;
	return $posts;
}


function getAllPublishProWithGrade($anonim,$field)
{
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}

    if(!$anonim)
        $sql="SELECT t.* , idVp, grade FROM (SELECT p.*,nome, cognome FROM proposte as p, utenti as u WHERE p.utente=u.idUs AND $field=1 AND deleted <> 1) AS t LEFT JOIN valPrp ON t.idPr=proposta AND valPrp.utente=".$_SESSION['user']['idUs'];
    else
        $sql="SELECT t.* , idVp, grade FROM (SELECT * FROM proposte WHERE $field=1 AND deleted <> 1) AS t LEFT JOIN valPrp ON idPr=proposta AND valPrp.utente=".$_SESSION['user']['idUs'];
    if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0){
        $sqd= " AND valPrp.dtIns between (SELECT dtStart FROM attivita WHERE idAt=204) AND DATEADD(DAY, 1, (SELECT dtStop FROM attivita WHERE idAt=204));";
    }
    else if($_SESSION['ini']['dbms'] == 'My SQL'){
        $sqd= " AND valPrp.dtIns between (SELECT dtStart FROM attivita WHERE idAt=204) AND ((SELECT dtStop FROM attivita WHERE idAt=204) + INTERVAL 1 DAY);";
    }
    $sql=$sql.$sqd;
    $stmt = $conn->prepare($sql);
    if(! $stmt->execute()) throw new Exception('Errore query GetAllPublishProposteWithGrade');
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $conn=null;
    $stmt=null;
	return $posts;
}



//function getAllPublishBisWithoutGrade($anonim)
//{
//    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}

//    // Admin can view all posts
//    // Author can only view their posts
//    // $sql = "SELECT * FROM ".$table." WHERE pubblicato=1 AND deleted <> 1;";  //1 cancellato dal revisore

//    if(!$anonim)
//        $sql="SELECT t.* , idVb, grade FROM (SELECT b.*,nome, cognome FROM bisogni as b, utenti as u WHERE b.utente=u.idUs AND pubblicato=1 AND deleted <> 1) AS t LEFT JOIN valBis ON idBs=bisogno AND valBis.utente=".$_SESSION['user']['idUs'];
//    else
//        $sql="SELECT t.* , idVb, grade FROM (SELECT * FROM bisogni WHERE pubblicato=1 AND deleted <> 1) AS t LEFT JOIN valBis ON idBs=bisogno AND valBis.utente=".$_SESSION['user']['idUs'];
//    $stmt = $conn->prepare($sql);
//    if(! $stmt->execute()) throw new Exception('Errore query GetAllPublishPost');
//    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

//    $conn=null;
//    $stmt=null;
//    //if(!$anonim)
//    //{
//    //    foreach ($posts as &$post) {
//    //            $post['utenteName'] = getPostAuthorById($post['utente']);
//    //    }
//    //}
//    return $posts;
//}


//function getAllCommentsCanceled($table){
//    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
//    $sql = "SELECT b.dtIns, b.content, u.nome as anome, u.cognome as acognome,r.nome as revnome, r.cognome as revcogn, b.dtRev,b.note  FROM $table as b, utenti as u, utenti as r  WHERE u.idUs=b.autore AND r.idUs=b.revisore AND stato = 3 order by acognome ASC, anome ASC;";
//    //try{
//    $stmt = $conn->prepare($sql);
//    if(!$stmt->execute()) throw new Exception('Errore query getAllCommentCanceled');
//    $comcanc = $stmt->fetchAll(PDO::FETCH_ASSOC);
//    return $comcanc;
//}


//0 normale, 1 segnalato, 2 ripristinato, 3 cancellato log
//stato 3 cancellati logicamente
function getAllCommentsNotCanceled($ib,$table,$campo){
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
	$sql = "SELECT b.*, u.nome as anome, u.cognome as acognome  FROM $table as b, utenti as u WHERE u.idUs=b.autore AND $campo=$ib AND b.risp=0  AND stato <> 3 order by dtIns DESC;";
    //try{
    $stmt = $conn->prepare($sql);
    if(!$stmt->execute()) throw new Exception('Errore query getAllCommentNotCanceled');
    $comme = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $comme;
}


//stato 3 cancellati logicamente
function getAllAnswersNotCanceled($ib,$table,$campo){
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
	$sql = "SELECT b.*, u.nome as anome, u.cognome as acognome  FROM $table as b, utenti as u WHERE u.idUs=b.autore AND b.$campo=$ib AND b.risp=1 AND stato <> 3 order by dtIns DESC;";
    //try{
    $stmt = $conn->prepare($sql);
    if(!$stmt->execute()) throw new Exception('Errore query getAllAnswersNotCanceled');
    $comme = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $comme;
}

function getOnePublishBisWithGrade($idB,$field,$anonim=true){

    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}

    if(!$anonim)
        $sql="SELECT t.* , idVb, grade FROM (SELECT b.*,nome, cognome FROM bisogni as b, utenti as u WHERE b.utente=u.idUs AND $field=1 AND deleted <> 1 AND idBs=$idB) AS t LEFT JOIN valBis ON idBs=bisogno AND valBis.utente=".$_SESSION['user']['idUs'];
    else
        $sql="SELECT t.* , idVb, grade FROM (SELECT * FROM bisogni WHERE $field=1 AND deleted <> 1 AND idBs=$idB) AS t LEFT JOIN valBis ON idBs=bisogno AND valBis.utente=".$_SESSION['user']['idUs'];

    if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0){
        $sqd= " AND valbis.dtIns between (SELECT dtStart FROM attivita WHERE idAt=104) AND DATEADD(DAY, 1, (SELECT dtStop FROM attivita WHERE idAt=104));";
    }
    else if($_SESSION['ini']['dbms'] == 'My SQL'){
        $sqd= " AND valbis.dtIns between (SELECT dtStart FROM attivita WHERE idAt=104) AND ((SELECT dtStop FROM attivita WHERE idAt=104) + INTERVAL 1 DAY);";
    }
    $sql=$sql.$sqd;
    $stmt = $conn->prepare($sql);
    if(! $stmt->execute()) throw new Exception('Errore query GetsingleBis');
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    $conn=null;
    $stmt=null;
	if ($post) {
		// get the topic to which this post belongs
		$post['ambitoName'] = getPostTopic($post['ambito']);
	}
	return $post;
}

function getOnePublishProWithGrade($idP,$field, $anonim=true)
{
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
    if(!$anonim)
        $sql="SELECT t.* , idVp, grade FROM (SELECT p.*,nome, cognome FROM proposte as p, utenti as u WHERE p.utente=u.idUs AND $field=1 AND deleted <> 1 AND idPr=$idP) AS t LEFT JOIN valPrp ON idPr=proposta AND valPrp.utente=".$_SESSION['user']['idUs'];
    else
        $sql="SELECT t.* , idVp, grade FROM (SELECT * FROM proposte WHERE $field=1 AND deleted <> 1 AND idPr=$idP) AS t LEFT JOIN valPrp ON idPr=proposta AND valPrp.utente=".$_SESSION['user']['idUs'];

    if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0){
        $sqd= " AND valPrp.dtIns between (SELECT dtStart FROM attivita WHERE idAt=204) AND DATEADD(DAY, 1, (SELECT dtStop FROM attivita WHERE idAt=204));";
    }
    else if($_SESSION['ini']['dbms'] == 'My SQL'){
        $sqd= " AND valPrp.dtIns between (SELECT dtStart FROM attivita WHERE idAt=204) AND ((SELECT dtStop FROM attivita WHERE idAt=204) + INTERVAL 1 DAY);";
    }
    $sql=$sql.$sqd;

    $stmt = $conn->prepare($sql);
    if(! $stmt->execute()) throw new Exception('Errore query GetsingleProposta');
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    $conn=null;
    $stmt=null;

	if ($post) {
		// ottieni i bisogni associati alla proposta
		$post['bisas'] = getTitleBisAssoc($post['idPr']);
	}
	return $post;
}

function getTitleBisAssoc($idPr)
{
    $bissa=getBisAssoc($idPr);
    $st="(";
    foreach($bissa as $b)
    {
        $st=$st.$b['bisogno'].',';
    }
    $rest = substr($st, 0, -1);
    $rest=$rest.")";
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
	$sql = "SELECT titleBis FROM bisogni WHERE idBs IN $rest;";
    $stmt = $conn->prepare($sql);
    if(!$stmt->execute()) throw new Exception('Errore query getSimilarProposte');
    $titleb = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $titleb;
}

function getBisAssoc($idPr){
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
	$sql = "SELECT bisogno FROM propBis WHERE proposta=$idPr;";
    $stmt = $conn->prepare($sql);
    if(!$stmt->execute()) throw new Exception('Errore query getPostTopic');
    $bissa = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $conn=null;
    $stmt=null;
	return $bissa;
}

function getIdSimilarProposte($idPr,$bissa)
{
    $st="(";
    foreach($bissa as $b)
    {
        $st=$st.$b['bisogno'].',';
    }
    $rest = substr($st, 0, -1);
    $rest=$rest.")";
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
	$sql = "SELECT proposta FROM propBis WHERE proposta <> $idPr AND bisogno IN $rest;";
    $stmt = $conn->prepare($sql);
    if(!$stmt->execute()) throw new Exception('Errore query getSimilarProposte');
    $idpro = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $conn=null;
    $stmt=null;
	return $idpro;
}

function getSimilarProposte($idPr,$field)
{
    $bisa=getBisAssoc($idPr);
    if($bisa!=null)
    {
       $idps=getIdSimilarProposte($idPr,$bisa);
       if($idps!=null)
       {
           $st="(";
           foreach($idps as $ip)
           {
               $st=$st.$ip['proposta'].',';
           }
           $rest = substr($st, 0, -1);
           $rest=$rest.")";
        if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
        $sql = "SELECT * FROM proposte WHERE idPr IN $rest AND $field=1 AND deleted <> 1;";
        $stmt = $conn->prepare($sql);
        if(!$stmt->execute()) throw new Exception('Errore query getSimilarProposte');
        $pro = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $conn=null;
        $stmt=null;
        return $pro;
       }
    }
    return null;
}
function getMyLikeB($idB,$idUs){
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}

    $sql = "SELECT * FROM miPiaceB WHERE bisogno=$idB AND utente=$idUs"; //;AND pubblicato=1"
    if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0){
        $sqd= " AND miPiaceB.dtIns between (SELECT dtStart FROM attivita WHERE idAt=104) AND DATEADD(DAY, 1, (SELECT dtStop FROM attivita WHERE idAt=104));";
    }
    else if($_SESSION['ini']['dbms'] == 'My SQL'){
        $sqd= " AND miPiaceB.dtIns between (SELECT dtStart FROM attivita WHERE idAt=104) AND ((SELECT dtStop FROM attivita WHERE idAt=104) + INTERVAL 1 DAY);";
    }
    $sql=$sql.$sqd;
    //try{
    $stmt = $conn->prepare($sql);
    if(! $stmt->execute()) throw new Exception('Errore query GetMyLike');
    $lk = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $conn=null;
    $stmt=null;
    return $lk;
}

function getMyLikeP($idP,$idUs){
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}

    $sql = "SELECT * FROM miPiaceP WHERE proposta=$idP AND utente=$idUs"; //;AND pubblicato=1"
    if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0){
        $sqd= " AND miPiaceP.dtIns between (SELECT dtStart FROM attivita WHERE idAt=204) AND DATEADD(DAY, 1, (SELECT dtStop FROM attivita WHERE idAt=204));";
    }
    else if($_SESSION['ini']['dbms'] == 'My SQL'){
        $sqd= " AND miPiaceP.dtIns between (SELECT dtStart FROM attivita WHERE idAt=204) AND ((SELECT dtStop FROM attivita WHERE idAt=204) + INTERVAL 1 DAY);";
    }
    $sql=$sql.$sqd;
    //try{
    $stmt = $conn->prepare($sql);
    if(! $stmt->execute()) throw new Exception('Errore query GetMyLikeP');
    $lk = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $conn=null;
    $stmt=null;
    return $lk;
}


/* * * * * * * * * * * * * * * *
 * Returns all posts (bisogni) under a topic (ambito)
 * * * * * * * * * * * * * * * *ok */
function getPublishedPostsByTopic($am_id,$field) {
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
	$sql = "SELECT *  FROM bisogni as b where b.ambito=$am_id AND b.$field=1 AND b.deleted <> 1";
    //try{
    $stmt = $conn->prepare($sql);
    if(!$stmt->execute()) throw new Exception('Errore query getPublishedPostsByTopic');
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);


    //} catch(PDOException $e) {echo 'Errore query getPublishedPostsByTopic '.$e->getMessage();}
    //$result = mysqli_query($conn, $sql);
    //// fetch all posts as an associative array called $posts
    //$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
    if(count($posts)!=0)
    {
        $anome=getPostTopic($am_id);
        $final_posts = array();
        foreach ($posts as $p) {
            $p['ambitoName'] = $anome;
            array_push($final_posts, $p);
        }
        $conn=null;
        $stmt=null;
        return $final_posts;
    }else return $posts;
}



/* * * * * * * * * * * * * * *
 * Receives a post id and
 * Returns topic of the post
 * * * * * * * * * * * * * * OK*/
function getPostTopic($tpc_id){
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
    //$sqlmy = "SELECT * FROM bisogni WHERE idBs=
    //        (SELECT bisId FROM postBisogni WHERE postId=$post_id) LIMIT 1";
	$sql = "SELECT ambito FROM ambiti WHERE idAm='$tpc_id';";
    //try{
    $stmt = $conn->prepare($sql);
    if(!$stmt->execute()) throw new Exception('Errore query getPostTopic');
    $topic = $stmt->fetch(PDO::FETCH_ASSOC);

    //} catch(PDOException $e) {echo 'Errore query getPostTopic '.$e->getMessage();}
    //$result = mysqli_query($conn, $sql);
    //$topic = mysqli_fetch_assoc($result);
    //ambito in formato testo ricavato dall'id del bisogno

    $conn=null;
    $stmt=null;
	return $topic['ambito'];
}

//non parametrizzato
function getLikeB($idBs)
{
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
    //totali per tutti i bisogni, se il bisogno è personale li filtro dopo
        $sql = "SELECT count(*) as nlike FROM miPiaceB WHERE bisogno=$idBs ";
        if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0){
            $sqd= " AND miPiaceB.dtIns between (SELECT dtStart FROM attivita WHERE idAt=104) AND DATEADD(DAY, 1, (SELECT dtStop FROM attivita WHERE idAt=104)) ";
        }
        else if($_SESSION['ini']['dbms'] == 'My SQL'){
            $sqd= " AND miPiaceB.dtIns between (SELECT dtStart FROM attivita WHERE idAt=104) AND ((SELECT dtStop FROM attivita WHERE idAt=104) + INTERVAL 1 DAY) ";
        }
        $sql=$sql.$sqd."GROUP BY bisogno;";

    $stmt = $conn->prepare($sql);
    if(! $stmt->execute()) throw new Exception('Errore query GetLikeBisogni');
    $nlik = $stmt->fetch(PDO::FETCH_ASSOC);
    $conn=null;
    $stmt=null;

	return $nlik;
}

function likeForMeB(&$posts,$me)
{
    if($me==0)
    {
        foreach($posts as &$po)
        {
            $lk=getLikeB($po['idBs']);
            if($lk)
                $po['nlike']=$lk['nlike'];
        }
    }
    else{
        foreach($posts as &$po)
        {
            if($po['utente']==$me)
            {
            $lk=getLikeB($po['idBs']);
            if($lk)
                $po['nlike']=$lk['nlike'];
            }
        }
    }
    //return $posts;
}


function getLikeP($idPr)
{
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
    //totali per tutti i bisogni, se il bisogno è personale li filtro dopo
    $sql = "SELECT count(*) as nlike FROM miPiaceP WHERE proposta=$idPr ";
    if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0){
        $sqd= " AND miPiaceP.dtIns between (SELECT dtStart FROM attivita WHERE idAt=204) AND DATEADD(DAY, 1, (SELECT dtStop FROM attivita WHERE idAt=204)) ";
    }
    else if($_SESSION['ini']['dbms'] == 'My SQL'){
        $sqd= " AND miPiaceP.dtIns between (SELECT dtStart FROM attivita WHERE idAt=204) AND ((SELECT dtStop FROM attivita WHERE idAt=204) + INTERVAL 1 DAY) ";
    }
    $sql=$sql.$sqd."GROUP BY proposta;";

    $stmt = $conn->prepare($sql);
    if(! $stmt->execute()) throw new Exception('Errore query GetLikeProposte');
    $nlik = $stmt->fetch(PDO::FETCH_ASSOC);
    $conn=null;
    $stmt=null;

	return $nlik;
}

function likeForMeP(&$posts,$me)
{
    if($me==0)
    {
        foreach($posts as &$po)
        {
            $lk=getLikeP($po['idPr']);
            if($lk)
                $po['nlike']=$lk['nlike'];
        }
    }
    else{
        foreach($posts as &$po)
        {
            if($po['utente']==$me)
            {
                $lk=getLikeP($po['idPr']);
                if($lk)
                    $po['nlike']=$lk['nlike'];
            }
        }
    }
    //return $posts;
}


//function getMyGrade($idBs)
//{
//    return 1;
//}

function getSummaryBis($field)
{
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}

    $sql = "SELECT idBs, titleBis FROM bisogni WHERE $field=1 AND deleted <> 1;";

    $stmt = $conn->prepare($sql);
    if(! $stmt->execute()) throw new Exception('Errore query getSummaryBis');
    $bis = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $conn=null;
    $stmt=null;

	return $bis;
}

?>