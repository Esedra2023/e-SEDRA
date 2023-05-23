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


$table=$_POST['table'];
$role=$_POST['role'];

if(!$conn = connectDB()) {echo errorConnectDB(); exit();}

// Admin can view all posts
// Author can only view their posts
if($role=="revisor"){
    //if(array_key_exists(1, $_SESSION['user']['roles'])){
    //if ($_SESSION['user']['role'] == "Admin") {
	$sql = "SELECT $table.* ,nome, cognome FROM $table,utenti WHERE utenti.idUs=utente;";
} else
{
    $user_id = $_SESSION['user']['idUs'];
    $sql = "SELECT $table.*,nome, cognome FROM $table,utenti WHERE utenti.idUs=utente AND utente=$user_id;";
}
$stmt = $conn->prepare($sql);
if(! $stmt->execute()) throw new Exception('Errore query GetAllPost');
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$conn=null;
$stmt=null;

if(!isset($_POST['notajax']))
        echo json_encode($posts);

    //$topic_name = $topic['ambito'];
    //$topic_val = $topic['valenza'];
    //header("location: ../topics.php");

    //exit(0);


