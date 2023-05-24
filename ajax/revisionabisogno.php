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


$output = [];
$errors = [];

$crud = $_POST['crud'];

if(isset($_POST['titleBis']))
	$title = esc($_POST['titleBis']);
else $title="";

if(isset($_POST['textBis']))
    $body = esc($_POST['textBis']);
else $body="";

if(isset($_POST['NdR']))
    $Ndr=esc($_POST['NdR']);
else $Ndr="";

	// validate form
    if (empty($title) && $crud!='D') {
        array_push($errors, "Titolo del bisogno mancante");
    }
    else if(empty($body) && $crud != 'D') {
        array_push($errors, "Corpo del bisogno vuoto");
    }
    else if(!isset($_POST['topic_id']) && $crud != 'D')
        array_push($errors, "Ambito obbligatorio in fase di revisione");
   else
    {

    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
    // revisiona bisogno if there are no errors in the form
	    if (count($errors) == 0) {
            $publish=0;
            if(isset($_POST['publish']))
                $publish=$_POST['publish'];
        if (!isset($_POST['topic_id']))
            $_POST['topic_id'] = null;

        //if($crud=='D')
            //{
            //    //uso solo il revisore e metto il campo deleted a 1 che indica CANCELLAZIONE LOGICA e pubblicato a zero
            //    $title=$body=$Ndr=null;
            //}
            if(!isset($_POST['moreambito']))
                $_POST['moreambito']="";
            //mantenuto image per eventuali sviluppi
            $sql = "CALL revBisogni(:vid, :topicid, :more, :title, :body, :image, :publish, :revisor, :note, :crud)";
            if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) $sql = '{'.$sql.'}';
            $stmt = $conn->prepare($sql);
            $arrVal = array(':vid'=>$_POST['hidden_post_id'], ':topicid'=>$_POST['topic_id'], ':more'=>$_POST['moreambito'],
                ':title'=>$title, ':body'=>$body, ':image'=>null, ':publish'=>$publish,
                                ':revisor'=>$_SESSION['user']['idUs'], ':note' => $Ndr,':crud'=>$crud);

          if(! $stmt->execute($arrVal)) throw new Exception('Errore query aggiorna bisogno');
            $conn=null;
            $stmt=null;

            if($crud=='R')  $output['success'] =  "Revisione bisogno effettuata";
            else  $output['success'] = "Cancellazione logica bisogno avvenuta con successo";
        }
    }

    if(count($errors) > 0) {
        $output['errors'] = $errors;}

     echo json_encode($output);

        //header('location: ../topics.php');
     exit(0);
