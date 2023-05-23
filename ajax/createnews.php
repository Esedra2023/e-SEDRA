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
//require_once ('../../config.php');
if (!defined('ROOT_PATH'))
    define ('ROOT_PATH', $_SESSION['ini']['ROOT_PATH']);
require_once ROOT_PATH .'/include/functions.php';
//require_once ROOT_PATH .'/adminsez/admin/includes/adminfunctions.php';


$output = [];
$errors = [];
    $quale=$_POST['idNw'];
    $title = esc($_POST['title']);
    $body = esc($_POST['text']);
    $datfin=$_POST['dtEnd'];
    $exp=$_POST['settScad'];
    if($exp=='mai') $exp=100;
    $crud=$_POST['crud'];

    if (empty($title)) {
        array_push($errors, "Titolo della news mancante");
    }
    if(empty($body)) {
        array_push($errors, " Corpo della news vuoto");
    }

        // register news if there are no errors in the form
	    if (count($errors) == 0)
        {
            if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
            if($crud =='C')
            {
                $sql = "CALL insNews(:crud,:utente, :title, :body, :dtend, :exp)";
                if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) $sql = '{'.$sql.'}';
                $stmt = $conn->prepare($sql);
                $arrVal = array(':crud'=>$crud,':utente'=>$_SESSION['user']['idUs'],':title'=>$title, ':body'=>$body, ':dtend'=>$datfin,':exp'=>$exp);
                if(! $stmt->execute($arrVal)) throw new Exception('Errore query salva news');
                $conn=null;
                $stmt=null;
                $output['success']="Creazione news terminata";
            }
            else if($crud =='U'){
                $sql = "CALL insNews(:crud, :idnews, :title, :body, :dtend, :exp)";
                if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) $sql = '{'.$sql.'}';
                $stmt = $conn->prepare($sql);
                $arrVal = array(':crud'=>$crud,':idnews'=>$quale,':title'=>$title, ':body'=>$body, ':dtend'=>$datfin,':exp'=>$exp);
                if(! $stmt->execute($arrVal)) throw new Exception('Errore query aggiorna news');
                $conn=null;
                $stmt=null;
                unset($_SESSION['isEditingNews']);
                $output['success']="Aggiornamento news terminato";
                //unset($_POST['idNw']);
            }
        }
        else
            $output['errors'] = $errors;
        echo json_encode($output);

        exit(0);