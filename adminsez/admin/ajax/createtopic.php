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

    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}

	$topic_name = esc($_POST['ambito']);
	$topic_val = esc($_POST['valenza']);
    $crud=$_POST['crud'];
    $how=$_POST['idAm'];
    $ck=$_POST['moreinfo'];
	// create slug: if topic is "Life Advice", return "life-advice" as slug
    //$topic_slug = makeSlug($topic_name);
	// validate form
    if (empty($topic_name)) {
        array_push($errors, "Manca il nome per l'ambito");
    }
    else{
        if($crud=='C')
        {

	        // Ensure that no topic is saved twice con lo stesso nome
            $topic_check_query = "CALL srcAmbiti(:how,:name, :valz)";
            if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) $topic_check_query = '{'.$topic_check_query.'}';
	        $stmt = $conn->prepare($topic_check_query);
            $stmt->bindValue(':how',0);		//0 per non cercare per id
            $stmt->bindValue(':name',$topic_name);
            $stmt->bindValue(':valz',-1);	//-1 per non cercare per valenza
            if(! $stmt->execute()) throw new Exception('Errore query search ambito per nome');
            //$topic_check_query = "SELECT * FROM ambiti WHERE ambito='$topic_name' LIMIT 1";
	        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	        if (count($result) > 0) { // if topic exists
		        array_push($errors, "Ambito esistente");
	        }
        }
        // register topic if there are no errors in the form
	    if (count($errors) == 0 || $crud=='U') {
            $sql = "CALL updAmbiti(:crud,:how,:name, :valz, :morei)";
            if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) $sql = '{'.$sql.'}';
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':crud',$crud);
            if($crud=='C')
                $stmt->bindValue(':how',0);
            else
                $stmt->bindValue(':how',$how);
            $stmt->bindValue(':name',$topic_name);
            $stmt->bindValue(':valz',$topic_val);
            $stmt->bindValue(':morei',$ck);
            if(! $stmt->execute()) throw new Exception('Errore query aggiorna ambito');
            $conn=null;
            $stmt=null;

            if($crud=='C')  $output['success'] =  "Nuovo ambito creato";
            else  $output['success'] = "Ambito aggiornato con successo";
        }
    }
    if(count($errors) > 0)
        $output['errors'] = $errors;

     echo json_encode($output);

        //header('location: ../topics.php');
		exit(0);

