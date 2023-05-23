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
//require_once ROOT_PATH .'/adminsez/admin/includes/adminfunctions.php';


$output = [];
$errors = [];

	$title = esc($_POST['titleBis']);
	$body = esc($_POST['textBis']);
	// validate form
    if (empty($title)) {
        array_push($errors, "Titolo del bisogno mancante");
    }
    if(empty($body)) {
        array_push($errors, " Corpo del bisogno vuoto");
    }
    if(!isset($_POST['topic_id']))
            $_POST['topic_id']=null;
    if(!isset($_POST['moreambito']))
        $morea="";
    else $morea=esc($_POST['moreambito']);

        // register bisogno if there are no errors in the form
	if (count($errors) == 0) {
            if(!$conn = connectDB()) {echo errorConnectDB(); exit();}

            if($_POST['hidden_post_id']==0)
                $crud='C';
            else $crud='U';
            if(isset($_POST['publish']))
                $publish=1;
            else
                $publish=0;
            $sql = "CALL setBisogni(:how, :topic, :more, :title, :image, :body, :publish, :utente, :crud)";
            if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) $sql = '{'.$sql.'}';
            $stmt = $conn->prepare($sql);
            $arrVal = array(':how'=>$_POST['hidden_post_id'], ':topic'=>$_POST['topic_id'],':more'=>$morea,
                ':title'=>$title, ':image'=>null, ':body'=>$body, ':publish'=>$publish,
                                ':utente'=>$_SESSION['user']['idUs'], ':crud'=>$crud);

          if(! $stmt->execute($arrVal)) throw new Exception('Errore query aggiorna bisogno');
            $conn=null;
            $stmt=null;

            if($crud=='C')  $output['success'] =  "Creazione bisogno terminata";
            else  $output['success'] = "Aggiornamento bisogno avvenuto con successo";
        }
    else {
        $output['errors'] = $errors;}

     echo json_encode($output);

        //header('location: ../topics.php');
  exit(0);
