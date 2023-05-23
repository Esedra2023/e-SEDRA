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


if(!$conn = connectDB()) {echo errorConnectDB(); exit();}

    $sql = "SELECT t.*, blogP.content as rife FROM
            (SELECT sc.idSC, sc.dtIns, nome, cognome, bb.idBl, bb.content, p.idPr, p.titlePrp as title, bb.idMaster FROM segnalaCommP as sc, utenti as u, blogP as bb,
            proposte as p
            WHERE sc.utente=u.idUs AND sc.commento=bb.idBl AND bb.proposta=p.idPr) as t LEFT JOIN blogP ON blogP.idBl=t.idMaster
            ORDER BY t.dtIns ASC;";

    //if($_POST['idSC'])
    //{
    //    $sql=$sql." WHERE idSC={$_POST['idSC']}";
    //}
    //if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) $sql = '{'.$sql.'}';
    try{
        $stmt = $conn->prepare($sql);
        //$stmt->bindValue(':iNw',0);
        //$stmt->bindValue(':iUs',$_SESSION['user']['idUs']);
        if(! $stmt->execute()) throw new Exception('Errore query get contenuti inadeguati');
        $CIna = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {echo error($e); exit();}

    $stmt = NULL;
    $conn = NULL;

    if(!isset($_POST['notajax']))
        echo json_encode($CIna);

    //$topic_name = $topic['ambito'];
    //$topic_val = $topic['valenza'];
    //header("location: ../topics.php");

    //exit(0);


