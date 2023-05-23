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
//require_once('../../config.php');
if (!defined('ROOT_PATH'))
    define ('ROOT_PATH', $_SESSION['ini']['ROOT_PATH']);
require_once ROOT_PATH .'/include/functions.php';



function uploadFile(&$errors,&$fileName){
    $uploadTo = $_SERVER['DOCUMENT_ROOT']."\\uploadpdf\\";
    //$allowFileType = array('pdf');
    $fileName = $_FILES['PDFToUpload']['name'];
    $tempPath=$_FILES["PDFToUpload"]["tmp_name"];

    //$basename = basename($fileName);
    do{
        $basename = generaNomeFile().".pdf";
    }while(fileEsisteGia($basename));
    $originalPath = $uploadTo.$basename;
    //$fileType = pathinfo($originalPath, PATHINFO_EXTENSION);
    //if(!empty($fileName)){

       //if(in_array($fileType, $allowFileType)){//già controllato in JS
         // Upload file to server
         if(move_uploaded_file($tempPath,$originalPath)){
             return $basename;
            //$output['success']= $fileName." caricato con successo";
           // write here sql query to store image name in database
          }
         else{
             return "";
            //array_push($errors,'Impossibile caricare il file');
          }
      //}else{
      //    array_push($errors, $fileType." tipo di file non permesso");
      //}
   //}else{
   //    array_push($errors,"Scegliere un file");
   //}
}


function setProposta()
{
    $output=[];
    $errors=[];
    $idbis=[];

    $title = esc($_POST['proTitle']);
	$body = esc($_POST['proBody']);
    $prop = esc($_POST['perconto']);

	// validate form - già campi richiesti in JS
    if (empty($title)) {
        array_push($errors, "Titolo del bisogno mancante");
    }
    if(empty($body)) {
        array_push($errors, " Corpo del bisogno vuoto");
    }
    if(empty($prop)) {
        array_push($errors, " manca Proponente");
    }
    if(!isset($_POST['bis']))
        $idbis=null;
    else{
        $idbis=explode(",",$_POST['bis']);
    }
    if(!isset($_POST['propmail']))
        $mail="";
    else  $mail = esc($_POST['propmail']);

    if(!isset($_POST['propcell']))
        $cell=null;
    else $cell=esc($_POST['propcell']);

    if(!isset($_POST['altreinfo']))
        $bisg=null;
    else $bisg=esc($_POST['altreinfo']);
    // registro la proposta if there are no errors in the form

    if($_POST['hidden_post_id']==0)
    {
        $crud='C';
        $filename=uploadFile($errors,$orfilename);
    }
    else {
        $crud='U';
        $filename="";
    }

    if (count($errors) == 0) {
        if(!$conn = connectDB()) {echo errorConnectDB(); exit();}


        if(isset($_POST['publish']))
            $publish=1;
        else
            $publish=0;
        $sql = "CALL setProposte(:how, :utente, :propo, :mail, :tel, :title, :body, :pdftit, :pdf, :bisg, :publish, :crud)";
        if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) $sql = '{'.$sql.'}';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':how', $_POST['hidden_post_id']);
        $stmt->bindParam(':utente',$_SESSION['user']['idUs']);
        $stmt->bindParam(':propo',$prop);
        $stmt->bindParam(':mail',$mail);
        $stmt->bindParam(':tel', $cell);
        $stmt->bindParam(':title',$title);
        $stmt->bindParam(':body',$body);
        $stmt->bindParam(':pdftit',$orfilename);
        $stmt->bindParam(':pdf',$filename);
        $stmt->bindParam(':bisg',$bisg);
        $stmt->bindParam(':publish',$publish);
        $stmt->bindParam(':crud',$crud);

        //$stmt->bindParam(':_lastid', $return_value, PDO::PARAM_INT);

        if(! $stmt->execute()) throw new Exception('Errore query aggiorna proposta');
        if($idbis!=null && $idbis!="")
        {
            if($crud=='C'){
                $return_value=$stmt->fetch(PDO::FETCH_ASSOC); //result set con ultimo id inserito
                $hi=$return_value[""];
                 $sql="";
            }
            else
            {
                $hi=$_POST['hidden_post_id'];
                 $sql="DELETE FROM propBis WHERE proposta=$hi;";
            }
            foreach($idbis as $b)
            {
                //$hi=$return_value;
                $sql=$sql."INSERT INTO propBis VALUES ($hi,$b);";
            }
            $stmt = $conn->prepare($sql);
            if(! $stmt->execute()) throw new Exception('Errore query save proposta_bisogni');
            // $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        $conn=null;
        $stmt=null;

        if($crud=='C')  $output['success']="Caricamento proposta terminato";
        else  $output['success']="Aggiornamento proposta terminato";
    }
    if(count($errors) > 0) {
        $output['errors'] = $errors;
    }

    echo json_encode($output);

}

function fileEsisteGia($name)
{
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}

    $sql = "SELECT pdfalleg FROM proposte WHERE pdfalleg='$name';";

    $stmt = $conn->prepare($sql);
    if(! $stmt->execute()) throw new Exception('Errore query getSummaryBis');
    $gia = $stmt->fetch(PDO::FETCH_ASSOC);
    $conn=null;
    $stmt=null;
    if($gia)
        return true;
    else
        return false;
}
function generaNomeFile(){

    $serieCaratteri = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    $numeroCar = substr(str_shuffle($serieCaratteri), 0, 12);

    return $numeroCar;

}



setProposta();


