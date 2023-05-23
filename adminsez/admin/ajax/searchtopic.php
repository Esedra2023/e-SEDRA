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

//if($_POST['view'] == 0)     //salvo solo per l'edit
//{
    $_SESSION['isEditingTopic'] = true;
    //$_SESSION['topic_id'] = $_POST['topic_id'];
//}

    //global $topic_name, $isEditingTopic, $topic_id;
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
    $sql = "CALL srcAmbiti(:how,:name, :valz)";
    if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) $sql = '{'.$sql.'}';
	$stmt = $conn->prepare($sql);
    $stmt->bindValue(':how',$_POST['idAm']);		//cerca per id
    $stmt->bindValue(':name','');
    $stmt->bindValue(':valz',-1);	//-1 per non cercare per valenza
    if(! $stmt->execute()) throw new Exception('Errore query search ambito per nome');
	$topic = $stmt->fetch(PDO::FETCH_ASSOC);
    $conn=null;
    $stmt=null;
    echo json_encode($topic);
    //$topic_name = $topic['ambito'];
    //$topic_val = $topic['valenza'];
    //header("location: ../topics.php");
    exit(0);


