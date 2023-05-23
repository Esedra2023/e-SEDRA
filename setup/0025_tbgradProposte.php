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

require_once ROOT_PATH.'/include/functions.php';
if(!isset($_POST['resume'])) forbidden();

if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0){
    $autoinc = 'IDENTITY(1,1)';
}
else if($_SESSION['ini']['dbms'] == 'My SQL'){
    $autoinc = 'AUTO_INCREMENT';
}

$sql =<<<SQL
    CREATE TABLE gradProposte (
    idGB INT $autoinc NOT NULL PRIMARY KEY,
    ballot INT NOT NULL,
    idRl INT NOT NULL,
    idPr INT NOT NULL,
    grade INT NOT NULL,
    nlike INT,
    votanti INT,
    FOREIGN KEY (idRl) REFERENCES ruoli(idRl) ON DELETE NO ACTION,
    FOREIGN KEY (idPr) REFERENCES proposte(idPr) ON DELETE NO ACTION
);
SQL;
if($_POST['resume'] === '1') {  //SQL SERVER
    if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0){
        $resume = 'if exists(select TABLE_NAME from INFORMATION_SCHEMA.TABLES where TABLE_NAME = \'gradProposte\') DROP TABLE gradProposte;';
    }
    else if($_SESSION['ini']['dbms'] == 'My SQL'){
        $resume = 'DROP TABLE IF EXISTS gradProposte;';
    }
    $sql = $resume . $sql;
}

if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
try{$conn->query( $sql );
} catch(PDOException $e) {echo error($e); exit();}
$conn = NULL;

echo "Table gradProposte creata...";
exit();
